<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{     
    // Récupérer tous les utilisateurs
    public function index()
    {
        return response()->json(User::all());
    }

    // Ajouter un administrateur
    public function registerAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'nom'      => 'required',
            'prenom'   => 'required',
            'telephone' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $admin = User::create([
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'nom'      => $request->nom,
            'prenom'   => $request->prenom,
            'telephone' => $request->telephone,
            'role'     => 'admin', 
            'status'   => 'pending', 
        ]);

        return response()->json(['message' => 'Administrateur ajouté avec succès', 'user' => $admin], 201);
    }

    // Ajouter un nouvel utilisateur
    public function registerUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'nom'      => 'required',
            'prenom'   => 'required',
            'role'     => 'required|in:admin,entreprise,soumissionnaire',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'nom'      => $request->nom,
            'prenom'   => $request->prenom,
            'role'     => $request->role,
            'status'   => 'en attente', 
        ]);

        return response()->json(['message' => 'Utilisateur ajouté avec succès', 'user' => $user], 201);
    }

    // Modifier un utilisateur
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'email|unique:users,email,'.$id,
            'nom'   => 'string',
            'prenom'=> 'string',
            'role'  => 'in:admin,entreprise,soumissionnaire',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->update($request->only(['nom', 'prenom', 'email', 'role']));

        return response()->json(['message' => 'Utilisateur mis à jour', 'user' => $user]);
    }

    // Supprimer un utilisateur
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'Utilisateur supprimé avec succès']);
    }

    // Valider un utilisateur
    public function approveUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        if ($user->status !== 'en attente') {
            return response()->json(['message' => 'Ce compte est déjà validé ou rejeté.'], 400);
        }
        $user->update(['status' => 'actif']);


        return response()->json(['message' => 'Compte activé avec succès !']);
    }

    // Rejeter un utilisateur
    public function rejectUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }
        $user->update(['status' => 'suspendu']);


        return response()->json(['message' => 'Compte rejeté avec succès']);
    }
}