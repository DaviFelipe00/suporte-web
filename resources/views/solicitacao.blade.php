@extends('layouts.app')

@section('title', 'Nova Solicitação')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-10 rounded-lg shadow-md border border-gray-100">
    
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Criar Nova Solicitação</h2>

    @if(session('sucesso'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            {{ session('sucesso') }}
        </div>
    @endif

    <form action="{{ route('solicitacao.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-6">
            <label for="nome" class="block text-gray-700 font-bold mb-2">* Nome do solicitante</label>
            <input type="text" id="nome" name="nome_solicitante" placeholder="Digite seu nome completo" value="{{ old('nome_solicitante') }}"
                class="w-full p-3 border @error('nome_solicitante') border-red-500 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('nome_solicitante') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="mb-6">
            <label for="telefone" class="block text-gray-700 font-bold mb-2">* Número de telefone do solicitante</label>
            <div class="flex gap-2">
                <span class="inline-flex items-center px-3 border border-gray-300 bg-gray-50 rounded-md text-xl">🇧🇷</span>
                <input type="text" id="telefone" name="telefone_solicitante" placeholder="(99) 99999-9999" value="{{ old('telefone_solicitante') }}"
                    class="w-full p-3 border @error('telefone_solicitante') border-red-500 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            @error('telefone_solicitante') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="mb-6">
            <label for="email" class="block text-gray-700 font-bold mb-2">* E-mail do solicitante</label>
            <input type="email" id="email" name="email_solicitante" placeholder="email@exemplo.com" value="{{ old('email_solicitante') }}"
                class="w-full p-3 border @error('email_solicitante') border-red-500 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('email_solicitante') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">* Motivo do contato</label>
            <div class="flex flex-wrap gap-4 mt-2">
                @foreach(['suporte' => 'Suporte técnico', 'duvida' => 'Dúvida', 'solicitacao' => 'Solicitação', 'outro' => 'Outro'] as $value => $label)
                    <label class="flex items-center cursor-pointer group">
                        <input type="radio" name="motivo_contato" value="{{ $value }}" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" {{ old('motivo_contato') == $value ? 'checked' : '' }}>
                        <span class="ml-2 text-gray-700 group-hover:text-blue-600 transition-colors">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
            @error('motivo_contato') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="mb-6">
            <label for="descricao" class="block text-gray-700 font-bold mb-2">Descreva sua dúvida</label>
            <p class="text-gray-500 text-sm mb-2">Forneça detalhes para que possamos ajudar melhor.</p>
            <textarea id="descricao" name="descricao_duvida" rows="4" placeholder="Descreva aqui o seu problema..."
                class="w-full p-3 border @error('descricao_duvida') border-red-500 @else border-gray-300 @enderror rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('descricao_duvida') }}</textarea>
            @error('descricao_duvida') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="mb-8 p-6 bg-blue-50 rounded-lg border-2 border-dashed border-blue-200">
            <label class="block text-blue-800 font-bold mb-2">Anexos e Evidências</label>
            <p class="text-blue-600 text-sm mb-4">Você pode selecionar múltiplos arquivos (Imagens ou PDF).</p>
            
            <input type="file" name="anexo[]" multiple 
                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer">
            
            <p class="mt-3 text-xs text-blue-500">Limite: 2MB por arquivo. Formatos: JPG, PNG, PDF.</p>
            @error('anexo.*') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="w-full md:w-48 bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1">
            Enviar Solicitação
        </button>
    </form>
</div>
@endsection