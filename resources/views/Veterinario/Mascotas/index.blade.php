<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pacientes - Pet Care</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/patient-cards.css') }}">
    {{-- Cargamos el CSS específico de tarjetas --}}
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/patient-cards.css'])
</head>
<body class="font-sans antialiased">
    <div class="panel-container">
        
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title">
                    <h3>Listado de Pacientes</h3>
                    <p>Gestión clínica y búsqueda rápida.</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('mascotas.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Nuevo Paciente
                    </a>
                </div>
            </header>

            <div class="panel-content">
                
                {{-- BARRA DE BÚSQUEDA --}}
                <div class="card border-0 shadow-sm p-3 mb-4">
                    <form action="{{ route('veterinario.mascotas.index') }}" method="GET">
                        <div class="row g-2 align-items-center">
                            {{-- Campo de texto (Ocupa la mayoría del espacio) --}}
                            <div class="col-md-8 col-lg-9">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control border-start-0 ps-0 shadow-none" 
                                           placeholder="Buscar por nombre de mascota, dueño o DNI..." 
                                           value="{{ request('search') }}">
                                </div>
                            </div>

                            {{-- Botones (Alineados a la derecha, tamaño normal) --}}
                            <div class="col-md-4 col-lg-3 d-flex justify-content-md-end gap-2">
                                <button type="submit" class="btn btn-primary px-4 fw-medium">
                                    Buscar
                                </button>
                                
                                @if(request('search'))
                                    <a href="{{ route('veterinario.mascotas.index') }}" class="btn btn-outline-danger px-3" title="Limpiar búsqueda">
                                        <i class="bi bi-x-lg"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>

                {{-- GRID DE TARJETAS --}}
                <div class="row g-3">
                    @forelse($mascotas as $mascota)
                        <div class="col-lg-6 col-xxl-4">
                            <div class="patient-card">
                                {{-- FOTO (Izquierda) --}}
                                <div class="patient-img-wrapper">
                                    @if($mascota->foto)
                                        <img src="{{ asset('storage/' . $mascota->foto) }}" alt="{{ $mascota->nombre }}" class="patient-img">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center h-100 text-muted opacity-25">
                                            <i class="bi bi-camera-fill fs-1"></i>
                                        </div>
                                    @endif
                                    <div class="patient-species-badge">
                                        {{ $mascota->especie }}
                                    </div>
                                </div>

                                {{-- DATOS (Derecha) --}}
                                <div class="patient-body">
                                    <div class="patient-header">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="patient-name" title="{{ $mascota->nombre }}">{{ $mascota->nombre }}</h5>
                                            @if($mascota->sexo)
                                                <i class="bi {{ $mascota->sexo == 'Macho' ? 'bi-gender-male text-primary' : 'bi-gender-female text-danger' }}"></i>
                                            @endif
                                        </div>
                                        <div class="patient-meta">
                                            <span>{{ $mascota->raza }}</span>
                                            @if($mascota->edad) <span>• {{ $mascota->edad }} años</span> @endif
                                        </div>
                                    </div>

                                    <div class="patient-owner" title="Propietario">
                                        <i class="bi bi-person-fill me-1"></i> 
                                        {{ $mascota->usuario->name ?? 'Sin dueño' }}
                                    </div>

                                    {{-- ACCIONES DE LA TARJETA --}}
                                    <div class="patient-actions">
                                        {{-- Botón Ver Ficha (Para todos) --}}
                                        <a href="{{ route('mascotas.show', $mascota->id) }}" class="btn btn-primary btn-compact flex-grow-1" title="Ver historial clínico">
                                            <i class="bi bi-file-medical"></i> Ficha
                                        </a>
                                        
                                        {{-- Botón Editar y Borrar (Para  Admin) --}}
                                        @if(Auth::user()->isAdmin())
                                        <a href="{{ route('mascotas.edit', $mascota->id) }}" class="btn btn-warning btn-compact" title="Editar datos">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                            <form action="{{ route('mascotas.destroy', $mascota->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro? Se borrará todo el historial médico de esta mascota.');" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-compact" title="Eliminar paciente">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-search fs-1 mb-3 d-block"></i>
                                <p class="h5">No se encontraron pacientes</p>
                                <p class="small">Intenta con otro nombre o registra uno nuevo.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
                
                <div class="mt-4 d-flex justify-content-center">
                    {{ $mascotas->links() }}
                </div>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>