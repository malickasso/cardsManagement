<?php

namespace App\Http\Controllers\Grossiste;

use App\Http\Controllers\Controller;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class PartenaireController extends Controller
{
    public function index(Request $request)
    {
        try {
            $grossiste = Auth::user();
            $partenaires = UserDetail::where('id_grossiste', $grossiste->id_user_detail)
                ->where('type_user', 'PARTENAIRE')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $partenaires
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des partenaires'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'raison_sociale' => 'required|string|max:150',
            'nom_proprietaire' => 'required|string|max:100',
            'prenom_proprietaire' => 'required|string|max:100',
            'ifu' => 'required|string|max:50|unique:users_details,ifu',
            'rccm' => 'required|string|max:50|unique:users_details,rccm',
            'email' => 'required|email|max:150|unique:users_details,email',
            'telephone' => 'nullable|string|max:30',
            'quartier' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'mot_de_passe' => 'required|string|min:8|confirmed',
            'solde' => 'nullable|numeric|min:0',
            'statut_general' => 'required|in:ACTIF,INACTIF',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $grossiste = Auth::user();

            $partenaire = UserDetail::create([
                'type_user' => 'PARTENAIRE',
                'id_grossiste' => $grossiste->id_user_detail,
                'raison_sociale' => $request->raison_sociale,
                'nom_proprietaire' => $request->nom_proprietaire,
                'prenom_proprietaire' => $request->prenom_proprietaire,
                'ifu' => $request->ifu,
                'rccm' => $request->rccm,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'quartier' => $request->quartier,
                'description' => $request->description,
                'mot_de_passe' => Hash::make($request->mot_de_passe),
                'solde' => $request->solde ?? 0,
                'statut_general' => $request->statut_general,
                'cree_par_admin' => $grossiste->cree_par_admin,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Partenaire créé avec succès',
                'data' => $partenaire
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du partenaire: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $grossiste = Auth::user();
        $partenaire = UserDetail::where('id_grossiste', $grossiste->id_user_detail)
            ->where('type_user', 'PARTENAIRE')
            ->where('id_user_detail', $id)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'raison_sociale' => 'required|string|max:150',
            'nom_proprietaire' => 'required|string|max:100',
            'prenom_proprietaire' => 'required|string|max:100',
            'ifu' => 'required|string|max:50|unique:users_details,ifu,' . $id . ',id_user_detail',
            'rccm' => 'required|string|max:50|unique:users_details,rccm,' . $id . ',id_user_detail',
            'email' => 'required|email|max:150|unique:users_details,email,' . $id . ',id_user_detail',
            'telephone' => 'nullable|string|max:30',
            'quartier' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'mot_de_passe' => 'nullable|string|min:8|confirmed',
            'statut_general' => 'required|in:ACTIF,INACTIF',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $data = [
                'raison_sociale' => $request->raison_sociale,
                'nom_proprietaire' => $request->nom_proprietaire,
                'prenom_proprietaire' => $request->prenom_proprietaire,
                'ifu' => $request->ifu,
                'rccm' => $request->rccm,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'quartier' => $request->quartier,
                'description' => $request->description,
                'statut_general' => $request->statut_general,
            ];

            if ($request->filled('mot_de_passe')) {
                $data['mot_de_passe'] = Hash::make($request->mot_de_passe);
            }

            $partenaire->update($data);

            return response()->json(['success' => true, 'message' => 'Partenaire mis à jour avec succès', 'data' => $partenaire]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la mise à jour du partenaire'], 500);
        }
    }

    public function credit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'montant' => 'required|numeric|min:0.01',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $grossiste = Auth::user();
            $partenaire = UserDetail::where('id_grossiste', $grossiste->id_user_detail)
                ->where('type_user', 'PARTENAIRE')
                ->where('id_user_detail', $id)
                ->firstOrFail();

            $partenaire->solde += $request->montant;
            $partenaire->save();

            return response()->json(['success' => true, 'message' => 'Compte crédité avec succès', 'data' => $partenaire]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors du crédit du compte'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $grossiste = Auth::user();
            $partenaire = UserDetail::where('id_grossiste', $grossiste->id_user_detail)
                ->where('type_user', 'PARTENAIRE')
                ->where('id_user_detail', $id)
                ->firstOrFail();
            $partenaire->delete();

            return response()->json(['success' => true, 'message' => 'Partenaire supprimé avec succès']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erreur lors de la suppression du partenaire'], 500);
        }
    }
}
