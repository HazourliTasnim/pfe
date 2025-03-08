<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Entreprise;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email'           => 'required|email|unique:users',
        'password'        => 'required|min:6|confirmed',
        'civilite'        => 'required',
        'nom'             => 'required',
        'prenom'          => 'required',
        'fonction'        => 'required',
        'role'            => ['required', 'in:entreprise,soumissionnaire'],
        'telephone'       => 'required',
        'pays'            => 'required',
        'wilaya'          => [Rule::requiredIf($request->pays == '4')],
        'adresse'         => 'required',
        'nom_entreprise'  => 'required',
        'activite'        => 'required',
        'categorie'       => 'required',
        'code_commerce'   => 'required|unique:entreprises',
        'num_fiscal'      => 'required|unique:entreprises',
        'num_statistique' => 'required|unique:entreprises',
        'num_national'    => 'required|unique:entreprises',
        'nombre_employes' => 'required|integer|min:1',
        'logo'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    \DB::beginTransaction();
    try {
        // 1️⃣ Création de l'entreprise sans logo
        $entreprise = Entreprise::create([
            'nom_entreprise'  => $request->nom_entreprise,
            'activite'        => $request->activite,
            'categorie'       => $request->categorie,
            'pays'            => $request->pays,
            'wilaya'          => $request->wilaya,
            'adresse'         => $request->adresse,
            'code_commerce'   => $request->code_commerce,
            'num_fiscal'      => $request->num_fiscal,
            'num_statistique' => $request->num_statistique,
            'num_national'    => $request->num_national,
            'nombre_employes' => $request->nombre_employes,
            'logo'            => 'storage/logo/default.png', // Valeur par défaut
        ]);

        // 2️⃣ Gestion de l'upload du logo (après la création de l'entreprise)
        if ($request->hasFile('logo')) {
            Log::info('Un fichier logo a été détecté.');
            $file = $request->file('logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('public/logo', $filename);
        
            Log::info('Chemin du fichier stocké : ' . $filePath);
        
            $logoPath = 'storage/logo/' . $filename;
            $entreprise->update(['logo' => $logoPath]);
        } else {
            Log::warning('Aucun fichier logo détecté.');
        }

        // 3️⃣ Création de l'utilisateur
        $user = User::create([
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'role'          => $request->role,
            'status'        => 'pending',
            'civilite'      => $request->civilite,
            'nom'           => $request->nom,
            'prenom'        => $request->prenom,     
            'telephone'     => $request->telephone,
            'fonction'      => $request->fonction,
            'entreprise_id' => $entreprise->id,
        ]);

        if (!$user) {
            $entreprise->delete();
            throw new \Exception('Erreur lors de la création de l\'utilisateur.');
        }

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
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
    
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
                'status'         => $user->status_label, 
            ]
        ]);
        
    }
    public function logout(Request $request)
    {
        try {
            if ($request->user()) {
                $request->user()->tokens()->delete(); 
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
        return response()->json($request->user());
    }
   
}