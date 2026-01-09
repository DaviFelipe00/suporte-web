<?php

use App\Http\Controllers\SolicitacaoController;
use Illuminate\Support\Facades\Route;

// Rota para ver o formulário
Route::get('/', function () {
    return view('solicitacao');
});

// Rota para processar o envio (nomeada para usar {{ route('solicitacao.store') }} no Blade)
Route::post('/enviar', [SolicitacaoController::class, 'store'])->name('solicitacao.store');