<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios - Pet Care</title>
    
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
                    <h3>Catálogo de Servicios</h3>
                    <p>Gestiona los servicios y precios ofrecidos en la clínica.</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('servicios.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Nuevo Servicio
                    </a>
                </div>
            </header>

            <div class="panel-content">
                @if (session('success'))
                    <div class="alert alert-success mb-4">{{ session('success') }}</div>
                @endif

                <div class="item-list">
                    {{-- CORRECCIÓN: Aquí usamos $servicios, no $citas --}}
                    @forelse ($servicios as $servicio)
                        <div class="item-card">
                            {{-- Icono de etiqueta --}}
                            <div class="item-photo-placeholder">
                                <i class="bi bi-tags-fill"></i>
                            </div>
                            
                            <div class="item-details">
                                <h4>{{ $servicio->nombre }}</h4>
                                <p class="mb-1 text-muted">{{ \Illuminate\Support\Str::limit($servicio->descripcion, 60) }}</p>
                                {{-- Precio destacado --}}
                                <span class="badge bg-success fs-6">${{ number_format($servicio->precio, 2) }}</span>
                            </div>

                            <div class="item-actions justify-content-end">
                                {{-- Botón Ver --}}
                                <a href="{{ route('servicios.show', $servicio) }}" class="btn btn-info btn-sm" title="Ver Detalles">
                                    <i class="bi bi-eye"></i>
                                </a>

                                {{-- Botón Editar --}}
                                <a href="{{ route('servicios.edit', $servicio) }}" class="btn btn-warning btn-sm" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                {{-- Botón Borrar --}}
                                <form action="{{ route('servicios.destroy', $servicio) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este servicio?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state-card">
                            <i class="bi bi-tags"></i>
                            <p>No hay servicios registrados.</p>
                            <p>¡Crea el primero para empezar a ofrecer citas!</p>
                        </div>
                    @endforelse
                </div>
                
                {{-- Paginación corregida --}}
                <div class="mt-4 d-flex justify-content-center">
                    {{ $servicios->links() }}
                </div>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>