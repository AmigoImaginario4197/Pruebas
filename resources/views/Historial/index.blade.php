<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial Médico - Pet Care</title>
    
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
                    <h3>Historial Médico</h3>
                    <p>Registro clínico y eventos de salud.</p>
                </div>
                {{-- Solo Admin/Vet pueden añadir registros --}}
                @if(Auth::user()->rol !== 'cliente')
                    <div class="header-actions">
                        <a href="{{ route('historial.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Nuevo Registro
                        </a>
                    </div>
                @endif
            </header>

            <div class="panel-content">
                @if (session('success'))
                    <div class="alert alert-success mb-4">{{ session('success') }}</div>
                @endif

                <div class="item-list">
                    @forelse ($historiales as $historial)
                        <div class="item-card">
                            {{-- Icono según el tipo de evento --}}
                            <div class="item-photo-placeholder">
                                @if(Str::contains(strtolower($historial->tipo), 'vacuna'))
                                    <i class="bi bi-eyedropper"></i>
                                @elseif(Str::contains(strtolower($historial->tipo), 'cirugía') || Str::contains(strtolower($historial->tipo), 'operación'))
                                    <i class="bi bi-bandaid"></i>
                                @else
                                    <i class="bi bi-clipboard2-pulse"></i>
                                @endif
                            </div>
                            
                            <div class="item-details">
                                <h4>{{ $historial->mascota->nombre }} <small class="text-muted fs-6">({{ $historial->mascota->especie }})</small></h4>
                                <p class="mb-1 fw-bold text-primary">{{ $historial->tipo }}</p>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-calendar3"></i> {{ \Carbon\Carbon::parse($historial->fecha)->format('d/m/Y') }}
                                </p>
                                <p class="text-muted small mt-1 text-truncate" style="max-width: 400px;">{{ $historial->descripcion }}</p>
                            </div>

                            <div class="item-actions justify-content-end">
                                {{-- Botón Ver (Todos) --}}
                                <a href="{{ route('historial.show', $historial) }}" class="btn btn-secondary btn-sm" title="Ver Detalles">
                                    <i class="bi bi-eye"></i>
                                </a>

                                {{-- Acciones de gestión (Solo Admin/Vet) --}}
                                @if(Auth::user()->rol !== 'cliente')
                                    <a href="{{ route('historial.edit', $historial) }}" class="btn btn-warning btn-sm" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('historial.destroy', $historial) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este registro médico?');">
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
                            <i class="bi bi-clipboard-x"></i>
                            <p>No hay registros en el historial médico.</p>
                        </div>
                    @endforelse
                </div>
                
                <div class="mt-4 d-flex justify-content-center">{{ $historiales->links() }}</div>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>