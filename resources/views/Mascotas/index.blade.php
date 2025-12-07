<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Mascotas - Pet Care</title>
    
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
                    <h3>Mis Mascotas</h3>
                    <p>Gestiona los perfiles de tus compañeros.</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('mascotas.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Añadir Mascota
                    </a>
                </div>
            </header>

            <div class="panel-content">
                {{-- Mostramos mensaje de éxito si existe --}}
                @if (session('success'))
                    <div class="alert alert-success mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                
                <div class="item-list">
                    @forelse ($mascotas as $mascota)
                        <div class="item-card">
                            <img src="{{ $mascota->foto ? asset('storage/' . $mascota->foto) : asset('images/placeholder-pet.png') }}" alt="Foto de {{ $mascota->nombre }}" class="item-photo">
                            
                            <div class="item-details">
                                <h4>{{ $mascota->nombre }}</h4>
                                <p>{{ $mascota->especie }} - {{ $mascota->raza ?? 'Raza no especificada' }}</p>
                            </div>

                            <div class="item-actions">
                                {{-- BOTÓN "VER" MODIFICADO PARA ABRIR LA MODAL --}}
                                <a href="{{ route('mascotas.show', $mascota) }}" class="btn btn-secondary">
                                <i class="bi bi-eye"></i> Ver
                                </a>
                                
                                {{-- MODIFICADO: Rutas descomentadas --}}
                                <a href="{{ route('mascotas.edit', $mascota) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <form action="{{ route('mascotas.destroy', $mascota) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar a {{ $mascota->nombre }}?');">
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

    {{-- AÑADIDO: Estructura HTML de la Ventana Modal --}}
    <div class="modal fade" id="mascotaModal" tabindex="-1" aria-labelledby="mascotaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mascotaModalLabel">Detalles de la Mascota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <img id="modalFoto" src="" class="img-fluid rounded-circle" style="max-height: 150px; max-width: 150px; object-fit: cover; border: 3px solid #eee;" alt="Foto de la mascota">
                    </div>
                    <p><strong>Nombre:</strong> <span id="modalNombre"></span></p>
                    <p><strong>Especie:</strong> <span id="modalEspecie"></span></p>
                    <p><strong>Raza:</strong> <span id="modalRaza"></span></p>
                    <p><strong>Edad:</strong> <span id="modalEdad"></span></p>
                    <p><strong>Peso:</strong> <span id="modalPeso"></span></p>
                    <p><strong>Fecha de Nacimiento:</strong> <span id="modalFechaNacimiento"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>