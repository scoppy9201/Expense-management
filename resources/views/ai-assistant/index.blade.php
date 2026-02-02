@extends('layouts.app')
@section('title', 'Trợ lý AI')
@section('content')
<style>
    :root {
        --primary: #4a90e2;
        --primary-dark: #2a5298;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --info: #06b6d4;
        --radius: 12px;
        --shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    /* Dark Mode Variables */
    body.dark {
        --bg-primary: #0f1217;
        --bg-secondary: #1a1f2e;
        --bg-tertiary: #242938;
        --text-primary: #e5e7eb;
        --text-secondary: #9ca3af;
        --border-color: rgba(255, 255, 255, 0.1);
        --shadow-dark: 0 4px 16px rgba(0, 0, 0, 0.3);
    }

    body.dark .ai-page-header {
        background: var(--bg-secondary);
        box-shadow: var(--shadow-dark);
    }

    body.dark .ai-page-title {
        color: var(--text-primary);
        font-weight: 800;
    }

    body.dark .chat-container {
        background: var(--bg-secondary);
        box-shadow: var(--shadow-dark);
    }

    body.dark .chat-header {
        background: var(--bg-tertiary);
        border-bottom-color: var(--border-color);
    }

    body.dark .chat-header h3 {
        color: var(--text-primary);
        font-weight: 800;
    }

    body.dark .chat-messages {
        background: var(--bg-primary);
    }

    body.dark .message.ai .message-bubble {
        background: var(--bg-tertiary);
        color: var(--text-primary);
        box-shadow: var(--shadow-dark);
        font-weight: 600;
    }

    body.dark .chat-input-area {
        background: var(--bg-secondary);
        border-top-color: var(--border-color);
    }

    body.dark .chat-input {
        background: var(--bg-tertiary);
        border-color: var(--border-color);
        color: var(--text-primary);
        font-weight: 600;
    }

    body.dark .chat-input::placeholder {
        color: var(--text-secondary);
        font-weight: 500;
    }

    body.dark .chat-input:focus {
        border-color: var(--primary);
        background: var(--bg-secondary);
    }

    body.dark .suggestion-chip {
        background: var(--bg-tertiary);
        color: var(--text-primary);
        border-color: var(--border-color);
        font-weight: 600;
    }

    body.dark .suggestion-chip:hover {
        background: var(--primary);
        color: white;
    }

    /* Welcome Screen */
    .welcome-screen {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        text-align: center;
        padding: 60px 40px;
        opacity: 1;
        transition: opacity 0.3s ease;
    }

    .welcome-screen.hidden {
        display: none;
    }

    .welcome-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        display: flex;
        align-items: center;
        justify-content: center;
margin-bottom: 24px;
        box-shadow: 0 12px 40px rgba(74, 144, 226, 0.3);
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    .welcome-avatar img {
        width: 70%;
        filter: brightness(0) invert(1);
    }

    .welcome-greeting {
        font-size: 32px;
        font-weight: 800;
        color: #1f2937;
        margin-bottom: 12px;
        animation: fadeInUp 0.6s ease;
    }

    body.dark .welcome-greeting {
        color: var(--text-primary);
    }

    .welcome-subtitle {
        font-size: 18px;
        color: #6b7280;
        margin-bottom: 48px;
        font-weight: 600;
        animation: fadeInUp 0.8s ease;
    }

    body.dark .welcome-subtitle {
        color: var(--text-secondary);
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .welcome-input-container {
        width: 100%;
        max-width: 700px;
        margin-bottom: 32px;
        animation: fadeInUp 1s ease;
    }

    .welcome-input-wrapper {
        display: flex;
        gap: 12px;
        padding: 8px;
        background: white;
        border-radius: 24px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    body.dark .welcome-input-wrapper {
        background: var(--bg-tertiary);
        box-shadow: var(--shadow-dark);
    }

    .welcome-input-wrapper:focus-within {
        box-shadow: 0 12px 40px rgba(74, 144, 226, 0.2);
        transform: translateY(-2px);
    }

    .welcome-input {
        flex: 1;
        padding: 18px 24px;
        border: none;
        background: transparent;
        font-size: 16px;
        color: #1f2937;
        outline: none;
        font-weight: 600;
    }

    body.dark .welcome-input {
        color: var(--text-primary);
    }

    .welcome-input::placeholder {
        color: #9ca3af;
        font-weight: 500;
    }

    body.dark .welcome-input::placeholder {
        color: var(--text-secondary);
    }

    .welcome-send-btn {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .welcome-send-btn:hover:not(:disabled) {
        transform: scale(1.1);
        box-shadow: 0 8px 24px rgba(74, 144, 226, 0.4);
    }

    .welcome-send-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .welcome-send-btn img {
        width: 24px;
        filter: brightness(0) invert(1);
    }

    .welcome-suggestions {
        display: grid;
grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        max-width: 700px;
        width: 100%;
        animation: fadeInUp 1.2s ease;
    }

    .welcome-suggestion-card {
        padding: 20px 24px;
        background: white;
        border-radius: 16px;
        border: 2px solid #e5e7eb;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: left;
    }

    body.dark .welcome-suggestion-card {
        background: var(--bg-tertiary);
        border-color: var(--border-color);
    }

    .welcome-suggestion-card:hover {
        border-color: var(--primary);
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(74, 144, 226, 0.15);
    }

    .welcome-suggestion-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, rgba(74, 144, 226, 0.1), rgba(74, 144, 226, 0.05));
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        transition: all 0.3s ease;
    }

    .welcome-suggestion-icon img {
        width: 28px;
        height: 28px;
        object-fit: contain;
        opacity: 0.8;
        transition: all 0.3s ease;
    }

    .welcome-suggestion-card:hover .welcome-suggestion-icon {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        transform: scale(1.1);
    }

    .welcome-suggestion-card:hover .welcome-suggestion-icon img {
        opacity: 1;
        filter: brightness(0) invert(1);
    }

    body.dark .welcome-suggestion-icon {
        background: linear-gradient(135deg, rgba(74, 144, 226, 0.15), rgba(74, 144, 226, 0.08));
    }

    body.dark .welcome-suggestion-icon img {
        opacity: 0.9;
    }

    body.dark .welcome-suggestion-card:hover .welcome-suggestion-icon img {
        opacity: 1;
        filter: brightness(0) invert(1);
    }

    .welcome-suggestion-text {
        font-size: 14px;
        font-weight: 700;
        color: #374151;
    }

    body.dark .welcome-suggestion-text {
        color: var(--text-primary);
    }

    /* Chat Container */
    .ai-page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        padding: 20px;
        background: white;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
    }

    .ai-page-title {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 24px;
        font-weight: 700;
        color: #1f2937;
    }

    .ai-page-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
    }

    .ai-page-icon img {
        width: 100%;
    }

    .chat-container {
        background: white;
border-radius: var(--radius);
        box-shadow: var(--shadow);
        display: flex;
        flex-direction: column;
        height: calc(100vh - 180px);
        min-height: 500px;
        overflow: hidden;
    }

    .chat-header {
        padding: 16px 24px;
        border-bottom: 1px solid #e5e7eb;
        background: linear-gradient(135deg, rgba(74, 144, 226, 0.03), rgba(74, 144, 226, 0.01));
        display: none;
    }

    .chat-header.visible {
        display: block;
    }

    .chat-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .chat-header .status-dot {
        width: 10px;
        height: 10px;
        background: var(--success);
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
        70% { box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
        100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    .chat-messages {
        flex: 1;
        padding: 24px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 20px;
        background: #f9fafb;
    }

    .message {
        max-width: 80%;
        display: flex;
        flex-direction: column;
        gap: 6px;
        animation: messageSlide 0.3s ease;
    }

    @keyframes messageSlide {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .message.user {
        align-self: flex-end;
    }

    .message.ai {
        align-self: flex-start;
    }

    .message-bubble {
        padding: 14px 18px;
        border-radius: 18px;
        font-size: 15px;
        line-height: 1.6;
        position: relative;
        font-weight: 500;
    }

    .message.user .message-bubble {
        background: var(--primary);
        color: white;
        border-bottom-right-radius: 6px;
        font-weight: 600;
    }

    .message.ai .message-bubble {
        background: white;
        color: #1f2937;
        border-bottom-left-radius: 6px;
        box-shadow: var(--shadow);
    }

    .typing-indicator {
        display: flex;
        gap: 6px;
        padding: 8px 0;
    }

    .typing-dot {
        width: 8px;
        height: 8px;
        background: #9ca3af;
        border-radius: 50%;
        animation: typing 1.4s infinite;
    }

    .typing-dot:nth-child(2) { animation-delay: 0.2s; }
    .typing-dot:nth-child(3) { animation-delay: 0.4s; }

    @keyframes typing {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-6px); }
    }

    .message-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        flex-shrink: 0;
        background: #e5e7eb;
        display: flex;
align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: bold;
        color: white;
    }

    .message.user .message-avatar {
        background: var(--primary-dark);
    }

    .message.ai .message-avatar {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
    }

    .chat-input-area {
        padding: 20px 24px;
        border-top: 1px solid #e5e7eb;
        background: white;
        display: none;
    }

    .chat-input-area.visible {
        display: block;
    }

    .chat-input-form {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .chat-input {
        flex: 1;
        padding: 14px 18px;
        border: 2px solid #e5e7eb;
        border-radius: 16px;
        font-size: 15px;
        resize: none;
        min-height: 52px;
        max-height: 140px;
        line-height: 1.5;
        transition: all 0.2s ease;
        font-weight: 500;
    }

    .chat-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.15);
    }

    .chat-send-btn {
        width: 52px;
        height: 52px;
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: white;
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }

    .chat-send-btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(74, 144, 226, 0.3);
    }

    .chat-send-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .suggestion-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 16px;
    }

    .suggestion-chip {
        padding: 8px 16px;
        background: #f3f4f6;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        color: #4b5563;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 1px solid #e5e7eb;
    }

    .suggestion-chip:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
        transform: translateY(-1px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .welcome-suggestions {
            grid-template-columns: 1fr;
        }

        .welcome-greeting {
            font-size: 24px;
        }

        .welcome-subtitle {
            font-size: 16px;
        }

        .chat-container {
            height: calc(100vh - 140px);
        }
    }
