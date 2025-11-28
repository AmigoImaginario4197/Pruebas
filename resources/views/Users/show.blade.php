<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- Título dinámico para la página --}}
    <title>Detalles: {{ $user->name }} - Pet Care</title>
    
    {{-- Dependencias de Estilos --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="panel-container">     
        
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title">
                    <h3>Detalles de: {{ $user->name }}</h3>
                    <p>Información completa del perfil de usuario.</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver al Listado
                    </a>
                </div>
            </header>

            <div class="panel-content">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            {{-- Columna para datos principales --}}
                            <div class="col-md-6">
                                <h5>Información Principal</h5>
                                <p><strong>ID:</strong> {{ $user->id }}</p>
                                <p><strong>Nombre:</strong> {{ $user->name }}</p>
                                <p><strong>Email:</strong> {{ $user->email }}</p>
                                <p><strong>Rol:</strong> 
                                    <span class="badge @if($user->rol == 'admin') bg-danger @elseif($user->rol == 'veterinario') bg-primary @else bg-secondary @endif">
                                        {{ ucfirst($user->rol) }}
                                    </span>
                                </p>
                                <p><strong>Miembro desde:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
                            </div>

                            {{-- Columna para datos adicionales (usamos '??' para evitar errores si son nulos) --}}
                            <div class="col-md-6">
                                <h5>Datos Adicionales</h5>
                                <p><strong>Teléfono:</strong> {{ $user->telefono ?? 'No especificado' }}</p>
                                <p><strong>Dirección:</strong> {{ $user->direccion ?? 'No especificada' }}</p>
                                <p><strong>NIF:</strong> {{ $user->nif ?? 'No especificado' }}</p>
                                <p><strong>Especialidad:</strong> {{ $user->especialidad ?? 'No especificada' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        {{-- Botón para ir directamente a la página de edición --}}
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Editar Usuario
                        </a>
                    </div>
                </div>
            </div>
        </main>

    </div> {{-- Cierre de <div class="panel-container"> --}}

    {{-- Scripts de JavaScript --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>