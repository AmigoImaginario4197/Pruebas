<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disponibilidad - Pet Care</title>
    
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
                    {{-- Título Dinámico según el Rol --}}
                    <h3>{{ Auth::user()->rol === 'admin' ? 'Gestión de Disponibilidad' : 'Mi Disponibilidad' }}</h3>
                    <p>
                        {{ Auth::user()->rol === 'admin' 
                            ? 'Añade o elimina los bloques de tiempo de los veterinarios.' 
                            : 'Gestiona tus horarios disponibles para citas.' 
                        }}
                    </p>
                </div>
                <div class="header-actions">
                    {{-- Botón Añadir Bloque solo para admin --}}
                    @if(Auth::user()->rol === 'admin')
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
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Selector de Veterinario (SOLO ADMIN) --}}
                @if(Auth::user()->rol === 'admin')
                    <div class="card p-3 mb-4">
                        <form action="{{ route('disponibilidad.index') }}" method="GET">
                            <label for="veterinario_id" class="form-label fw-bold">Selecciona un veterinario para ver su agenda:</label>
                            <div class="input-group">
                                <select name="veterinario_id" id="veterinario_id" class="form-select" onchange="this.form.submit()">
                                    <option value="">-- Elige un veterinario --</option>
                                    @foreach ($veterinarios as $veterinario)
                                        <option value="{{ $veterinario->id }}" @selected($selectedVetId == $veterinario->id)>
                                            {{ $veterinario->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                @endif

                {{-- Lista de Disponibilidad (Visible si es Vet O si es Admin y seleccionó a alguien) --}}
                @if (Auth::user()->rol === 'veterinario' || (Auth::user()->rol === 'admin' && $selectedVetId))
                    <div class="item-list">
                        @forelse ($disponibilidades as $disponibilidad)
                            <div class="item-card">
                                <div class="item-photo-placeholder">
                                    <i class="bi bi-calendar-check"></i>
                                </div>
                                
                                <div class="item-details">
                                    {{-- Agregado locale('es') para mostrar días en español --}}
                                    <h4>{{ \Carbon\Carbon::parse($disponibilidad->fecha)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</h4>
                                    <p>
                                        De <span class="badge bg-success">{{ \Carbon\Carbon::parse($disponibilidad->hora_inicio)->format('H:i') }}</span>
                                        a <span class="badge bg-danger">{{ \Carbon\Carbon::parse($disponibilidad->hora_fin)->format('H:i') }}</span>
                                    </p>
                                </div>
                                
                                {{-- Botones solo para admin --}}
                                @if(Auth::user()->rol === 'admin')
                                <div class="item-actions justify-content-end">
                                    {{-- Botón Editar --}}
                                    <a href="{{ route('disponibilidad.edit', $disponibilidad) }}" class="btn btn-warning btn-sm" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    {{-- Botón Borrar --}}
                                    <form action="{{ route('disponibilidad.destroy', $disponibilidad) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este bloque horario?');">
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
                                <p>No hay horarios de disponibilidad configurados.</p>
                                @if(Auth::user()->rol === 'admin')
                                <p>¡Haz clic en "Añadir Bloque" para empezar!</p>
                                @endif
                            </div>
                        @endforelse
                    </div>
                @endif
            </div>
        </main>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>