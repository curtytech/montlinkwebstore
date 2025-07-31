<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\AuthController;

Route::get('/', [ProdutoController::class, 'index']);
Route::get('/produto/{id}', [ProdutoController::class, 'show'])->name('produto.show');

// Rotas do Carrinho
Route::prefix('carrinho')->name('carrinho.')->group(function () {
    Route::get('/', [CarrinhoController::class, 'index'])->name('index');
    Route::post('/adicionar/{produto}', [CarrinhoController::class, 'adicionar'])->name('adicionar');
    Route::patch('/atualizar/{item}', [CarrinhoController::class, 'atualizar'])->name('atualizar');
    Route::delete('/remover/{item}', [CarrinhoController::class, 'remover'])->name('remover');
    Route::delete('/limpar', [CarrinhoController::class, 'limpar'])->name('limpar');
    Route::get('/contar', [CarrinhoController::class, 'contar'])->name('contar');
    Route::post('/verificar-cep', [CarrinhoController::class, 'verificarCep'])->name('verificar-cep');
    Route::post('/aplicar-cupom', [CarrinhoController::class, 'aplicarCupom'])->name('aplicar-cupom');
    Route::delete('/remover-cupom', [CarrinhoController::class, 'removerCupom'])->name('remover-cupom');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
