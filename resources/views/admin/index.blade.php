@extends('layouts.app')

@section('title', 'Controle de Chamados - Simplemind')

@section('content')
<div class="max-w-7xl mx-auto">
    @if(session('sucesso'))
        <div class="mb-6 flex items-center gap-3 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-xl shadow-sm animate-fade-in">
            <span class="text-green-600 text-xl">✅</span>
            <p class="text-sm font-bold text-green-800">{{ session('sucesso') }}</p>
        </div>
    @endif

    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Gerenciamento de Solicitações</h2>
            <p class="text-sm text-gray-500 mt-1">Clique em "Ver Detalhes" para ler a descrição completa e ver os anexos.</p>
        </div>
        <span class="bg-blue-100 text-blue-700 text-xs font-black px-4 py-2 rounded-full uppercase tracking-widest">
            {{ $chamados->count() }} Chamados no Total
        </span>
    </div>

    <div class="bg-white shadow-2xl rounded-2xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Solicitante</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Protocolo / Data</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-black text-gray-400 uppercase tracking-widest">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($chamados as $chamado)
                    <tr class="hover:bg-blue-50/10 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $chamado->nome_solicitante }}</span>
                                <span class="text-xs text-gray-500 font-medium">{{ $chamado->email_solicitante }}</span>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-[11px] font-mono font-bold text-blue-600 mb-1">{{ $chamado->protocolo }}</span>
                                <span class="text-[10px] text-gray-400 font-bold uppercase">{{ $chamado->created_at->format('d/m/Y - H:i') }}</span>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusStyles = [
                                    'novo' => 'bg-gray-100 text-gray-700 border-gray-300',
                                    'pendente' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'em_andamento' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'resolvido' => 'bg-green-50 text-green-700 border-green-200',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black uppercase border {{ $statusStyles[$chamado->status] }}">
                                {{ str_replace('_', ' ', $chamado->status) }}
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-3">
                                <button onclick="openModal('{{ addslashes($chamado->nome_solicitante) }}', 
                                                           '{{ $chamado->email_solicitante }}', 
                                                           '{{ $chamado->protocolo }}', 
                                                           '{{ addslashes($chamado->descricao_duvida) }}', 
                                                           '{{ $chamado->arquivo_anexo }}')" 
                                        class="text-blue-600 hover:text-blue-800 font-black text-xs uppercase tracking-tighter transition-all">
                                    Ver Detalhes
                                </button>

                                <form action="{{ route('admin.chamados.destroy', $chamado) }}" method="POST" onsubmit="return confirm('Excluir permanentemente?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 p-1 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="detailsModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl overflow-hidden animate-fade-in-up">
        <div class="bg-blue-600 p-6 text-white flex justify-between items-center">
            <div>
                <p class="text-blue-100 text-[10px] font-black uppercase tracking-widest">Detalhes do Chamado</p>
                <h3 id="modalProtocolo" class="text-xl font-mono font-bold mt-1">---</h3>
            </div>
            <button onclick="closeModal()" class="bg-white/10 hover:bg-white/20 p-2 rounded-xl transition-all">&times;</button>
        </div>

        <div class="p-8 max-h-[70vh] overflow-y-auto">
            <div class="grid grid-cols-2 gap-6 mb-8">
                <div>
                    <h4 class="text-[10px] font-black text-gray-400 uppercase mb-1">Solicitante</h4>
                    <p id="modalNome" class="text-sm font-bold text-gray-800">---</p>
                    <p id="modalEmail" class="text-xs text-gray-500">---</p>
                </div>
            </div>

            <div class="mb-8">
                <h4 class="text-[10px] font-black text-gray-400 uppercase mb-2">Descrição Completa</h4>
                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 italic text-gray-700 text-sm leading-relaxed" id="modalDescricao">
                    ---
                </div>
            </div>

            <div>
                <h4 class="text-[10px] font-black text-gray-400 uppercase mb-3">Anexos e Evidências</h4>
                <div id="modalAnexos" class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(nome, email, protocolo, descricao, anexosJson) {
        document.getElementById('modalNome').textContent = nome;
        document.getElementById('modalEmail').textContent = email;
        document.getElementById('modalProtocolo').textContent = protocolo;
        document.getElementById('modalDescricao').textContent = descricao;

        const anexosContainer = document.getElementById('modalAnexos');
        anexosContainer.innerHTML = '';

        if (anexosJson && anexosJson !== 'null') {
            const anexos = JSON.parse(anexosJson);
            anexos.forEach(path => {
                const url = `/storage/${path}`;
                const extension = path.split('.').pop().toLowerCase();
                
                let element = '';
                if (['jpg', 'jpeg', 'png', 'gif'].includes(extension)) {
                    element = `<a href="${url}" target="_blank" class="group relative block aspect-square rounded-xl overflow-hidden border border-gray-200">
                                <img src="${url}" class="w-full h-full object-cover transition-transform group-hover:scale-110">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all">
                                    <span class="text-[10px] text-white font-bold">Ver Foto</span>
                                </div>
                               </a>`;
                } else {
                    element = `<a href="${url}" target="_blank" class="flex items-center justify-center aspect-square rounded-xl bg-gray-100 border border-gray-200 hover:bg-gray-200 transition-all">
                                <span class="text-[10px] font-bold text-gray-600 uppercase">📄 PDF / Doc</span>
                               </a>`;
                }
                anexosContainer.innerHTML += element;
            });
        } else {
            anexosContainer.innerHTML = '<p class="text-xs text-gray-400 italic">Nenhum anexo enviado.</p>';
        }

        document.getElementById('detailsModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('detailsModal').classList.add('hidden');
    }
</script>
@endsection