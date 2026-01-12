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
                    <a href="{{ route('admin.user.create') }}" class="hover:text-blue-600 transition-colors text-sm px-2">
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
                    <a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors {{ request()->routeIs('home') ? 'text-blue-600 font-bold underline decoration-2 underline-offset-8' : '' }}">
                        Suporte Técnico
                    </a>

                    <a href="{{ route('login') }}" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition-all shadow-md active:scale-95">
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
                    <li><a href="/" class="hover:text-white transition-colors">Nova Solicitação</a></li>
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
                    <p class="flex items-center gap-2 font-medium">📞 +55 (81) 99999-9999</p>
                </div>
            </div>
        </div>
        <div class="container mx-auto px-6 mt-16 pt-8 border-t border-gray-800/50 text-center text-xs">
            &copy; {{ date('Y') }} <strong>Simplemind</strong>. Todos os direitos reservados.
        </div>
    </footer>

    @guest
        <div class="fixed bottom-6 left-6 z-50">
            <a href="https://wa.me/5581999999999?text=Olá,%20preciso%20de%20ajuda%20com%20a%20Simplemind" 
                target="_blank" 
                class="bg-[#25D366] hover:bg-[#20ba5a] text-white p-4 rounded-full shadow-lg transition-all transform hover:scale-110 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="currentColor" viewBox="0 0 448 512">
                    <path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-5.5-2.8-23.2-8.5-44.2-27.2-16.4-14.6-27.4-32.7-30.6-38.2-3.2-5.6-.3-8.6 2.5-11.3 2.5-2.5 5.5-6.5 8.3-9.7 2.8-3.3 3.7-5.6 5.5-9.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 13.2 5.8 23.5 9.2 31.5 11.8 13.3 4.2 25.4 3.6 35 2.2 10.7-1.5 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/>
                </svg>
            </a>
        </div>

        <div class="fixed bottom-6 right-6 z-50">
            <button onclick="toggleChat()" class="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-full shadow-lg transition-all transform hover:scale-110 flex items-center justify-center border-2 border-white/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
            </button>
        </div>
    @endguest

    <script>
        function toggleChat() {
            alert('Olá! Eu sou o assistente inteligente da Simplemind. Como posso ajudar?');
        }
    </script>
</body>
</html>