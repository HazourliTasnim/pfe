<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AppelOffreController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProfileController;
Route::get('/', function () {
    return 'Api';
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
 
    Route::get('/user', [AuthController::class, 'getUser']);
    Route::post('/appel-offre', [AppelOffreController::class, 'store']);
Route::post('/documents', [DocumentController::class, 'store']);

});
Route::get('/appel-offres', [AppelOffreController::class, 'index']);
Route::get('/profile', [ProfileController::class, 'show']);
Route::put('/profile/update', [ProfileController::class, 'update']);
