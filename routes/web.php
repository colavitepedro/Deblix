<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RespostaController;
use App\Http\Controllers\TopicoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TopicoController::class, 'index'])->name('topicos.index');
Route::get('/topicos', [TopicoController::class, 'index'])->name('topicos.lista');
Route::get('/topicos/{topico}', [TopicoController::class, 'show'])->name('topicos.show')->whereNumber('topico');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/perfil', [AuthController::class, 'profile'])->name('profile.show');
    Route::put('/perfil', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/perfil/foto', [AuthController::class, 'updateProfilePhoto'])->name('profile.photo.update');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/topicos/create', [TopicoController::class, 'create'])->name('topicos.create');
    Route::post('/topicos', [TopicoController::class, 'store'])->name('topicos.store');
    Route::get('/topicos/{topico}/edit', [TopicoController::class, 'edit'])->name('topicos.edit')->whereNumber('topico');
    Route::put('/topicos/{topico}', [TopicoController::class, 'update'])->name('topicos.update')->whereNumber('topico');
    Route::delete('/topicos/{topico}', [TopicoController::class, 'destroy'])->name('topicos.destroy')->whereNumber('topico');

    Route::post('/topicos/{topico}/respostas', [RespostaController::class, 'store'])->name('respostas.store')->whereNumber('topico');
    Route::get('/respostas/{resposta}/edit', [RespostaController::class, 'edit'])->name('respostas.edit')->whereNumber('resposta');
    Route::put('/respostas/{resposta}', [RespostaController::class, 'update'])->name('respostas.update')->whereNumber('resposta');
    Route::delete('/respostas/{resposta}', [RespostaController::class, 'destroy'])->name('respostas.destroy')->whereNumber('resposta');
});
