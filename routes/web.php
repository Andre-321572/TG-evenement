<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BilletController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ConferenceController;
use App\Http\Controllers\EvenementController;
use App\Http\Controllers\EventfuturController;
use App\Http\Controllers\OrganisateurController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\SponsorController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ScannerController;

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

Route::get('/', [PublicController::class, 'index'])->name('index');

/*-------------------------------------public---------------------------------- */
// Routes avec le préfixe '/p' et le préfixe de nom 'p.'
Route::prefix('p')->name('p.')->group(function () {
    Route::get('/a-propos', [PublicController::class, 'a_propos'])->name('a-propos');
    Route::get('/contats', [PublicController::class, 'contact'])->name('contact');
    Route::get('/concert-et-festival-de-musique', [PublicController::class, 'concert_et_festival_de_musique'])->name('concert et festival de musique');
    Route::get('/conferences-et-congres', [PublicController::class, 'conference_et_congres'])->name('conferences et congres');
    Route::get('/evenement-sportif', [PublicController::class, 'evenement_sportif'])->name('evenement sportif');
    Route::get('/evenement', [PublicController::class, 'evenement'])->name('evenement');
    Route::get('/faq', [PublicController::class, 'faq'])->name('faq');
    Route::get('/fete', [PublicController::class, 'fete'])->name('fete');
    Route::get('/sante', [PublicController::class, 'sante'])->name('santé');
    Route::get('/vie-nocturne', [PublicController::class, 'vie_nocturne'])->name('vie nocturne');
    Route::get('/voyage', [PublicController::class, 'voyage'])->name('voyage');
    Route::get('/detail/{id}/info',[PublicController::class,'show'])->name('detail');
    Route::post('/payement/checkout', [PaiementController::class, 'createCheckout'])->name('paiement.checkout');
    Route::get('/payement/success', [PaiementController::class, 'success'])->name('paiement.success');
    Route::get('/payement/cancel/{evenement}', [PaiementController::class, 'cancel'])->name('paiement.cancel');
    Route::get('/payement/process', [PaiementController::class, 'processPayment'])->name('paiement.process');
    Route::get('/payement/{evenement}', [PaiementController::class, 'showForm'])->name('paiement.form');
});

Route::middleware('auth')->group(function () {
    // Vous pouvez ajouter ici des routes nécessitant une authentification
});

Auth::routes();

/*-------------------------------------organisateur/admin---------------------------------- */
// Routes pour l'organisateur avec le préfixe 'organisateur' et le préfixe de nom 'organisateur.'
Route::prefix('organisateur')->name('organisateur.')->middleware(['auth'])->group(function () {
    Route::get('/', [OrganisateurController::class, 'index'])->name('dashboard');
    Route::get('/historique', [OrganisateurController::class, 'historique'])->name('historique');

    // Routes pour les événements
    Route::get('/creer-un-evenement', [EvenementController::class, 'create'])->name('ajouter-un-evenement');
    Route::post('/evenement-valider', [EvenementController::class, 'store'])->name('evenement_valider');
    Route::get('/evenement-en-cours', [OrganisateurController::class, 'evenementencours'])->name('evenement-en-cours');
    Route::get('/evenement-en-cours/{id}/supprimer', [EvenementController::class, 'destroy'])->name('supprimer');
    Route::get('/detail-evenement/{id}/detail', [EvenementController::class, 'show'])->name('detail');
    Route::get('/modifier-un-evenement/{id}/modifier', [EvenementController::class, 'edit'])->name('update_form');
    Route::put('/modifier-evenement/{id}', [EvenementController::class, 'update'])->name('ev-update');
    Route::get('/evenement/{id}/publier', [OrganisateurController::class, 'publier'])->name('publier');
    Route::get('/evenement/{id}/archiver', [OrganisateurController::class, 'archiver'])->name('archiver');

    // Routes futures
    Route::get('/futur/evenement-en-attente', [OrganisateurController::class, 'futurevenement'])->name('future.future');
    Route::get('/futur/organiser-en-attente', [OrganisateurController::class, 'organiserenattente'])->name('future.organiser-un-evenement-pour-le-future');
    Route::get('/evenement-passe', [OrganisateurController::class, 'evenement_passer'])->name('evenement-passe');

    // Chat
    Route::match(['post','get','delete'],'/chat', [ChatController::class, 'chat'])->name('chat');

    // Les sponsors
    Route::get('/sponsor', [SponsorController::class, 'create'])->name('sponsor-form');
    Route::match(['get', 'post', 'put'], '/sponsor-send', [SponsorController::class, 'store'])->name('valide-sponsor');

    // Les billets
    Route::match(['get', 'post', 'put'], '/billet-store', [BilletController::class, 'store'])->name('valide-billet');
    Route::match(['get', 'post', 'put'], '/billet', [BilletController::class, 'index'])->name('billet');
    Route::match(['get', 'post', 'put'], '/billet/form', [BilletController::class, 'create'])->name('billet-form');
    Route::match(['get'], '/billet/all', [BilletController::class, 'allBillets'])->name('billet-all');


});

