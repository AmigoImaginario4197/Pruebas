<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;

// Importaciones para Eventos de Auth
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;

// Modelos y Observers
use App\Models\LogEvento;
use App\Models\Cita;
use App\Models\Tarea;
use App\Models\Mascota;
use App\Models\User;
use App\Observers\LogObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // =========================================================
        // 1. TUS GATES ACTUALES (Permisos y Reglas)
        // =========================================================
        
        // Gate para verificar email verificado
        Gate::define('access-dashboard', function ($user) {
            return $user->hasVerifiedEmail();
        });
        
        // Gate para verificar que un usuario puede ver una mascota
        Gate::define('view-mascota', function ($user, $mascota) {
            // Nota: Asegúrate que tu columna en BD sea 'usuario_id' o 'user_id'
            return $user->id === $mascota->usuario_id; 
        });

        // Gate para administradores/veterinarios
        Gate::define('admin-access', function ($user) {
            return in_array($user->rol, ['admin', 'veterinario']);
        });

        // 2. ACTIVACIÓN DE OBSERVERS (Registro Automático CRUD)

        // Cada vez que se cree, edite o borre uno de estos, se guardará en log_eventos
        Cita::observe(LogObserver::class);
        Tarea::observe(LogObserver::class);
        Mascota::observe(LogObserver::class);
        User::observe(LogObserver::class);

        // 3. REGISTRO DE INICIO Y CIERRE DE SESIÓN

        // Registrar cuando alguien INICIA sesión
        Event::listen(Login::class, function ($event) {
            LogEvento::create([
                'user_id' => $event->user->id,
                'accion'  => 'Inicio de Sesión',
                'detalle' => 'Ingreso al sistema',
                'fecha'   => now()
            ]);
        });

        // Registrar cuando alguien CIERRA sesión
        Event::listen(Logout::class, function ($event) {
            if ($event->user) {
                LogEvento::create([
                    'user_id' => $event->user->id,
                    'accion'  => 'Cierre de Sesión',
                    'detalle' => 'Salida del sistema',
                    'fecha'   => now()
                ]);
            }
        });
    }
}