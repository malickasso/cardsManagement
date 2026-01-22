<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\GrossisteController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware('guest:admin')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('admin.register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [LoginController::class, 'create'])->name('admin.login');
    Route::post('login', [LoginController::class, 'store']);
});

Route::prefix('admin')->middleware('auth:admin')->group(function () {

    Route::get('/dashboard', function () {return view('admin.dashboard');})->name('admin.dashboard');
    Route::get('/create-grossiste', function () {return view('admin.create-grossiste');})->name('admin.create-grossiste');
// Page principale (votre blade)
    Route::get('/grossistes', function () {
        return view('admin.create-grossiste');
    })->name('grossistes.index');

    // Routes pour les opérations AJAX (elles retournent du JSON)
    Route::get('/grossistes/data', [GrossisteController::class, 'getGrossistes'])->name('admin.grossistes.data');
    Route::post('/grossistes', [GrossisteController::class, 'store'])->name('admin.grossistes.store');
    Route::put('/grossistes/{id}', [GrossisteController::class, 'update'])->name('admin.grossistes.update');
    Route::delete('/grossistes/{id}', [GrossisteController::class, 'destroy'])->name('admin.grossistes.destroy');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('admin.logout');
});
