<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\GrossisteController;
use App\Http\Controllers\Grossiste\PartenaireController;

/*
|--------------------------------------------------------------------------
| Route d'accueil
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Routes d'authentification
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
require __DIR__.'/admin-auth.php';

/*
|--------------------------------------------------------------------------
| Routes ADMIN
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {

    // Dashboard Admin
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Gestion des Grossistes
    Route::get('/grossistes', function () {
        return view('admin.create-grossiste');
    })->name('grossistes.index');

    Route::get('/grossistes/data', [GrossisteController::class, 'getGrossistes'])->name('grossistes.data');
    Route::get('/api/grossistes', [GrossisteController::class, 'getGrossistes'])->name('api.grossistes.index');
    Route::post('/grossistes', [GrossisteController::class, 'store'])->name('grossistes.store');
    Route::post('/api/grossistes', [GrossisteController::class, 'store'])->name('api.grossistes.store');
    Route::put('/grossistes/{id}', [GrossisteController::class, 'update'])->name('grossistes.update');
    Route::put('/api/grossistes/{id}', [GrossisteController::class, 'update'])->name('api.grossistes.update');
    Route::delete('/grossistes/{id}', [GrossisteController::class, 'destroy'])->name('grossistes.destroy');
    Route::delete('/api/grossistes/{id}', [GrossisteController::class, 'destroy'])->name('api.grossistes.destroy');
});

/*
|--------------------------------------------------------------------------
| Routes GROSSISTE (Type: GROSSISTE uniquement)
|--------------------------------------------------------------------------
*/
Route::prefix('grossiste')->name('grossiste.')->middleware(['auth:web', 'user.type:grossiste'])->group(function () {

    // Dashboard Grossiste
    Route::get('/dashboard', function () {
        return view('grossiste.dashbord');
    })->name('dashboard');

    // Dotation
    Route::get('/dotation', function () {
        return view('grossiste.dotation');
    })->name('dotation');

    // Gestion des Partenaires
    Route::prefix('partenaires')->name('partenaires.')->group(function () {
        Route::get('/', function () {
            return view('grossiste.partenaires.index');
        })->name('index');
        Route::get('/data', [PartenaireController::class, 'index'])->name('data');
        Route::post('/', [PartenaireController::class, 'store'])->name('store');
        Route::put('/{id}', [PartenaireController::class, 'update'])->name('update');
        Route::post('/{id}/credit', [PartenaireController::class, 'credit'])->name('credit');
        Route::delete('/{id}', [PartenaireController::class, 'destroy'])->name('destroy');
    });

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Routes PARTENAIRE (Type: PARTENAIRE uniquement)
|--------------------------------------------------------------------------
*/
Route::prefix('partenaire')->name('partenaire.')->middleware(['auth:web', 'user.type:partenaire'])->group(function () {

    // Dashboard Partenaire
    Route::get('/dashboard', function () {
        return view('partenaire.dashbord');
    })->name('dashboard');

    // Voir son solde
    Route::get('/solde', function () {
        $user = Auth::user();
        return view('partenaire.solde', compact('user'));
    })->name('solde');

    // Mes cartes
    Route::get('/cartes', function () {
        return view('partenaire.cartes.index');
    })->name('cartes.index');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Route de Fallback - Redirection automatique selon le type
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:web', 'verified'])->get('/dashboard', function () {
    $user = Auth::user();

    if (!isset($user->type_user)) {
        Auth::logout();
        return redirect()->route('login')->with('error', 'Type de compte invalide.');
    }

    if ($user->type_user === 'GROSSISTE') {
        return redirect()->route('grossiste.dashboard');
    } elseif ($user->type_user === 'PARTENAIRE') {
        return redirect()->route('partenaire.dashboard');
    }

    Auth::logout();
    return redirect()->route('login')->with('error', 'Type de compte non reconnu.');
})->name('dashboard');
