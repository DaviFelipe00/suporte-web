@extends('layouts.app')

@section('title', 'Dashboard Operacional - Simplemind')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Painel de Controle</h1>
            <p class="mt-1 text-sm text-gray-500 font-medium italic">Dados atualizados em tempo real para a gestão Simplemind.</p>
        </div>
        <div class="flex gap-2">
            <span class="inline-flex items-center px-4 py-2 rounded-xl bg-white border border-gray-100 shadow-sm text-xs font-black text-blue-600 uppercase tracking-widest">
                📅 {{ date('d/m/Y') }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Novos</p>
            <p class="text-2xl font-bold text-gray-900">{{ $statusCounts['novo'] ?? 0 }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-[10px] font-black text-amber-400 uppercase tracking-widest mb-1">Pendentes</p>
            <p class="text-2xl font-bold text-gray-900">{{ $statusCounts['pendente'] ?? 0 }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-1">Em Andamento</p>
            <p class="text-2xl font-bold text-gray-900">{{ $statusCounts['em_andamento'] ?? 0 }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <p class="text-[10px] font-black text-green-400 uppercase tracking-widest mb-1">Resolvidos</p>
            <p class="text-2xl font-bold text-gray-900">{{ $statusCounts['resolvido'] ?? 0 }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1 space-y-8">
            <div class="bg-gradient-to-br from-blue-600 to-blue-800 p-8 rounded-3xl shadow-xl text-white relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-lg font-bold opacity-80">Hoje</h3>
                    <p class="text-5xl font-black mt-2">{{ $chamadosHoje }}</p>
                    <p class="text-sm mt-4 font-medium italic opacity-90">Novas solicitações recebidas nas últimas 24h.</p>
                </div>
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
            </div>

            <div class="bg-white shadow-sm rounded-3xl p-8 border border-gray-100">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6">Volume por Categoria</h3>
                <div class="space-y-6">
                    @foreach($estatisticasMotivo as $item)
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="font-bold text-gray-700">{{ ucfirst($item->motivo_contato) }}</span>
                            <span class="text-gray-400 font-mono">{{ $item->total }}</span>
                        </div>
                        <div class="w-full bg-gray-50 rounded-full h-1.5">
                            <div class="bg-blue-600 h-1.5 rounded-full shadow-sm" style="width: {{ ($item->total / max($totalChamados, 1)) * 100 }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white shadow-sm rounded-3xl border border-gray-100 h-full">
                <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest">Solicitações Recentes</h3>
                    <a href="{{ route('admin.index') }}" class="text-blue-600 text-[10px] font-black uppercase hover:underline">Ver tudo</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <tbody class="divide-y divide-gray-50">
                            @forelse($ultimosChamados as $chamado)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-8 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-gray-800">{{ $chamado->nome_solicitante }}</span>
                                        <span class="text-[10px] font-mono font-bold text-blue-500 mt-1">{{ $chamado->protocolo }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="text-[10px] font-black text-gray-400 uppercase">{{ $chamado->created_at->diffForHumans() }}</span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    @php
                                        $color = match($chamado->status) {
                                            'novo' => 'text-gray-400',
                                            'pendente' => 'text-amber-500',
                                            'em_andamento' => 'text-blue-500',
                                            'resolvido' => 'text-green-500',
                                            default => 'text-gray-400'
                                        };
                                    @endphp
                                    <span class="text-[10px] font-black uppercase tracking-tighter {{ $color }}">
                                        {{ str_replace('_', ' ', $chamado->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-8 py-20 text-center text-sm text-gray-400 italic">Nenhuma atividade recente registrada.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection