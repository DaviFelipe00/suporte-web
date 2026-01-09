<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suporte Web - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">

    <header class="bg-white shadow-sm sticky top-0 z-50">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <a href="/" class="flex items-center gap-2 transition-opacity hover:opacity-80">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo SuporteWeb" class="h-10 w-auto">
                </a>
            </div>
            
            <div class="hidden md:flex items-center gap-8 text-gray-600 font-medium">
                <a href="/" class="hover:text-blue-600 transition-colors">Início</a>
                <a href="{{ route('admin.index') }}" class="hover:text-blue-600 transition-colors">Solicitações</a>
                <a href="#" class="hover:text-blue-600 transition-colors">Sobre</a>
                <a href="#" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition-all shadow-md">Falar com Consultor</a>
            </div>
        </nav>
    </header>

    <main class="flex-grow container mx-auto px-6 py-10">
        @yield('content')
    </main>

    <footer class="bg-gray-900 text-gray-300 py-12 mt-auto">
        <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-12">
            <div>
                <h3 class="text-white text-lg font-bold mb-4">SuporteWeb</h3>
                <p class="text-sm leading-relaxed">Transformando a gestão de atendimentos e suporte técnico com tecnologia e eficiência.</p>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4">Links Úteis</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-blue-400">Política de Privacidade</a></li>
                    <li><a href="#" class="hover:text-blue-400">Termos de Uso</a></li>
                    <li><a href="#" class="hover:text-blue-400">FAQ</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-4">Contato</h4>
                <p class="text-sm">suporte@empresa.com.br</p>
                <p class="text-sm mt-2">+55 (81) 99999-9999</p>
            </div>
        </div>
        <div class="container mx-auto px-6 mt-10 pt-6 border-t border-gray-800 text-center text-xs">
            &copy; {{ date('Y') }} SuporteWeb. Todos os direitos reservados.
        </div>
    </footer>

    <div class="fixed bottom-6 left-6 z-50">
        <a href="https://wa.me/5581999999999?text=Olá,%20preciso%20de%20ajuda%20com%20o%20SuporteWeb" 
            target="_blank" 
            title="Falar no WhatsApp"
            class="bg-green-500 hover:bg-green-600 text-white p-4 rounded-full shadow-2xl transition-all duration-300 transform hover:scale-110 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="currentColor" viewBox="0 0 24 24">
                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.316 1.592 5.448 0 9.886-4.438 9.889-9.886.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884 0 2.225.569 3.846 1.594 5.46l-1.043 3.813 3.914-1.026z"/>
            </svg>
        </a>
    </div>

    <div class="fixed bottom-6 right-6 z-50">
        <button onclick="toggleChat()" title="Dúvidas Gerais com IA"
            class="bg-gray-900 hover:bg-black text-white p-4 rounded-full shadow-2xl transition-all duration-300 transform hover:scale-110 flex items-center justify-center border-2 border-gray-700 relative">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
            </svg>
            <span class="absolute -top-1 -right-1 bg-blue-500 text-[9px] px-1.5 py-0.5 rounded-full uppercase font-bold tracking-tighter shadow-sm">IA</span>
        </button>
    </div>

    <script>
        function toggleChat() {
            // Placeholder para futura integração com API de IA
            alert('Integrando o motor de IA... Em breve você poderá tirar dúvidas diretamente por aqui!');
        }
    </script>

</body>
</html>