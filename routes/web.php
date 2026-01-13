<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SolicitacaoController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas Públicas (Simplemind - Suporte Técnico)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('solicitacao');
})->name('home');

Route::post('/enviar', [SolicitacaoController::class, 'store'])->name('solicitacao.store');

// ROTAS DE PROTOCOLO (MOVIDAS PARA FORA DO AUTH)
Route::get('/acompanhar', function () {
    return view('acompanhar');
})->name('protocolo.index');

Route::post('/acompanhar-busca', [SolicitacaoController::class, 'acompanhar'])->name('protocolo.buscar');

// Rota para o ChatBot Inteligente
Route::post('/chatbot', [App\Http\Controllers\ChatBotController::class, 'handle'])->name('chatbot.handle');
/*
|--------------------------------------------------------------------------
| Rotas Protegidas (Painel Administrativo)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [SolicitacaoController::class, 'dashboard'])->name('dashboard');
    Route::get('/admin', [SolicitacaoController::class, 'index'])->name('admin.index');
    
    // Gerenciamento de Equipe e Chamados
    Route::get('/admin/novo-usuario', [RegisteredUserController::class, 'create'])->name('admin.user.create');
    Route::post('/admin/novo-usuario', [RegisteredUserController::class, 'store'])->name('admin.user.store');
    Route::patch('/admin/chamados/{solicitacao}', [SolicitacaoController::class, 'update'])->name('admin.chamados.update');
    Route::delete('/admin/chamados/{solicitacao}', [SolicitacaoController::class, 'destroy'])->name('admin.chamados.destroy');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';