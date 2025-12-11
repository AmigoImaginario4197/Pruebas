<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles Historial - Pet Care</title>
    
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
    <link rel="stylesheet" href="{{ asset('css/historial.css') }}"> <!-- NUEVO CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="panel-container">     
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title">
                    <h3>Registro Médico #{{ $historial->id }}</h3>
                    <p>Detalles del evento clínico.</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('historial.index') }}" class="btn btn-secondary text-white">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </header>

            <div class="panel-content">
                <div class="profile-card">
                    
                    <div class="historial-layout">

                        <!-- COLUMNA IZQUIERDA -->
                        <div class="col-left">
                            
                            <!-- Cabecera Mascota -->
                            <div class="section-header">
                                <h5 class="pet-name">
                                    <i class="bi bi-heart-pulse text-danger"></i> {{ $historial->mascota->nombre }}
                                </h5>
                                <p class="pet-details">
                                    Paciente ({{ $historial->mascota->especie }})
                                </p>
                            </div>

                            <!-- Fecha -->
                            <div class="info-box">
                                <span class="info-label">Fecha del Suceso</span>
                                <div class="info-value">
                                    <i class="bi bi-calendar-event" style="color: #4f46e5;"></i>
                                    {{ \Carbon\Carbon::parse($historial->fecha)->format('d/m/Y') }}
                                </div>
                            </div>

                            <!-- Diagnóstico -->
                            <div class="info-box">
                                <span class="info-label">Diagnóstico / Descripción</span>
                                <div class="diagnosis-box">
                                    {{ $historial->descripcion }}
                                </div>
                            </div>
                        </div>

                        <!-- COLUMNA DERECHA -->
                        <div class="col-right">
                            
                            <h5 class="section-title" style="margin-bottom: 20px; color: #4f46e5; font-size: 1.1rem; font-weight: 600;">Tratamiento Vinculado</h5>

                            @if($historial->tratamiento)
                                <!-- Tarjeta de Tratamiento -->
                                <div class="treatment-card">
                                    <div class="treatment-title">
                                        {{ $historial->tratamiento->tipo ?? 'Tratamiento' }}
                                    </div>
                                    <p class="treatment-desc">
                                        {{ \Str::limit($historial->tratamiento->descripcion, 50) }}
                                    </p>
                                    
                                    <a href="{{ route('tratamientos.show', $historial->tratamiento_id) }}" class="btn btn-sm btn-outline-success w-100">
                                        Ver Detalles Completos <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            @else
                                <!-- Tarjeta de Chequeo -->
                                <div class="checkup-card">
                                    <i class="bi bi-journal-check checkup-icon"></i>
                                    <p style="color: #6b7280; font-weight: 600; margin-top: 10px;">Solo Chequeo</p>
                                    <p style="font-size: 0.85rem; color: #9ca3af;">No se asignó medicación.</p>
                                </div>
                            @endif

                            <!-- Acciones (Admin/Vet) -->
                            @if(Auth::user()->rol !== 'cliente')
                                <div class="mt-5 pt-4 border-top">
                                    <span class="info-label mb-2">Acciones</span>
                                    <a href="{{ route('historial.edit', $historial->id) }}" class="btn-save" style="background-color: #f59e0b; text-align: center; text-decoration: none; display: block;">
                                        <i class="bi bi-pencil-square"></i> Editar Registro
                                    </a>
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