@extends('layouts.app')

@section('title', 'Acompanhar Solicitação - Simplemind')

@section('content')
<div class="max-w-3xl mx-auto px-4">
    <div class="text-center mb-10">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Acompanhar Chamado</h1>
        <p class="text-gray-500 mt-2 font-medium">Insira o número do seu protocolo para verificar o status atual.</p>
    </div>

    <div class="bg-white p-6 md:p-8 rounded-3xl shadow-xl border border-gray-100 mb-10">
        <form action="{{ route('protocolo.buscar') }}" method="POST" class="flex flex-col md:flex-row gap-4">
            @csrf
            <div class="flex-grow relative">
                <span class="absolute inset-y-0 left-4 flex items-center text-gray-400">#</span>
                <input type="text" name="protocolo" required 
                       placeholder="Ex: 20260113-ABC123" 
                       value="{{ old('protocolo', $solicitacao->protocolo ?? '') }}"
                       class="w-full pl-10 pr-4 py-4 rounded-2xl border border-gray-200 focus:ring-4 focus:ring-blue-50 focus:border-blue-500 outline-none transition-all font-mono uppercase text-lg">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 active:scale-95">
                Consultar
            </button>
        </form>
    </div>

    @if(isset($busca_realizada))
        @if($solicitacao)
            <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden animate-fade-in border-t-8 border-blue-600">
                <div class="p-8 md:p-12">
                    
                    <div class="mb-16 relative">
                        <div class="flex items-center justify-between relative z-10">
                            @php
                                $etapas = [
                                    ['label' => 'Recebido', 'icon' => '📩', 'active' => true],
                                    ['label' => 'Em Análise', 'icon' => '🔍', 'active' => in_array($solicitacao->status, ['pendente', 'em_andamento', 'resolvido'])],
                                    ['label' => 'Resolvido', 'icon' => '✅', 'active' => $solicitacao->status == 'resolvido']
                                ];
                            @endphp

                            @foreach($etapas as $index => $etapa)
                                <div class="flex flex-col items-center flex-1 relative">
                                    <div class="w-12 h-12 md:w-16 md:h-16 rounded-full flex items-center justify-center transition-all duration-500 border-4 
                                        {{ $etapa['active'] ? 'bg-blue-600 border-blue-100 text-white shadow-lg' : 'bg-white border-gray-100 text-gray-300' }}">
                                        <span class="text-xl md:text-2xl">{{ $etapa['icon'] }}</span>
                                    </div>
                                    <p class="mt-4 text-[10px] md:text-xs font-black uppercase tracking-tighter {{ $etapa['active'] ? 'text-blue-600' : 'text-gray-400' }}">
                                        {{ $etapa['label'] }}
                                    </p>

                                    @if($index < count($etapas) - 1)
                                        <div class="hidden md:block absolute top-8 left-[60%] w-[80%] h-1 -z-10 rounded-full
                                            {{ $etapas[$index + 1]['active'] ? 'bg-blue-600' : 'bg-gray-100' }}"></div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if($solicitacao->resposta_admin)
                        <div class="p-8 bg-green-50 border border-green-100 rounded-3xl mb-10">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="text-2xl">👨‍💻</span>
                                <h4 class="text-xs font-black text-green-700 uppercase tracking-widest">Parecer da Simplemind</h4>
                            </div>
                            <p class="text-green-900 text-sm leading-relaxed font-medium italic">
                                "{!! nl2br(e($solicitacao->resposta_admin)) !!}"
                            </p>
                        </div>
                    @endif

                    <div class="grid md:grid-cols-2 gap-10 pt-6 border-t border-gray-50">
                        <div>
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Sua Descrição</h4>
                            <p class="text-sm text-gray-600 leading-relaxed">{{ $solicitacao->descricao_duvida }}</p>
                        </div>
                        <div class="md:text-right">
                            <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Solicitante</h4>
                            <p class="text-lg font-bold text-gray-800">{{ $solicitacao->nome_solicitante }}</p>
                            <p class="text-xs text-gray-400 font-mono mt-2 uppercase">Aberto em: {{ $solicitacao->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-red-50 border border-red-100 p-10 rounded-3xl text-center animate-fade-in">
                <div class="bg-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                    <span class="text-3xl">🔍</span>
                </div>
                <p class="text-red-700 font-black uppercase tracking-widest text-sm">Protocolo não encontrado</p>
                <p class="text-red-500 text-sm mt-2">Verifique o código digitado e tente novamente.</p>
            </div>
        @endif
    @endif
</div>
@endsection