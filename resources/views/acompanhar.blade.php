@extends('layouts.app')

@section('title', 'Acompanhar Chamado - Simplemind')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="text-center mb-10">
        <h1 class="text-3xl font-bold text-gray-800">Acompanhar Solicitação</h1>
        <p class="text-gray-500 mt-2">Verifique o progresso do seu suporte usando o número do protocolo.</p>
    </div>

    <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 mb-8">
        <form action="{{ route('protocolo.buscar') }}" method="POST" class="flex flex-col md:flex-row gap-4">
            @csrf
            <input type="text" name="protocolo" required placeholder="Ex: 20260113-XPT021" value="{{ old('protocolo') }}"
                   class="flex-grow px-4 py-3 rounded-xl border border-gray-200 focus:ring-4 focus:ring-blue-50 focus:border-blue-500 outline-none transition-all font-mono uppercase">
            <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                Consultar Status
            </button>
        </form>
    </div>

    @if(isset($busca_realizada))
        @if($solicitacao)
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden animate-fade-in border-t-4 border-blue-600">
                <div class="p-8">
                    <div class="flex justify-between items-start mb-8">
                        <div>
                            <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Protocolo</p>
                            <h2 class="text-2xl font-mono font-bold text-blue-600">{{ $solicitacao->protocolo }}</h2>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Status Atual</p>
                            @php
                                $statusColors = [
                                    'novo' => 'bg-gray-100 text-gray-700',
                                    'pendente' => 'bg-amber-100 text-amber-700',
                                    'em_andamento' => 'bg-blue-100 text-blue-700',
                                    'resolvido' => 'bg-green-100 text-green-700',
                                ];
                            @endphp
                            <span class="px-4 py-1.5 rounded-full text-xs font-black uppercase {{ $statusColors[$solicitacao->status] ?? 'bg-gray-100' }}">
                                {{ str_replace('_', ' ', $solicitacao->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 border-b border-gray-50">
                        <div>
                            <h4 class="text-xs font-bold text-gray-400 uppercase mb-1">Solicitante</h4>
                            <p class="text-gray-800 font-bold">{{ $solicitacao->nome_solicitante }}</p>
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-gray-400 uppercase mb-1">Data de Abertura</h4>
                            <p class="text-gray-800 font-medium">{{ $solicitacao->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h4 class="text-xs font-bold text-gray-400 uppercase mb-2">Descrição da Solicitação</h4>
                        <p class="text-gray-600 leading-relaxed bg-gray-50 p-4 rounded-xl italic">"{{ $solicitacao->descricao_duvida }}"</p>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-red-50 border border-red-100 p-8 rounded-2xl text-center">
                <span class="text-3xl">🔍</span>
                <p class="text-red-700 font-bold mt-4 text-lg">Protocolo não encontrado.</p>
                <p class="text-red-500 text-sm mt-1">Verifique o código e tente novamente.</p>
            </div>
        @endif
    @endif
</div>
@endsection