<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Tarea - Pet Care</title>
    
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
                    <h3>Detalle de la Tarea</h3>
                    <p>Información completa de la actividad interna.</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('tareas.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left-circle"></i> Volver al Listado
                    </a>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('tareas.edit', $tarea) }}" class="btn btn-primary">
                            <i class="bi bi-pencil-square"></i> Editar Tarea
                        </a>
                    @endif
                </div>
            </header>

            <div class="panel-content">
                <div class="card shadow-sm border-0" style="max-width: 800px; margin: 0 auto;">
                    
                    <div class="card-header text-white p-3" 
     style="--header-bg: {{ $tarea->color }}; background-color: var(--header-bg);">
                        <h4 class="mb-0"><i class="bi bi-clipboard-check-fill"></i> {{ $tarea->titulo }}</h4>
                    </div>
                    
                    <div class="card-body p-4">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p class="text-muted mb-1"><i class="bi bi-calendar-event"></i> <strong>Inicio</strong></p>
                                <p class="fs-5">{{ $tarea->inicio->format('d/m/Y - H:i') }} h</p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1"><i class="bi bi-calendar-x"></i> <strong>Fin</strong></p>
                                <p class="fs-5">{{ $tarea->fin->format('d/m/Y - H:i') }} h</p>
                            </div>
                        </div>

                        {{-- ============================================= --}}
                        {{--    NUEVA SECCIÓN: DETALLES DE ASIGNACIÓN      --}}
                        {{-- ============================================= --}}
                        <div class="mb-4 p-3 bg-light rounded border">
                            <h6 class="mb-3 fw-bold">Detalles de Asignación</h6>
                            @if($tarea->asignadoA)
                                <p class="mb-0 fs-5">
                                    <span class="badge bg-primary fs-6">
                                        <i class="bi bi-person-check-fill me-1"></i> Asignada a: {{ $tarea->asignadoA->name }}
                                    </span>
                                </p>
                            @elseif($tarea->especialidad_asignada)
                                <p class="mb-0 fs-5">
                                    <span class="badge bg-info text-dark fs-6">
                                        <i class="bi bi-briefcase-fill me-1"></i> Especialidad: {{ $tarea->especialidad_asignada }}
                                    </span>
                                </p>
                            @else
                                <p class="mb-0 fs-5">
                                    <span class="badge bg-secondary fs-6">
                                        <i class="bi bi-people-fill me-1"></i> Tarea General
                                    </span>
                                </p>
                            @endif
                        </div>

                        <div class="mb-4">
                            <p class="text-muted mb-1"><i class="bi bi-person-fill"></i> <strong>Creado por</strong></p>
                            {{-- CORRECCIÓN: Usamos ->creador en lugar de ->user --}}
                            <p>{{ $tarea->creador->name ?? 'Sistema' }} el {{ $tarea->created_at->format('d/m/Y') }}</p>
                        </div>

                        <div class="mb-3">
                            <p class="text-muted mb-1"><i class="bi bi-card-text"></i> <strong>Observaciones</strong></p>
                            <div class="p-3 bg-white rounded border mt-1">
                                {{-- Usamos {!! nl2br(e($tarea->observaciones)) !!} para respetar los saltos de línea --}}
                                <p class="mb-0">{!! nl2br(e($tarea->observaciones ?? 'Sin observaciones.')) !!}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>