<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;

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
        
        // Gate para verificar email verificado
        Gate::define('access-dashboard', function ($user) {
            return $user->hasVerifiedEmail();
        });
        
        // Gate para verificar que un usuario puede ver una mascota
        Gate::define('view-mascota', function ($user, $mascota) {
            return $user->id === $mascota->usuario_id;
        });

        // Gate para administradores/veterinarios
        Gate::define('admin-access', function ($user) {
            return in_array($user->rol, ['admin', 'veterinario']);
        });

    }
}