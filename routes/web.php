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
use App\Http\Controllers\ContactoController;

// ========== RUTAS PÚBLICAS ==========
Route::get('/', function () { return view('home'); })->name('home');
Route::get('/presentacion', function () { return view('presentacion'); })->name('presentacion');
Route::get('/faqs', function () { return view('faqs'); })->name('faqs');

// Rutas de Contacto
Route::get('/contacto', [ContactoController::class, 'show'])->name('contacto');
Route::post('/contacto/enviar', [ContactoController::class, 'enviar'])->name('contacto.enviar');

// Rutas legales
Route::prefix('legal')->name('legal.')->group(function () {
    Route::get('/aviso', function () { return view('aviso-legal'); })->name('aviso');
    Route::get('/privacidad', function () { return view('politica-privacidad'); })->name('privacidad');
    Route::get('/cookies', function () { return view('politica-cookies'); })->name('politica-cookies');
});
Route::post('/contacto/enviar', [ContactoController::class, 'enviar'])->name('contacto.enviar');
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
    Route::get('/veterinario/mascotas', [MascotaController::class, 'indexVeterinario'])
        ->name('veterinario.mascotas.index')
        ->middleware('role:veterinario');
        
    Route::resource('mascotas', MascotaController::class)->middleware('role:cliente,admin');

    // --- CITAS (Lógica de Negocio) ---
    // 1. Horarios 
    Route::get('/api/horarios-disponibles', [CitaController::class, 'obtenerHorarios'])->name('api.horarios');
    
    // 2. Rutas específicas
    Route::get('/citas/pago-exitoso/{cita}', [CitaController::class, 'pagoExitoso'])->name('citas.success');
    Route::patch('/citas/{cita}/cancelar', [CitaController::class, 'cancelar'])->name('citas.cancelar');
    
    // 3. CRUD completo
    Route::resource('citas', CitaController::class);

    // --- AGENDA (Visualización) ---
    Route::get('/agenda/data', [AgendaController::class, 'getEvents'])->name('agenda.data');
    Route::get('/agenda', [AgendaController::class, 'index'])
        ->name('agenda.index')
        ->middleware('role:admin,veterinario');

    // --- TAREAS INTERNAS ---
    Route::resource('tareas', TareaController::class);

    // --- LOGS (Auditoría) ---
    Route::get('/logs', [LogsController::class, 'index'])->name('logs.index');

    // --- Otros Módulos ---
    Route::resource('tratamientos', TratamientoController::class);
    Route::resource('historial', HistorialMedicoController::class);
    
    // --- Solo Admin/Veterinario ---
    Route::middleware('role:admin,veterinario')->group(function() {
        Route::resource('disponibilidad', DisponibilidadController::class);
        Route::resource('medicamentos', MedicamentoController::class);
    });

    // --- Solo Admin ---
    Route::middleware('role:admin')->group(function() {
        Route::resource('users', UserController::class);
        Route::resource('servicios', ServicioController::class);
    });

});