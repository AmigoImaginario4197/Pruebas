<?php

// --- IMPORTS ---
// Agrupamos todos los controladores que usaremos.
use App\Http\Controllers\CitaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistorialMedicoController;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TratamientoController;
use App\Http\Controllers\UserController; 
use Illuminate\Support\Facades\Route;

// ========== RUTAS PÚBLICAS ==========
// Accesibles para cualquier visitante, no requieren inicio de sesión.
Route::get('/', function () { return view('home'); })->name('home');
Route::get('/presentacion', function () { return view('presentacion'); })->name('presentacion');
Route::get('/faqs', function () { return view('faqs'); })->name('faqs');
Route::get('/contacto', function () { return view('contacto'); })->name('contacto');

// Rutas de páginas legales
Route::prefix('legal')->name('legal.')->group(function () {
    Route::get('/aviso', function () { return view('aviso-legal'); })->name('aviso');
    Route::get('/privacidad', function () { return view('politica-privacidad'); })->name('privacidad');
    Route::get('/cookies', function () { return view('politica-cookies'); })->name('politica-cookies');
});


// ========== RUTAS DE AUTENTICACIÓN ==========
// Manejan el login, registro, logout, recuperación de contraseña, etc.
require __DIR__.'/auth.php';


// ========== RUTAS PRIVADAS (PANEL DE CONTROL) ==========
// Requieren que el usuario haya iniciado sesión y verificado su email.
Route::middleware(['auth', 'verified'])->group(function () {

    // --- Panel Principal ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- Gestión del Perfil del Propio Usuario ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // --- Gestión de Mascotas ---
    // Esta única línea ahora maneja correctamente todas las rutas necesarias,
    // incluyendo las de 'show' y 'edit' que solicitaste.
    Route::resource('mascotas', MascotaController::class)->middleware('role:cliente,admin');

    // Ruta especial para que los veterinarios vean el listado general de mascotas
    Route::get('/veterinario/mascotas', [MascotaController::class, 'indexVeterinario'])
        ->name('veterinario.mascotas.index')
        ->middleware('role:veterinario');

    // --- Funcionalidades Principales ---
    Route::resource('citas', CitaController::class);
    Route::resource('tratamientos', TratamientoController::class);
    Route::resource('historial', HistorialMedicoController::class);

    // --- Panel de Administración (Solo para Admins) ---
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        // Ejemplo: Gestionar todos los usuarios del sistema
        Route::resource('usuarios', UserController::class);
    });
         
});