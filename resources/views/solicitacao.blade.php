<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário de Solicitação</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-3xl mx-auto bg-white p-10 rounded-lg shadow-sm">
        
        @if(session('sucesso'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('sucesso') }}
            </div>
        @endif

        <form action="{{ route('solicitacao.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-6">
                <label for="nome" class="block text-gray-700 font-bold mb-2">* Nome do solicitante</label>
                <input type="text" id="nome" name="nome_solicitante" placeholder="Digite aqui..." value="{{ old('nome_solicitante') }}"
                    class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('nome_solicitante') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <label for="telefone" class="block text-gray-700 font-bold mb-2">* Número de telefone do solicitante</label>
                <div class="flex gap-2">
                    <span class="inline-flex items-center px-3 border border-gray-300 bg-gray-50 rounded-md">🇧🇷</span>
                    <input type="text" id="telefone" name="telefone_solicitante" placeholder="(99) 99999-9999" value="{{ old('telefone_solicitante') }}"
                        class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                @error('telefone_solicitante') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <label for="email" class="block text-gray-700 font-bold mb-2">* E-mail do solicitante</label>
                <input type="email" id="email" name="email_solicitante" placeholder="Digite aqui..." value="{{ old('email_solicitante') }}"
                    class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('email_solicitante') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">* Motivo do contato</label>
                <div class="flex flex-wrap gap-4 mt-2">
                    <label class="flex items-center cursor-pointer"><input type="radio" name="motivo_contato" value="suporte" class="mr-2" {{ old('motivo_contato') == 'suporte' ? 'checked' : '' }}> Suporte técnico</label>
                    <label class="flex items-center cursor-pointer"><input type="radio" name="motivo_contato" value="duvida" class="mr-2" {{ old('motivo_contato') == 'duvida' ? 'checked' : '' }}> Dúvida</label>
                    <label class="flex items-center cursor-pointer"><input type="radio" name="motivo_contato" value="solicitacao" class="mr-2" {{ old('motivo_contato') == 'solicitacao' ? 'checked' : '' }}> Solicitação</label>
                    <label class="flex items-center cursor-pointer"><input type="radio" name="motivo_contato" value="outro" class="mr-2" {{ old('motivo_contato') == 'outro' ? 'checked' : '' }}> Outro</label>
                </div>
                @error('motivo_contato') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-6">
                <label for="descricao" class="block text-gray-700 font-bold mb-2">Descreva sua dúvida</label>
                <p class="text-gray-500 text-sm mb-2">Descreva um breve relato sobre sua dúvida.</p>
                <textarea id="descricao" name="descricao_duvida" rows="4" placeholder="Digite aqui..."
                    class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('descricao_duvida') }}</textarea>
                @error('descricao_duvida') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-8">
                <label class="block text-gray-700 font-bold mb-2">Anexos</label>
                <p class="text-gray-500 text-sm mb-2">Favor inserir uma ou mais evidências</p>
                
                <input type="file" name="anexo[]" multiple 
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                
                <p class="mt-2 text-xs text-gray-400">Formatos aceitos: JPG, PNG, PDF (Máx. 2MB por arquivo)</p>
                @error('anexo.*') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="w-32 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-md transition duration-200">
                Enviar
            </button>
        </form>
    </div>

</body>
</html>