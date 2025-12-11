<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Usuario - Pet Care</title>
    
    <!-- Iconos Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Estilos Generales -->
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    
    <!-- TU CSS PERSONALIZADO PARA FORMULARIOS -->
    <link rel="stylesheet" href="{{ asset('css/users-form.css') }}">
    
    <!-- Scripts (Tu JS con la lógica del rol) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="panel-container">     
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title">
                    <h3>Crear Nuevo Usuario</h3>
                    <p>Registra un nuevo miembro en la plataforma.</p>
                </div>
            </header>

            <div class="panel-content">
                <!-- Usamos la clase .user-card de tu CSS nuevo -->
                <div class="user-card">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf

                        <!-- ALERTAS DE ERROR -->
                        @if ($errors->any())
                            <div class="alert alert-danger d-flex align-items-center mb-4">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <div>
                                    <strong>¡Atención!</strong> Corrige los errores antes de continuar.
                                </div>
                            </div>
                        @endif

                        <!-- SECCIÓN 1: DATOS PERSONALES -->
                        <h4 class="form-section-title"><i class="bi bi-person-vcard"></i> Datos Personales</h4>
                        <div class="form-grid">
                            
                            <!-- Nombre -->
                            <div>
                                <label for="name" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Ej. Juan Pérez" required>
                                </div>
                                @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <!-- NIF -->
                            <div>
                                <label for="nif" class="form-label">DNI / NIF <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-card-heading"></i></span>
                                    <input type="text" class="form-control @error('nif') is-invalid @enderror" id="nif" name="nif" value="{{ old('nif') }}" placeholder="00000000X" required>
                                </div>
                                @error('nif')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="usuario@ejemplo.com" required>
                                </div>
                                @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <!-- Teléfono -->
                            <div>
                                <label for="telefono" class="form-label">Teléfono <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                    <input type="text" class="form-control @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono') }}" placeholder="+34 600 000 000" required>
                                </div>
                                @error('telefono')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <!-- Dirección (Ocupa 2 columnas) -->
                            <div class="full-width">
                                <label for="direccion" class="form-label">Dirección Completa <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                    <input type="text" class="form-control @error('direccion') is-invalid @enderror" id="direccion" name="direccion" value="{{ old('direccion') }}" placeholder="Calle, número, piso, ciudad..." required>
                                </div>
                                @error('direccion')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <!-- SECCIÓN 2: ROL Y PERMISOS -->
                        <h4 class="form-section-title"><i class="bi bi-briefcase"></i> Rol Profesional</h4>
                        <div class="form-grid">
                            
                            <!-- ROL -->
                            <div>
                                <label for="rolSelect" class="form-label">Rol del Usuario <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                                    
                                    {{-- ID="rolSelect" necesario para el JS --}}
                                    <select name="rol" id="rolSelect" class="form-select @error('rol') is-invalid @enderror" required>
                                        <option value="cliente" @selected(old('rol', 'cliente') == 'cliente')>Cliente</option>
                                        <option value="veterinario" @selected(old('rol') == 'veterinario')>Veterinario</option>
                                        <option value="admin" @selected(old('rol') == 'admin')>Administrador</option>
                                    </select>
                                </div>
                                @error('rol') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <!-- ESPECIALIDAD -->
                            {{-- ID="especialidad-wrapper" necesario para el JS --}}
                            <div id="especialidad-wrapper" style="display: none;">
                                <label for="especialidad" class="form-label">Especialidad Médica</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-hospital"></i></span>
                                    <select name="especialidad" id="especialidad" class="form-select @error('especialidad') is-invalid @enderror">
                                        <option value="">Seleccione especialidad...</option>
                                        <option value="Medicina General" @selected(old('especialidad') == 'Medicina General')>Medicina General</option>
                                        <option value="Cardiología" @selected(old('especialidad') == 'Cardiología')>Cardiología</option>
                                        <option value="Cirugía" @selected(old('especialidad') == 'Cirugía')>Cirugía</option>
                                        <option value="Dermatología" @selected(old('especialidad') == 'Dermatología')>Dermatología</option>
                                    </select>
                                </div>
                                @error('especialidad') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- SECCIÓN 3: SEGURIDAD -->
                        <h4 class="form-section-title"><i class="bi bi-lock"></i> Seguridad</h4>
                        <div class="form-grid">
                            <!-- Contraseña -->
                            <div>
                                <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-key"></i></span>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                </div>
                                @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <!-- Confirmar Contraseña -->
                            <div>
                                <label for="password_confirmation" class="form-label">Confirmar Contraseña <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>

                        <!-- BOTONES -->
                        <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary px-4">Cancelar</a>
                            <button type="submit" class="btn btn-primary px-4" style="background-color: #4f46e5; border:none;">
                                <i class="bi bi-save me-1"></i> Crear Usuario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>