<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Бизнес Ассистент ИИ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Стили для скроллбара, чтобы он был тонким и красивым */
        #chat-messages::-webkit-scrollbar { width: 6px; }
        #chat-messages::-webkit-scrollbar-track { background: transparent; }
        #chat-messages::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 3px; }

        /* Анимация троеточия "Бот печатает" */
        .typing-dot { animation: typing 1.4s infinite; opacity: 0.3; }
        .typing-dot:nth-child(2) { animation-delay: 0.2s; }
        .typing-dot:nth-child(3) { animation-delay: 0.4s; }
        @keyframes typing { 0%, 100% { opacity: 0.3; transform: translateY(0); } 50% { opacity: 1; transform: translateY(-3px); } }
    </style>
</head>
<body class="bg-gray-50 h-screen flex flex-col font-sans antialiased">

<header class="bg-white border-b border-gray-200 py-3 px-4 flex items-center justify-between sticky top-0 z-10">
    <div class="flex items-center gap-3">
        <div class="bg-blue-100 text-blue-600 p-2.5 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </div>
        <div>
            <h1 class="text-lg font-semibold text-gray-900">Бизнес-Ассистент</h1>
            <p class="text-xs text-green-600 font-medium flex items-center gap-1">
                <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span></span>
                Онлайн | На базе Gemini AI
            </p>
        </div>
    </div>
    <button onclick="clearChat()" class="text-gray-500 hover:text-red-500 p-2 rounded-lg hover:bg-red-50">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
    </button>
</header>

<main id="chat-messages" class="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8 space-y-6 bg-gray-50">

    <div class="flex items-start gap-3 assistant-message">
        <div class="flex-shrink-0 bg-white border border-gray-200 p-2 rounded-full shadow-sm mt-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
        </div>
        <div class="p-4 rounded-2xl bg-white border border-gray-100 shadow-sm text-gray-800 max-w-[85%] md:max-w-[75%] rounded-tl-none">
            <p class="leading-relaxed">Здравствуйте! 👋 Я ваш персональный бизнес-ассистент.</p>
            <p class="leading-relaxed mt-2">Я могу помочь вам сравнить различные виды бизнеса, проанализировать их доходность, риски и окупаемость. Просто опишите, что вас интересует, или назовите две компании для сравнения.</p>
            <p class="text-sm text-gray-400 mt-3">{{ now()->format('H:i') }}</p>
        </div>
    </div>

</main>

<footer class="bg-white border-t border-gray-200 p-4 sticky bottom-0 z-10">
    <form id="chat-form" class="flex items-end gap-3 max-w-7xl mx-auto">
        @csrf
        <div class="flex-1 relative">
                <textarea
                    id="user-input"
                    name="message"
                    rows="1"
                    placeholder="Спросите меня о бизнесе..."
                    class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 resize-none transition bg-gray-50 focus:bg-white overflow-y-hidden"
                    oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"
                ></textarea>
            <button type="submit" class="absolute right-2 bottom-2 p-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition" id="send-btn">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
            </button>
        </div>
    </form>
    <p class="text-center text-xs text-gray-400 mt-2">ИИ может ошибаться. Проверяйте важную информацию.</p>
</footer>

<template id="typing-indicator-template">
    <div class="flex items-start gap-3 assistant-message" id="typing-indicator">
        <div class="flex-shrink-0 bg-white border border-gray-200 p-2 rounded-full shadow-sm mt-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
        </div>
        <div class="px-5 py-4 rounded-2xl bg-gray-100 text-gray-600 rounded-tl-none">
            <div class="flex gap-1">
                <span class="typing-dot w-2 h-2 bg-gray-400 rounded-full inline-block"></span>
                <span class="typing-dot w-2 h-2 bg-gray-400 rounded-full inline-block"></span>
                <span class="typing-dot w-2 h-2 bg-gray-400 rounded-full inline-block"></span>
            </div>
        </div>
    </div>
</template>

<template id="user-message-template">
    <div class="flex items-start gap-3 justify-end user-message">
        <div class="p-4 rounded-2xl bg-blue-600 text-white shadow-sm max-w-[85%] md:max-w-[75%] rounded-tr-none">
            <p class="leading-relaxed message-content"></p>
            <p class="text-sm text-blue-100 mt-2 message-time"></p>
        </div>
        <div class="flex-shrink-0 bg-blue-100 text-blue-700 font-semibold p-2.5 rounded-full shadow-sm text-sm mt-1">
            {{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 2)) : 'ВЫ' }}
        </div>
    </div>
