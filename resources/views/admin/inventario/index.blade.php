<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestão de Inventário de TI') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('sucesso'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('sucesso') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <ul class="list-disc ml-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">Ativos Registrados</h2>
                <button onclick="toggleModal('modal-create')" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">
                    + Novo Equipamento
                </button>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-50">
                                <th class="p-3 text-sm font-semibold text-gray-700">Equipamento</th>
                                <th class="p-3 text-sm font-semibold text-gray-700">Empresa Alocada</th>
                                <th class="p-3 text-sm font-semibold text-gray-700">Nº de Série</th>
                                <th class="p-3 text-sm font-semibold text-gray-700">Status</th>
                                <th class="p-3 text-sm font-semibold text-right text-gray-700">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($itens as $item)
                                <tr class="border-b hover:bg-gray-50 transition">
                                    <td class="p-3 text-sm">
                                        <div class="font-medium text-gray-900">{{ $item->nome }}</div>
                                        <div class="text-gray-400 text-xs">{{ $item->tipo }}</div>
                                    </td>
                                    <td class="p-3 text-sm text-gray-600">{{ $item->empresa }}</td>
                                    <td class="p-3 text-sm font-mono text-xs text-gray-500">{{ $item->numero_serie }}</td>
                                    <td class="p-3 text-sm">
                                        <span class="px-2 py-1 rounded-full text-xs font-bold 
                                            {{ $item->status == 'disponivel' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                                            {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-sm text-right">
                                        <form action="{{ route('admin.inventario.destroy', $item) }}" method="POST" class="inline">
                                            @csrf 
                                            @method('DELETE')
                                            <button class="text-red-600 hover:text-red-800 font-semibold" onclick="return confirm('Tem certeza que deseja excluir este equipamento?')">
                                                Remover
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-gray-500">
                                        Nenhum equipamento registrado até o momento.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <div class="mt-6">
                        {{ $itens->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-create" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">Novo Registro de Equipamento</h3>
                <button onclick="toggleModal('modal-create')" class="text-gray-400 hover:text-gray-600 font-bold text-xl">&times;</button>
            </div>
            
            <form action="{{ route('admin.inventario.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nome do Equipamento</label>
                    <input type="text" name="nome" value="{{ old('nome') }}" placeholder="Ex: Macbook Pro 14" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipo</label>
                    <input type="text" name="tipo" value="{{ old('tipo') }}" placeholder="Ex: Notebook, Monitor" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Número de Série</label>
                    <input type="text" name="numero_serie" value="{{ old('numero_serie') }}" placeholder="Nº de Série único" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Empresa Alocada</label>
                    <input type="text" name="empresa" value="{{ old('empresa') }}" placeholder="Unidade ou Cliente" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="disponivel" {{ old('status') == 'disponivel' ? 'selected' : '' }}>Disponível</option>
                        <option value="em_uso" {{ old('status') == 'em_uso' ? 'selected' : '' }}>Em Uso</option>
                        <option value="manutencao" {{ old('status') == 'manutencao' ? 'selected' : '' }}>Manutenção</option>
                        <option value="descartado" {{ old('status') == 'descartado' ? 'selected' : '' }}>Descartado</option>
                    </select>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                    <button type="button" onclick="toggleModal('modal-create')" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 shadow-sm transition">
                        Salvar Registro
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModal(id) {
            const modal = document.getElementById(id);
            modal.classList.toggle('hidden');
        }
    </script>
</x-app-layout>