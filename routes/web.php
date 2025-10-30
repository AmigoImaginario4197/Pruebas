<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HistorialMedicoController;
use App\Http\Controllers\Auth\RegisterController;

Route::resource('usuarios', UserController::class);
Route::resource('historial', HistorialMedicoController::class);

// Página principal pública
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/presentacion', function () {
    return view('presentacion');
})->name('presentacion');

Route::get('/faqs', function () {
    return view('faqs');
})->name('faqs');

Route::get('/contacto', function () {
    return view('contacto');
})->name('contacto');

Route::get('/aviso-legal', function () {
    return view('aviso-legal');
})->name('aviso-legal');

Route::get('/politica-privacidad', function () {
    return view('politica-privacidad');
})->name('politica-privacidad');

Route::get('/politica-cookies', function () {
    return view('politica-cookies');
})->name('politica-cookies');

// Ruta de registro
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Dashboard privado
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas de perfil (autenticado)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';