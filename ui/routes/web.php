<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PreferenceController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\PurchaseController;

// Guest (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

// Auth (sudah login)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/menu', [MenuController::class, 'index']);
    Route::get('/history', [HistoryController::class, 'index'])->name('history');
    Route::post('/logout', [AuthController::class, 'logout']);

    // Purchase route
    Route::post('/purchase', [PurchaseController::class, 'store']);
    
    // Preference route
    Route::get('/preference', function () {
        return view('preference');
    });
    Route::post('/preference', [PreferenceController::class, 'store']);
});