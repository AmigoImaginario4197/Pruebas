<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Disponibilidad - Pet Care</title>
    
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
                    <h3>Mi Disponibilidad</h3>
                    <p>Gestiona tus horarios disponibles para citas.</p>
                </div>
                
                <div class="header-actions"> 
                    @if(Auth::check() && Auth::user()->role === 'admin')
                    <a href="{{ route('disponibilidad.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Añadir Bloque
                    </a>
                    @endif
                </div>
            </header>

            <div class="panel-content">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="item-list">
                    @forelse ($disponibilidades as $disponibilidad)
                        <div class="item-card">
                            <div class="item-photo-placeholder">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                            
                            <div class="item-details">
                                <!-- CAMBIO AQUÍ: Agregar locale('es') -->
                                <h4>{{ \Carbon\Carbon::parse($disponibilidad->fecha)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</h4>
                                <p>
                                    De <span class="badge bg-success">{{ \Carbon\Carbon::parse($disponibilidad->hora_inicio)->format('H:i') }}</span>
                                    a <span class="badge bg-danger">{{ \Carbon\Carbon::parse($disponibilidad->hora_fin)->format('H:i') }}</span>
                                </p>
                            </div>

                            @if(Auth::check() && Auth::user()->role === 'admin')
                            <div class="item-actions justify-content-end">
                                <a href="{{ route('disponibilidad.edit', $disponibilidad) }}" class="btn btn-warning btn-sm" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('disponibilidad.destroy', $disponibilidad) }}" method="POST" onsubmit="return confirm('¿Estás seguro?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    @empty
                        <div class="empty-state-card">
                            <i class="bi bi-calendar-x"></i>
                            <p>No tienes horarios configurados.</p>
                            <p>¡Haz clic en "Añadir Bloque" para empezar!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>