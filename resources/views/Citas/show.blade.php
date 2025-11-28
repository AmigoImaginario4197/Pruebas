<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Cita - Pet Care</title>
    
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
                    <h3>Detalles de la Cita #{{ $cita->id }}</h3>
                    <p>Información completa de la consulta programada.</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('citas.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </header>

            <div class="panel-content">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            {{-- Columna Izquierda: Datos Médicos y de Tiempo --}}
                            <div class="col-md-8">
                                <div class="d-flex justify-content-between align-items-start mb-4">
                                    <div>
                                        <h5 class="card-title mb-1">Paciente: {{ $cita->mascota->nombre }}</h5>
                                        <p class="text-muted">{{ $cita->mascota->especie }} - {{ $cita->mascota->raza }}</p>
                                    </div>
                                    @php
                                        $badgeClass = match($cita->estado) {
                                            'confirmada' => 'bg-success',
                                            'pendiente' => 'bg-warning text-dark',
                                            'cancelada' => 'bg-danger',
                                            'completada' => 'bg-secondary',
                                            default => 'bg-primary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }} fs-6">{{ ucfirst($cita->estado) }}</span>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="text-muted small fw-bold text-uppercase">Fecha y Hora</label>
                                        <p class="fs-5">
                                            <i class="bi bi-calendar-event me-2"></i>
                                            {{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d/m/Y - H:i A') }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted small fw-bold text-uppercase">Veterinario</label>
                                        <p class="fs-5">
                                            <i class="bi bi-person-badge me-2"></i>
                                            Dr. {{ $cita->veterinario->name }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="text-muted small fw-bold text-uppercase">Motivo de la Consulta</label>
                                    <p class="p-3 bg-light rounded">{{ $cita->motivo }}</p>
                                </div>
                            </div>

                            {{-- Columna Derecha: Datos del Cliente y Servicio --}}
                            <div class="col-md-4 border-start">
                                <div class="mb-4">
                                    <label class="text-muted small fw-bold text-uppercase">Dueño / Cliente</label>
                                    <p class="fs-6 fw-bold">{{ $cita->cliente->name }}</p>
                                    <p class="text-muted small">{{ $cita->cliente->email }}</p>
                                </div>

                                <div class="mb-4">
                                    <label class="text-muted small fw-bold text-uppercase">Servicio Contratado</label>
                                    @if($cita->servicio)
                                        <p class="fs-5 text-primary fw-bold">{{ $cita->servicio->nombre }}</p>
                                        <p class="fs-4 fw-bold text-success">${{ number_format($cita->servicio->precio, 2) }}</p>
                                    @else
                                        <p class="text-muted">Servicio General / No especificado</p>
                                    @endif
                                </div>

                                {{-- Botones de Acción --}}
                                <div class="d-grid gap-2 mt-5">
                                    @if($cita->estado != 'cancelada' && $cita->estado != 'completada')
                                        <a href="{{ route('citas.edit', $cita) }}" class="btn btn-warning">
                                            <i class="bi bi-pencil"></i> Reprogramar / Editar
                                        </a>
                                        
                                        <form action="{{ route('citas.destroy', $cita) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas cancelar esta cita?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger w-100">
                                                <i class="bi bi-x-lg"></i> Cancelar Cita
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>