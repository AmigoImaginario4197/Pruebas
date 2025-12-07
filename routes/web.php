<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\CitaController;      
use App\Http\Controllers\AgendaController;    
use App\Http\Controllers\TratamientoController;
use App\Http\Controllers\HistorialMedicoController;
use App\Http\Controllers\DisponibilidadController;
use App\Http\Controllers\MedicamentoController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\LogsController;

// ========== RUTAS PÚBLICAS ==========
Route::get('/', function () { return view('home'); })->name('home');
Route::get('/presentacion', function () { return view('presentacion'); })->name('presentacion');
Route::get('/faqs', function () { return view('faqs'); })->name('faqs');
Route::get('/contacto', function () { return view('contacto'); })->name('contacto');

// Rutas legales
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

    // --- Perfil Usuario ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Mascotas ---
    // Ruta específica antes del resource
    Route::get('/veterinario/mascotas', [MascotaController::class, 'indexVeterinario'])
        ->name('veterinario.mascotas.index')
        ->middleware('role:veterinario');
        
    Route::resource('mascotas', MascotaController::class)->middleware('role:cliente,admin');


    // --- CITAS (Lógica de Negocio) ---
    // 1. Rutas específicas de citas (SIEMPRE ANTES DEL RESOURCE)
    Route::get('/citas/pago-exitoso/{cita}', [CitaController::class, 'pagoExitoso'])->name('citas.success');
    Route::patch('/citas/{cita}/cancelar', [CitaController::class, 'cancelar'])->name('citas.cancelar');
    
    // 2. CRUD completo de citas
    Route::resource('citas', CitaController::class);


    // --- AGENDA (Solo Visualización) ---
    // Solo necesitamos el index para ver el calendario y alimentar el JSON
    Route::get('/agenda', [AgendaController::class, 'index'])
        ->name('agenda.index')
        ->middleware('role:admin,veterinario');


    // --- Otros Módulos ---
    Route::resource('tratamientos', TratamientoController::class);
    Route::resource('historial', HistorialMedicoController::class);
    Route::resource('users', UserController::class)->middleware('role:admin');
    
    Route::resource('disponibilidad', DisponibilidadController::class)->middleware('role:admin,veterinario');
    Route::resource('medicamentos', MedicamentoController::class)->middleware('role:admin,veterinario');
    Route::resource('servicios', ServicioController::class)->middleware('role:admin');
    Route::resource('tareas', TareaController::class)->only(['store', 'update', 'destroy']);

    Route::get('/agenda/data', [AgendaController::class, 'getEvents'])->name('agenda.data');
    Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');
    Route::resource('tareas', TareaController::class);
    Route::get('/logs', [LogsController::class, 'index'])->name('logs.index')->middleware('role:admin');
    
});