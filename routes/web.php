<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Contrôleurs généraux
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\SmsController;

// Contrôleurs d'authentification
use App\Modules\Auth\Controllers\LoginController;
use App\Modules\Auth\Controllers\RegisterController;
use App\Modules\Auth\Controllers\ForgotPasswordController;
use App\Modules\Auth\Controllers\ResetPasswordController;

// Contrôleurs par rôle
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUserController;
use App\Http\Controllers\SuperAdmin\CabinetController as SuperAdminCabinetController;
use App\Http\Controllers\SuperAdmin\StatistiqueController as SuperAdminStatistiqueController;
use App\Http\Controllers\SuperAdmin\AuditController as SuperAdminAuditController;
use App\Http\Controllers\SuperAdmin\ConfigurationController as SuperAdminConfigurationController;
use App\Http\Controllers\SuperAdmin\MaintenanceController as SuperAdminMaintenanceController;
use App\Http\Controllers\Medecin\DashboardController as MedecinDashboardController;
use App\Http\Controllers\Secretaire\DashboardController as SecretaireDashboardController;
use App\Http\Controllers\Patient\DashboardController as PatientDashboardController;
use App\Http\Controllers\Fournisseur\DashboardController as FournisseurDashboardController;
use App\Http\Controllers\Fournisseur\FournisseurController;

// ===========================================
// ROUTES PUBLIQUES
// ===========================================
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/debug-ping', function() { return 'pong'; });

// ===========================================
// AUTHENTIFICATION
// ===========================================
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // Register
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/patient/register', [RegisterController::class, 'showPatientRegistrationForm'])->name('patient.register');
    Route::post('/patient/register', [RegisterController::class, 'registerPatient'])->name('patient.register.submit');
    
    // Password reset
    Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// Logout (POST seulement)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ===========================================
