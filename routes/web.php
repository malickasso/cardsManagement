<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\GrossisteController;
use App\Http\Controllers\Grossiste\PartenaireController;
use App\Models\Banque;
use App\Models\Carte;
use App\Models\Grossiste;
use App\Models\TypeCarte;
use App\Models\UserDetail;
use Illuminate\Validation\Rule;

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

    // Gestion des Banques
    Route::get('/banques/create', function () {
        $adminId = Auth::guard('admin')->id();
        $banques = Banque::where('cree_par_admin', $adminId)
            ->orderBy('id_banque', 'asc')
            ->get();
        $stats = [
            'total'   => Banque::where('cree_par_admin', $adminId)->count(),
            'actif'   => Banque::where('cree_par_admin', $adminId)->where('statut', 'ACTIF')->count(),
            'inactif' => Banque::where('cree_par_admin', $adminId)->where('statut', 'INACTIF')->count(),
        ];

        return view('admin.banques.create', compact('banques', 'stats'));
    })->name('banques.create');

    Route::post('/banques', function (\Illuminate\Http\Request $request) {
        $adminId = Auth::guard('admin')->id();

        $validated = $request->validate([
            'nom_banque' => ['required', 'string', 'max:150'],
            'code_banque' => ['required', 'string', 'max:50', 'unique:banque,code_banque'],
            'statut' => ['nullable', 'in:ACTIF,INACTIF'],
        ]);

        Banque::create([
            'cree_par_admin' => $adminId,
            'nom_banque' => $validated['nom_banque'],
            'code_banque' => strtoupper($validated['code_banque']),
            'statut' => $validated['statut'] ?? 'ACTIF',
        ]);

        return redirect()->route('admin.banques.create')->with('success', 'Banque créée avec succès.');
    })->name('banques.store');

    // Gestion des Types de Carte
    Route::get('/type-cartes/create', function () {
        $adminId = Auth::guard('admin')->id();
        $typesCartes = TypeCarte::where('cree_par_admin', $adminId)
            ->orderBy('id_type_carte', 'asc')
            ->get();
        $stats = [
            'total'   => TypeCarte::where('cree_par_admin', $adminId)->count(),
            'actif'   => TypeCarte::where('cree_par_admin', $adminId)->where('statut', 'ACTIF')->count(),
            'inactif' => TypeCarte::where('cree_par_admin', $adminId)->where('statut', 'INACTIF')->count(),
        ];
        return view('admin.type-cartes.create', compact('typesCartes', 'stats'));
    })->name('type-cartes.create');

    Route::post('/type-cartes', function (\Illuminate\Http\Request $request) {
        $adminId = Auth::guard('admin')->id();

        TypeCarte::create([
            'cree_par_admin' => $adminId,
            'nom_type_carte' => strtoupper($request->nom_type_carte),
            'description'    => $request->description,
            'statut'         => $request->statut ?? 'ACTIF',
        ]);
        return redirect()->route('admin.type-cartes.create')->with('success', 'Type de carte créé avec succès.');
    })->name('type-cartes.store');

    Route::put('/type-cartes/{id}', function (\Illuminate\Http\Request $request, $id) {
        $adminId = Auth::guard('admin')->id();
        $type = TypeCarte::where('id_type_carte', $id)
            ->where('cree_par_admin', $adminId)
            ->firstOrFail();

        $type->update([
            'nom_type_carte' => strtoupper($request->nom_type_carte),
            'description'    => $request->description,
            'statut'         => $request->statut,
        ]);
        return redirect()->route('admin.type-cartes.create')->with('success', 'Type de carte mis à jour.');
    })->name('type-cartes.update');

    Route::patch('/type-cartes/{id}/toggle', function ($id) {
        $adminId = Auth::guard('admin')->id();
        $type = TypeCarte::where('id_type_carte', $id)
            ->where('cree_par_admin', $adminId)
            ->firstOrFail();

        $type->statut = $type->statut === 'ACTIF' ? 'INACTIF' : 'ACTIF';
        $type->save();
        return redirect()->route('admin.type-cartes.create')->with('success', 'Statut mis à jour.');
    })->name('type-cartes.toggle');

    Route::delete('/type-cartes/{id}', function ($id) {
        $adminId = Auth::guard('admin')->id();
        $type = TypeCarte::where('id_type_carte', $id)
            ->where('cree_par_admin', $adminId)
            ->firstOrFail();

        $type->delete();

        return redirect()->route('admin.type-cartes.create')->with('success', 'Type de carte supprimé.');
    })->name('type-cartes.destroy');

    // Gestion des Cartes
    Route::get('/cartes/create', function () {
        $adminId = Auth::guard('admin')->id();

        $typesCartes = TypeCarte::where('cree_par_admin', $adminId)
            ->orderBy('id_type_carte', 'asc')
            ->get();

        $banques = Banque::where('cree_par_admin', $adminId)
            ->orderBy('id_banque', 'asc')
            ->get();

        $grossistes = UserDetail::where('type_user', 'GROSSISTE')
            ->where('cree_par_admin', $adminId)
            ->where('statut_general', 'ACTIF')
            ->orderBy('raison_sociale', 'asc')
            ->get();

        $cartes = Carte::with([
            'typeCarte:id_type_carte,nom_type_carte',
            'banque:id_banque,nom_banque',
            'grossiste:id_user_detail,raison_sociale,cree_par_admin',
        ])
            ->where('cree_par_admin', $adminId)
            ->orderBy('id_carte', 'asc')
            ->get();

        return view('admin.cartes.create', compact('typesCartes', 'banques', 'grossistes', 'cartes'));
    })->name('cartes.create');

    Route::post('/cartes', function (\Illuminate\Http\Request $request) {
        $adminId = Auth::guard('admin')->id();

        $validated = $request->validate([
            'numero_carte' => ['required', 'string', 'max:20', 'unique:carte,numero_carte'],
            'id_type_carte' => ['required', 'integer'],
            'id_banque' => ['required', 'integer', 'exists:banque,id_banque'],
            'id_grossiste' => ['required', 'integer', 'exists:users_details,id_user_detail'],
            'date_expiration' => ['required', 'date', 'after_or_equal:today'],
            'statut_carte' => ['nullable', Rule::in(['ENREGISTREE', 'ACTIVE', 'BLOQUEE', 'EXPIREE', 'ANNULEE'])],
        ]);

        TypeCarte::where('id_type_carte', $validated['id_type_carte'])
            ->where('cree_par_admin', $adminId)
            ->firstOrFail();

        UserDetail::where('id_user_detail', $validated['id_grossiste'])
            ->where('type_user', 'GROSSISTE')
            ->where('cree_par_admin', $adminId)
            ->firstOrFail();

        Banque::where('id_banque', $validated['id_banque'])
            ->where('cree_par_admin', $adminId)
            ->firstOrFail();

        Carte::create([
            'cree_par_admin' => $adminId,
            'numero_carte' => strtoupper(trim($validated['numero_carte'])),
            'id_type_carte' => $validated['id_type_carte'],
            'id_banque' => $validated['id_banque'],
            'id_grossiste' => $validated['id_grossiste'],
            'date_expiration' => $validated['date_expiration'],
            'statut_carte' => $validated['statut_carte'] ?? 'ENREGISTREE',
        ]);

        return redirect()->route('admin.cartes.create')->with('success', 'Carte créée avec succès.');
    })->name('cartes.store');

    Route::put('/cartes/{id}', function (\Illuminate\Http\Request $request, $id) {
        $adminId = Auth::guard('admin')->id();

        $carte = Carte::where('id_carte', $id)
            ->where('cree_par_admin', $adminId)
            ->firstOrFail();

        $validated = $request->validate([
            'numero_carte' => ['required', 'string', 'max:20', Rule::unique('carte', 'numero_carte')->ignore($id, 'id_carte')],
            'id_type_carte' => ['required', 'integer'],
            'id_banque' => ['required', 'integer', 'exists:banque,id_banque'],
            'id_grossiste' => ['required', 'integer', 'exists:users_details,id_user_detail'],
            'date_expiration' => ['required', 'date'],
            'statut_carte' => ['required', Rule::in(['ENREGISTREE', 'ACTIVE', 'BLOQUEE', 'EXPIREE', 'ANNULEE'])],
        ]);

        TypeCarte::where('id_type_carte', $validated['id_type_carte'])
            ->where('cree_par_admin', $adminId)
            ->firstOrFail();

        Banque::where('id_banque', $validated['id_banque'])
            ->where('cree_par_admin', $adminId)
            ->firstOrFail();

        UserDetail::where('id_user_detail', $validated['id_grossiste'])
            ->where('type_user', 'GROSSISTE')
            ->where('cree_par_admin', $adminId)
            ->firstOrFail();

        $carte->update([
            'numero_carte' => strtoupper(trim($validated['numero_carte'])),
            'id_type_carte' => $validated['id_type_carte'],
            'id_banque' => $validated['id_banque'],
            'id_grossiste' => $validated['id_grossiste'],
            'date_expiration' => $validated['date_expiration'],
            'statut_carte' => $validated['statut_carte'],
        ]);

        return redirect()->route('admin.cartes.create')->with('success', 'Carte mise à jour avec succès.');
    })->name('cartes.update');

    Route::delete('/cartes/{id}', function ($id) {
        $adminId = Auth::guard('admin')->id();

        $carte = Carte::where('id_carte', $id)
            ->where('cree_par_admin', $adminId)
            ->firstOrFail();

        $carte->delete();

        return redirect()->route('admin.cartes.create')->with('success', 'Carte supprimée avec succès.');
    })->name('cartes.destroy');
    Route::post('/cartes/bulk', function () {})->name('cartes.bulk-store');
    Route::post('/cartes/{id}/assign', function () {})->name('cartes.assign');

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

    // Gestion des Cartes
    Route::prefix('cartes')->name('cartes.')->group(function () {
        Route::get('/', function () {
            $grossiste = Auth::user();

            $typesCartes = TypeCarte::where('cree_par_admin', $grossiste->cree_par_admin)
                ->where('statut', 'ACTIF')
                ->orderBy('id_type_carte', 'asc')
                ->get();

            $banques = Banque::where('cree_par_admin', $grossiste->cree_par_admin)
                ->where('statut', 'ACTIF')
                ->orderBy('id_banque', 'asc')
                ->get();

            $cartes = Carte::with([
                'typeCarte:id_type_carte,nom_type_carte',
                'banque:id_banque,nom_banque',
            ])
                ->where('id_grossiste', $grossiste->id_user_detail)
                ->orderBy('id_carte', 'desc')
                ->get();

            $stats = [
                'total' => $cartes->count(),
                'enregistrees' => $cartes->where('statut_carte', 'ENREGISTREE')->count(),
                'actives' => $cartes->where('statut_carte', 'ACTIVE')->count(),
                'bloquees' => $cartes->where('statut_carte', 'BLOQUEE')->count(),
            ];

            return view('grossiste.cartes', compact('typesCartes', 'banques', 'cartes', 'stats'));
        })->name('index');

        Route::post('/', function (\Illuminate\Http\Request $request) {
            $grossiste = Auth::user();

            $validated = $request->validate([
                'numero_carte' => ['required', 'string', 'max:20', 'unique:carte,numero_carte'],
                'id_type_carte' => ['required', 'integer'],
                'id_banque' => ['required', 'integer', 'exists:banque,id_banque'],
                'date_expiration' => ['required', 'date', 'after_or_equal:today'],
                'statut_carte' => ['nullable', Rule::in(['ENREGISTREE', 'ACTIVE', 'BLOQUEE', 'EXPIREE', 'ANNULEE'])],
            ]);

            TypeCarte::where('id_type_carte', $validated['id_type_carte'])
                ->where('cree_par_admin', $grossiste->cree_par_admin)
                ->where('statut', 'ACTIF')
                ->firstOrFail();

            Banque::where('id_banque', $validated['id_banque'])
                ->where('cree_par_admin', $grossiste->cree_par_admin)
                ->where('statut', 'ACTIF')
                ->firstOrFail();

            Carte::create([
                'cree_par_admin' => $grossiste->cree_par_admin,
                'numero_carte' => strtoupper(trim($validated['numero_carte'])),
                'id_type_carte' => $validated['id_type_carte'],
                'id_banque' => $validated['id_banque'],
                'id_grossiste' => $grossiste->id_user_detail,
                'date_expiration' => $validated['date_expiration'],
                'statut_carte' => $validated['statut_carte'] ?? 'ENREGISTREE',
            ]);

            return redirect()->route('grossiste.cartes.index')->with('success', 'Carte créée avec succès.');
        })->name('store');
    });

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
