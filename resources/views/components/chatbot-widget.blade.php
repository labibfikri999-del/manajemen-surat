<!-- Chatbot Floating Widget -->
    <div id="chatbot-container" class="fixed bottom-6 right-6 z-[9999] animate-fade-in font-sans">
        <!-- Chatbot Toggler Button -->
        <button id="chatbot-toggle" class="w-14 h-14 rounded-full chatbot-btn text-white shadow-lg flex items-center justify-center hover:scale-105 hover:bg-emerald-600 hover:shadow-xl transition-all duration-200 relative group focus:outline-none focus:ring-4 focus:ring-emerald-200">
            <svg id="chatbot-icon-msg" class="w-6 h-6 absolute transition-all duration-300 scale-100 opacity-100 z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
            <svg id="chatbot-icon-close" class="w-6 h-6 absolute transition-all duration-300 scale-50 opacity-0 z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"></path></svg>
            
            <!-- Notification Badge -->
            <span class="absolute top-0 right-0 w-3.5 h-3.5 bg-red-500 border-2 border-white rounded-full z-20 shadow-sm animate-pulse"></span>
        </button>

        <!-- Chatbot Window panel -->
        <div id="chatbot-panel" class="absolute bottom-20 right-0 w-[350px] sm:w-[400px] h-[550px] max-h-[85vh] bg-white rounded-2xl shadow-2xl border border-slate-200 flex flex-col overflow-hidden transition-all duration-300 ease-out origin-bottom-right scale-0 opacity-0 pointer-events-none">
            
            <!-- Header (Solid Professional) -->
            <div class="relative px-5 py-4 text-white chatbot-header z-10 shrink-0 shadow-md">
                <div class="relative flex justify-between items-center z-10">
                    <div class="flex items-center space-x-3.5">
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center text-white backdrop-blur-[2px]">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"><path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-base tracking-normal">YARSI Intelligence</h3>
                            <div class="flex items-center space-x-1.5 opacity-90">
                                <span class="relative flex h-2 w-2">
                                  <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-200"></span>
                                </span>
                                <span class="text-xs font-medium text-emerald-50">Online & Siap Membantu</span>
                            </div>
                        </div>
                    </div>
                    <button id="chatbot-reset" title="Mulai Obrolan Baru" class="p-2 rounded-xl text-white/80 hover:text-white hover:bg-white/10 transition-colors focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Messages Area (Clean Design) -->
            <div id="chatbot-messages" class="flex-1 p-5 overflow-y-auto chat-scroll space-y-4 scroll-smooth bg-[#F8FAFC]">
                <!-- Welcome Message AI -->
                <div class="flex items-start">
                    <div class="w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center mr-3 flex-shrink-0 shadow-sm mt-1">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <div class="bg-white p-3.5 rounded-2xl rounded-tl-[4px] border border-slate-200 shadow-sm text-[13.5px] text-slate-700 max-w-[85%] leading-[1.6]">
                        <span class="font-semibold text-slate-900">Halo 👋</span><br>Saya Asisten AI Pintar YARSI NTB.
                        <br><br>
                        Ada yang bisa saya ringkaskan, jadwalkan, atau bantu info dari dokumen Yayasan?
                    </div>
                </div>

                <!-- Suggested Questions Chips via Staggered Micro Animation -->
                <div id="chatbot-suggestions" class="flex flex-col gap-2 pt-1 px-11 w-full">
                    <button class="suggestion-btn chip-stagger text-[13px] bg-white border border-emerald-100 text-slate-600 rounded-xl px-4 py-2.5 hover:bg-emerald-50 hover:border-emerald-200 hover:text-emerald-700 transition-colors text-left shadow-sm">
                        <span class="font-medium">Apa saja wewenang direktur?</span>
                    </button>
                    <button class="suggestion-btn chip-stagger text-[13px] bg-white border border-emerald-100 text-slate-600 rounded-xl px-4 py-2.5 hover:bg-emerald-50 hover:border-emerald-200 hover:text-emerald-700 transition-colors text-left shadow-sm">
                        <span class="font-medium">Struktur organisasi terkini</span>
                    </button>
                    <button class="suggestion-btn chip-stagger text-[13px] bg-white border border-emerald-100 text-slate-600 rounded-xl px-4 py-2.5 hover:bg-emerald-50 hover:border-emerald-200 hover:text-emerald-700 transition-colors text-left shadow-sm">
                        <span class="font-medium">Prosedur mutasi surat masuk?</span>
                    </button>
                </div>
            </div>

            <!-- Input Area (Professional Layout) -->
            <div class="bg-white border-t border-slate-200 shrink-0 relative">
                
                <!-- Floating File Preview Container -->
                <div id="chatbot-file-preview" class="hidden absolute -top-12 left-2 right-2 flex items-center justify-between bg-white border border-emerald-100 rounded-lg px-3 py-2 shadow-lg animate-fade-in z-20">
                    <div class="flex items-center space-x-2 overflow-hidden">
                        <div class="w-6 h-6 rounded bg-emerald-50 flex items-center justify-center flex-shrink-0 text-emerald-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                        </div>
                        <span id="chatbot-file-name" class="text-[12px] text-slate-600 font-medium truncate">filename.pdf</span>
                    </div>
                    <button type="button" id="chatbot-file-remove" class="text-slate-400 hover:text-red-500 transition-colors focus:outline-none p-1 shrink-0 rounded hover:bg-red-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form id="chatbot-form" class="flex items-end gap-1 p-3">
                    <input type="file" id="chatbot-file-input" class="hidden" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg,.webp,.txt" />
                    
                    <button type="button" id="chatbot-attach-btn" class="w-10 h-10 shrink-0 flex items-center justify-center text-slate-400 hover:text-emerald-600 hover:bg-slate-100 rounded-full transition-colors focus:outline-none" title="Lampirkan File/Foto">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                    </button>
                    
                    <button type="button" id="chatbot-mic-btn" class="w-10 h-10 shrink-0 flex items-center justify-center text-slate-400 hover:text-emerald-600 hover:bg-slate-100 rounded-full transition-all focus:outline-none" title="Dikte Suara (Voice Input)">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
                        <span class="absolute w-2 h-2 rounded-full bg-red-500 top-1 right-2 hidden" id="chatbot-mic-indicator"></span>
                    </button>

                    <div class="flex-1 bg-slate-100 rounded-[20px] flex items-end relative border border-transparent focus-within:border-emerald-300 focus-within:ring-2 focus-within:ring-emerald-50 transition-all overflow-hidden ml-1">
                        <textarea id="chatbot-input" rows="1" class="w-full bg-transparent text-[13.5px] text-slate-700 pl-4 pr-2 py-2.5 focus:outline-none resize-none min-h-[44px] max-h-[120px] scroll-smooth" placeholder="Ketik pesan..." autocomplete="off"></textarea>
                    </div>
                    
                    <button type="submit" id="chatbot-submit" class="w-10 h-10 shrink-0 flex items-center justify-center text-emerald-600 bg-emerald-50 hover:bg-emerald-100 rounded-full transition-colors focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed mb-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    </button>
                </form>
                <div class="text-center pb-2.5 pt-0 flex justify-center items-center">
                    <span class="text-[10px] font-medium text-slate-400">Powered by AI Analytics</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Chatbot Logic -->
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('chatbot-toggle');
            const panel = document.getElementById('chatbot-panel');
            const iconMsg = document.getElementById('chatbot-icon-msg');
            const iconClose = document.getElementById('chatbot-icon-close');
            const msgsContainer = document.getElementById('chatbot-messages');
            const cForm = document.getElementById('chatbot-form');
            const cInput = document.getElementById('chatbot-input');
            const cSubmit = document.getElementById('chatbot-submit');
            const cReset = document.getElementById('chatbot-reset');
            const micBtn = document.getElementById('chatbot-mic-btn');
            const micIndicator = document.getElementById('chatbot-mic-indicator');
            
            let isOpen = false;
            let activeStreamMsgElement = null;
            const initialMsgsHTML = msgsContainer.innerHTML;

            // Markdown Config
            marked.setOptions({ breaks: true, gfm: true });

            // Toggle Panel Logic
            toggleBtn.addEventListener('click', () => {
                isOpen = !isOpen;
                sessionStorage.setItem('yarsi_chat_isOpen', isOpen);
                if(isOpen) {
                    panel.classList.remove('scale-0', 'opacity-0', 'pointer-events-none');
                    panel.classList.add('scale-100', 'opacity-100', 'pointer-events-auto');
                    iconMsg.style.transform = 'scale(0) rotate(-45deg)';
                    iconMsg.style.opacity = '0';
                    iconClose.style.transform = 'scale(1) rotate(0deg)';
                    iconClose.style.opacity = '1';
                    
                    // Hide notification badge
                    const badge = toggleBtn.querySelector('span.bg-red-500');
                    if(badge) badge.classList.add('hidden');
                    
                    setTimeout(() => cInput.focus(), 300);
                } else {
                    panel.classList.replace('scale-100', 'scale-0');
                    panel.classList.replace('opacity-100', 'opacity-0');
                    panel.classList.replace('pointer-events-auto', 'pointer-events-none');
                    
                    iconClose.style.transform = 'scale(0) rotate(45deg)';
                    iconClose.style.opacity = '0';
                    iconMsg.style.transform = 'scale(1) rotate(0)';
                    iconMsg.style.opacity = '1';
                }
            });

            // Focus Effects (None needed since we use focus-within on flex container)

            function appendUserMessage(text, fileName = null) {
                const sugg = document.getElementById('chatbot-suggestions');
                if(sugg) sugg.style.display = 'none';
                
                let safeText = text ? text.replace(/</g, "&lt;").replace(/>/g, "&gt;") : '';
                
                let fileNoticeHTML = '';
                if (fileName) {
                    const iconSvg = `<svg class="w-3 h-3 text-emerald-500 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>`;
                    fileNoticeHTML = `<div class="bg-white/20 px-2 py-1 rounded text-[11px] mb-1 italic flex items-center">${iconSvg} Melampirkan: ${fileName.replace(/</g, "&lt;").replace(/>/g, "&gt;")}</div>`;
                }

                const msgHTML = `
                <div class="flex items-end justify-end space-x-2 animate-fade-in pl-10 mb-2">
                    <div class="bg-emerald-600 text-white p-3.5 px-4 rounded-2xl rounded-br-[4px] text-[13.5px] max-w-[85%] break-words leading-relaxed">
                        ${fileNoticeHTML}
                        ${safeText}
                    </div>
                </div>`;
                msgsContainer.insertAdjacentHTML('beforeend', msgHTML);
                scrollToBottom();
            }

            function appendAIMessage(text, isStreaming = false) {
                // Remove citation marks e.g. 【7:0†source】
                const cleanText = text.replace(/【.*?】/g, '');
                const parsedText = marked.parse(cleanText);
                
                if (isStreaming && activeStreamMsgElement) {
                    // Update existing bubble
                    activeStreamMsgElement.innerHTML = parsedText;
                    scrollToBottom();
                    return;
                }

                const msgHTML = `
                <div class="flex items-start space-x-3 animate-fade-in pr-6 mb-2">
                    <div class="w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <div class="ai-msg-content bg-white p-4 px-5 rounded-2xl rounded-tl-[4px] border border-slate-200 shadow-sm text-[13.5px] text-slate-800 max-w-[95%] sm:max-w-[90%] leading-[1.65] prose prose-sm prose-emerald prose-p:my-1.5 prose-ul:my-1.5 prose-li:my-0.5" style="& ul { list-style-type: disc; padding-left: 1.5rem; } ol { list-style-type: decimal; padding-left: 1.5rem; }">
                        ${parsedText}
                    </div>
                </div>`;
                
                msgsContainer.insertAdjacentHTML('beforeend', msgHTML);
                scrollToBottom();

                if (isStreaming) {
                    const aiMessages = msgsContainer.querySelectorAll('.ai-msg-content');
                    activeStreamMsgElement = aiMessages[aiMessages.length - 1];
                }
            }

            function showTyping() {
                const typingHTML = `
                <div id="typing-indicator" class="flex items-start space-x-3 animate-fade-in mb-2">
                    <div class="w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center flex-shrink-0 mt-0.5 shadow-sm">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" /></svg>
                    </div>
                    <div class="bg-white px-4 py-4 rounded-2xl rounded-tl-[4px] border border-slate-200 shadow-sm flex space-x-1.5 items-center">
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-400 typing-dot"></div>
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-400 typing-dot"></div>
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-400 typing-dot"></div>
                    </div>
                </div>`;
                msgsContainer.insertAdjacentHTML('beforeend', typingHTML);
                scrollToBottom();
            }

            function removeTyping() {
                const typingEl = document.getElementById('typing-indicator');
                if(typingEl) typingEl.remove();
            }

            function scrollToBottom() {
                msgsContainer.scrollTop = msgsContainer.scrollHeight;
            }

            const fileInput = document.getElementById('chatbot-file-input');
            const attachBtn = document.getElementById('chatbot-attach-btn');
            const filePreview = document.getElementById('chatbot-file-preview');
            const fileNameDisplay = document.getElementById('chatbot-file-name');
            const fileRemoveBtn = document.getElementById('chatbot-file-remove');

            let selectedFile = null;

            // Handle Attachment Button Click
            attachBtn.addEventListener('click', () => {
                fileInput.click();
            });

            // Handle File Selection
            fileInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    selectedFile = file;
                    fileNameDisplay.textContent = file.name;
                    filePreview.classList.remove('hidden');
                    filePreview.classList.add('flex');
                    cInput.focus();
                }
            });

            // Handle File Remove
            fileRemoveBtn.addEventListener('click', () => {
                selectedFile = null;
                fileInput.value = ''; // Clear input
                filePreview.classList.add('hidden');
                filePreview.classList.remove('flex');
                cInput.focus();
            });

            // Web Speech API for Voice Input
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            if (SpeechRecognition) {
                const recognition = new SpeechRecognition();
                recognition.lang = 'id-ID'; // Indonesian
                recognition.interimResults = false;
                recognition.maxAlternatives = 1;

                let isRecording = false;

                micBtn.addEventListener('click', () => {
                    if (isRecording) {
                        recognition.stop();
                    } else {
                        recognition.start();
                    }
                });

                recognition.onstart = function() {
                    isRecording = true;
                    micBtn.classList.add('text-red-500', 'bg-red-50');
                    micIndicator.classList.remove('hidden');
                    micIndicator.classList.add('animate-ping');
                    cInput.placeholder = "Mendengarkan...";
                };

                recognition.onresult = function(event) {
                    const transcript = event.results[0][0].transcript;
                    cInput.value = (cInput.value + ' ' + transcript).trim();
                    // trigger auto-resize
                    cInput.dispatchEvent(new Event('input'));
                    cInput.focus();
                };

                recognition.onerror = function(event) {
                    console.error('Speech recognition error', event.error);
                    cInput.placeholder = "Ketik pesan...";
                };

                recognition.onend = function() {
                    isRecording = false;
                    micBtn.classList.remove('text-red-500', 'bg-red-50');
                    micIndicator.classList.add('hidden');
                    micIndicator.classList.remove('animate-ping');
                    cInput.placeholder = "Ketik pesan...";
                };
            } else {
                micBtn.style.display = 'none'; // Hide if not supported
            }

            async function sendMessage(text) {
                if(!text.trim() && !selectedFile) return;
                
                cInput.value = '';
                // trigger auto-resize to shrink back
                cInput.dispatchEvent(new Event('input')); 
                
                cSubmit.disabled = true;
                cInput.disabled = true;
                attachBtn.disabled = true;
                if(micBtn) micBtn.disabled = true;
                
                const fileName = selectedFile ? selectedFile.name : null;

                // Prepare FormData with Context Awareness
                const formData = new FormData();
                formData.append('message', text || 'Tolong analisa file terlampir.');
                formData.append('page_context', document.title + ' (' + window.location.pathname + ')');
                if (selectedFile) {
                    formData.append('file', selectedFile);
                }

                // Hide file preview immediately from input area
                filePreview.classList.add('hidden');
                filePreview.classList.remove('flex');
                
                appendUserMessage(text, fileName);
                
                let history = JSON.parse(sessionStorage.getItem('yarsi_chat_history') || '[]');
                history.push({ role: 'user', text: text, fileName: fileName });
                sessionStorage.setItem('yarsi_chat_history', JSON.stringify(history));

                showTyping();
                activeStreamMsgElement = null; // Reset current stream target

                try {
                    const response = await fetch('/chatbot/send', {
                        method: 'POST',
                        headers: {
                            'Accept': 'text/event-stream',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    });

                    removeTyping();

                    if (!response.ok) {
                        // Handled non-stream json error
                        const data = await response.json();
                        let errorMsg = data.error || "Terjadi kesalahan sambungan jaringan.";
                        appendAIMessage(errorMsg);
                        
                        let errHistory = JSON.parse(sessionStorage.getItem('yarsi_chat_history') || '[]');
                        errHistory.push({ role: 'ai', text: errorMsg });
                        sessionStorage.setItem('yarsi_chat_history', JSON.stringify(errHistory));
                    } else {
                        // Handle SSE Stream
                        const reader = response.body.getReader();
                        const decoder = new TextDecoder('utf-8');
                        let accumulatedText = "";
                        let buffer = "";

                        while (true) {
                            const { done, value } = await reader.read();
                            if (done) break;
                            
                            buffer += decoder.decode(value, { stream: true });
                            
                            // Process SSE messages separated by double newline
                            const parts = buffer.split('\n\n');
                            buffer = parts.pop(); // Keep incomplete part in buffer

                            for (const part of parts) {
                                if (part.startsWith('data: ')) {
                                    const dataStr = part.replace('data: ', '');
                                    if (dataStr === '[DONE]') {
                                        let aiHistory = JSON.parse(sessionStorage.getItem('yarsi_chat_history') || '[]');
                                        aiHistory.push({ role: 'ai', text: accumulatedText });
                                        sessionStorage.setItem('yarsi_chat_history', JSON.stringify(aiHistory));
                                        break;
                                    }
                                    
                                    try {
                                        const jsonData = JSON.parse(dataStr);
                                        if (jsonData.text) {
                                            if (!activeStreamMsgElement) {
                                                // Create the initial bubble
                                                accumulatedText += jsonData.text;
                                                appendAIMessage(accumulatedText, true);
                                            } else {
                                                // Append to existing bubble
                                                accumulatedText += jsonData.text;
                                                appendAIMessage(accumulatedText, true);
                                            }
                                        }
                                    } catch(e) {
                                        console.error('SSE JSON Parse Error', e, dataStr);
                                    }
                                }
                            }
                        }
                    }

                    // Reset state after stream completes
                    cSubmit.disabled = false;
                    cInput.disabled = false;
                    attachBtn.disabled = false;
                    if(micBtn) micBtn.disabled = false;
                    selectedFile = null;
                    fileInput.value = ''; 
                    cInput.focus();

                } catch (error) {
                    removeTyping();
                    cSubmit.disabled = false;
                    cInput.disabled = false;
                    attachBtn.disabled = false;
                    if(micBtn) micBtn.disabled = false;
                    
                    let msg = "Koneksi terputus. Silakan periksa jaringan Anda dan coba lagi.";
                    appendAIMessage(msg);
                    
                    let errHistory = JSON.parse(sessionStorage.getItem('yarsi_chat_history') || '[]');
                    errHistory.push({ role: 'ai', text: msg });
                    sessionStorage.setItem('yarsi_chat_history', JSON.stringify(errHistory));
                }
            }

            cForm.addEventListener('submit', (e) => {
                e.preventDefault();
                sendMessage(cInput.value);
            });
            // Support enter to submit in textarea, shift+enter for new line
            cInput.addEventListener('keydown', function(e) {
                if(e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    cForm.dispatchEvent(new Event('submit'));
                }
            });
            // Auto resize textarea
            cInput.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
                if(this.value === '') this.style.height = '44px';
            });

            document.querySelectorAll('.suggestion-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    sendMessage(this.querySelector('span').innerText);
                });
            });

            // Soft Reset
            cReset.addEventListener('click', async () => {
                if(!confirm('Mulai sesi obrolan baru?')) return;
                
                try {
                    cReset.querySelector('svg').classList.add('animate-spin');
                    await fetch('/chatbot/reset', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } });
                    
                    sessionStorage.removeItem('yarsi_chat_history');
                    msgsContainer.innerHTML = initialMsgsHTML;
                    
                    // Re-bind suggestion chips listeners
                    msgsContainer.querySelectorAll('.suggestion-btn').forEach(btn => {
                        btn.addEventListener('click', function() {
                            sendMessage(this.querySelector('span').innerText);
                        });
                    });
                    
                    cReset.querySelector('svg').classList.remove('animate-spin');
                } catch(e) {
                    cReset.querySelector('svg').classList.remove('animate-spin');
                }
            });

            // --- Initialization from sessionStorage ---
            let savedHistory = sessionStorage.getItem('yarsi_chat_history');
            if(savedHistory) {
                try {
                    let parsed = JSON.parse(savedHistory);
                    if(parsed.length > 0) {
                        msgsContainer.innerHTML = ''; // Clear default Welcome msg
                        parsed.forEach(msg => {
                            if(msg.role === 'user') {
                                appendUserMessage(msg.text, msg.fileName);
                            } else if(msg.role === 'ai') {
                                appendAIMessage(msg.text);
                            }
                        });
                    }
                } catch(e) {
                    console.error("Failed to restore chat history", e);
                }
            }
            
            let isSavedOpen = sessionStorage.getItem('yarsi_chat_isOpen');
            if(isSavedOpen === 'true') {
                toggleBtn.click();
            }
        });
    </script>
