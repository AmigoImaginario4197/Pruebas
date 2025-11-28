<?php

use App\Http\Controllers\CitaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistorialMedicoController;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TratamientoController;
use App\Http\Controllers\UserController; 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DisponibilidadController;
use App\Http\Controllers\MedicamentoController;
use App\Http\Controllers\PlanDiarioController;

// ========== RUTAS PÚBLICAS ==========
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
require __DIR__.'/auth.php';


// ========== RUTAS PRIVADAS (PANEL DE CONTROL) ==========
Route::middleware(['auth', 'verified'])->group(function () {

    // --- Panel Principal ---
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- Gestión del Perfil del Propio Usuario ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // --- Gestión de Mascotas ---
    Route::resource('mascotas', MascotaController::class)->middleware('role:cliente,admin');

    // Ruta especial para que los veterinarios vean el listado general de mascotas
    Route::get('/veterinario/mascotas', [MascotaController::class, 'indexVeterinario'])
        ->name('veterinario.mascotas.index')
        ->middleware('role:veterinario');

    // --- Funcionalidades Principales ---
    Route::resource('citas', CitaController::class);
    Route::resource('tratamientos', TratamientoController::class);
    Route::resource('historial', HistorialMedicoController::class);
    
    Route::resource('users', UserController::class)->middleware('role:admin');
   
    Route::resource('disponibilidad', DisponibilidadController::class)->middleware('role:admin,veterinario');
    Route::resource('medicamentos', MedicamentoController::class)->middleware('role:admin,veterinario');
    Route::resource('plan-diario', PlanDiarioController::class)->middleware('role:admin,veterinario');

 // Ruta para cuando el pago es exitoso
Route::get('/citas/pago-exitoso/{cita}', [CitaController::class, 'pagoExitoso'])->name('citas.success');
});