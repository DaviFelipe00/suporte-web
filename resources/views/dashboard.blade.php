@extends('layouts.app')

@section('title', 'Inteligência Operacional - Simplemind')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Painel de Inteligência</h1>
            <p class="mt-1 text-sm text-gray-500 font-medium italic">Métricas de performance e saúde da operação em tempo real.</p>
        </div>
        <div class="flex gap-3">
            <span class="inline-flex items-center px-4 py-2 rounded-xl bg-white border border-gray-100 shadow-sm text-[10px] font-black text-blue-600 uppercase tracking-widest">
                📅 {{ date('d/m/Y') }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 transition-all hover:shadow-md">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">TMR Médio</p>
            <p class="text-4xl font-bold text-blue-600">
                {{ round($tmrHoras, 1) }}<span class="text-sm font-medium text-gray-400 ml-1">hrs</span>
            </p>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 transition-all hover:shadow-md">
            <p class="text-[10px] font-black text-red-500 uppercase tracking-widest mb-1">Fora do SLA (>24h)</p>
            <p class="text-4xl font-bold text-red-600">{{ $foraDoSla }}</p>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 transition-all hover:shadow-md">
            <p class="text-[10px] font-black text-green-500 uppercase tracking-widest mb-1">Taxa de Resolução</p>
            <p class="text-4xl font-bold text-green-600">
                {{ $totalChamados > 0 ? round(($totalResolvidos / $totalChamados) * 100) : 0 }}%
            </p>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 transition-all hover:shadow-md">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Geral</p>
            <p class="text-4xl font-bold text-gray-900">{{ $totalChamados }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        <div class="lg:col-span-2 bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest">Fluxo de Solicitações (Últimos 7 dias)</h3>
                <span class="text-[10px] font-bold text-blue-500 bg-blue-50 px-2 py-1 rounded-lg">Tendência</span>
            </div>
            <div class="h-72">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-8 text-center">Perfil de Urgência</h3>
            <div class="h-72">
                <canvas id="priorityChart"></canvas>
            </div>
        </div>

        <div class="lg:col-span-3 bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-8">Análise de Demanda por Categoria</h3>
            <div class="h-64">
                <canvas id="reasonChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const commonOptions = { maintainAspectRatio: false, plugins: { legend: { display: false } } };

    // 1. Gráfico de Tendência (Line)
    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($tendenciaSemanal->pluck('data')) !!},
            datasets: [{
                data: {!! json_encode($tendenciaSemanal->pluck('total')) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.05)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#3b82f6'
            }]
        },
        options: commonOptions
    });

    // 2. Gráfico de Prioridade (Radar)
    new Chart(document.getElementById('priorityChart'), {
        type: 'radar',
        data: {
            labels: ['Baixa', 'Média', 'Alta', 'Urgente'],
            datasets: [{
                label: 'Volume',
                data: [
                    {{ $prioridades['baixa'] ?? 0 }}, {{ $prioridades['media'] ?? 0 }},
                    {{ $prioridades['alta'] ?? 0 }}, {{ $prioridades['urgente'] ?? 0 }}
                ],
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                borderColor: '#ef4444',
                borderWidth: 2,
                pointBackgroundColor: '#ef4444'
            }]
        },
        options: { 
            maintainAspectRatio: false, 
            scales: { r: { beginAtZero: true, ticks: { display: false } } },
            plugins: { legend: { display: false } }
        }
    });

    // 3. Gráfico de Motivos (Horizontal Bar)
    new Chart(document.getElementById('reasonChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($estatisticasMotivo->pluck('motivo_contato')->map(fn($m) => ucfirst($m))) !!},
            datasets: [{
                data: {!! json_encode($estatisticasMotivo->pluck('total')) !!},
                backgroundColor: '#3b82f6',
                borderRadius: 12
            }]
        },
        options: { ...commonOptions, indexAxis: 'y' }
    });
</script>
@endsection