<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-semibold text-gray-800">📦 Inventário de TI</h2>
                <button onclick="toggleModal('modal-create')" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">
                    + Novo Equipamento
                </button>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-50">
                                <th class="p-3 text-sm font-semibold">Equipamento</th>
                                <th class="p-3 text-sm font-semibold">Empresa</th>
                                <th class="p-3 text-sm font-semibold">Série</th>
                                <th class="p-3 text-sm font-semibold">Status</th>
                                <th class="p-3 text-sm font-semibold text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($itens as $item)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="p-3 text-sm">{{ $item->nome }} <span class="text-gray-400">({{ $item->tipo }})</span></td>
                                <td class="p-3 text-sm">{{ $item->empresa }}</td>
                                <td class="p-3 text-sm font-mono text-xs">{{ $item->numero_serie }}</td>
                                <td class="p-3 text-sm">
                                    <span class="px-2 py-1 rounded-full text-xs font-bold 
                                        {{ $item->status == 'disponivel' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td class="p-3 text-sm text-right">
                                    <form action="{{ route('admin.inventario.destroy', $item) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 hover:underline" onclick="return confirm('Excluir item?')">Remover</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $itens->links() }}</div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-create" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6">
            <h3 class="text-lg font-bold mb-4">Novo Registro</h3>
            <form action="{{ route('admin.inventario.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="text" name="nome" placeholder="Nome do Item" class="w-full border-gray-300 rounded" required>
                <input type="text" name="tipo" placeholder="Tipo (Ex: Notebook)" class="w-full border-gray-300 rounded" required>
                <input type="text" name="numero_serie" placeholder="Número de Série" class="w-full border-gray-300 rounded" required>
                <input type="text" name="empresa" placeholder="Empresa Alocada" class="w-full border-gray-300 rounded" required>
                <select name="status" class="w-full border-gray-300 rounded">
                    <option value="disponivel">Disponível</option>
                    <option value="em_uso">Em Uso</option>
                    <option value="manutencao">Manutenção</option>
                </select>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="toggleModal('modal-create')" class="px-4 py-2 text-gray-600">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleModal(id) { document.getElementById(id).classList.toggle('hidden'); }
    </script>
</x-app-layout>