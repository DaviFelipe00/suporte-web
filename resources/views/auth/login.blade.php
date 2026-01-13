<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-2xl font-black text-gray-900 tracking-tight">Bem-vindo !</h2>
        <p class="text-sm text-gray-500 font-medium">Acesse sua conta administrativa para gerenciar chamados.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <label for="email" class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">E-mail Corporativo</label>
            <x-text-input id="email" class="block mt-1 w-full !rounded-2xl border-gray-100 bg-gray-50/50 focus:bg-white transition-all" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-6">
            <div class="flex justify-between items-center mb-2">
                <label for="password" class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Senha</label>
                @if (Route::has('password.request'))
                    <a class="text-[10px] font-black text-blue-600 hover:text-blue-800 uppercase tracking-tighter" href="{{ route('password.request') }}">
                        Esqueceu?
                    </a>
                @endif
            </div>
            <x-text-input id="password" class="block mt-1 w-full !rounded-2xl border-gray-100 bg-gray-50/50 focus:bg-white transition-all"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-6">
            <label for="remember_me" class="inline-flex items-center group cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded-lg border-gray-200 text-blue-600 shadow-sm focus:ring-blue-500 transition-all" name="remember">
                <span class="ms-2 text-xs font-bold text-gray-500 group-hover:text-gray-700 transition-colors uppercase tracking-widest">Lembrar neste navegador</span>
            </label>
        </div>

        <div class="flex flex-col gap-4 mt-10">
            <button type="submit" class="w-full bg-blue-600 text-white font-black py-4 rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 active:scale-95 uppercase tracking-[0.2em] text-xs">
                Entrar no Painel
            </button>

            <a href="/" class="w-full text-center py-3 text-[10px] font-black text-gray-400 hover:text-gray-600 uppercase tracking-[0.2em] transition-colors border border-dashed border-gray-200 rounded-2xl hover:border-gray-400">
                ← Voltar para o Site
            </a>
        </div>
    </form>
</x-guest-layout>