// ─── Scanner routes ───────────────────────────────────────────────────────────
Route::prefix('scanner')->name('scanner.')->middleware(['auth', 'scanner'])->group(function () {
    Route::get('/', [ScannerController::class, 'dashboard'])->name('dashboard');
    Route::post('/verify', [ScannerController::class, 'verify'])->name('verify');
    Route::get('/stats', [ScannerController::class, 'stats'])->name('stats');
});

// ─── Admin: manage scanners ───────────────────────────────────────────────────
Route::prefix('organisateur')->name('organisateur.')->middleware(['auth'])->group(function () {
    Route::get('/scanners', [ScannerController::class, 'listScanners'])->name('scanners');
    Route::get('/scanners/creer', [ScannerController::class, 'createScanner'])->name('scanner-create');
    Route::post('/scanners/creer', [ScannerController::class, 'storeScanner'])->name('scanner-store');
    Route::delete('/scanners/{user}', [ScannerController::class, 'deleteScanner'])->name('scanner-delete');
});

// Routes générales (pour les pages d'accueil alternatives)
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

// Production Administration Helper Route
Route::get('/setup-production', function () {
    $output = [];
    
    // 1. Link storage
    try {
        if (file_exists(public_path('storage'))) {
            if (is_link(public_path('storage'))) {
                unlink(public_path('storage'));
                $output[] = 'Existing storage symlink unlinked.';
            } else if (is_dir(public_path('storage'))) {
                // If it is a directory (e.g. from previous upload errors), rename it
                rename(public_path('storage'), public_path('storage_backup_' . time()));
                $output[] = 'Existing public/storage directory backed up.';
            }
        }
        
        \Illuminate\Support\Facades\Artisan::call('storage:link');
        $output[] = 'storage:link command executed: ' . trim(\Illuminate\Support\Facades\Artisan::output());
    } catch (\Exception $e) {
        $output[] = 'Error linking storage: ' . $e->getMessage();
    }
    
    // 2. Remove public/hot if exists
    try {
        $hotFile = public_path('hot');
        if (file_exists($hotFile)) {
            unlink($hotFile);
            $output[] = 'public/hot file deleted.';
        } else {
            $output[] = 'public/hot file did not exist.';
        }
    } catch (\Exception $e) {
        $output[] = 'Error deleting public/hot: ' . $e->getMessage();
    }
    
    // 3. Clear cache
    try {
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        $output[] = 'Application caches cleared successfully!';
    } catch (\Exception $e) {
        $output[] = 'Error clearing caches: ' . $e->getMessage();
    }
    
    return response()->json($output);
});

/*
// Routes optionnelles pour les utilisateurs authentifiés
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
});

// Exemple d'utilisation dans une vue Blade:
@auth
    <p>Bienvenue, {{ Auth::user()->name }} !</p>
    <a href="{{ route('profile') }}">Mon profil</a>
@else
    <p>Veuillez <a href="{{ route('login') }}">vous connecter</a> pour accéder à cette page.</p>
@endauth
*/
