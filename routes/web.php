<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SolicitacaoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas Públicas (Acessíveis por qualquer visitante)
|--------------------------------------------------------------------------
*/

// Exibe o formulário de solicitação da Simplemind
Route::get('/', function () {
    return view('solicitacao');
})->name('home');

// Processa o envio do formulário e dos anexos
Route::post('/enviar', [SolicitacaoController::class, 'store'])->name('solicitacao.store');

/*
|--------------------------------------------------------------------------
| Rotas Protegidas (Exigem Login)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Painel Administrativo: Visualização dos chamados
    Route::get('/admin', [SolicitacaoController::class, 'index'])->name('admin.index');

    // Dashboard padrão do Breeze (redirecionado ou mantido para estatísticas)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Gerenciamento de Perfil do Usuário
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Inclui as rotas de autenticação (Login, Registro, Password Reset, etc.)
require __DIR__.'/auth.php';