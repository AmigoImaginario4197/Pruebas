<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles: {{ $user->name }} - Pet Care</title>
    
    <!-- Iconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- CSS Base -->
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    
    <!-- TU CSS REUTILIZADO (El mismo de Create/Edit) -->
    <link rel="stylesheet" href="{{ asset('css/users-form.css') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Estilos ligeros extra solo para 'lectura' (para que el texto se vea bonito) -->
    <style>
        .view-label {
            color: #6b7280; /* Gris suave para la etiqueta */
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.2rem;
            display: block;
        }
        .view-value {
            color: #1f2937; /* Gris oscuro para el dato */
            font-size: 1.1rem;
            font-weight: 500;
            padding-bottom: 0.5rem;
            border-bottom: 1px dashed #e5e7eb; /* Línea punteada sutil */
        }
        .avatar-header {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #f3f4f6;
        }
        .avatar-circle {
            width: 70px;
            height: 70px;
            background-color: #e0e7ff; /* Fondo morado claro */
            color: #4f46e5; /* Icono morado */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="panel-container">     
        
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title">
                    <h3>Detalles de Usuario</h3>
                    <p>Ficha técnica de: <strong>{{ $user->name }}</strong></p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </header>

            <div class="panel-content">
                <!-- Reutilizamos tu clase .user-card -->
                <div class="user-card">
                    
                    <!-- Encabezado Visual con Avatar -->
                    <div class="avatar-header">
                        <div class="avatar-circle">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <div>
                            <h2 style="margin: 0; font-size: 1.5rem;">{{ $user->name }}</h2>
                            <div class="mt-1">
                                <!-- Badge de Rol dinámico -->
                                @php
                                    $badgeClass = match($user->rol) {
                                        'admin' => 'bg-danger', // Rojo
                                        'veterinario' => 'bg-primary', // Azul
                                        default => 'bg-secondary' // Gris (cliente)
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} rounded-pill px-3">
                                    {{ ucfirst($user->rol) }}
                                </span>
                                <span class="text-muted ms-2 small">
                                    <i class="bi bi-clock"></i> Miembro desde: {{ $user->created_at->format('d/m/Y') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN 1: DATOS PERSONALES (Usando tu .form-grid) -->
                    <h4 class="form-section-title"><i class="bi bi-person-lines-fill"></i> Información de Contacto</h4>
                    <div class="form-grid">
                        
                        <!-- Email -->
                        <div>
                            <span class="view-label">Correo Electrónico</span>
                            <div class="view-value">{{ $user->email }}</div>
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <span class="view-label">Teléfono</span>
                            <div class="view-value">{{ $user->telefono ?? 'No especificado' }}</div>
                        </div>

                        <!-- NIF -->
                        <div>
                            <span class="view-label">DNI / NIF</span>
                            <div class="view-value">{{ $user->nif ?? 'No registrado' }}</div>
                        </div>

                        <!-- Dirección (.full-width del CSS original) -->
                        <div class="full-width">
                            <span class="view-label">Dirección Completa</span>
                            <div class="view-value">
                                <i class="bi bi-geo-alt text-danger"></i> 
                                {{ $user->direccion ?? 'Sin dirección registrada' }}
                            </div>
                        </div>
                    </div>

                    <!-- SECCIÓN 2: INFORMACIÓN PROFESIONAL (Condicional) -->
                    @if($user->rol === 'veterinario' || $user->especialidad)
                        <h4 class="form-section-title mt-4"><i class="bi bi-briefcase"></i> Perfil Profesional</h4>
                        <div class="form-grid">
                            <div>
                                <span class="view-label">Especialidad Médica</span>
                                <div class="view-value text-primary fw-bold">
                                    <i class="bi bi-hospital me-1"></i> {{ $user->especialidad }}
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- SECCIÓN 3: METADATOS SISTEMA -->
                    <h4 class="form-section-title mt-4"><i class="bi bi-hdd-stack"></i> Datos del Sistema</h4>
                    <div class="form-grid">
                        <div>
                            <span class="view-label">ID Interno</span>
                            <div class="view-value">#{{ $user->id }}</div>
                        </div>
                        <div>
                            <span class="view-label">Última Actualización</span>
                            <div class="view-value">{{ $user->updated_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>

                    <!-- BOTONES ACCIÓN -->
                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <!-- Solo Admin puede borrar -->
                        @if(Auth::user()->rol === 'admin')
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('¿Eliminar usuario permanentemente?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                            <i class="bi bi-pencil-square"></i> Editar Datos
                        </a>
                    </div>

                </div>
            </div>
        </main>
    </div>
</body>
</html>