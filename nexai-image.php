<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="https://imagizer.imageshack.com/img923/3161/sxavmO.png" type="image/png">
    <title>NexAi - Chat</title>
    
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

    <style>
        :root {
            --text-color: #f0f4f8;
            --border-color: rgba(255, 255, 255, 0.1);
            --user-bubble: #0084ff;
            --ai-bubble: rgba(45, 45, 55, 0.7);
            --background-start: #0f0c29;
            --background-mid: #302b63;
            --background-end: #24243e;
            --purple-line: #8a2be2;
        }

        * {
            box-sizing: border-box;
            scrollbar-width: thin;
            scrollbar-color: var(--user-bubble) transparent;
        }
        *::-webkit-scrollbar { width: 6px; }
        *::-webkit-scrollbar-track { background: transparent; }
        *::-webkit-scrollbar-thumb {
            background-color: var(--user-bubble);
            border-radius: 20px;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Vazirmatn', Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            height: 100vh;
            height: 100dvh;
            overflow: hidden;
            background: linear-gradient(-45deg, var(--background-start), var(--background-mid), var(--background-end), #1d4ed8);
            background-size: 400% 400%;
            animation: gradientBG 20s ease infinite;
        }
        
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .main-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            padding: 1rem;
            position: relative;
        }

        .back-btn {
            position: absolute;
            top: 30px;
            left: 30px;
            color: #d1c4e9;
            background: rgba(0, 0, 0, 0.3);
            padding: 8px 16px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            z-index: 100;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }
        .back-btn:hover {
            color: white;
            background: rgba(0, 0, 0, 0.5);
            transform: translateY(-2px);
        }

        .chat-container {
            width: 100%;
            max-width: 800px;
            background: rgba(20, 20, 30, 0.5);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border-radius: 20px;
            box-shadow: 0 15px 50px rgba(0,0,0,0.5);
            display: flex;
            flex-direction: column;
            height: 95vh;
            height: 95dvh;
            border: 1px solid var(--border-color);
            position: relative;
            animation: float-in 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
        }
        @keyframes float-in { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        
        .chat-container::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 20px;
            padding: 1px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }

        .chat-header {
            padding: 12px 20px;
            color: var(--text-color);
            border-bottom: 1px solid var(--border-color);
            background: rgba(0,0,0,0.2);
            flex-shrink: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 20px 20px 0 0;
        }
        .chat-header button {
            background: none; border: none; color: #a0a0a0;
            cursor: pointer; transition: all 0.3s ease;
        }
        .chat-header button:hover { color: white; transform: scale(1.1) rotate(15deg); }
        .logo-wrapper { display: flex; align-items: center; gap: 10px; font-weight: bold; font-size: 1.1rem; }
        .logo-img {
            border-radius: 50%; width: 32px; height: 32px;
            box-shadow: 0 0 15px #8a00e5;
            animation: pulse-glow 4s linear infinite;
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 0 15px #6300a5; transform: scale(1); }
            50% { box-shadow: 0 0 25px #a020f0; transform: scale(1.05); }
        }

        #chat-box {
            flex-grow: 1; padding: 20px; overflow-y: auto;
            display: flex; flex-direction: column; gap: 15px;
        }

        .chat-bubble {
            max-width: 85%; padding: 12px 18px; border-radius: 20px;
            line-height: 1.6; color: var(--text-color); word-wrap: break-word;
            animation: pop-in 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            position: relative;
        }
        @keyframes pop-in { from { opacity: 0; transform: translateY(10px) scale(0.95); } to { opacity: 1; transform: translateY(0) scale(1); } }
        .user {
            background-color: var(--user-bubble); align-self: flex-end;
            border-bottom-right-radius: 5px;
        }
        .ai {
            background-color: var(--ai-bubble); align-self: flex-start;
            border-bottom-left-radius: 5px;
        }
        .ai p, .ai ul, .ai ol { margin: 0.5em 0; }
        pre {
            background: rgba(0, 0, 0, 0.4);
            border-radius: 8px;
            padding: 12px;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
            overflow-x: auto;
            display: block;
            margin: 12px 0;
            border-bottom: 3px solid var(--purple-line);
            position: relative;
        }

        code {
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
        }

        .copy-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(138, 43, 226, 0.9);
            color: white;
            border: none;
            border-radius: 6px;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
            opacity: 0;
            z-index: 10;
        }
        pre:hover .copy-btn {
            opacity: 1;
        }
        .copy-btn:hover {
            background: #a855f7;
            transform: scale(1.1);
        }
        .copy-btn.copied {
            background: #10b981;
        }
        .copy-btn.copied .lucide-copy { display: none; }
        .copy-btn:not(.copied) .lucide-check { display: none; }

        .typing-indicator { display: flex; align-items: center; gap: 5px; padding: 10px 0; }
        .typing-indicator span {
            width: 8px; height: 8px; background-color: rgba(255, 255, 255, 0.7);
            border-radius: 50%; animation: bounce 1.4s infinite both;
        }
        .typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
        .typing-indicator span:nth-child(3) { animation-delay: 0.4s; }
        @keyframes bounce { 0%, 80%, 100% { transform: scale(0); } 40% { transform: scale(1.0); } }
        
        .input-area-wrapper {
            flex-shrink: 0; background: rgba(0,0,0,0.25);
            border-top: 1px solid var(--border-color);
            border-radius: 0 0 20px 20px;
            padding: 8px 12px;
        }
        .input-area {
            position: relative; display: flex; align-items: flex-end; gap: 10px;
            background-color: rgba(0,0,0,0.2); border-radius: 24px;
            border: 1px solid var(--border-color);
            padding: 4px;
        }
        #prompt-input {
            flex-grow: 1; padding: 10px 18px; border: none; background: transparent;
            font-size: 16px; resize: none; color: var(--text-color);
            max-height: 150px; font-family: inherit;
        }
        #prompt-input:focus { outline: none; }
        .input-buttons button {
            background: transparent; border: none; color: #a0a0a0;
            cursor: pointer; width: 44px; height: 44px;
            border-radius: 50%; display: flex; justify-content: center; align-items: center;
            transition: all 0.3s ease;
        }
        .input-buttons button:hover { color: white; background-color: rgba(255,255,255,0.1); }
        #send-button { background: var(--user-bubble); color: white; }
        #send-button:hover { background: #0095ff; }
        #send-button:disabled { background-color: #555; cursor: not-allowed; transform: scale(1); color: #999; }

        @media (max-width: 768px) {
            .main-container { padding: 0; }
            .chat-container {
                height: 100vh; height: 100dvh; max-height: 100dvh;
                width: 100%; border-radius: 0; border: none;
            }
            .chat-container::before, .chat-header, .input-area-wrapper { border-radius: 0; }
            .back-btn { top: 15px; left: 15px; background: rgba(0,0,0,0.5); }
        }

        .creator-container {
            position: relative;
        }
        .image-container {
            position: relative; width: 40px; height: 40px; border-radius: 40px; overflow: hidden;
            animation: gentlePulse 2s infinite;
        }
        .creator-image {
            width: 100%; height: 100%; object-fit: cover; border-radius: 50%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2); transition: opacity 0.7s;
        }
        .image-container:hover .creator-image { opacity: 0.2; }
        .overlay {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            display: flex; align-items: center; justify-content: center;
            opacity: 0; transition: opacity 0.7s; background-color: rgba(0, 0, 0, 0.5);
        }
        .image-container:hover .overlay { opacity: 1; }
        .overlay-text { color: white; font-weight: bold; font-size: 12px; }
        @keyframes gentlePulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <a href="nexai-home.php" class="back-btn">بازگشت</a>

        <div class="chat-container">
            <div class="chat-header">
                <button onclick="App.newChat()" title="چت جدید"><i data-lucide="file-plus-2"></i></button>
                <div class="logo-wrapper">
                    <img src="https://imagizer.imageshack.com/img923/3161/sxavmO.png" class="logo-img" />
                    <span>NexAi</span>
                    <a href="https://neximage.xo.je/about">
                        <div class="creator-container">
                            <div class="image-container">
                                <img src="https://imagizer.imageshack.com/img923/8025/Vv1G6E.png" alt="Creator" class="creator-image" />
                                <div class="overlay">
                                    <span class="overlay-text">درباره من</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <button onclick="window.location.href='./about2.html'" title="قابلیت های بیشتر">
                    <!-- <i data-lucide="layout-grid"></i> -->
                </button>
            </div>
            <div id="chat-box"></div>
            <div class="input-area-wrapper">
                <div id="preview-area" style="display: none; padding: 5px 15px; color: var(--text-color); display: flex; justify-content: space-between; align-items: center;"></div>
                <div class="input-area">
                    <div class="input-buttons">
                        <button id="attach-button" title="آپلود فایل"><i data-lucide="paperclip"></i></button>
                    </div>
                    <textarea id="prompt-input" placeholder="پیام خود را بنویسید..." rows="1"></textarea>
                    <div class="input-buttons">
                        <button id="send-button" title="ارسال"><i data-lucide="send-horizontal"></i></button>
                    </div>
                    <input type="file" id="file-input" accept="image/*,video/*,application/pdf" style="display: none;">
                </div>
            </div>
        </div>
    </div>

    <script>
        const GEMINI_API_KEY = "AIzaSyDdd9zqKPjG_UtkJsg3fXp-M8bEeNyxCxk";
        const App = {
            elements: {}, chatHistory: [], attachedFile: null,

            init() {
                this.elements = {
                    chatBox: document.getElementById('chat-box'),
                    promptInput: document.getElementById('prompt-input'),
                    sendButton: document.getElementById('send-button'),
                    attachButton: document.getElementById('attach-button'),
                    fileInput: document.getElementById('file-input'),
                    previewArea: document.getElementById('preview-area'),
                };
                
                // ===== تغییر کلیدی طبق درخواست شما در این خط انجام شد =====
                this.API_ENDPOINT = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent";

                this.attachEventListeners();
                this.newChat();
                lucide.createIcons();
            },

            attachEventListeners() {
                this.elements.sendButton.addEventListener('click', () => this.sendMessage());
                this.elements.attachButton.addEventListener('click', () => this.elements.fileInput.click());
                this.elements.fileInput.addEventListener('change', (e) => this.handleFileSelect(e));
                this.elements.promptInput.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault(); this.sendMessage();
                    }
                });
                this.elements.promptInput.addEventListener('input', this.autoResizeTextarea);
            },

            newChat() {
                this.chatHistory = [];
                this.elements.chatBox.innerHTML = '';
                this.removeFile();
                this.addMessage('ai', 'سلام! من NexAi هستم. چطور می‌توانم به شما کمک کنم؟');
            },
            
            addMessage(role, content) {
                const bubble = document.createElement('div');
                bubble.classList.add('chat-bubble', role);
                bubble.innerHTML = marked.parse(content);
                this.elements.chatBox.appendChild(bubble);
                if (role === 'ai') {
                    this.addCodeCopyButtons(bubble);
                }
                this.elements.chatBox.scrollTop = this.elements.chatBox.scrollHeight;
                return bubble;
            },

            addCodeCopyButtons(container) {
                container.querySelectorAll('pre').forEach(preElement => {
                    if (preElement.querySelector('.copy-btn')) return;
                    const codeText = preElement.querySelector('code').innerText;

                    const copyBtn = document.createElement('button');
                    copyBtn.className = 'copy-btn';
                    copyBtn.innerHTML = '<i data-lucide="copy" class="lucide-copy"></i><i data-lucide="check" class="lucide-check"></i>';
                    copyBtn.title = 'کپی کردن کد';

                    copyBtn.onclick = () => {
                        navigator.clipboard.writeText(codeText).then(() => {
                            copyBtn.classList.add('copied');
                            setTimeout(() => copyBtn.classList.remove('copied'), 2000);
                        });
                    };
                    preElement.appendChild(copyBtn);
                });
                lucide.createIcons();
            },

            async sendMessage() {
                const prompt = this.elements.promptInput.value.trim();
                if (!prompt && !this.attachedFile) return;

                this.elements.sendButton.disabled = true;
                const userParts = [];
                let userMessageHtml = '';

                if (prompt) {
                    userParts.push({ text: prompt });
                    userMessageHtml += `<p>${prompt.replace(/\n/g, '<br>')}</p>`;
                }

                if (this.attachedFile) {
                    const fileData = await this.fileToBase64(this.attachedFile.file);
                    userParts.push({ inline_data: { mime_type: this.attachedFile.mimeType, data: fileData } });
                    if (this.attachedFile.mimeType.startsWith('image/')) {
                        userMessageHtml += `<img src="data:${this.attachedFile.mimeType};base64,${fileData}" style="max-width: 200px; border-radius: 8px; margin-top: 8px;">`;
                    }
                }

                this.addMessage('user', userMessageHtml);
                this.chatHistory.push({ role: 'user', parts: userParts });

                this.removeFile();
                this.elements.promptInput.value = '';
                this.autoResizeTextarea();

                const loadingBubble = this.addMessage('ai', '<div class="typing-indicator"><span></span><span></span><span></span></div>');

                try {
                    const requestBody = { contents: this.chatHistory };
                    const response = await fetch(`${this.API_ENDPOINT}?key=${GEMINI_API_KEY}`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(requestBody),
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.error.message || "خطای ناشناخته API");
                    }

                    const data = await response.json();
                    if (!data.candidates || data.candidates.length === 0) {
                        throw new Error("پاسخی از API دریافت نشد. ممکن است محتوای شما به دلیل قوانین ایمنی مسدود شده باشد.");
                    }
                    const responseText = data.candidates[0]?.content?.parts[0]?.text || "پاسخی دریافت نشد.";

                    loadingBubble.innerHTML = marked.parse(responseText);
                    this.addCodeCopyButtons(loadingBubble);

                    this.chatHistory.push({ role: 'model', parts: [{ text: responseText }] });
                } catch (error) {
                    console.error("API Error:", error);
                    loadingBubble.innerHTML = `<strong>خطا در ارتباط با سرور:</strong> <br><small>${error.message}</small>`;
                } finally {
                    this.elements.sendButton.disabled = false;
                    this.elements.promptInput.focus();
                }
            },

            autoResizeTextarea() {
                const el = App.elements.promptInput;
                el.style.height = 'auto';
                el.style.height = (el.scrollHeight) + 'px';
            },

            fileToBase64(file) {
                return new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    reader.onload = () => resolve(reader.result.split(',')[1]);
                    reader.onerror = reject;
                    reader.readAsDataURL(file);
                });
            },

            handleFileSelect(event) {
                const file = event.target.files[0];
                if (!file) return;

                this.attachedFile = { file, mimeType: file.type };
                this.elements.previewArea.innerHTML = `
                    <span>${file.name}</span>
                    <button onclick="App.removeFile()" style="background:none; border:none; color: #ff5555; cursor:pointer;"><i data-lucide="x-circle"></i></button>
                `;
                this.elements.previewArea.style.display = 'flex';
                lucide.createIcons();
            },

            removeFile() {
                this.attachedFile = null;
                this.elements.fileInput.value = '';
                this.elements.previewArea.style.display = 'none';
            },
        };

        document.addEventListener('DOMContentLoaded', () => App.init());
    </script>
</body>
</html>

