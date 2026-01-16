<!-- BOTÃO WHATSAPP -->
<div class="fixed bottom-6 left-6 z-50">
    <a href="https://wa.me/558182350502?text=Olá,%20preciso%20de%20ajuda%20com%20a%20Simplemind"
       target="_blank"
       class="bg-[#25D366] hover:bg-[#20ba5a] text-white p-4 rounded-full
              shadow-lg transition-all transform hover:scale-110
              flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg"
             class="h-7 w-7"
             fill="currentColor"
             viewBox="0 0 448 512">
            <path d="M380.9 97.1C339 55.1 283.2 32 223.9 32
            c-122.4 0-222 99.6-222 222
            0 39.1 10.2 77.3 29.6 111L0 480
            l117.7-30.9c32.4 17.7 68.9 27
            106.1 27h.1c122.3 0 224.1-99.6
            224.1-222 0-59.3-25.2-115-67.1-157z"/>
        </svg>
    </a>
</div>

<!-- BOTÃO CHATBOT -->
<div class="fixed bottom-6 right-6 z-50">
    <button onclick="toggleChat()"
        class="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-full
               shadow-lg transition-all transform hover:scale-110
               flex items-center justify-center border-2 border-white/20
               active:scale-95 shadow-blue-200">

        <!-- ÍCONE BOT -->
        <svg id="bot-icon-open"
             xmlns="http://www.w3.org/2000/svg"
             class="h-7 w-7"
             fill="none"
             viewBox="0 0 24 24"
             stroke="currentColor">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9.663 17h4.673M12 3v1
                     m6.364 1.636l-.707.707
                     M21 12h-1M4 12H3
                     m3.343-5.657l-.707-.707
                     m2.828 9.9a5 5 0 117.072 0
                     l-.548.547A3.374 3.374 0 0014 18.469
                     V19a2 2 0 11-4 0v-.531
                     c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
        </svg>

        <!-- ÍCONE FECHAR -->
        <span id="bot-icon-close"
              class="hidden text-2xl font-light">&times;</span>
    </button>
</div>
