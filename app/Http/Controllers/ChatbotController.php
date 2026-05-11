<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Session;

class ChatbotController extends Controller
{
    protected $assistantId;

    public function __construct()
    {
        $this->assistantId = env('OPENAI_ASSISTANT_ID');
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required_without:file|string|nullable',
            // Update 3: Meningkatkan validasi mime types agar file docx dan format umum lainnya tidak tertolak
            'file'    => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg,webp,txt,xls,xlsx,csv|mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,image/png,image/jpeg,image/webp,text/plain,application/zip,application/x-zip-compressed,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/csv|max:10240', // 10MB max
            'page_context' => 'nullable|string',
        ]);

        $rawUserMessage = $request->input('message') ?? 'Tolong analisa file terlampir.';
        
        // Inject system-level formatting instruction and context to the user message dynamically
        $currentDate = now()->translatedFormat('l, d F Y');
        $pageContext = $request->input('page_context') ? "\n(Konteks URL Saat Ini: " . $request->input('page_context') . ")" : "";
        $formattingInstruction = "\n\n(Informasi Sistem: Hari ini adalah tanggal $currentDate. $pageContext Tolong jawab pertanyaan di atas dengan bahasa Indonesia yang sangat profesional, terstruktur, ramah, dan ringkas. Gunakan format bullet points atau penomoran markdown jika menjabarkan daftar. JANGAN PERNAH menyertakan anotasi sumber referensi file seperti 【7:0†source】dalam teks jawabanmu.)";
        
        $userMessage = $rawUserMessage . $formattingInstruction;

        try {
            // Check if file is uploaded
            $uploadedFileId = null;
            $isImage = false;
            $fileExtension = null;
            
            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                $file = $request->file('file');
                $mimeType = $file->getMimeType();
                $isImage = str_starts_with($mimeType, 'image/');
                $fileExtension = strtolower($file->getClientOriginalExtension());
                
                // Simpan file sementara dengan ekstensi aslinya agar dikenali OpenAI
                $originalName = $file->getClientOriginalName();
                $tempDir = storage_path('app/temp');
                if (!file_exists($tempDir)) {
                    mkdir($tempDir, 0755, true);
                }
                $tempPath = $tempDir . '/' . uniqid() . '_' . preg_replace('/[^a-zA-Z0-9_.-]/', '_', $originalName);
                
                copy($file->getRealPath(), $tempPath);
                
                // Upload file to OpenAI
                $openAiFile = OpenAI::files()->upload([
                    'purpose' => $isImage ? 'vision' : 'assistants',
                    'file' => fopen($tempPath, 'r'),
                ]);
                
                $uploadedFileId = $openAiFile->id;
                
                // Track uploaded file IDs in session to delete them later (Update 2: Storage Bloat)
                $sessionFiles = Session::get('openai_uploaded_files', []);
                $sessionFiles[] = $uploadedFileId;
                Session::put('openai_uploaded_files', $sessionFiles);
                
                // Hapus file sementara
                if (file_exists($tempPath)) {
                    unlink($tempPath);
                }
            }

            // Retrieve or Create a Thread ID stored in the user's session
            if (!Session::has('openai_thread_id')) {
                $thread = OpenAI::threads()->create([]);
                Session::put('openai_thread_id', $thread->id);
            }
            $threadId = Session::get('openai_thread_id');
            
            // 1. Build Message Content array
            $messageContent = [];
            
            if ($isImage && $uploadedFileId) {
                // If it's an image, use image_file content type (Vision)
                $messageContent[] = [
                    'type' => 'image_file',
                    'image_file' => ['file_id' => $uploadedFileId]
                ];
                $messageContent[] = [
                    'type' => 'text',
                    'text' => $userMessage
                ];
                
                OpenAI::threads()->messages()->create($threadId, [
                    'role' => 'user',
                    'content' => $messageContent,
                ]);
            } else {
                // If it's text only OR a document (PDF, Word, etc.)
                $messagePayload = [
                    'role' => 'user',
                    'content' => $userMessage,
                ];
                
                // Add attachments for File Search if a document was uploaded
                if ($uploadedFileId && !$isImage) {
                    $tools = [['type' => 'code_interpreter']]; // Code interpreter is broadly supported
                    
                    // file_search supported extensions
                    $fileSearchSupported = ['c', 'cpp', 'csv', 'docx', 'html', 'java', 'json', 'md', 'pdf', 'php', 'pptx', 'py', 'rb', 'tex', 'txt', 'css', 'ts', 'doc'];
                    if ($fileExtension && in_array($fileExtension, $fileSearchSupported)) {
                        $tools[] = ['type' => 'file_search'];
                    }

                    $messagePayload['attachments'] = [
                        [
                            'file_id' => $uploadedFileId,
                            'tools' => $tools
                        ]
                    ];
                }
                
                OpenAI::threads()->messages()->create($threadId, $messagePayload);
            }

            // 2 & 3. Run the Assistant and Stream directly using Symphony StreamedResponse
            $response = new \Symfony\Component\HttpFoundation\StreamedResponse(function () use ($threadId) {
                try {
                    $stream = OpenAI::threads()->runs()->createStreamed(
                        threadId: $threadId,
                        parameters: [
                            'assistant_id' => $this->assistantId,
                        ]
                    );

                    foreach ($stream as $response) {
                        if ($response->event === 'thread.run.failed') {
                            $errorCode = $response->response->last_error->code ?? 'unknown';
                            $errorMsg = $response->response->last_error->message ?? 'Terjadi kesalahan sistem AI.';
                            \Illuminate\Support\Facades\Log::error("Assistant Run Failed: [$errorCode] $errorMsg");
                            echo "data: " . json_encode(['text' => "\n[Pesan Sistem: Run AI gagal karena '$errorMsg'. Pastikan file didukung dan Assistant memiliki tools yang aktif.]"]) . "\n\n";
                            ob_flush();
                            flush();
                        }
                        
                        if ($response->event === 'thread.message.delta') {
                            $deltaText = $response->response->delta->content[0]->text->value ?? '';
                            if (!empty($deltaText)) {
                                // Format to SSE standard
                                echo "data: " . json_encode(['text' => $deltaText]) . "\n\n";
                                ob_flush();
                                flush();
                            }
                        }
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Stream Error: " . $e->getMessage());
                    echo "data: " . json_encode(['text' => "\n[Maaf, terjadi kesalahan internal saat AI memproses data. Silakan coba lagi.]"]) . "\n\n";
                    ob_flush();
                    flush();
                }
                
                // Send an end stream event
                echo "data: [DONE]\n\n";
                ob_flush();
                flush();
            });

            // Set headers for SSE Stream
            $response->headers->set('Content-Type', 'text/event-stream');
            $response->headers->set('Cache-Control', 'no-cache');
            $response->headers->set('Connection', 'keep-alive');
            $response->headers->set('X-Accel-Buffering', 'no'); // Prevent Nginx from buffering

            return $response;

        } catch (\Exception $e) {
            Log::error('Chatbot API Error: ' . $e->getMessage());
            // Since we might be returning a stream, error handling on frontend requires care. 
            // If it fails before stream starts, it will return this regular JSON error.
            return response()->json(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }
    
    public function resetSession(Request $request)
    {
        // Update 2: Delete thread and files from OpenAI to prevent storage bloat
        try {
            if (Session::has('openai_thread_id')) {
                OpenAI::threads()->delete(Session::get('openai_thread_id'));
            }
            
            if (Session::has('openai_uploaded_files')) {
                $files = Session::get('openai_uploaded_files');
                foreach ($files as $fileId) {
                    try {
                        OpenAI::files()->delete($fileId);
                    } catch (\Exception $e) {
                        Log::warning('Failed to delete OpenAI file: ' . $fileId);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Error cleaning up OpenAI session: ' . $e->getMessage());
        }

        Session::forget('openai_thread_id');
        Session::forget('openai_uploaded_files');
        return response()->json(['status' => 'success', 'message' => 'Sesi chat telah direset dan data sementara telah dibersihkan.']);
    }
}
