<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AppelOffreController;
use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return 'API';
});

//  Authentification
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'getUser']);
    //  Déconnexion
    Route::post('/logout', [AuthController::class, 'logout']);

    //  Gestion des utilisateurs
  

        Route::get('/users', [AdminController::class, 'index']); 
        Route::get('/users/pending', [AdminController::class, 'pendingUsers']); 
        Route::post('/users/{id}/approve', [AdminController::class, 'approveUser']); 
        Route::post('/users/{id}/reject', [AdminController::class, 'rejectUser']); 
        Route::post('/admin/register', [AdminController::class, 'registerAdmin']); 
        Route::post('/users', [AdminController::class, 'registerUser']); 
        Route::put('/users/{id}', [AdminController::class, 'update']); 
        Route::delete('/users/{id}', [AdminController::class, 'destroy']);
   

    // Gestion des entreprises
    Route::get('/entreprises', [EntrepriseController::class, 'index']);

    // Gestion des appels d'offre
    Route::post('/appel-offre', [AppelOffreController::class, 'store']);

});

//  Affichage des appels d'offre dans l'espace public 
Route::get('/appel-offres', [AppelOffreController::class, 'index']);
Route::get('/appel-offres/{id}', [AppelOffreController::class, 'show']);