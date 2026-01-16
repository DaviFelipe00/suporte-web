<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        Simplemind -
        {{ $title ?? trim($__env->yieldContent('title')) ?? 'Gestão' }}
    </title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 flex flex-col min-h-screen font-sans antialiased">

<!-- HEADER PRINCIPAL -->
<!-- HEADER PRINCIPAL -->
<header class="bg-white shadow-sm sticky top-0 z-50">
    <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
        <a href="{{ route('home') }}" class="flex items-center gap-2 hover:opacity-80 transition">
            <img src="{{ asset('images/logo.png') }}" class="h-10" alt="Simplemind">
        </a>

        <div class="hidden md:flex items-center gap-3">
            @auth
                <a href="{{ route('admin.inventario.index') }}"
                   class="px-5 py-2 rounded-lg border transition font-medium
                   {{ request()->routeIs('admin.inventario.*')
                        ? 'bg-blue-50 text-blue-700 border-blue-600 font-bold'
                        : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50 hover:border-gray-400' }}">
                    Inventário
                </a>

                <a href="{{ route('admin.user.create') }}"
                   class="px-5 py-2 rounded-lg border transition font-medium
                   {{ request()->routeIs('admin.user.create')
                        ? 'bg-blue-50 text-blue-700 border-blue-600 font-bold'
                        : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50 hover:border-gray-400' }}">
                    Adicionar usuário
                </a>

                <a href="{{ route('admin.index') }}"
                   class="px-5 py-2 rounded-lg border transition font-medium
                   {{ request()->routeIs('admin.index')
                        ? 'bg-blue-50 text-blue-700 border-blue-600 font-bold'
                        : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50 hover:border-gray-400' }}">
                    Controle de Chamados
                </a>

                <a href="{{ route('dashboard') }}"
                   class="px-5 py-2 rounded-lg border transition font-medium
                   {{ request()->routeIs('dashboard')
                        ? 'bg-blue-50 text-blue-700 border-blue-600 font-bold'
                        : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50 hover:border-gray-400' }}">
                    Dashboard
                </a>

                <form method="POST" action="{{ route('logout') }}" class="ml-2">
                    @csrf
                    <button class="px-5 py-2 rounded-lg border transition font-medium
                                   bg-white text-red-600 border-red-300 hover:bg-red-50 hover:border-red-400">
                        Sair
                    </button>
                </form>
            @else
                <a href="{{ route('protocolo.index') }}"
                   class="px-5 py-2 rounded-lg border transition font-medium
                          bg-white text-gray-600 border-gray-300 hover:bg-gray-50 hover:border-gray-400">
                    Acompanhar Chamado
                </a>

                <a href="{{ route('home') }}"
                   class="px-5 py-2 rounded-lg border transition font-medium
                          bg-white text-gray-600 border-gray-300 hover:bg-gray-50 hover:border-gray-400">
                    Suporte Técnico
                </a>

                <a href="{{ route('login') }}"
                   class="px-5 py-2 rounded-lg border transition font-medium
                          bg-blue-600 text-white border-blue-600 hover:bg-blue-700 hover:border-blue-700">
                    Painel de Controle
                </a>
            @endauth
        </div>
    </nav>
</header>

<!-- HEADER OPCIONAL (Jetstream / Componentes) -->
@if (isset($header))
<header class="bg-white border-b">
    <div class="max-w-7xl mx-auto py-6 px-6">
        {{ $header }}
    </div>
</header>
@endif

<!-- CONTEÚDO -->
<main class="flex-grow container mx-auto px-6 py-10">
    {{ $slot ?? '' }}
    @yield('content')
</main>

<!-- FOOTER -->
<footer class="bg-gray-900 text-gray-400 py-16 border-t border-gray-800">
    <div class="container mx-auto px-6 grid md:grid-cols-4 gap-12">
        <div>
            <img src="{{ asset('images/logo.png') }}" class="h-8 brightness-0 invert mb-4">
            <p class="text-sm italic border-l-2 border-blue-500 pl-4">
                Soluções inteligentes que simplificam seu suporte.
            </p>
        </div>

        <div>
            <h4 class="text-white text-xs font-bold mb-4">Plataforma</h4>
            <ul class="space-y-2 text-sm">
                <li><a href="{{ route('home') }}">Nova Solicitação</a></li>
                <li><a href="{{ route('protocolo.index') }}">Acompanhar Chamado</a></li>
                @auth
                    <li><a href="{{ route('admin.index') }}">Controle</a></li>
                    <li><a href="{{ route('admin.inventario.index') }}">Inventário TI</a></li>
                @endauth
            </ul>
        </div>

        <div>
            <h4 class="text-white text-xs font-bold mb-4">Empresa</h4>
            <ul class="space-y-2 text-sm">
                <li><a href="#">Política de Privacidade</a></li>
                <li><a href="#">Termos de Uso</a></li>
            </ul>
        </div>

        <div>
            <h4 class="text-white text-xs font-bold mb-4">Contato</h4>
            <p>📧 suporte@simplemind.com.br</p>
            <p>📞 +55 (81) 98235-0502</p>
        </div>
    </div>

    <div class="text-center text-xs mt-12">
        © {{ date('Y') }} Simplemind. Todos os direitos reservados.
    </div>
</footer>

<!-- CHATBOT (APENAS GUEST) -->
@guest
@include('partials.chatbot')
@endguest

</body>
</html>