// ROUTES PROTÉGÉES (AUTH)
// ===========================================
Route::middleware(['auth'])->group(function () {
    
    // Home après connexion
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Dashboard principal (redirection)
    Route::get('/dashboard', function () {
        $user = Auth::user();
        $role = $user->role;
        
        $routes = [
            'super_admin' => 'super_admin.dashboard',
            'admin_cabinet' => 'admin.dashboard',
            'medecin' => 'medecin.dashboard',
            'secretaire' => 'secretaire.dashboard',
            'patient' => 'patient.dashboard',
            'fournisseur' => 'fournisseur.dashboard',
        ];
        
        $route = $routes[$role] ?? 'patient.dashboard';  // Sécurité : rôle inconnu → patient
        
        return redirect()->route($route);
    })->name('dashboard');
    
    // Données pour les graphiques
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');
    
    // ===========================================
    // SUPER ADMIN
    // ===========================================
    Route::middleware(['role:super_admin'])->prefix('super-admin')->name('super_admin.')->group(function () {
        
        Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');
        
        // Gestion des utilisateurs
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [SuperAdminUserController::class, 'index'])->name('index');
            Route::get('/create', [SuperAdminUserController::class, 'create'])->name('create');
            Route::post('/', [SuperAdminUserController::class, 'store'])->name('store');
            Route::get('/{id}', [SuperAdminUserController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [SuperAdminUserController::class, 'edit'])->name('edit');
            Route::put('/{id}', [SuperAdminUserController::class, 'update'])->name('update');
            Route::delete('/{id}', [SuperAdminUserController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/toggle-status', [SuperAdminUserController::class, 'toggleStatus'])->name('toggle-status');
        });
        
        // Gestion des cabinets
        Route::prefix('cabinets')->name('cabinets.')->group(function () {
            Route::get('/', [SuperAdminCabinetController::class, 'index'])->name('index');
            Route::get('/create', [SuperAdminCabinetController::class, 'create'])->name('create');
            Route::post('/', [SuperAdminCabinetController::class, 'store'])->name('store');
            Route::get('/{id}', [SuperAdminCabinetController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [SuperAdminCabinetController::class, 'edit'])->name('edit');
            Route::put('/{id}', [SuperAdminCabinetController::class, 'update'])->name('update');
            Route::delete('/{id}', [SuperAdminCabinetController::class, 'destroy'])->name('destroy');
        });
        
        // Statistiques
        Route::prefix('statistiques')->name('statistiques.')->group(function () {
            Route::get('/', [SuperAdminStatistiqueController::class, 'index'])->name('index');
            Route::get('/rapports', [SuperAdminStatistiqueController::class, 'rapports'])->name('rapports');
        });
        
        // Audits
        Route::prefix('audits')->name('audits.')->group(function () {
            Route::get('/', [SuperAdminAuditController::class, 'index'])->name('index');
        });
        
        // Configuration
        Route::prefix('configurations')->name('configurations.')->group(function () {
            Route::get('/', [SuperAdminConfigurationController::class, 'index'])->name('index');
            Route::post('/', [SuperAdminConfigurationController::class, 'store'])->name('store');
        });
        
        // Maintenance
        Route::prefix('maintenance')->name('maintenance.')->group(function () {
            Route::get('/', [SuperAdminMaintenanceController::class, 'index'])->name('index');
        });
    });
    
    // ===========================================
    // ADMIN CABINET
    // ===========================================
    Route::middleware(['role:admin_cabinet'])->prefix('admin')->group(function () {
        
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        
        // Inclusion des modules (avec vérification d'existence)
        $moduleFiles = [
            'patient' => __DIR__.'/modules/patient.php',
            'rendezvous' => __DIR__.'/modules/rendezvous.php',
            'stock' => __DIR__.'/modules/stock.php',
            'cnam' => __DIR__.'/modules/cnam.php',
        ];
        
        foreach ($moduleFiles as $module => $path) {
            if (file_exists($path)) {
                if (in_array($module, ['patient', 'rendezvous', 'cnam'])) {
                    Route::name('admin.')->group(function() use ($path) {
                        require $path;
                    });
                } else {
                    require $path;
                }
            }
        }
        
        // Fournisseurs
        Route::resource('fournisseurs', FournisseurController::class);
        
        // SMS
        Route::resource('sms', SmsController::class)->except(['create', 'edit']);
        Route::post('/sms/send-rappel-rdv', [SmsController::class, 'sendRappelRdv'])->name('sms.send-rappel-rdv');
        Route::post('/sms/send-alerte-stock', [SmsController::class, 'sendAlerteStock'])->name('sms.send-alerte-stock');
        
        // Consultations
        Route::resource('consultations', ConsultationController::class);
        
        // Gestion utilisateurs (pour admin cabinet)
        Route::prefix('users')->name('admin.users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
            Route::post('/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
        });
    });
    
    // ===========================================
    // MEDECIN
    // ===========================================
    Route::middleware(['role:medecin'])->prefix('medecin')->name('medecin.')->group(function () {
        Route::get('/dashboard', [MedecinDashboardController::class, 'index'])->name('dashboard');
        
        // Module CNAM pour médecin
        if (file_exists(__DIR__.'/modules/cnam.php')) {
            require __DIR__.'/modules/cnam.php';
        }
    });
    
    // ===========================================
    // SECRETAIRE
    // ===========================================
    Route::middleware(['role:secretaire'])->prefix('secretaire')->name('secretaire.')->group(function () {
        Route::get('/dashboard', [SecretaireDashboardController::class, 'index'])->name('dashboard');
        
        // Inclure les modules communs si nécessaire
        if (file_exists(__DIR__.'/modules/patient.php')) {
            require __DIR__.'/modules/patient.php';
        }
    });
    
    // ===========================================
    // PATIENT
    // ===========================================
    Route::middleware(['role:patient'])->prefix('patient')->name('patient.')->group(function () {
        Route::get('/dashboard', [PatientDashboardController::class, 'index'])->name('dashboard');
        Route::get('/historique', [PatientDashboardController::class, 'historique'])->name('historique');
        
        // Modules pour patients
        if (file_exists(__DIR__.'/modules/rendezvous.php')) {
            require __DIR__.'/modules/rendezvous.php';
        }
    });
    
    // ===========================================
    // FOURNISSEUR
    // ===========================================
    Route::middleware(['role:fournisseur'])->prefix('fournisseur')->name('fournisseur.')->group(function () {
        Route::get('/dashboard', [FournisseurDashboardController::class, 'index'])->name('dashboard');
    });
    
    // ===========================================
    // API INTERNE (AJAX)
    // ===========================================
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/stats', [DashboardController::class, 'getChartData'])->name('stats');
        if (class_exists(\App\Http\Controllers\NotificationController::class)) {
            Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications');
        }
    });
});

// ===========================================
// API EXTERNE (token)
// ===========================================
if (file_exists(__DIR__.'/modules/api.php')) {
    require __DIR__.'/modules/api.php';
}

// ===========================================
// FALLBACK
// ===========================================
Route::fallback(function () {
    return Auth::check() 
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});
