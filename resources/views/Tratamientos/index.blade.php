<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tratamientos - Pet Care</title>
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
                    <h3>
                        {{ Auth::user()->isCliente() ? 'Mis Tratamientos' : 'Gestión de Tratamientos' }}
                    </h3>
                    <p>Seguimiento médico y prescripciones.</p>
                </div>
                {{-- Solo Admin y Vet pueden crear --}}
                @if(!Auth::user()->isCliente())
                    <div class="header-actions">
                        <a href="{{ route('tratamientos.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Nuevo Tratamiento
                        </a>
                    </div>
                @endif
            </header>

            <div class="panel-content">
                @if (session('success'))
                    <div class="alert alert-success mb-4">{{ session('success') }}</div>
                @endif

                <div class="item-list">
                    @forelse ($tratamientos as $tratamiento)
                        <div class="item-card">
                            <div class="item-photo-placeholder" style="background-color: #f0fdf4; color: #16a34a;">
                                <i class="bi bi-prescription2"></i>
                            </div>
                            
                            <div class="item-details">
                                <h4 class="mb-1">{{ $tratamiento->diagnostico }}</h4>
                                <p class="mb-1">
                                    <strong>Paciente:</strong> {{ $tratamiento->mascota->nombre }} 
                                    <small class="text-muted">({{ $tratamiento->mascota->especie }})</small>
                                </p>
                                <p class="text-muted small mb-1">
                                    <i class="bi bi-calendar3"></i> Inicio: {{ \Carbon\Carbon::parse($tratamiento->fecha_inicio)->format('d/m/Y') }}
                                </p>
                                
                                {{-- Estado calculado --}}
                                @php
                                    $isActive = !$tratamiento->fecha_fin || \Carbon\Carbon::parse($tratamiento->fecha_fin)->isFuture();
                                @endphp
                                <span class="badge {{ $isActive ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $isActive ? 'En Curso' : 'Finalizado' }}
                                </span>
                            </div>

                            <div class="item-actions justify-content-end">
                                {{-- Ver (Todos) --}}
                                <a href="{{ route('tratamientos.show', $tratamiento) }}" class="btn btn-info btn-sm" title="Ver Detalles">
                                    <i class="bi bi-eye"></i>
                                </a>

                                {{-- Editar/Borrar (Solo Personal) --}}
                                @if(!Auth::user()->isCliente())
                                    <a href="{{ route('tratamientos.edit', $tratamiento) }}" class="btn btn-warning btn-sm" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('tratamientos.destroy', $tratamiento) }}" method="POST" onsubmit="return confirm('¿Eliminar este tratamiento?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="empty-state-card">
                            <i class="bi bi-clipboard2-pulse"></i>
                            <p>No hay tratamientos registrados.</p>
                        </div>
                    @endforelse
                </div>
                
                <div class="mt-4 d-flex justify-content-center">{{ $tratamientos->links() }}</div>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>