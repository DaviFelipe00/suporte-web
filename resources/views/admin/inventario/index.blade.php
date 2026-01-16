@extends('layouts.app')

@section('title', 'Gestão de Inventário - Simplemind')

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Alertas Padronizados --}}
    @if(session('sucesso'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-xl shadow-sm animate-fade-in">
            <p class="text-sm font-bold text-green-800">✅ {{ session('sucesso') }}</p>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm">
            <p class="text-sm font-bold text-red-800 mb-2">❌ Verifique os erros abaixo:</p>
            <ul class="list-disc ml-5 text-xs text-red-700 font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Cabeçalho com Botão Padronizado --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Gestão de Inventário de TI</h2>
            <p class="text-sm text-gray-500 mt-1">Gerencie os ativos, números de série e alocação de equipamentos.</p>
        </div>
        <div>
            <button onclick="toggleModal('modal-create')" 
                    class="flex items-center gap-2 bg-blue-600 text-white font-bold py-3 px-6 rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 active:scale-95">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Novo Equipamento
            </button>
        </div>
    </div>

    {{-- Tabela Estilizada --}}
    <div class="bg-white shadow-2xl rounded-2xl overflow-hidden border border-gray-100">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50/50">
                <tr>
                    <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Equipamento</th>
                    <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Empresa Alocada</th>
                    <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Nº de Série</th>
                    <th class="px-6 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                    <th class="px-6 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($itens as $item)
                    <tr class="hover:bg-blue-50/10 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">{{ $item->nome }}</div>
                            <div class="text-[10px] text-gray-400 uppercase font-bold">{{ $item->tipo }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">
                            {{ $item->empresa }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-mono text-xs text-blue-600 font-bold">
                            {{ $item->numero_serie }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase border 
                                {{ $item->status == 'disponivel' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-amber-50 text-amber-700 border-amber-200' }}">
                                {{ str_replace('_', ' ', $item->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <form action="{{ route('admin.inventario.destroy', $item) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button class="text-red-500 hover:text-red-700 font-black text-[10px] uppercase tracking-tighter transition-all" 
                                        onclick="return confirm('Tem certeza que deseja excluir este equipamento?')">
                                    Remover Ativo
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-400 italic">
                            Nenhum equipamento registrado até o momento.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $itens->links() }}
    </div>
</div>

{{-- Modal de Criação (Mantendo a funcionalidade com estilo ajustado) --}}
<div id="modal-create" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden animate-fade-in-up">
        <div class="bg-blue-600 p-6 text-white flex justify-between items-center">
            <div>
                <p class="text-blue-100 text-[10px] font-black uppercase tracking-widest">Novo Registro</p>
                <h3 class="text-xl font-bold">Cadastrar Equipamento</h3>
            </div>
            <button type="button" onclick="toggleModal('modal-create')" class="text-white/80 hover:text-white text-3xl">&times;</button>
        </div>
        
        <form action="{{ route('admin.inventario.store') }}" method="POST" class="p-8 space-y-4">
            @csrf
            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase block mb-1">Nome do Equipamento</label>
                <input type="text" name="nome" value="{{ old('nome') }}" placeholder="Ex: Macbook Pro 14" class="w-full rounded-xl border-gray-200 text-sm font-bold focus:ring-blue-500" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase block mb-1">Tipo</label>
                    <input type="text" name="tipo" value="{{ old('tipo') }}" placeholder="Notebook" class="w-full rounded-xl border-gray-200 text-sm font-bold focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase block mb-1">Número de Série</label>
                    <input type="text" name="numero_serie" value="{{ old('numero_serie') }}" placeholder="SN123..." class="w-full rounded-xl border-gray-200 text-sm font-bold focus:ring-blue-500" required>
                </div>
            </div>

            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase block mb-1">Empresa Alocada</label>
                <input type="text" name="empresa" value="{{ old('empresa') }}" placeholder="Unidade ou Cliente" class="w-full rounded-xl border-gray-200 text-sm font-bold focus:ring-blue-500" required>
            </div>

            <div>
                <label class="text-[10px] font-black text-gray-400 uppercase block mb-1">Status Atual</label>
                <select name="status" class="w-full rounded-xl border-gray-200 text-sm font-bold focus:ring-blue-500">
                    <option value="disponivel">Disponível</option>
                    <option value="em_uso">Em Uso</option>
                    <option value="manutencao">Manutenção</option>
                    <option value="descartado">Descartado</option>
                </select>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-100">
                <button type="button" onclick="toggleModal('modal-create')" class="px-6 py-3 text-xs font-black uppercase text-gray-400 hover:text-gray-600 transition">
                    Cancelar
                </button>
                <button type="submit" class="bg-blue-600 text-white font-bold py-3 px-6 rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                    Salvar Equipamento
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(id) {
        document.getElementById(id).classList.toggle('hidden');
    }
</script>
@endsection