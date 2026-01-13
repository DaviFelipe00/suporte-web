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
            <p class="text-sm text-gray-500 mt-1">Monitore e atualize o progresso dos chamados técnicos.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <span class="bg-blue-100 text-blue-700 text-xs font-black px-4 py-2 rounded-full uppercase tracking-widest shadow-sm">
                {{ $chamados->count() }} Chamados no Total
            </span>
        </div>
    </div>

    <div class="bg-white shadow-2xl rounded-2xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Solicitante</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Motivo / Data</th>
                        <th class="px-6 py-4 text-left text-xs font-black text-gray-400 uppercase tracking-widest">Status Atual</th>
                        <th class="px-6 py-4 text-right text-xs font-black text-gray-400 uppercase tracking-widest">Ações de Gestão</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($chamados as $chamado)
                    <tr class="hover:bg-blue-50/20 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $chamado->nome_solicitante }}</span>
                                <span class="text-xs text-gray-500 font-medium">{{ $chamado->email_solicitante }}</span>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="px-2 py-0.5 text-[10px] font-bold bg-gray-100 text-gray-600 rounded w-fit border border-gray-200 mb-1">
                                    {{ ucfirst($chamado->motivo_contato) }}
                                </span>
                                <span class="text-[10px] text-gray-400 font-bold uppercase">{{ $chamado->created_at->format('d/m/Y - H:i') }}</span>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                // Configuração de cores profissional para cada status
                                $statusStyles = [
                                    'novo'         => 'bg-gray-100 text-gray-700 border-gray-300',
                                    'pendente'     => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'em_andamento' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'resolvido'    => 'bg-green-50 text-green-700 border-green-200',
                                ];
                                
                                $statusLabel = [
                                    'novo'         => 'Novo',
                                    'pendente'     => 'Pendente',
                                    'em_andamento' => 'Em Andamento',
                                    'resolvido'    => 'Resolvido',
                                ];
                            @endphp
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-black uppercase border {{ $statusStyles[$chamado->status] ?? $statusStyles['novo'] }}">
                                    @if($chamado->status == 'novo' || $chamado->status == 'em_andamento')
                                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $chamado->status == 'novo' ? 'bg-gray-400' : 'bg-blue-500 animate-pulse' }}"></span>
                                    @endif
                                    {{ $statusLabel[$chamado->status] ?? 'Desconhecido' }}
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-3">
                                <form action="{{ route('admin.chamados.update', $chamado) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" 
                                            class="text-[10px] font-bold uppercase tracking-wider border-gray-200 rounded-lg focus:ring-4 focus:ring-blue-50 focus:border-blue-500 py-1.5 pl-3 pr-8 transition-all cursor-pointer">
                                        <option value="novo" {{ $chamado->status == 'novo' ? 'selected' : '' }}>Novo</option>
                                        <option value="pendente" {{ $chamado->status == 'pendente' ? 'selected' : '' }}>Pendente</option>
                                        <option value="em_andamento" {{ $chamado->status == 'em_andamento' ? 'selected' : '' }}>Andamento</option>
                                        <option value="resolvido" {{ $chamado->status == 'resolvido' ? 'selected' : '' }}>Resolvido</option>
                                    </select>
                                </form>

                                <form action="{{ route('admin.chamados.destroy', $chamado) }}" method="POST" 
                                      onsubmit="return confirm('ATENÇÃO: Deseja excluir permanentemente este chamado da Simplemind?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Excluir Chamado">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="bg-gray-100 p-6 rounded-full mb-4">
                                    <span class="text-4xl opacity-50">📂</span>
                                </div>
                                <h3 class="text-lg font-bold text-gray-800">Tudo limpo por aqui!</h3>
                                <p class="text-gray-500 text-sm">Nenhuma solicitação técnica pendente no momento.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection