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
    Route::get('/scanners/{user}', [ScannerController::class, 'showScanner'])->name('scanner-show');
    Route::get('/scanners/{user}/modifier', [ScannerController::class, 'editScanner'])->name('scanner-edit');
    Route::put('/scanners/{user}', [ScannerController::class, 'updateScanner'])->name('scanner-update');
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
    
    // 3. Run migrations
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $output[] = 'Database migrations executed: ' . trim(\Illuminate\Support\Facades\Artisan::output());
    } catch (\Exception $e) {
        $output[] = 'Error running database migrations: ' . $e->getMessage();
    }

    // 4. Clear cache
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

// Route de diagnostic pour inspecter les fichiers publics et d'images
Route::get('/debug-files', function () {
    $output = [];
    
    $output['document_root'] = $_SERVER['DOCUMENT_ROOT'] ?? 'unknown';
    $output['script_filename'] = $_SERVER['SCRIPT_FILENAME'] ?? 'unknown';
    if (isset($_SERVER['SCRIPT_FILENAME']) && file_exists($_SERVER['SCRIPT_FILENAME'])) {
        $output['script_content'] = file_get_contents($_SERVER['SCRIPT_FILENAME']);
        $docroot = dirname($_SERVER['SCRIPT_FILENAME']);
        $output['docroot_path'] = $docroot;
        $output['docroot_files'] = array_map(function($file) {
            return basename($file) . (is_dir($file) ? '/' : '') . ' - ' . (is_dir($file) ? 'dir' : filesize($file) . ' bytes');
        }, glob($docroot . '/*'));
        
        $docrootImages = $docroot . '/images';
        if (file_exists($docrootImages)) {
            $output['docroot_images_files'] = array_map(function($file) {
                return basename($file) . (is_dir($file) ? '/' : '') . ' - ' . (is_dir($file) ? 'dir' : filesize($file) . ' bytes');
            }, glob($docrootImages . '/*'));
        }
        
        $docrootDownloads = $docroot . '/downloads';
        if (file_exists($docrootDownloads)) {
            $output['docroot_downloads_files'] = array_map(function($file) {
                return basename($file) . (is_dir($file) ? '/' : '') . ' - ' . (is_dir($file) ? 'dir' : filesize($file) . ' bytes');
            }, glob($docrootDownloads . '/*'));
        }
    }
    
    if (request()->has('fix')) {
        $docroot = dirname($_SERVER['SCRIPT_FILENAME']);
        $publicPath = public_path();
        
        $sync_dirs = ['images', 'downloads', 'build', 'asset', 'assets'];
        $copied_files = [];
        $errors = [];
        
        $sync_fn = function ($src, $dst) use (&$sync_fn, &$copied_files, &$errors) {
            if (!file_exists($src)) return;
            if (!file_exists($dst)) {
                if (!mkdir($dst, 0755, true)) {
                    $errors[] = "Failed to create directory $dst";
                    return;
                }
            }
            $dir = opendir($src);
            if ($dir === false) {
                $errors[] = "Failed to open directory $src";
                return;
            }
            while (false !== ($file = readdir($dir))) {
                if ($file != '.' && $file != '..') {
                    $srcFile = $src . '/' . $file;
                    $dstFile = $dst . '/' . $file;
                    if (is_dir($srcFile)) {
                        $sync_fn($srcFile, $dstFile);
                    } else {
                        if ($file === 'index.php' || $file === '.htaccess') {
                            continue;
                        }
                        if (!file_exists($dstFile) || filesize($srcFile) !== filesize($dstFile)) {
                            if (copy($srcFile, $dstFile)) {
                                $copied_files[] = basename($src) . '/' . $file;
                            } else {
                                $errors[] = "Failed to copy $srcFile to $dstFile";
                            }
                        }
                    }
                }
            }
            closedir($dir);
        };

        foreach ($sync_dirs as $dirName) {
            $srcDir = $publicPath . '/' . $dirName;
            $dstDir = $docroot . '/' . $dirName;
            if (file_exists($srcDir)) {
                $sync_fn($srcDir, $dstDir);
            }
        }
        
        foreach (['favicon.ico', 'robots.txt'] as $flatFile) {
            $srcFile = $publicPath . '/' . $flatFile;
            $dstFile = $docroot . '/' . $flatFile;
            if (file_exists($srcFile)) {
                if (!file_exists($dstFile) || filesize($srcFile) !== filesize($dstFile)) {
                    if (copy($srcFile, $dstFile)) {
                        $copied_files[] = $flatFile;
                    } else {
                        $errors[] = "Failed to copy $srcFile to $dstFile";
                    }
                }
            }
        }
        
        $output['fix_status'] = 'Sync completed.';
        $output['copied_files'] = $copied_files;
        $output['errors'] = $errors;
    }
    
    $publicPath = public_path();
    $output['public_path'] = $publicPath;
    $output['public_exists'] = file_exists($publicPath);
    
    if (file_exists($publicPath)) {
        $output['public_files'] = array_map(function($file) {
            return basename($file) . (is_dir($file) ? '/' : '') . ' - ' . (is_dir($file) ? 'dir' : filesize($file) . ' bytes');
        }, glob($publicPath . '/*'));
    }
    
    $imagesPath = public_path('images');
    $output['images_path'] = $imagesPath;
    $output['images_exists'] = file_exists($imagesPath);
    
    if (file_exists($imagesPath)) {
        $output['images_files'] = array_map(function($file) {
            $perms = file_exists($file) ? substr(sprintf('%o', fileperms($file)), -4) : 'unknown';
            $owner = file_exists($file) ? fileowner($file) : 'unknown';
            $header = '';
            if (file_exists($file) && !is_dir($file)) {
                $fp = fopen($file, 'rb');
                $bytes = fread($fp, 8);
                fclose($fp);
                $header = bin2hex($bytes);
            }
            return basename($file) . (is_dir($file) ? '/' : '') . ' - ' . (is_dir($file) ? 'dir' : filesize($file) . ' bytes') . ' - Perms: ' . $perms . ' - Owner: ' . $owner . ' - Header: ' . $header;
        }, glob($imagesPath . '/*'));
    }
    
    // Inspecter le dossier parent du projet
    $parentPath = dirname($publicPath);
    $output['parent_path'] = $parentPath;
    $output['parent_perms'] = file_exists($parentPath) ? substr(sprintf('%o', fileperms($parentPath)), -4) : 'unknown';
    $output['public_perms'] = file_exists($publicPath) ? substr(sprintf('%o', fileperms($publicPath)), -4) : 'unknown';
    $output['images_perms'] = file_exists($imagesPath) ? substr(sprintf('%o', fileperms($imagesPath)), -4) : 'unknown';
    
    if (file_exists($parentPath)) {
        $output['parent_files'] = array_map(function($file) {
            $perms = file_exists($file) ? substr(sprintf('%o', fileperms($file)), -4) : 'unknown';
            return basename($file) . (is_dir($file) ? '/' : '') . ' - Perms: ' . $perms;
        }, glob($parentPath . '/*'));
        
        // Vérifier spécifiquement s'il y a un .htaccess caché dans le parent
        $output['parent_htaccess_exists'] = file_exists($parentPath . '/.htaccess');
        if ($output['parent_htaccess_exists']) {
            $output['parent_htaccess_content'] = file_get_contents($parentPath . '/.htaccess');
        }
    }
    
    return response()->json($output);
});

// Route de secours pour servir les images/médias si les liens symboliques sont désactivés ou mal configurés sur LWS
Route::get('/storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    if (!file_exists($filePath)) {
        abort(404);
    }
    return response()->file($filePath);
})->where('path', '.*');

