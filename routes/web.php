<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SolicitacaoController;
use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\InventarioController; // Importação necessária corrigida
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('solicitacao');
})->name('home');

Route::post('/enviar', [SolicitacaoController::class, 'store'])->name('solicitacao.store');
Route::get('/acompanhar', function () {
    return view('acompanhar');
})->name('protocolo.index');
Route::post('/acompanhar-busca', [SolicitacaoController::class, 'acompanhar'])->name('protocolo.buscar');
Route::post('/chatbot', [ChatBotController::class, 'handle'])->name('chatbot.handle');

/*
|--------------------------------------------------------------------------
| Rotas Protegidas (Painel Administrativo)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard e Chamados
    Route::get('/dashboard', [SolicitacaoController::class, 'dashboard'])->name('dashboard');
    Route::get('/admin', [SolicitacaoController::class, 'index'])->name('admin.index');
    
    // Gestão de Equipe e Perfil
    Route::get('/admin/novo-usuario', [RegisteredUserController::class, 'create'])->name('admin.user.create');
    Route::post('/admin/novo-usuario', [RegisteredUserController::class, 'store'])->name('admin.user.store');
    Route::patch('/admin/chamados/{solicitacao}', [SolicitacaoController::class, 'update'])->name('admin.chamados.update');
    Route::delete('/admin/chamados/{solicitacao}', [SolicitacaoController::class, 'destroy'])->name('admin.chamados.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Gestão de Inventário (Agrupada com prefixo para evitar conflitos)
    Route::prefix('admin/inventario')->name('admin.inventario.')->group(function () {
        Route::get('/', [InventarioController::class, 'index'])->name('index');
        Route::post('/', [InventarioController::class, 'store'])->name('store');
        Route::patch('/{equipamento}', [InventarioController::class, 'update'])->name('update');
        Route::delete('/{equipamento}', [InventarioController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__.'/auth.php';