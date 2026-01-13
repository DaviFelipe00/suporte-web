@extends('layouts.app')

@section('title', 'Controle de Chamados - Simplemind')

@section('content')
<div class="max-w-7xl mx-auto">
    @if(session('sucesso'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-xl shadow-sm animate-fade-in">
            <p class="text-sm font-bold text-green-800">✅ {{ session('sucesso') }}</p>
        </div>
    @endif

    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Gestão de Chamados</h2>
            <p class="text-sm text-gray-500 mt-1">Visualize detalhes, anexos e atualize o status dos pedidos.</p>
        </div>
    </div>

    <div class="bg-white shadow-2xl rounded-2xl overflow-hidden border border-gray-100">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50/50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Solicitante</th>
                    <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Protocolo</th>
                    <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-black text-gray-400 uppercase tracking-widest">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @foreach($chamados as $chamado)
                <tr class="hover:bg-blue-50/10 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-bold text-gray-900">{{ $chamado->nome_solicitante }}</div>
                        <div class="text-xs text-gray-500">{{ $chamado->email_solicitante }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap font-mono text-xs text-blue-600 font-bold">
                        {{ $chamado->protocolo }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase border 
                            {{ $chamado->status == 'resolvido' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-amber-50 text-amber-700 border-amber-200' }}">
                            {{ str_replace('_', ' ', $chamado->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button onclick="openTicketModal({{ $chamado->id }}, '{{ addslashes($chamado->status) }}', '{{ addslashes($chamado->resposta_admin) }}', '{{ addslashes($chamado->descricao_duvida) }}', '{{ $chamado->arquivo_anexo }}', '{{ $chamado->protocolo }}')" 
                                class="text-blue-600 hover:text-blue-800 font-black text-xs uppercase tracking-tighter transition-all">
                            Ver Detalhes & Editar
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="ticketModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-3xl rounded-3xl shadow-2xl overflow-hidden animate-fade-in-up">
        <form id="ticketForm" method="POST">
            @csrf @method('PATCH')
            
            <div class="bg-blue-600 p-6 text-white flex justify-between items-center">
                <div>
                    <p class="text-blue-100 text-[10px] font-black uppercase tracking-widest">Protocolo de Atendimento</p>
                    <h3 id="modalProtocolo" class="text-xl font-mono font-bold">---</h3>
                </div>
                <button type="button" onclick="closeModal()" class="text-white/80 hover:text-white text-3xl">&times;</button>
            </div>

            <div class="p-8 max-h-[75vh] overflow-y-auto space-y-8">
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase block mb-2">Descrição do Cliente</label>
                    <p id="modalDescricao" class="text-sm text-gray-700 leading-relaxed bg-gray-50 p-4 rounded-2xl italic border border-gray-100"></p>
                </div>

                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase block mb-3">Arquivos em Anexo</label>
                    <div id="modalAnexos" class="grid grid-cols-3 md:grid-cols-4 gap-4">
                        </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6 pt-6 border-t border-gray-100">
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase block mb-2">Atualizar Status</label>
                        <select name="status" id="modalStatus" class="w-full rounded-xl border-gray-200 text-sm font-bold focus:ring-blue-500">
                            <option value="novo">Novo</option>
                            <option value="pendente">Pendente</option>
                            <option value="em_andamento">Em Andamento</option>
                            <option value="resolvido">Resolvido</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                            Salvar Alterações
                        </button>
                    </div>
                </div>

                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase block mb-2">Resposta Técnica (Visível ao Cliente)</label>
                    <textarea name="resposta_admin" id="modalResposta" rows="4" 
                              class="w-full rounded-2xl border-gray-200 text-sm focus:ring-blue-500" 
                              placeholder="Escreva a solução ou orientações para o cliente aqui..."></textarea>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function openTicketModal(id, status, resposta, descricao, anexosJson, protocolo) {
        // Configura o formulário
        document.getElementById('ticketForm').action = `/admin/chamados/${id}`;
        document.getElementById('modalStatus').value = status;
        document.getElementById('modalResposta').value = resposta;
        document.getElementById('modalDescricao').textContent = descricao;
        document.getElementById('modalProtocolo').textContent = protocolo;

        // Limpa e reconstrói a galeria de anexos
        const container = document.getElementById('modalAnexos');
        container.innerHTML = '';

        if (anexosJson && anexosJson !== 'null' && anexosJson !== '[]') {
            const anexos = JSON.parse(anexosJson);
            anexos.forEach(path => {
                const url = `/storage/${path}`;
                const ext = path.split('.').pop().toLowerCase();
                
                let html = '';
                if (['jpg', 'jpeg', 'png', 'gif'].includes(ext)) {
                    html = `<a href="${url}" target="_blank" class="block aspect-square rounded-xl overflow-hidden border border-gray-200 hover:opacity-80 transition-opacity">
                                <img src="${url}" class="w-full h-full object-cover">
                            </a>`;
                } else {
                    html = `<a href="${url}" target="_blank" class="flex items-center justify-center aspect-square rounded-xl bg-gray-100 border border-gray-200 text-[10px] font-bold text-gray-500 uppercase hover:bg-gray-200">
                                📄 PDF
                            </a>`;
                }
                container.innerHTML += html;
            });
        } else {
            container.innerHTML = '<p class="text-xs text-gray-400 italic col-span-full">Nenhum anexo disponível.</p>';
        }

        document.getElementById('ticketModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('ticketModal').classList.add('hidden');
    }
</script>
@endsection