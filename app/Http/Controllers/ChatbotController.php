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
            'file'    => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg,webp,txt|max:10240', // 10MB max
        ]);

        $rawUserMessage = $request->input('message') ?? 'Tolong analisa file terlampir.';
        
        // Inject system-level formatting instruction to the user message dynamically
        $currentDate = now()->translatedFormat('l, d F Y');
        $formattingInstruction = "\n\n(Informasi Waktu Sistem: Hari ini adalah tanggal $currentDate. Tolong jawab pertanyaan di atas dengan bahasa Indonesia yang sangat profesional, terstruktur, ramah, dan ringkas. Gunakan format bullet points atau penomoran markdown jika menjabarkan daftar. JANGAN PERNAH menyertakan anotasi sumber referensi file seperti 【7:0†source】dalam teks jawabanmu.)";
        
        $userMessage = $rawUserMessage . $formattingInstruction;

        try {
            // Check if file is uploaded
            $uploadedFileId = null;
            $isImage = false;
            
            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                $file = $request->file('file');
                $mimeType = $file->getMimeType();
                $isImage = str_starts_with($mimeType, 'image/');
                
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
            
            // Catat riwayat chat ke file log Laravel (Saran 2: History Tracking)
            $ipAddress = $request->ip();

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
                    $messagePayload['attachments'] = [
                        [
                            'file_id' => $uploadedFileId,
                            'tools' => [['type' => 'file_search'], ['type' => 'code_interpreter']]
                        ]
                    ];
                }
                
                OpenAI::threads()->messages()->create($threadId, $messagePayload);
            }

            // 2. Run the Assistant
            $run = OpenAI::threads()->runs()->create(
                threadId: $threadId,
                parameters: [
                    'assistant_id' => $this->assistantId,
                ],
            );

            // 3. Wait for completion (Polling)
            $completed = false;
            $maxRetries = 30; // 30 seconds max
            $retries = 0;

            while (!$completed && $retries < $maxRetries) {
                sleep(1); 
                $runStatus = OpenAI::threads()->runs()->retrieve(
                    threadId: $threadId,
                    runId: $run->id,
                );

                if ($runStatus->status === 'completed') {
                    $completed = true;
                } elseif ($runStatus->status === 'failed' || $runStatus->status === 'cancelled' || $runStatus->status === 'expired') {
                     Log::error('OpenAI Run Failed: ' . $runStatus->status);
                     return response()->json(['error' => 'Maaf, sistem AI sedang mengalami gangguan. Silakan coba beberapa saat lagi.'], 500);
                }
                $retries++;
            }

            if (!$completed) {
                return response()->json(['error' => 'Waktu tunggu habis (Timeout).'], 504);
            }

            // 4. Retrieve Messages
            $messages = OpenAI::threads()->messages()->list($threadId);
            
            // The first message is the latest response from the assistant
            $assistantResponse = $messages->data[0]->content[0]->text->value;

            return response()->json([
                'response' => $assistantResponse
            ]);

        } catch (\Exception $e) {
            Log::error('Chatbot API Error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()], 500);
        }
    }
    
    public function resetSession(Request $request)
    {
        Session::forget('openai_thread_id');
        return response()->json(['status' => 'success', 'message' => 'Sesi chat telah direset.']);
    }
}
