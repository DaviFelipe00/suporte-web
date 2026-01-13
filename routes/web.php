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

// Rota inicial que exibe o formulário de abertura de chamado
Route::get('/', function () {
    return view('solicitacao');
})->name('home');

// Processa o envio dos dados do chamado e anexos
Route::post('/enviar', [SolicitacaoController::class, 'store'])->name('solicitacao.store');

/*
|--------------------------------------------------------------------------
| Rotas Protegidas (Painel Administrativo)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

// Rota para a página de busca de protocolo
    Route::get('/acompanhar', function () {
    return view('acompanhar');
        })->name('protocolo.index');

    // Rota que processa a busca
    Route::post('/acompanhar-busca', [SolicitacaoController::class, 'acompanhar'])->name('protocolo.buscar');
    
    // Dashboard: Visão geral de métricas e status
   Route::get('/dashboard', [SolicitacaoController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
    
    // Controle de Chamados: Listagem principal de solicitações
    Route::get('/admin', [SolicitacaoController::class, 'index'])->name('admin.index');

    // Gerenciamento de Equipe: Criação de novos Administradores (Protegido internamente)
    // Essas rotas permitem que o Admin logado cadastre outros membros da equipe
    Route::get('/admin/novo-usuario', [RegisteredUserController::class, 'create'])->name('admin.user.create');
    Route::post('/admin/novo-usuario', [RegisteredUserController::class, 'store'])->name('admin.user.store');
    Route::patch('/admin/chamados/{solicitacao}', [SolicitacaoController::class, 'update'])->name('admin.chamados.update');
    Route::delete('/admin/chamados/{solicitacao}', [SolicitacaoController::class, 'destroy'])->name('admin.chamados.destroy');

    // Gestão de Perfil (Breeze default)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Inclui as rotas de autenticação (Login, Logout, Recuperação de Senha)
require __DIR__.'/auth.php';