</style>

<div class="ai-container">
    <div class="ai-page-header">
        <div class="ai-page-title">
            <div class="ai-page-icon">
                <img src="{{ asset('images/AI assistant.png') }}" alt="AI Assistant">
</div>
            <span>Trợ lý AI Tài chính</span>
        </div>
    </div>

    <div class="chat-container">
        <!-- Welcome Screen -->
        <div class="welcome-screen" id="welcome-screen">
            <div class="welcome-avatar">
                <img src="{{ asset('images/AI assistant.png') }}" alt="AI">
            </div>

            <h1 class="welcome-greeting">
                Xin chào, {{ Auth::user()->name }}! 
            </h1>

            <p class="welcome-subtitle">
                Tôi là trợ lý AI tài chính của bạn. Hãy hỏi tôi bất cứ điều gì!
            </p>

            <div class="welcome-input-container">
                <form id="welcome-form" class="welcome-input-wrapper">
                    <input 
                        type="text" 
                        id="welcome-input" 
                        class="welcome-input" 
                        placeholder="Nhập câu hỏi của bạn..."
                        autocomplete="off"
                    >
                    <button type="submit" class="welcome-send-btn" id="welcome-send-btn" disabled>
                        <img src="{{ asset('images/send.png') }}" alt="Send">
                    </button>
                </form>
            </div>

            <div class="welcome-suggestions">
                <div class="welcome-suggestion-card" onclick="startChatWith('Phân tích chi tiêu của tôi tháng này')">
                    <div class="welcome-suggestion-icon">
                        <img src="{{ asset('images/chart.png') }}" alt="Chart">
                    </div>
                    <div class="welcome-suggestion-text">Phân tích chi tiêu tháng này</div>
                </div>

                <div class="welcome-suggestion-card" onclick="startChatWith('Tôi nên tiết kiệm như thế nào?')">
                    <div class="welcome-suggestion-icon">
                        <img src="{{ asset('images/money.png') }}" alt="Money">
                    </div>
                    <div class="welcome-suggestion-text">Gợi ý tiết kiệm</div>
                </div>

                <div class="welcome-suggestion-card" onclick="startChatWith('Dự báo số dư cuối năm')">
                    <div class="welcome-suggestion-icon">
                        <img src="{{ asset('images/target.png') }}" alt="Target">
                    </div>
                    <div class="welcome-suggestion-text">Dự báo tài chính</div>
                </div>

                <div class="welcome-suggestion-card" onclick="startChatWith('Danh mục nào tôi chi nhiều nhất?')">
                    <div class="welcome-suggestion-icon">
                        <img src="{{ asset('images/statistics.png') }}" alt="Statistics">
                    </div>
                    <div class="welcome-suggestion-text">Thống kê chi tiêu</div>
                </div>
            </div>
        </div>

        <!-- Chat Header  -->
