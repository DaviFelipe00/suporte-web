<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        Simplemind - {{ $title ?? trim($__env->yieldContent('title')) ?? 'Gestão' }}
    </title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 flex flex-col min-h-screen font-sans antialiased">

    <header class="bg-white shadow-sm sticky top-0 z-50">
        @include('layouts.navigation')
    </header>

    @if (isset($header))
    <header class="bg-white border-b">
        <div class="max-w-7xl mx-auto py-6 px-6">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $header }}
            </h2>
        </div>
    </header>
    @endif

    <main class="flex-grow container mx-auto px-4 sm:px-6 py-10">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <footer class="bg-gray-900 text-gray-400 py-16 border-t border-gray-800">
        <div class="container mx-auto px-6 grid md:grid-cols-4 gap-12">
            <div>
                <img src="{{ asset('images/logo.png') }}" class="h-8 brightness-0 invert mb-4" alt="Simplemind">
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

    @guest
        @include('partials.chatbot')
    @endguest

</body>
</html>