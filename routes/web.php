<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ParticipantController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ParticipantController::class, 'create'])->name('welcome');
Route::post('/participants', [ParticipantController::class, 'store'])->name('participants.store');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [ParticipantController::class, 'index'])->name('dashboard');
    Route::get('/tombola', [ParticipantController::class, 'tombola'])->name('tombola.index');
    Route::get('/tombola/winner', [ParticipantController::class, 'getWinner'])->name('tombola.winner');
    Route::post('/tombola/reset', [ParticipantController::class, 'resetTombola'])->name('tombola.reset');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';