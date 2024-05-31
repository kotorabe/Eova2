<?php

use App\Http\Controllers\BackOffice\EquipeController;
use App\Http\Controllers\Employe\DashboardController;
use App\Http\Controllers\Employe\LivraisonController;
use App\Http\Controllers\Employe\PlanningController;
use App\Http\Controllers\Employe\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


// Route::middleware('auth:api')->group(function () {
    // Vos routes protégées ici
Route::post('authentification', [DashboardController::class, 'login']);
// });

//Tableau de bord
Route::get('tableauBord/{id}', [DashboardController::class, 'index']);
Route::get('dash/{id}/{mois}', [DashboardController::class, 'dash']);

//Profile
Route::get('profile/{id}', [ProfileController::class, 'index']);

//Planning
Route::get('planning/{id}', [PlanningController::class, 'index']);

//Details Planning
Route::get('planning/Details/{id_devis}', [PlanningController::class, 'getDetails']);

//equipe
Route::get('getDataEquipe/{id}', [DashboardController::class, 'getDataEquipe']);

//livraison
Route::post('Position', [LivraisonController::class, 'getPosition']);
Route::get('getLivraison/{id}', [LivraisonController::class, 'getLivraison']);
Route::get('beginLivraison/{id}/{pos}', [LivraisonController::class, 'beginLivraison']);
Route::post('Livraison', [LivraisonController::class, 'goLivraison']);
Route::post('Fini', [LivraisonController::class, 'FiniLivraison']);

