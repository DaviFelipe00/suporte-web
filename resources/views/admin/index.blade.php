@extends('layouts.app')

@section('title', 'Painel Admin - Simplemind')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8 border-b border-gray-200">
        <div class="flex justify-between items-end">
            <nav class="-mb-px flex space-x-8">
                <a href="{{ route('dashboard') }}" 
                   class="{{ request()->routeIs('dashboard') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all">
                    Dashboard
                </a>

                <a href="{{ route('admin.index') }}" 
                   class="{{ request()->routeIs('admin.index') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-all">
                    Controle de Chamados
                </a>
            </nav>

            <div class="pb-4">
                <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                    {{ $chamados->count() }} Chamados Ativos
                </span>
            </div>
        </div>
    </div>

    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Gerenciamento de Solicitações</h2>
        <p class="text-sm text-gray-500">Visualize e gerencie todos os pedidos de suporte técnico da Simplemind.</p>
    </div>

    <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-100">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Solicitante</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Motivo</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Data</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($chamados as $chamado)
                <tr class="hover:bg-blue-50/30 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-bold text-gray-900">{{ $chamado->nome_solicitante }}</div>
                        <div class="text-xs text-gray-500">{{ $chamado->email_solicitante }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2.5 py-1 text-xs font-medium rounded-md bg-gray-100 text-gray-700 border border-gray-200">
                            {{ ucfirst($chamado->motivo_contato) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $chamado->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="flex items-center gap-1.5 text-xs font-semibold text-amber-600">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                            Pendente
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button class="text-blue-600 hover:text-blue-900 font-bold transition-colors">Ver Detalhes</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center">
                            <span class="text-4xl mb-4">📂</span>
                            <p class="text-gray-500 italic">Nenhum chamado encontrado no banco de dados.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection