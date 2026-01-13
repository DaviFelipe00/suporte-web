<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Simplemind - @yield('title')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 flex flex-col min-h-screen font-sans antialiased">

    <header class="bg-white shadow-sm sticky top-0 z-50">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="flex items-center gap-2 transition-opacity hover:opacity-80">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Simplemind" class="h-10 w-auto">
                </a>
            </div>
            
            <div class="hidden md:flex items-center gap-4 text-gray-600 font-medium">
                @auth
                    <a href="{{ route('admin.user.create') }}" 
                       class="hover:text-blue-600 transition-colors text-sm px-2 {{ request()->routeIs('admin.user.create') ? 'text-blue-600 font-bold' : '' }}">
                        + Novo Admin
                    </a>

                    <a href="{{ route('admin.index') }}" 
                       class="px-5 py-2 rounded-lg transition-all shadow-sm active:scale-95 flex items-center gap-2 border
                       {{ request()->routeIs('admin.index') ? 'bg-blue-50 text-blue-700 border-blue-600 font-bold ring-2 ring-blue-100' : 'bg-white border-gray-300 text-gray-600 hover:bg-gray-50' }}">
                        @if(request()->routeIs('admin.index'))
                            <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span> 
                        @endif
                        Controle de Chamados
                    </a>

                    <a href="{{ route('dashboard') }}" 
                       class="px-5 py-2 rounded-lg transition-all shadow-sm active:scale-95 flex items-center gap-2 border
                       {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 border-blue-600 font-bold ring-2 ring-blue-100' : 'bg-white border-gray-300 text-gray-600 hover:bg-gray-50' }}">
                        @if(request()->routeIs('dashboard'))
                            <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span> 
                        @endif
                        Dashboard
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}" class="inline ml-2">
                        @csrf
                        <button type="submit" class="text-red-500 hover:text-red-700 font-bold transition-all px-3 py-2 hover:bg-red-50 rounded-lg active:scale-95">
                            Sair
                        </button>
                    </form>
                @else
                    <a href="{{ route('protocolo.index') }}" 
                       class="hover:text-blue-600 transition-colors {{ request()->routeIs('protocolo.index') ? 'text-blue-600 font-bold underline decoration-2 underline-offset-8' : '' }}">
                        Acompanhar Chamado
                    </a>

                    <a href="{{ route('home') }}" 
                       class="hover:text-blue-600 transition-colors {{ request()->routeIs('home') ? 'text-blue-600 font-bold underline decoration-2 underline-offset-8' : '' }}">
                        Suporte Técnico
                    </a>

                    <a href="{{ route('login') }}" 
                       class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition-all shadow-md active:scale-95">
                        Painel de Controle
                    </a>
                @endauth
            </div>
        </nav>
    </header>

    <main class="flex-grow container mx-auto px-6 py-10">
        @yield('content')
    </main>

    <footer class="bg-gray-900 text-gray-400 py-16 mt-auto border-t border-gray-800">
        <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-12">
            <div class="col-span-1">
                <div class="flex items-center gap-2 mb-6">
                    <img src="{{ asset('images/logo.png') }}" alt="Simplemind Logo" class="h-8 w-auto brightness-0 invert opacity-90">
                </div>
                <p class="text-sm leading-relaxed mb-6 italic border-l-2 border-blue-500 pl-4">
                    "Soluções inteligentes que simplificam o seu suporte diário e impulsionam sua eficiência."
                </p>
            </div>

            <div>
                <h4 class="text-white font-bold mb-6 uppercase text-xs tracking-widest">Plataforma</h4>
                <ul class="space-y-4 text-sm font-medium">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Nova Solicitação</a></li>
                    <li><a href="{{ route('protocolo.index') }}" class="hover:text-white transition-colors">Acompanhar Chamado</a></li>
                    @auth
                        <li><a href="{{ route('admin.index') }}" class="hover:text-white transition-colors">Controle de Chamados</a></li>
                    @endauth
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold mb-6 uppercase text-xs tracking-widest">Empresa</h4>
                <ul class="space-y-4 text-sm font-medium">
                    <li><a href="#" class="hover:text-white transition-colors">Política de Privacidade</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Termos de Uso</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold mb-6 uppercase text-xs tracking-widest">Contato</h4>
                <div class="space-y-4 text-sm text-gray-300">
                    <p class="flex items-center gap-2 font-medium">📧 suporte@simplemind.com.br</p>
                    <p class="flex items-center gap-2 font-medium">📞 +55 (81) 98235-0502</p>
                </div>
            </div>
        </div>
        <div class="container mx-auto px-6 mt-16 pt-8 border-t border-gray-800/50 text-center text-xs">
            &copy; {{ date('Y') }} <strong>Simplemind</strong>. Todos os direitos reservados.
        </div>
    </footer>

    @guest
        <div id="chat-window" class="hidden fixed bottom-24 right-6 w-80 md:w-96 bg-white shadow-2xl rounded-2xl overflow-hidden border border-gray-100 z-50 flex flex-col transition-all duration-300 transform">
            <div class="bg-blue-600 p-4 text-white flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">🤖</div>
                    <div>
                        <p class="text-sm font-bold leading-none">Assistente Simplemind</p>
                        <p class="text-[10px] text-blue-100 mt-1">Online agora</p>
                    </div>
                </div>
                <button onclick="toggleChat()" class="hover:bg-blue-700 p-1 rounded transition-colors text-xl">&times;</button>
            </div>

            <div id="chat-messages" class="h-80 overflow-y-auto p-4 space-y-4 bg-gray-50 flex flex-col">
                <div class="bg-white p-3 rounded-lg shadow-sm self-start max-w-[80%] border border-gray-100 text-sm text-gray-700">
                    Olá! Sou o assistente da <strong>Simplemind</strong>. Como posso ajudar você hoje?
                </div>
            </div>

            <div class="p-4 border-t border-gray-100 bg-white">
                <form id="chat-form" onsubmit="handleChatSubmit(event)" class="flex gap-2">
                    <input type="text" id="chat-input" placeholder="Digite sua dúvida..." 
                           class="flex-grow px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                    <button type="submit" class="bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700 transition-all flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <div class="fixed bottom-6 left-6 z-50">
            <a href="https://wa.me/558182350502?text=Olá,%20preciso%20de%20ajuda%20com%20a%20Simplemind" 
                target="_blank" 
                class="bg-[#25D366] hover:bg-[#20ba5a] text-white p-4 rounded-full shadow-lg transition-all transform hover:scale-110 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="currentColor" viewBox="0 0 448 512">
                    <path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-5.5-2.8-23.2-8.5-44.2-27.2-16.4-14.6-27.4-32.7-30.6-38.2-3.2-5.6-.3-8.6 2.5-11.3 2.5-2.5 5.5-6.5 8.3-9.7 2.8-3.3 3.7-5.6 5.5-9.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 13.2 5.8 23.5 9.2 31.5 11.8 13.3 4.2 25.4 3.6 35 2.2 10.7-1.5 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/>
                </svg>
            </a>
        </div>

        <div class="fixed bottom-6 right-6 z-50">
            <button onclick="toggleChat()" class="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-full shadow-lg transition-all transform hover:scale-110 flex items-center justify-center border-2 border-white/20 active:scale-95 shadow-blue-200">
                <svg id="bot-icon-open" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
                <span id="bot-icon-close" class="hidden text-2xl font-light">&times;</span>
            </button>
        </div>
    @endguest

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

            // 1. Mensagem do usuário
            appendMessage(text, 'user');
            input.value = '';

            // 2. Indicador de pensamento
            const typingId = 'typing-' + Date.now();
            const typingDiv = document.createElement('div');
            typingDiv.id = typingId;
            typingDiv.className = "bg-gray-200 text-gray-500 p-2 rounded-lg self-start text-xs italic animate-pulse";
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

                // Verifica se a resposta é OK antes de processar
                if (!response.ok) {
                    throw new Error(`Erro HTTP: ${response.status}`);
                }

                const data = await response.json();
                
                // Remove o indicador de digitação
                const typingElement = document.getElementById(typingId);
                if (typingElement) {
                    typingElement.remove();
                }

                // 3. Renderiza a resposta ou mensagem de erro padrão
                const reply = data.response || data.message || "Erro ao receber resposta do servidor.";
                appendMessage(reply, 'bot');

            } catch (error) {
                console.error('Erro no chatbot:', error);
                
                // Remove o indicador de digitação
                const typingElement = document.getElementById(typingId);
                if (typingElement) {
                    typingElement.remove();
                }
                
                appendMessage("⚠️ Erro de conexão com o bot. Por favor, tente novamente.", 'bot');
            }
        }

        // Função auxiliar para adicionar mensagens
        function appendMessage(text, side) {
            const messages = document.getElementById('chat-messages');
            const div = document.createElement('div');
            div.className = side === 'user' 
                ? "bg-blue-600 text-white p-3 rounded-lg shadow-sm self-end max-w-[80%] text-sm"
                : "bg-white p-3 rounded-lg shadow-sm self-start max-w-[80%] border border-gray-100 text-sm text-gray-700";
            
            // Sanitiza o texto para evitar XSS e preserva quebras de linha
            const sanitizedText = String(text)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;')
                .replace(/\n/g, '<br>');
            
            div.innerHTML = sanitizedText;
            messages.appendChild(div);
            messages.scrollTop = messages.scrollHeight;
        }
    </script>
</body>
</html>