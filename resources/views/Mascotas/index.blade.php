<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    {{-- Mismo head que tu dashboard para mantener la consistencia --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Mascotas - Pet Care</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    @if(file_exists(public_path('css/dashboard.css')))
        <style>{{ file_get_contents(public_path('css/dashboard.css')) }}</style>
    @endif
    <style>
        /* Estilos específicos para la lista de mascotas */
        .pet-list { display: flex; flex-direction: column; gap: 1rem; }
        .pet-card { display: flex; align-items: center; background: #fff; padding: 1rem; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); gap: 1.5rem; }
        .pet-photo { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #eee; }
        .pet-details { flex-grow: 1; }
        .pet-details h3 { margin: 0; font-size: 1.25rem; font-weight: 600; }
        .pet-actions { display: flex; gap: 0.5rem; }
        .pet-actions .btn { padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; color: #fff; font-weight: 500; display: inline-flex; align-items: center; gap: 0.25rem; }
        .btn-view { background-color: #3b82f6; }
        .btn-edit { background-color: #f59e0b; }
        .btn-delete { background-color: #ef4444; border: none; font-family: inherit; font-size: inherit; cursor: pointer; }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="dashboard-container">
        <!-- Sidebar (puedes incluirla desde un archivo parcial para no repetir) -->
        @include('layouts.sidebar') {{-- Asumimos que tienes un sidebar.blade.php, si no, pega el código del menú aquí --}}

        <!-- Main Content -->
        <div class="dashboard-main">
            <header class="dashboard-header">
                <div class="user-info">
                    <h3>Mis Mascotas</h3>
                    <p>Gestiona los perfiles de tus compañeros</p>
                </div>
                <div class="header-actions">
                    {{-- Botón para añadir una nueva mascota --}}
                    <a href="{{ route('mascotas.create') }}" class="btn-accept" style="text-decoration:none;">
                        <i class="bi bi-plus-circle"></i> Añadir Mascota
                    </a>
                </div>
            </header>

            <main class="dashboard-content">
                <div class="pet-list">
                    @forelse ($mascotas as $mascota)
                        <div class="pet-card">
                            <!-- Foto de la Mascota -->
                            <img src="{{ $mascota->foto ? asset('storage/' . $mascota->foto) : asset('images/placeholder-pet.png') }}" alt="Foto de {{ $mascota->nombre }}" class="pet-photo">
                            
                            <!-- Detalles y Nombre -->
                            <div class="pet-details">
                                <h3>{{ $mascota->nombre }}</h3>
                                <p class="text-gray-600">{{ $mascota->especie }} - {{ $mascota->raza ?? 'Raza no especificada' }}</p>
                            </div>

                            <!-- Botones de Acción -->
                            <div class="pet-actions">
                                <a href="{{ route('mascotas.show', $mascota) }}" class="btn btn-view">
                                    <i class="bi bi-eye"></i> Ver
                                </a>
                                <a href="{{ route('mascotas.edit', $mascota) }}" class="btn btn-edit">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <form action="{{ route('mascotas.destroy', $mascota) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar a {{ $mascota->nombre }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="no-appointments-card" style="text-align: center; padding: 20px;">
                            <p>Aún no has registrado ninguna mascota. ¡Añade a tu primer compañero!</p>
                        </div>
                    @endforelse
                </div>
            </main>
        </div>
    </div>
</body>
</html>