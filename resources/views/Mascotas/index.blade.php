<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Mascotas - Pet Care</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Cargamos nuestro CSS maestro -->
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="panel-container">
        
        {{-- ¡MAGIA! Incluimos el menú reutilizable con una sola línea --}}
        @include('layouts.sidebar')

        <!-- Main Content -->
        <main class="panel-main">
            {{-- Header de la página --}}
            <header class="panel-header">
                <div class="header-title">
                    <h3>Mis Mascotas</h3>
                    <p>Gestiona los perfiles de tus compañeros.</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('mascotas.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Añadir Mascota
                    </a>
                </div>
            </header>

            {{-- Contenido principal de la página --}}
            <div class="panel-content">
                <div class="item-list">
                    @forelse ($mascotas as $mascota)
                        <div class="item-card">
                            <!-- Foto -->
                            <img src="{{ $mascota->foto ? asset('storage/' . $mascota->foto) : asset('images/placeholder-pet.png') }}" alt="Foto de {{ $mascota->nombre }}" class="item-photo">
                            
                            <!-- Detalles -->
                            <div class="item-details">
                                <h4>{{ $mascota->nombre }}</h4>
                                <p>{{ $mascota->especie }} - {{ $mascota->raza ?? 'Raza no especificada' }}</p>
                            </div>

                            <!-- Acciones -->
                            <div class="item-actions">
                                <a href="{{-- route('mascotas.show', $mascota) --}}" class="btn btn-secondary">
                                    <i class="bi bi-eye"></i> Ver
                                </a>
                                <a href="{{-- route('mascotas.edit', $mascota) --}}" class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <form action="{{-- route('mascotas.destroy', $mascota) --}}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar a {{ $mascota->nombre }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state-card">
                            <i class="bi bi-heart-half"></i>
                            <p>Aún no has registrado ninguna mascota.</p>
                            <p>¡Haz clic en "Añadir Mascota" para empezar!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>
</body>
</html>