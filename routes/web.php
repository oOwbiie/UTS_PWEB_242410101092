<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

// Halaman Login
Route::get('/', [PageController::class, 'showLogin'])->name('login');
Route::post('/login', [PageController::class, 'processLogin'])->name('login.process');
Route::get('/logout', [PageController::class, 'logout'])->name('logout');

// Halaman setelah login (pakai middleware sederhana via controller)
Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
Route::get('/pengelolaan', [PageController::class, 'pengelolaan'])->name('pengelolaan');
Route::post('/pengelolaan/simpan', [PageController::class, 'simpanData'])->name('pengelolaan.simpan');
Route::get('/profile', [PageController::class, 'profile'])->name('profile');
