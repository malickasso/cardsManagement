<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\UserDetail;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'mot_de_passe' => 'required',
        ]);

        // Chercher l'utilisateur (grossiste ou partenaire)
        $user = UserDetail::where('email', $credentials['email'])
            ->where('statut_general', 'ACTIF')
            ->first();

        if ($user && Hash::check($credentials['mot_de_passe'], $user->mot_de_passe)) {
            Auth::guard('web')->login($user);
            $request->session()->regenerate();

            // Redirection selon le type d'utilisateur
            if ($user->type_user === 'GROSSISTE') {
                return redirect()->route('grossiste.dashboard');
            } else if ($user->type_user === 'PARTENAIRE') {
                return redirect()->route('partenaire.dashboard');
            } else {
                Auth::guard('web')->logout();
                return back()->withErrors([
                    'email' => 'Type d\'utilisateur non reconnu.',
                ])->onlyInput('email');
            }
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis ne correspondent pas à nos enregistrements ou votre compte est inactif.',
        ])->onlyInput('email');
    }

        public function logout(Request $request)
        {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/');
        }
    }
