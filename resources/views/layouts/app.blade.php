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
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-xl">S</div>
                <span class="text-xl font-bold text-gray-800">Suporte<span class="text-blue-600">Web</span></span>
            </div>
            
            <div class="hidden md:flex items-center gap-8 text-gray-600 font-medium">
                <a href="/" class="hover:text-blue-600 transition-colors">Início</a>
                <a href="#" class="hover:text-blue-600 transition-colors">Solicitações</a>
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

</body>
</html>