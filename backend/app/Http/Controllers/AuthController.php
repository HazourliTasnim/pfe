<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Entreprise;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validation des données reçues
        $validator = Validator::make($request->all(), [
            'email'           => 'required|email|unique:users',
            'password'        => 'required|min:6|confirmed',
            // Informations anciennement du représentant, désormais stockées dans la table users
            'civilite'        => 'required',
            'nom'             => 'required',
            'prenom'          => 'required',
            'fonction'        => 'required',
            'telephone'       => 'required',
            'pays'            => 'required',
            'wilaya' => ($request->pays == '4') ? 'required' : 'nullable',
            'adresse'         => 'required',
            'nom_entreprise'  => 'required',
            'activite'        => 'required',
            'categorie'       => 'required',
            'code_commerce'   => 'required|unique:entreprises',
            'num_fiscal'      => 'required|unique:entreprises',
            'num_statistique' => 'required|unique:entreprises',
            'num_national'    => 'required|unique:entreprises',
            'nombre_employes' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        Log::info('Validation réussie, création de l\'utilisateur');

        // Utilisation d'une transaction pour garantir la cohérence en cas d'erreur
        \DB::beginTransaction();
        try {
            // Création de l'utilisateur avec les informations du représentant intégrées
           // Création de l'entreprise en premier
$entreprise = Entreprise::create([
    'nom_entreprise' => $request->nom_entreprise,
    'activite'       => $request->activite,
    'categorie'      => $request->categorie,
    'telephone'      => $request->telephone,
    'pays'           => $request->pays,
    'wilaya'         => $request->wilaya,
    'adresse'        => $request->adresse,
    'code_commerce'  => $request->code_commerce,
    'num_fiscal'     => $request->num_fiscal,
    'num_statistique'=> $request->num_statistique,
    'num_national'   => $request->num_national,
    'nombre_employes'=> $request->nombre_employes,
]);

// Puis création de l'utilisateur
$user = User::create([
    'email'      => $request->email,
    'password'   => Hash::make($request->password),
    'role'       => 'entreprise',
    'status'     => 'pending',
    'civilite'   => $request->civilite,
    'nom'        => $request->nom,
    'prenom'     => $request->prenom,
    'fonction'   => $request->fonction,
    'entreprise_id' => $entreprise->id, // Maintenant, $entreprise->id est bien défini
]);

            Log::info('Entreprise créée avec ID: ' . $entreprise->id);

            \DB::commit();

            return response()->json([
                'message' => 'Inscription réussie!',
                'user'    => [
                    'id'    => $user->id,
                    'email' => $user->email,
                    'role'  => $user->role,
                ]
            ], 201);
        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Erreur lors de la création: ' . $e->getMessage());
            return response()->json([
                'error'   => 'Erreur lors de l\'inscription',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
    
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email ou mot de passe incorrect'
            ], 401);
        }
    
        $token = $user->createToken('authToken')->plainTextToken;
        $entreprise = $user->entreprise;
    
        return response()->json([
            'success' => true,
            'token'   => $token,
            'user'    => [
                'id'             => $user->id,
                'civilite'       => $user->civilite,
                'nom'            => $user->nom,
                'prenom'         => $user->prenom,
                'nom_entreprise' => $entreprise ? $entreprise->nom_entreprise : null,
                'role'           => $user->role,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        try {
            if ($request->user()) {
                $request->user()->tokens()->delete(); // Supprime les tokens de l'utilisateur
                return response()->json(['message' => 'Déconnexion réussie'], 200);
            }
            return response()->json(['error' => 'Utilisateur non authentifié'], 401);
        } catch (\Exception $e) {
            return response()->json([
                'error'   => 'Erreur serveur',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getUser(Request $request)
    {
        // Charge la relation avec l'entreprise
        $user = $request->user()->load('entreprise');
    
        if (!$user) {
            return response()->json(['error' => 'Utilisateur non authentifié'], 401);
        }
    
        return response()->json([
            'id'         => $user->id,
            'email'      => $user->email,
            'role'       => $user->role,
            'status'     => $user->status,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
            // Les informations du représentant sont désormais intégrées dans la table users
            'representant' => [
                'civilite'  => $user->civilite,
                'nom'       => $user->nom,
                'prenom'    => $user->prenom,
                'fonction'  => $user->fonction,
                'telephone' => $user->telephone,
                'pays'      => $user->pays,
                'wilaya'    => $user->wilaya,
                'adresse'   => $user->adresse,
            ],
            'entreprise' => $user->entreprise ? [
                'nom_entreprise'  => $user->entreprise->nom_entreprise,
                'activite'        => $user->entreprise->activite,
                'categorie'       => $user->entreprise->categorie,
                'code_commerce'   => $user->entreprise->code_commerce,
                'num_fiscal'      => $user->entreprise->num_fiscal,
                'num_statistique' => $user->entreprise->num_statistique,
                'num_national'    => $user->entreprise->num_national,
                'nombre_employes' => $user->entreprise->nombre_employes,
            ] : null
        ]);
    }
}