</template>

<template id="assistant-message-template">
    <div class="flex items-start gap-3 assistant-message">
        <div class="flex-shrink-0 bg-white border border-gray-200 p-2 rounded-full shadow-sm mt-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
        </div>
        <div class="p-4 rounded-2xl bg-white border border-gray-100 shadow-sm text-gray-800 max-w-[85%] md:max-w-[75%] rounded-tl-none">
            <div class="leading-relaxed message-content prose prose-sm max-w-none prose-blue">
            </div>
            <p class="text-sm text-gray-400 mt-3 message-time"></p>
        </div>
    </div>
</template>

<script>
    const chatForm = document.getElementById('chat-form');
    const userInput = document.getElementById('user-input');
    const chatMessages = document.getElementById('chat-messages');
    const sendBtn = document.getElementById('send-btn');

    // Скролл вниз при загрузке
    chatMessages.scrollTop = chatMessages.scrollHeight;

    // Обработка отправки формы
    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const message = userInput.value.trim();
        if (!message) return;

        // 1. Отобразить сообщение пользователя
        appendUserMessage(message);
        userInput.value = '';
        userInput.style.height = ''; // Сброс высоты textarea
        sendBtn.disabled = true; // Отключить кнопку

        // 2. Показать индикатор "Бот печатает"
        showTypingIndicator();

        try {
            // 3. Отправить запрос на сервер Laravel (Route: 'chat.message')
            const response = await fetch("{{ route('chat.message') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message: message })
            });

            if (!response.ok) throw new Error('Ошибка сети');

            const data = await response.json();

            // 4. Скрыть индикатор и отобразить ответ бота
            hideTypingIndicator();
            if (data.status === 'success') {
                appendAssistantMessage(data.message);
            } else {
                appendAssistantMessage('Извините, произошла ошибка при обработке запроса.');
            }

        } catch (error) {
            hideTypingIndicator();
            appendAssistantMessage('Произошла критическая ошибка. Попробуйте позже.');
            console.error(error);
        } finally {
            sendBtn.disabled = false; // Включить кнопку обратно
            userInput.focus();
        }
    });

    // Обработка отправки по Enter (без Shift)
    userInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            chatForm.requestSubmit();
        }
    });

    // Функции хелперы для добавления сообщений в DOM
    function getCurrentTime() {
        const now = new Date();
        return now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
    }

    function appendUserMessage(text) {
        const template = document.getElementById('user-message-template');
        const clone = template.content.cloneNode(true);
        clone.querySelector('.message-content').textContent = text;
        clone.querySelector('.message-time').textContent = getCurrentTime();
        chatMessages.appendChild(clone);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function appendAssistantMessage(htmlOrText) {
        const template = document.getElementById('assistant-message-template');
        const clone = template.content.cloneNode(true);

        // Если вы возвращаете Markdown с бэкенда, вам нужно подключить библиотеку (например, marked.js)
        // Чтобы безопасно вставить HTML, используем innerHTML (убедитесь, что HTML санирован на бэкенде!)
        clone.querySelector('.message-content').innerHTML = htmlOrText.replace(/\n/g, '<br>');

        clone.querySelector('.message-time').textContent = getCurrentTime();
        chatMessages.appendChild(clone);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function showTypingIndicator() {
        if (document.getElementById('typing-indicator')) return;
        const template = document.getElementById('typing-indicator-template');
        const clone = template.content.cloneNode(true);
        chatMessages.appendChild(clone);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function hideTypingIndicator() {
        const indicator = document.getElementById('typing-indicator');
        if (indicator) indicator.remove();
    }

    function clearChat() {
        if (confirm('Очистить историю чата?')) {
            // Здесь можно добавить AJAX запрос на сервер для удаления сообщений из БД
            location.reload();
        }
    }
</script>
{{--<script>--}}
{{--    async function sendToBot() {--}}
{{--        const message = document.getElementById('user-message').value;--}}

{{--// Показываем «печатает...»--}}
{{--        showLoader();--}}

{{--        const response = await fetch('/chat/message', {--}}
{{--            method: 'POST',--}}
{{--            headers: {--}}
{{--                'Content-Type': 'application/json',--}}
{{--                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content--}}
{{--            },--}}
{{--            body: JSON.stringify({ message: message })--}}
{{--        });--}}

{{--        const data = await response.json();--}}

{{--// Добавляем ответ бота в окно чата--}}
{{--        appendMessageToChat('assistant', data.reply);--}}
{{--    }--}}
{{--</script>--}}
</body>
</html>
