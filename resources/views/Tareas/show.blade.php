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
                    <h3>Detalle de Tarea</h3>
                </div>
                <div class="header-actions">
                    <a href="{{ route('tareas.index') }}" class="btn btn-light">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('tareas.edit', $tarea) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                    @endif
                </div>
            </header>

            <div class="panel-content">
                <div class="card shadow-sm border-0" style="max-width: 800px; margin: 0 auto;">
                    
                    {{-- TRUCO: Usamos --bg-color para evitar errores visuales en el editor --}}
                    <div class="card-header text-white p-3" 
                         style="--bg-color: {{ $tarea->color }}; background-color: var(--bg-color);">
                        <h4 class="mb-0"><i class="bi bi-clipboard"></i> {{ $tarea->titulo }}</h4>
                    </div>
                    
                    <div class="card-body p-4">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <small class="text-muted text-uppercase fw-bold">Inicio</small>
                                <p class="fs-5">{{ $tarea->inicio->format('d/m/Y - H:i') }}</p>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted text-uppercase fw-bold">Fin</small>
                                <p class="fs-5">{{ $tarea->fin->format('d/m/Y - H:i') }}</p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <small class="text-muted text-uppercase fw-bold">Creado por</small>
                            <p>{{ $tarea->user->name ?? 'Sistema' }}</p>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted text-uppercase fw-bold">Observaciones</small>
                            <div class="p-3 bg-light rounded border mt-1">
                                {{ $tarea->observaciones ?? 'Sin observaciones.' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>