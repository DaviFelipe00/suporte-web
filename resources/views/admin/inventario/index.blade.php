<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestão de Inventário') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                <h3 class="text-lg font-medium mb-4">Registrar Novo Equipamento</h3>
                <form action="{{ route('admin.inventario.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @csrf
                    <input type="text" name="nome" placeholder="Nome do Equipamento" class="rounded-md border-gray-300" required>
                    <input type="text" name="tipo" placeholder="Tipo (Ex: Notebook)" class="rounded-md border-gray-300" required>
                    <input type="text" name="numero_serie" placeholder="Nº de Série" class="rounded-md border-gray-300" required>
                    <input type="text" name="empresa" placeholder="Empresa Alocada" class="rounded-md border-gray-300" required>
                    <select name="status" class="rounded-md border-gray-300">
                        <option value="disponivel">Disponível</option>
                        <option value="em_uso">Em Uso</option>
                        <option value="manutencao">Manutenção</option>
                    </select>
                    <button type="submit" class="bg-blue-600 text-white rounded-md px-4 py-2 hover:bg-blue-700">
                        Salvar Item
                    </button>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Equipamento</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Série</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Empresa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($itens as $item)
                        <tr>
                            <td class="px-6 py-4">{{ $item->nome }} ({{ $item->tipo }})</td>
                            <td class="px-6 py-4">{{ $item->numero_serie }}</td>
                            <td class="px-6 py-4">{{ $item->empresa }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $item->status == 'em_uso' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.inventario.destroy', $item) }}" method="POST" onsubmit="return confirm('Excluir este item?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Remover</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $itens->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>