<?php

use App\Http\Controllers\SolicitacaoController;
use Illuminate\Support\Facades\Route;

// Rota para o bot criar chamados
Route::post('/v1/chamados', [SolicitacaoController::class, 'storeFromBot']);