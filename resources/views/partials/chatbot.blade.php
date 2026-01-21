<div id="chat-window" class="hidden fixed bottom-20 right-4 left-4 md:left-auto md:bottom-24 md:right-6 md:w-96 bg-white shadow-2xl rounded-2xl overflow-hidden border border-gray-100 z-50 flex flex-col transition-all duration-300 transform">
    
    <div class="bg-blue-600 p-4 text-white flex justify-between items-center shadow-md">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center text-lg">🤖</div>
            <div>
                <p class="text-sm font-bold leading-none">Assistente Simplemind</p>
                <p class="text-[10px] text-blue-100 mt-1 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span> Online agora
                </p>
            </div>
        </div>
        <button onclick="toggleChat()" class="hover:bg-blue-700 p-2 rounded-full transition-colors text-2xl leading-none">&times;</button>
    </div>

    <div id="chat-messages" class="h-80 md:h-96 overflow-y-auto p-4 space-y-4 bg-gray-50 flex flex-col">
        <div class="bg-white p-3 rounded-2xl rounded-tl-none shadow-sm self-start max-w-[85%] border border-gray-100 text-sm text-gray-700 leading-relaxed">
            Olá! Sou o assistente da <strong>Simplemind</strong>. Como posso ajudar você hoje?
        </div>
    </div>

    <div class="p-4 border-t border-gray-100 bg-white">
        <form id="chat-form" onsubmit="handleChatSubmit(event)" class="flex gap-2">
            <input type="text" id="chat-input" placeholder="Digite sua dúvida..." 
                   class="flex-grow px-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all bg-gray-50">
            <button type="submit" class="bg-blue-600 text-white p-2.5 rounded-xl hover:bg-blue-700 active:scale-95 transition-all flex items-center justify-center shadow-md shadow-blue-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
            </button>
        </form>
    </div>
</div>

<div class="fixed bottom-6 left-0 w-full px-6 z-40 flex flex-row justify-between items-end pointer-events-none">
    
    <a href="https://wa.me/558182350502?text=Olá,%20preciso%20de%20ajuda%20com%20a%20Simplemind" 
       target="_blank" 
       class="pointer-events-auto w-14 h-14 bg-[#25D366] hover:bg-[#20ba5a] text-white rounded-full shadow-lg transition-all transform hover:scale-110 flex items-center justify-center border-2 border-white/20 active:scale-95 shadow-green-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="currentColor" viewBox="0 0 448 512">
            <path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-5.5-2.8-23.2-8.5-44.2-27.2-16.4-14.6-27.4-32.7-30.6-38.2-3.2-5.6-.3-8.6 2.5-11.3 2.5-2.5 5.5-6.5 8.3-9.7 2.8-3.3 3.7-5.6 5.5-9.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 13.2 5.8 23.5 9.2 31.5 11.8 13.3 4.2 25.4 3.6 35 2.2 10.7-1.5 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/>
        </svg>
    </a>

    <button onclick="toggleChat()" 
            class="pointer-events-auto w-14 h-14 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-lg transition-all transform hover:scale-110 flex items-center justify-center border-2 border-white/20 active:scale-95 shadow-blue-200">
        <svg id="bot-icon-open" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
        </svg>
        <span id="bot-icon-close" class="hidden text-2xl font-light">&times;</span>
    </button>
</div>

<script>
    function toggleChat() {
        const chatWindow = document.getElementById('chat-window');
        const iconOpen = document.getElementById('bot-icon-open');
        const iconClose = document.getElementById('bot-icon-close');
        
        chatWindow.classList.toggle('hidden');
        iconOpen.classList.toggle('hidden');
        iconClose.classList.toggle('hidden');
        
        if(!chatWindow.classList.contains('hidden')) {
            document.getElementById('chat-input').focus();
        }
    }

    async function handleChatSubmit(e) {
        e.preventDefault();
        const input = document.getElementById('chat-input');
        const messages = document.getElementById('chat-messages');
        const text = input.value.trim();

        if (text === '') return;

        appendMessage(text, 'user');
        input.value = '';

        const typingId = 'typing-' + Date.now();
        const typingDiv = document.createElement('div');
        typingDiv.id = typingId;
        typingDiv.className = "bg-gray-200 text-gray-500 px-3 py-2 rounded-xl rounded-tl-none self-start text-xs italic animate-pulse";
        typingDiv.textContent = "🤖 Bot está pensando...";
        messages.appendChild(typingDiv);
        messages.scrollTop = messages.scrollHeight;

        try {
            const response = await fetch("{{ route('chatbot.handle') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ message: text })
            });

            const data = await response.json();
            document.getElementById(typingId)?.remove();
            appendMessage(data.response || data.message || "Erro no servidor.", 'bot');

        } catch (error) {
            document.getElementById(typingId)?.remove();
            appendMessage("⚠️ Erro de conexão com o bot.", 'bot');
        }
    }

    function appendMessage(text, side) {
        const messages = document.getElementById('chat-messages');
        const div = document.createElement('div');
        
        if(side === 'user') {
            div.className = "bg-blue-600 text-white p-3 rounded-2xl rounded-tr-none shadow-sm self-end max-w-[85%] text-sm leading-relaxed";
        } else {
            div.className = "bg-white p-3 rounded-2xl rounded-tl-none shadow-sm self-start max-w-[85%] border border-gray-100 text-sm text-gray-700 leading-relaxed";
        }
        
        div.innerHTML = String(text).replace(/\n/g, '<br>');
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
    }
</script>