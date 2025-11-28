<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Citas - Pet Care</title>
    
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
                    <h3>
                        @if(Auth::user()->isCliente()) Mis Citas
                        @elseif(Auth::user()->isVeterinario()) Mi Agenda
                        @else Gestión de Citas
                        @endif
                    </h3>
                    <p>Consulta y gestiona las visitas programadas.</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('citas.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Agendar Cita
                    </a>
                </div>
            </header>

            <div class="panel-content">
                @if (session('success'))
                    <div class="alert alert-success mb-4">{{ session('success') }}</div>
                @endif

                <div class="item-list">
                    @forelse ($citas as $cita)
                        <div class="item-card">
                            {{-- Fecha grande como icono --}}
                            <div class="item-photo-placeholder" style="flex-direction: column; gap: 2px; background-color: #eef2ff; color: #4f46e5;">
                                <span style="font-size: 0.75rem; text-transform: uppercase; font-weight: bold;">{{ \Carbon\Carbon::parse($cita->fecha_hora)->translatedFormat('M') }}</span>
                                <span style="font-size: 1.5rem; font-weight: 800; line-height: 1;">{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d') }}</span>
                                <span style="font-size: 0.7rem;">{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('H:i') }}</span>
                            </div>
                            
                            <div class="item-details">
                                <h4 class="mb-1">{{ $cita->mascota->nombre }} <small class="text-muted fs-6">({{ $cita->mascota->especie }})</small></h4>
                                
                                {{-- Mostrar info extra según quien lo vea --}}
                                @if(Auth::user()->isCliente())
                                    <p class="text-muted mb-1"><i class="bi bi-person-badge"></i> Dr. {{ $cita->veterinario->name }}</p>
                                @else
                                    <p class="text-muted mb-1"><i class="bi bi-person"></i> Dueño: {{ $cita->cliente->name }}</p>
                                @endif

                                {{-- Badge de Estado --}}
                                @php
                                    $badgeClass = match($cita->estado) {
                                        'confirmada' => 'bg-success',
                                        'pendiente' => 'bg-warning text-dark',
                                        'cancelada' => 'bg-danger',
                                        'completada' => 'bg-secondary',
                                        default => 'bg-primary',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($cita->estado) }}</span>
                            </div>

                            <div class="item-actions justify-content-end">
                                {{-- Botón Editar / Reprogramar --}}
                                {{-- El cliente solo puede editar si es pendiente/confirmada y fecha futura --}}
                                @if(Auth::user()->isAdmin() || Auth::user()->isVeterinario() || ($cita->estado != 'cancelada' && $cita->estado != 'completada' && $cita->fecha_hora > now()))
                                    <a href="{{ route('citas.edit', $cita) }}" class="btn btn-warning btn-sm" title="Editar / Reprogramar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endif

                                {{-- Botón Cancelar --}}
                                @if($cita->estado != 'cancelada')
                                    <form action="{{ route('citas.destroy', $cita) }}" method="POST" onsubmit="return confirm('¿Estás seguro de cancelar esta cita?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Cancelar Cita">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="empty-state-card">
                            <i class="bi bi-calendar-x"></i>
                            <p>No hay citas para mostrar.</p>
                            <p>¡Agenda una nueva visita!</p>
                        </div>
                    @endforelse
                </div>
                
                <div class="mt-4 d-flex justify-content-center">
                    {{ $citas->links() }}
                </div>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>