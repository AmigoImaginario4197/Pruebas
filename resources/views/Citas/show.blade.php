<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de Cita - Pet Care</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
    <link rel="stylesheet" href="{{ asset('css/citas.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
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
                    <a href="{{ route('citas.index') }}" class="btn btn-secondary text-white">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </header>

            <div class="panel-content">
                <div class="profile-card">
                    
                    <div class="cita-layout">

                        <!-- COLUMNA IZQUIERDA: DATOS MEDICOS -->
                        <div class="cita-col-left">
                            
                            <!-- Cabecera -->
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 25px;">
                                <div>
                                    <h5 style="font-size: 1.5rem; color: #1f2937; margin: 0;">{{ $cita->mascota->nombre }}</h5>
                                    <p style="color: #6b7280; margin: 5px 0 0 0;">
                                        {{ $cita->mascota->especie }} • {{ $cita->mascota->raza }}
                                    </p>
                                </div>
                                
                                <!-- Badge de Estado -->
                                <span class="status-badge status-{{ $cita->estado }}">
                                    {{ ucfirst($cita->estado) }}
                                </span>
                            </div>

                            <!-- Información Grid Interno -->
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
                                
                                <!-- Caja Fecha -->
                                <div class="info-box">
                                    <span class="info-label">Fecha y Hora</span>
                                    <div class="info-value">
                                        <i class="bi bi-calendar-event" style="color: #4f46e5;"></i>
                                        {{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d/m/Y') }}
                                    </div>
                                    <div class="info-subvalue">
                                        {{ \Carbon\Carbon::parse($cita->fecha_hora)->format('H:i A') }}
                                    </div>
                                </div>

                                <!-- Caja Veterinario -->
                                <div class="info-box">
                                    <span class="info-label">Veterinario</span>
                                    <div class="info-value">
                                        <i class="bi bi-person-badge" style="color: #4f46e5;"></i>
                                        Dr. {{ $cita->veterinario->name }}
                                    </div>
                                    <div class="info-subvalue">Especialista</div>
                                </div>
                            </div>

                            <!-- Motivo -->
                            <div class="form-group">
                                <span class="info-label">Motivo de la Consulta</span>
                                <div style="padding: 15px; background-color: #f3f4f6; border-radius: 8px; color: #374151; line-height: 1.6;">
                                    {{ $cita->motivo }}
                                </div>
                            </div>
                        </div>

                        <!-- COLUMNA DERECHA: CLIENTE Y ACCIONES -->
                        <div class="cita-col-right">
                            
                            <div style="margin-bottom: 30px;">
                                <span class="info-label">Propietario</span>
                                <div style="font-weight: 600; font-size: 1rem;">{{ $cita->cliente->name }}</div>
                                <div style="color: #6b7280; font-size: 0.9rem;">{{ $cita->cliente->email }}</div>
                            </div>

                            <!-- Ticket Precio -->
                            <div class="price-ticket">
                                <span class="label">Servicio Contratado</span>
                                @if($cita->servicio)
                                    <div style="font-size: 1.1rem; font-weight: 600; color: #15803d; margin-bottom: 5px;">
                                        {{ $cita->servicio->nombre }}
                                    </div>
                                    <div class="value">${{ number_format($cita->servicio->precio, 2) }}</div>
                                @else
                                    <p class="text-muted">Servicio General</p>
                                @endif
                            </div>

                            <!-- Botones -->
                            @if($cita->estado != 'cancelada' && $cita->estado != 'completada')
                                <div class="btn-action-group">
                                    <a href="{{ route('citas.edit', $cita) }}" class="btn-save" style="background-color: #f59e0b; text-align: center; text-decoration: none; display: block;">
                                        <i class="bi bi-pencil-square"></i> Editar Cita
                                    </a>
                                    
                                    <!-- LOGICA NUEVA DE CANCELAR (PATCH) -->
                                    <form action="{{ route('citas.cancelar', $cita) }}" method="POST" onsubmit="return confirm('¿Deseas cancelar esta cita? Quedará en el historial.');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn-cancel-full">
                                            <i class="bi bi-x-circle"></i> Cancelar Cita
                                        </button>
                                    </form>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>