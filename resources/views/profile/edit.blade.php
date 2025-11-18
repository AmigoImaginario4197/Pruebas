<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - Pet Care</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    {{-- ¡Importante! Usamos tu CSS personalizado --}}
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="panel-container">
        
        {{-- Incluimos tu layout de sidebar --}}
        @include('layouts.sidebar')

        <main class="panel-main">
            {{-- Header de la página --}}
            <header class="panel-header">
                <div class="header-title">
                    <h3>Perfil de Usuario</h3>
                    <p>Gestiona la información de tu cuenta.</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver al Dashboard
                    </a>
                </div>
            </header>

            {{-- Contenido principal con los formularios --}}
            <div class="panel-content">
                
                {{-- Tarjeta para actualizar la información del perfil --}}
                <div class="card p-4 mb-4">
                    <h5 class="card-title mb-3">Información del Perfil</h5>
                    <p class="card-subtitle mb-3 text-muted">Actualiza el nombre y la dirección de correo electrónico de tu cuenta.</p>
                    
                    <form method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="username">
                            @error('email')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror

                            {{-- Lógica para la verificación de email si se cambia --}}
                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div class="mt-2">
                                    <p class="text-sm text-warning">
                                        Tu dirección de correo no está verificada.
                                        <a href="{{ route('verification.send') }}" class="text-decoration-underline">
                                            Haz clic aquí para reenviar el correo de verificación.
                                        </a>
                                    </p>
                                    @if (session('status') === 'verification-link-sent')
                                        <p class="mt-2 text-sm text-success">
                                            Se ha enviado un nuevo enlace de verificación a tu correo.
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="d-flex align-items-center gap-4">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            @if (session('status') === 'profile-updated')
                                <p class="text-success m-0">Guardado.</p>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- Tarjeta para actualizar la contraseña --}}
                <div class="card p-4 mb-4">
                    <h5 class="card-title mb-3">Actualizar Contraseña</h5>
                    <p class="card-subtitle mb-3 text-muted">Asegúrate de que tu cuenta utilice una contraseña larga y aleatoria para mantenerse segura.</p>

                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Contraseña Actual</label>
                            <input id="current_password" name="current_password" type="password" class="form-control" autocomplete="current-password">
                            @error('current_password', 'updatePassword')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Nueva Contraseña</label>
                            <input id="password" name="password" type="password" class="form-control" autocomplete="new-password">
                             @error('password', 'updatePassword')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password">
                            @error('password_confirmation', 'updatePassword')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex align-items-center gap-4">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            @if (session('status') === 'password-updated')
                                <p class="text-success m-0">Contraseña actualizada.</p>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- Tarjeta para eliminar la cuenta (acción peligrosa) --}}
                <div class="card p-4 border-danger">
                    <div class="card-body" style="color: var(--bs-danger-text-emphasis); background-color: var(--bs-danger-bg-subtle);">
                        <h5 class="card-title">Eliminar Cuenta</h5>
                        <p class="card-text">Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán borrados permanentemente. Antes de eliminar tu cuenta, por favor descarga cualquier dato o información que desees conservar.</p>
                        
                        {{-- Usamos un formulario simple con confirmación JS para la acción de borrar --}}
                        <form method="post" action="{{ route('profile.destroy') }}" onsubmit="return confirm('¿Estás absolutamente seguro de que quieres eliminar tu cuenta? Esta acción no se puede deshacer.');">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-exclamation-triangle"></i> Eliminar Cuenta
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </main>
    </div>
</body>
</html>