<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:login');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Redirect root to dashboard
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Master Data: Categories & Payrolls CRUD
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('payrolls', PayrollController::class)->except(['show']);

    Route::middleware('throttle:transactions')->group(function () {
        Route::get('/transactions/export', [TransactionController::class, 'export'])->name('transactions.export');
        Route::resource('transactions', TransactionController::class)->except(['show']);
    });

    // Security: Owner Only User Management CRUD
    Route::middleware('role:owner')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });
});
