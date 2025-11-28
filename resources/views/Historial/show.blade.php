<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Historial - Pet Care</title>
    
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
                    <h3>Detalle del Registro Médico</h3>
                    <p>Información completa del evento.</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('historial.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </header>

            <div class="panel-content">
                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $historial->tipo }}</h5>
                        <span class="badge bg-secondary">{{ \Carbon\Carbon::parse($historial->fecha)->format('d/m/Y') }}</span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="text-muted text-uppercase small fw-bold">Paciente</label>
                                <p class="fs-5 fw-bold">{{ $historial->mascota->nombre }}</p>
                                <p class="text-muted">{{ $historial->mascota->especie }} - {{ $historial->mascota->raza }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted text-uppercase small fw-bold">Dueño</label>
                                <p class="fs-5">{{ $historial->mascota->usuario->name }}</p>
                            </div>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="text-muted text-uppercase small fw-bold mb-2">Descripción / Diagnóstico</label>
                            <div class="p-3 bg-light rounded border">
                                {{ $historial->descripcion }}
                            </div>
                        </div>
                    </div>
                    
                    @if(Auth::user()->rol !== 'cliente')
                        <div class="card-footer text-end">
                            <a href="{{ route('historial.edit', $historial) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Editar Registro
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</body>
</html>