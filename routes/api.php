<?php

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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
/*<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\BilletController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ConferenceController;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\EventfuturController;
use App\Http\Controllers\OrganisateurController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\SponsorController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Route::get('/', function () {
//    return view('index');
//});
Route::get('/',[PublicController::class,'index'])->name('index');


// Routes avec le préfixe '/admin' et le préfixe de nom 'admin.'
Route::prefix('p')->name('p.')->group(function () {
    Route::get('/a-propos', [PublicController::class, 'a_propos'])->name('a-propos');

    Route::get('/concert et festival de musique', [PublicController::class, 'concert_et_festival_de_musique'])->name('concert et festival de musique');
    Route::get('/conferences et congres', [PublicController::class, 'conference_et_congres'])->name('conferences et congres');
    Route::get('/evenement sportif',[PublicController::class,'evenement_sportif'])->name('evenement sportif');
    Route::get('/evenement',[PublicController::class,'evenement'])->name('evenement');
    Route::get('/faq',[PublicController::class,'faq'])->name('faq');
    Route::get('/fete',[PublicController::class,'fete'])->name('fete');
    Route::get('/santé',[PublicController::class,'sante'])->name('santé');
    Route::get('/vie nocturne',[PublicController::class,'vie_nocturne'])->name('vie nocturne');
    Route::get('/voyage',[PublicController::class,'voyage'])->name('voyage');
});

// Routes pour l'organisateur avec le préfixe 'organisateur' et le préfixe de nom 'organisateur.'
Route::prefix('organisateur')->name('organisateur.')->group(function () {
    Route::get('/dashboard', [OrganisateurController::class, 'index'])->name('dashboard');
    Route::get('/events', [OrganizerController::class, 'listEvents'])->name('events');
    Route::get('/events/create', [OrganizerController::class, 'createEventForm'])->name('createEvent');
    Route::post('/events', [OrganizerController::class, 'storeEvent'])->name('storeEvent');
    Route::get('/events/{id}/edit', [OrganizerController::class, 'editEventForm'])->name('editEvent');
    Route::put('/events/{id}', [OrganizerController::class, 'updateEvent'])->name('updateEvent');
    Route::delete('/events/{id}', [OrganizerController::class, 'deleteEvent'])->name('deleteEvent');
    // Vous pouvez ajouter d'autres routes spécifiques à l'organisateur ici
});

// Routes générales
//Route::get('/', [HomeController::class, 'index'])->name('home');
//Route::get('/events', [EventController::class, 'index'])->name('events.index');
//Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
