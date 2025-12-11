<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - Pet Care</title>

    <!-- Fuentes y Iconos -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Estilos CSS Separados -->
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">  
    <link rel="stylesheet" href="{{ asset('css/perfil.css') }}"> 
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    
    <div class="panel-container">
        
        @include('layouts.sidebar')

        <main class="panel-main">
            
            <header class="panel-header">
                <div class="header-title">
                    <h3>Perfil de Usuario</h3>
                    <p>Gestiona tu información personal y la seguridad de tu cuenta.</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver al Dashboard
                    </a>
                </div>
            </header>

            <!-- Sección de Tarjetas -->
            <div class="panel-content">         
                
                <!-- === DATOS PERSONALES === -->
                <div class="profile-card">
                    <h5>Información Personal</h5>
                    <p class="subtitle">Actualiza tus datos de contacto y facturación.</p>
                    
                    <form method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="row" style="display: flex; gap: 20px; flex-wrap: wrap;">
                            <!-- Nombre -->
                            <div class="form-group" style="flex: 1; min-width: 300px;">
                                <label for="name" class="form-label">Nombre Completo</label>
                                <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autofocus>
                                @error('name') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>

                            <!-- Email -->
                            <div class="form-group" style="flex: 1; min-width: 300px;">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                @error('email') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row" style="display: flex; gap: 20px; flex-wrap: wrap;">
                            <!-- Teléfono -->
                            <div class="form-group" style="flex: 1; min-width: 250px;">
                                <label for="telefono" class="form-label">Teléfono Móvil</label>
                                <input id="telefono" name="telefono" type="tel" class="form-control" 
                                       value="{{ old('telefono', $user->telefono) }}" 
                                       required pattern="[0-9]{9,15}" 
                                       title="Solo números (mínimo 9 dígitos)"
                                       placeholder="600123456">
                                @error('telefono') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>

                            <!-- NIF/DNI -->
                            <div class="form-group" style="flex: 1; min-width: 250px;">
                                <label for="nif" class="form-label">NIF / DNI</label>
                                <input id="nif" name="nif" type="text" class="form-control" 
                                       value="{{ old('nif', $user->nif) }}" 
                                       required pattern="[0-9]{8}[A-Za-z]{1}" 
                                       title="8 números y 1 letra (Ej: 12345678Z)"
                                       placeholder="12345678Z" style="text-transform: uppercase;">
                                @error('nif') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- Dirección -->
                        <div class="form-group">
                            <label for="direccion" class="form-label">Dirección Completa</label>
                            <input id="direccion" name="direccion" type="text" class="form-control" 
                                   value="{{ old('direccion', $user->direccion) }}" 
                                   required placeholder="Calle Principal, Nº 123, Piso 1, Madrid">
                            @error('direccion') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Aviso de verificación de email (si aplica) -->
                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div class="alert alert-warning mt-2">
                                Tu correo no está verificado. 
                                <button form="send-verification" class="btn-link">Reenviar verificación</button>.
                            </div>
                        @endif

                        <div class="d-flex align-items-center mt-3">
                            <button type="submit" class="btn-save">Guardar Cambios</button>
                            @if (session('status') === 'profile-updated')
                                <span class="status-msg"><i class="bi bi-check-circle"></i> Datos guardados.</span>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="profile-card">
                    <h5>Seguridad</h5>
                    <p class="subtitle">Actualiza tu contraseña para mantener tu cuenta segura.</p>

                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="form-group">
                            <label for="current_password" class="form-label">Contraseña Actual</label>
                            <input id="current_password" name="current_password" type="password" class="form-control" autocomplete="current-password">
                            @error('current_password', 'updatePassword') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label">Nueva Contraseña</label>
                            <input id="password" name="password" type="password" class="form-control" autocomplete="new-password">
                            @error('password', 'updatePassword') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password">
                        </div>

                        <div class="d-flex align-items-center">
                            <button type="submit" class="btn-save">Actualizar Contraseña</button>
                            @if (session('status') === 'password-updated')
                                <span class="status-msg"><i class="bi bi-check-circle"></i> Contraseña actualizada.</span>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="profile-card danger-zone">
                    <h5>Zona de Peligro</h5>
                    <p class="subtitle">Eliminar tu cuenta es irreversible. Se borrarán todas tus mascotas, citas y datos.</p>
                    
                    <form method="post" action="{{ route('profile.destroy') }}" onsubmit="return confirm('¿ESTÁS SEGURO?\n\nEsta acción borrará PERMANENTEMENTE tu cuenta y todos tus datos.\n\nNo se puede deshacer.');">
                        @csrf
                        @method('delete')
                        
                        <button type="submit" class="btn-delete">
                            <i class="bi bi-trash3-fill"></i> Eliminar mi cuenta permanentemente
                        </button>
                    </form>
                </div>

            </div>
        </main>
    </div>
</body>
</html>