<div class="chat-header" id="chat-header">
            <h3>
                <span class="status-dot"></span>
                Đang trò chuyện
            </h3>
        </div>

        <!-- Chat Messages  -->
        <div class="chat-messages" id="chat-messages" style="display: none;"></div>

        <!-- Chat Input Area  -->
        <div class="chat-input-area" id="chat-input-area">
            <div class="suggestion-chips" id="suggestion-chips">
                <div class="suggestion-chip" onclick="sendQuickMessage('Phân tích chi tiêu tháng này')">Phân tích chi tiêu</div>
                <div class="suggestion-chip" onclick="sendQuickMessage('Nên tiết kiệm bao nhiêu?')">Gợi ý tiết kiệm</div>
                <div class="suggestion-chip" onclick="sendQuickMessage('Dự báo cuối năm')">Dự báo tài chính</div>
            </div>

            <form id="chat-form" class="chat-input-form">
                <textarea 
                    id="chat-input" 
                    class="chat-input" 
                    placeholder="Tiếp tục hỏi..." 
                    rows="1"
                ></textarea>
                <button type="submit" class="chat-send-btn" id="send-btn" disabled>
                    <img src="{{ asset('images/send.png') }}" alt="Send" style="width: 24px; height: 24px;">
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    const welcomeScreen = document.getElementById('welcome-screen');
    const welcomeInput = document.getElementById('welcome-input');
    const welcomeSendBtn = document.getElementById('welcome-send-btn');
    const chatHeader = document.getElementById('chat-header');
    const chatMessages = document.getElementById('chat-messages');
    const chatInputArea = document.getElementById('chat-input-area');
    const chatInput = document.getElementById('chat-input');
    const sendBtn = document.getElementById('send-btn');

    let isChatStarted = false;

    // Welcome input handling
    welcomeInput.addEventListener('input', function() {
        welcomeSendBtn.disabled = !this.value.trim();
    });

    document.getElementById('welcome-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const message = welcomeInput.value.trim();
        if (message) {
            startChat(message);
        }
    });

    // Chat input handling
    chatInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
        sendBtn.disabled = !this.value.trim();
    });

    // Functions
    function startChatWith(message) {
        welcomeInput.value = message;
        startChat(message);
    }

    function startChat(firstMessage) {
        if (!isChatStarted) {
            isChatStarted = true;

            // Hide welcome screen
            welcomeScreen.classList.add('hidden');

            // Show chat UI
chatHeader.classList.add('visible');
            chatMessages.style.display = 'flex';
            chatInputArea.classList.add('visible');

            // Add welcome message from AI
            appendMessage('ai', 'Xin chào! Tôi đã sẵn sàng hỗ trợ bạn. Hãy hỏi tôi bất cứ điều gì về tài chính!');
        }

        // Send first message
        sendMessage(firstMessage);
    }

    function sendQuickMessage(text) {
        chatInput.value = text;
        document.getElementById('chat-form').dispatchEvent(new Event('submit'));
    }

    document.getElementById('chat-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const message = chatInput.value.trim();
        if (!message) return;

        sendMessage(message);
        chatInput.value = '';
        chatInput.style.height = 'auto';
        sendBtn.disabled = true;
    });

    async function sendMessage(message) {
        // Show user message
        appendMessage('user', message);

        // Show thinking indicator
        const thinkingId = 'thinking-' + Date.now();
        appendMessage('ai', '<div class="typing-indicator"><div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div></div>', thinkingId);

        try {
            const response = await fetch('{{ route("ai-assistant.chat") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ message })
            });

            const data = await response.json();

            // Remove thinking indicator
            document.getElementById(thinkingId)?.remove();

            if (data.success) {
                appendMessage('ai', data.message);
            } else {
                appendMessage('ai', 'Xin lỗi, có lỗi xảy ra: ' + (data.message || 'Không rõ lý do'));
            }

        } catch (err) {
            document.getElementById(thinkingId)?.remove();
            appendMessage('ai', 'Không thể kết nối đến AI. Vui lòng thử lại sau.');
        }

        scrollToBottom();
    }

    function appendMessage(sender, content, customId = null) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}`;
        if (customId) messageDiv.id = customId;

        const isUser = sender === 'user';

        messageDiv.innerHTML = `
            <div style="display: flex; gap: 12px; ${isUser ? 'flex-direction: row-reverse;' : ''}">
                <div class="message-avatar">${isUser ? 'Bạn' : 'AI'}</div>
                <div class="message-bubble">
                    ${content.replace(/\n/g, '<br>')}
                </div>
            </div>
        `;

        chatMessages.appendChild(messageDiv);
        scrollToBottom();
    }
function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
</script>
@endsection
