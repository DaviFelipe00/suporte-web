@extends('layouts.app')

@section('title', 'Cadastrar Novo Admin - Simplemind')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Cadastrar Novo Administrador</h1>
            <p class="text-sm text-gray-500">Adicione um novo membro à equipe de suporte da Simplemind.</p>
        </div>
        <a href="{{ route('admin.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">
            &larr; Voltar para Chamados
        </a>
    </div>

    <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
        <div class="p-8">
            <form method="POST" action="{{ route('admin.user.store') }}">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nome Completo</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all outline-none"
                           placeholder="Ex: João Silva">
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="mt-6">
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Endereço de E-mail</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all outline-none"
                           placeholder="joao.admin@simplemind.com.br">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Senha de Acesso</label>
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all outline-none"
                               placeholder="Mínimo 8 caracteres">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Confirmar Senha</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                               class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-blue-50 focus:border-blue-500 transition-all outline-none"
                               placeholder="Repita a senha">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>
                </div>

                <div class="mt-10 flex items-center justify-end border-t border-gray-50 pt-6">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-blue-200 transition-all transform active:scale-95">
                        Concluir Cadastro
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-6 flex items-center gap-3 bg-amber-50 border border-amber-100 p-4 rounded-xl">
        <span class="text-xl">⚠️</span>
        <p class="text-xs text-amber-800 leading-relaxed">
            <strong>Aviso de Segurança:</strong> Novos administradores terão acesso total ao sistema de chamados. 
            Certifique-se de que o e-mail cadastrado é de um membro confiável da equipe <strong>Simplemind</strong>.
        </p>
    </div>
</div>
@endsection