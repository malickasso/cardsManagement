<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class GrossisteController extends Controller
{
    /**
     * Récupérer tous les grossistes créés par l'admin connecté (pour AJAX)
     */
    public function getGrossistes(Request $request)
    {
        try {
            // Récupérer uniquement les grossistes créés par l'admin connecté
            $adminId = Auth::guard('admin')->id();

            $grossistes = UserDetail::where('cree_par_admin', $adminId)
                ->where('type_user', 'GROSSISTE')
                ->orderBy('date_creation', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $grossistes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des grossistes'
            ], 500);
        }
    }

    /**
     * Créer un nouveau grossiste
     */
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'raison_sociale' => 'required|string|max:150',
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'ifu' => 'required|string|max:50|unique:users_details,ifu',
            'rccm' => 'required|string|max:50|unique:users_details,rccm',
            'email' => 'required|email|max:150|unique:users_details,email',
            'telephone' => 'nullable|string|max:30',
            'quartier' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'password' => 'required|string|min:8|confirmed',
            'statut' => 'required|in:Actif,Inactif,ACTIF,INACTIF',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $grossiste = UserDetail::create([
                'type_user' => 'GROSSISTE',
                'raison_sociale' => $request->raison_sociale,
                'nom_proprietaire' => $request->nom,
                'prenom_proprietaire' => $request->prenom,
                'ifu' => $request->ifu,
                'rccm' => $request->rccm,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'quartier' => $request->quartier,
                'description' => $request->description,
                'mot_de_passe' => Hash::make($request->password),
                'statut_general' => strtoupper($request->statut),
                'cree_par_admin' => Auth::guard('admin')->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Grossiste créé avec succès',
                'data' => $grossiste
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur création grossiste: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du grossiste: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mettre à jour un grossiste
     */
    public function update(Request $request, $id)
    {
        $adminId = Auth::guard('admin')->id();

        // Vérifier que le grossiste appartient à l'admin connecté
        $grossiste = UserDetail::where('id_user_detail', $id)
            ->where('type_user', 'GROSSISTE')
            ->where('cree_par_admin', $adminId)
            ->firstOrFail();

        // Validation
        $validator = Validator::make($request->all(), [
            'raison_sociale' => 'required|string|max:150',
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'ifu' => 'required|string|max:50|unique:users_details,ifu,' . $id . ',id_user_detail',
            'rccm' => 'required|string|max:50|unique:users_details,rccm,' . $id . ',id_user_detail',
            'email' => 'required|email|max:150|unique:users_details,email,' . $id . ',id_user_detail',
            'telephone' => 'nullable|string|max:30',
            'quartier' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'password' => 'nullable|string|min:8',
            'statut' => 'required|in:Actif,Inactif,ACTIF,INACTIF',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = [
                'raison_sociale' => $request->raison_sociale,
                'nom_proprietaire' => $request->nom,
                'prenom_proprietaire' => $request->prenom,
                'ifu' => $request->ifu,
                'rccm' => $request->rccm,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'quartier' => $request->quartier,
                'description' => $request->description,
                'statut_general' => strtoupper($request->statut),
            ];

            // Mettre à jour le mot de passe seulement s'il est fourni
            if ($request->filled('password')) {
                $data['mot_de_passe'] = Hash::make($request->password);
            }

            $grossiste->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Grossiste mis à jour avec succès',
                'data' => $grossiste
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du grossiste'
            ], 500);
        }
    }

    /**
     * Supprimer un grossiste
     */
    public function destroy($id)
    {
        try {
            $adminId = Auth::guard('admin')->id();

            // Vérifier que le grossiste appartient à l'admin connecté
            $grossiste = UserDetail::where('id_user_detail', $id)
                ->where('type_user', 'GROSSISTE')
                ->where('cree_par_admin', $adminId)
                ->firstOrFail();

            $grossiste->delete();

            return response()->json([
                'success' => true,
                'message' => 'Grossiste supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du grossiste'
            ], 500);
        }
    }
}
