@extends('layouts.app')

@section('title', 'Dashboard - Simplemind')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Resumo Operacional</h1>
        <p class="mt-2 text-sm text-gray-600">Acompanhe o desempenho do suporte Simplemind em tempo real.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-blue-600">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total de Solicitações</dt>
                            <dd class="text-2xl font-bold text-gray-900">{{ $totalChamados }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-green-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Abertos Hoje</dt>
                            <dd class="text-2xl font-bold text-gray-900">{{ $chamadosHoje }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg border-l-4 border-amber-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-amber-100 rounded-md p-3">
                        <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Aguardando Resposta</dt>
                            <dd class="text-2xl font-bold text-gray-900">{{ $totalChamados }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 text-center">Volume por Motivo de Contato</h3>
        <div class="space-y-4">
            @foreach($estatisticasMotivo as $item)
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="font-medium text-gray-700">{{ ucfirst($item->motivo_contato) }}</span>
                    <span class="text-gray-500">{{ $item->total }} chamados</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($item->total / $totalChamados) * 100 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection