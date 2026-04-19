<div class="flex flex-col h-full max-w-4xl mx-auto">

    <div id="chat-window" class="flex-1 overflow-y-auto p-4 space-y-6 scrollbar-thin scrollbar-thumb-gray-700">
        @foreach($messages as $message)
            <div class="flex {{ $message['role'] === 'user' ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[80%] rounded-2xl px-4 py-3 {{ $message['role'] === 'user' ? 'bg-blue-600' : 'bg-gray-800 border border-gray-700' }}">
                    <p class="text-sm leading-relaxed">{{ $message['content'] }}</p>
                </div>
            </div>
        @endforeach

        <div wire:loading class="text-gray-400 text-xs animate-pulse">
            Gemini анализирует рестораны...
        </div>
    </div>

    <div class="p-4 bg-gray-900">
        <div class="relative flex items-center">
            <input type="number"
                   wire:model.defer="budget"
                   wire:keydown.enter="sendMessage"
                   placeholder="Введите ваш бюджет (например, 1500)..."
                   class="w-full bg-gray-800 border border-gray-700 rounded-2xl py-4 pl-6 pr-16 focus:outline-none focus:ring-2 focus:ring-blue-500 text-white placeholder-gray-500">

            <button wire:click="sendMessage"
                    class="absolute right-3 p-2 bg-blue-600 hover:bg-blue-500 rounded-xl transition shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </button>
        </div>
        <p class="text-[10px] text-center text-gray-500 mt-2">
            ИИ может ошибаться. Проверяйте важную информацию в ресторанах.
        </p>
    </div>

</div>
<script>
    window.addEventListener('scroll-to-bottom', event => {
        const chatWindow = document.getElementById('chat-window');
        chatWindow.scrollTop = chatWindow.scrollHeight;
    });
</script>
