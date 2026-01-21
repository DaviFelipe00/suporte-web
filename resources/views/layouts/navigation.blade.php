<nav x-data="{ open: false }" class="bg-white">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
        <a href="{{ route('home') }}" class="flex items-center gap-2 hover:opacity-80 transition">
            <img src="{{ asset('images/logo.png') }}" class="h-10" alt="Simplemind">
        </a>

        <div class="hidden md:flex items-center gap-3">
            @auth
                <a href="{{ route('admin.inventario.index') }}"
                   class="px-5 py-2 rounded-lg border transition font-medium {{ request()->routeIs('admin.inventario.*') ? 'bg-blue-50 text-blue-700 border-blue-600 font-bold' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}">
                    Inventário
                </a>

                <a href="{{ route('admin.user.create') }}"
                   class="px-5 py-2 rounded-lg border transition font-medium {{ request()->routeIs('admin.user.create') ? 'bg-blue-50 text-blue-700 border-blue-600 font-bold' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}">
                    Adicionar usuário
                </a>

                <a href="{{ route('admin.index') }}"
                   class="px-5 py-2 rounded-lg border transition font-medium {{ request()->routeIs('admin.index') ? 'bg-blue-50 text-blue-700 border-blue-600 font-bold' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}">
                    Controle de Chamados
                </a>

                <a href="{{ route('dashboard') }}"
                   class="px-5 py-2 rounded-lg border transition font-medium {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 border-blue-600 font-bold' : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}">
                    Dashboard
                </a>

                <form method="POST" action="{{ route('logout') }}" class="ml-2">
                    @csrf
                    <button class="px-5 py-2 rounded-lg border transition font-medium bg-white text-red-600 border-red-300 hover:bg-red-50">
                        Sair
                    </button>
                </form>
            @else
                <a href="{{ route('protocolo.index') }}" class="px-5 py-2 rounded-lg border transition font-medium bg-white text-gray-600 border-gray-300 hover:bg-gray-50">
                    Acompanhar Chamado
                </a>

                <a href="{{ route('home') }}" class="px-5 py-2 rounded-lg border transition font-medium bg-white text-gray-600 border-gray-300 hover:bg-gray-50">
                    Abrir Chamado
                </a>

                <a href="{{ route('login') }}" class="px-5 py-2 rounded-lg border transition font-medium bg-blue-600 text-white border-blue-600 hover:bg-blue-700">
                    Painel de Controle
                </a>
            @endauth
        </div>

        <div class="flex items-center md:hidden">
            <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden md:hidden border-t border-gray-100 bg-gray-50 pb-4">
        <div class="pt-2 pb-3 space-y-1 px-4">
            @auth
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.index')" :active="request()->routeIs('admin.index')">Chamados</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.inventario.index')" :active="request()->routeIs('admin.inventario.*')">Inventário</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.user.create')" :active="request()->routeIs('admin.user.create')">Novo Usuário</x-responsive-nav-link>
                
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="px-4 mb-3">
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="w-full text-left px-4 py-2 text-red-600 font-bold">Sair</button>
                    </form>
                </div>
            @else
                <x-responsive-nav-link :href="route('home')">Abrir Chamado</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('protocolo.index')">Acompanhar Chamado</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('login')" class="text-blue-600 font-bold">Painel de Controle</x-responsive-nav-link>
            @endauth
        </div>
    </div>
</nav>