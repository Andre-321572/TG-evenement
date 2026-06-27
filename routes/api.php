<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiEventController;
use App\Http\Controllers\Api\ApiTicketController;
use App\Http\Controllers\Api\ApiScannerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Authentification publique
Route::post('/auth/register', [ApiAuthController::class, 'register']);
Route::post('/auth/login', [ApiAuthController::class, 'login']);

// Événements publics
Route::get('/events', [ApiEventController::class, 'index']);
Route::get('/events/{id}', [ApiEventController::class, 'show']);
Route::get('/categories', [ApiEventController::class, 'categories']);

// Routes protégées par jeton (Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    // Authentification privée
    Route::post('/auth/logout', [ApiAuthController::class, 'logout']);
    Route::get('/auth/me', [ApiAuthController::class, 'me']);
    
    // Tickets du participant
    Route::get('/my-tickets', [ApiTicketController::class, 'myTickets']);
    
    // Fonctionnalités du Scanner
    Route::post('/scanner/verify', [ApiScannerController::class, 'verify']);
    Route::get('/scanner/stats', [ApiScannerController::class, 'stats']);
    
    // Fonctionnalités de l'Organisateur
    Route::get('/organisateur/dashboard', [ApiEventController::class, 'organizerDashboard']);
    Route::post('/organisateur/events', [ApiEventController::class, 'store']);
    Route::post('/organisateur/events/{id}', [ApiEventController::class, 'update']);
    Route::delete('/organisateur/events/{id}', [ApiEventController::class, 'destroy']);
    Route::post('/organisateur/events/{id}/publish', [ApiEventController::class, 'publish']);
    Route::post('/organisateur/events/{id}/archive', [ApiEventController::class, 'archive']);
    
    // Gestion des Scanners par l'Organisateur
    Route::get('/organisateur/scanners', [ApiScannerController::class, 'listScanners']);
    Route::post('/organisateur/scanners', [ApiScannerController::class, 'storeScanner']);
    Route::delete('/organisateur/scanners/{user}', [ApiScannerController::class, 'deleteScanner']);
});
