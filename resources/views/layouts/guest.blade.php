<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Simplemind - Acesso Administrativo</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-gray-50 to-blue-50 px-4">
        
        <div class="w-full sm:max-w-md px-8 py-12 bg-white shadow-[0_20px_60px_rgba(8,_112,_184,_0.1)] border border-gray-100 overflow-hidden sm:rounded-[2.5rem]">
            {{ $slot }}
        </div>

        <div class="mt-10 text-center">
            <p class="text-[10px] text-gray-400 font-black tracking-[0.3em] uppercase opacity-60">
                Simplemind • Painel Seguro
            </p>
        </div>
    </div>
</body>
</html>