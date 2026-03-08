<?php

use App\Http\Controllers\PartidaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\JogoBatalha;
use App\Http\Controllers\RankingController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('partida', PartidaController::class);
    Route::get('/modos', [PartidaController::class, 'modos'])->name('partida.modos');
    Route::get('/batalha/{id}', JogoBatalha::class)->name('jogo.batalha');
    Route::get('/ranking', [RankingController::class, 'index'])->name('ranking.index');
});

require __DIR__.'/auth.php';
