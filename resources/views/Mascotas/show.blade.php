<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de {{ $mascota->nombre }} - Pet Care</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/mascota.css') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-light">
    <div class="panel-container">
        
        @include('layouts.sidebar')

        <main class="panel-main">

            {{-- HEADER --}}

            <header class="panel-header">
                <div class="header-title">
                    <h3>Perfil de la Mascota</h3>
                    <p>Información detallada de {{ $mascota->nombre }}.</p>
                </div>
                <div class="header-actions">
                    {{-- Botón de "Volver" con color azul --}}
                   @if(Auth::user()->rol === 'veterinario' || Auth::user()->rol === 'admin')
    {{-- Si es Personal Médico: Vuelve al Buscador de Pacientes --}}
    <a href="{{ route('veterinario.mascotas.index') }}" class="btn btn-primary">
        <i class="bi bi-arrow-left"></i> Volver a Pacientes
    </a>
@else
    {{-- Si es Cliente: Vuelve a "Mis Mascotas" --}}
    <a href="{{ route('mascotas.index') }}" class="btn btn-primary">
        <i class="bi bi-arrow-left"></i> Volver a Mis Mascotas
    </a>
@endif
                </div>
            </header>

            {{-- Contenido principal --}}
            <div class="panel-content">
                <div class="row">
                    {{-- TARJETA DE PERFIL --}}
                    <div class="col-lg-4 mb-4">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="profile-card">
                                <img src="{{ $mascota->foto ? asset('storage/' . $mascota->foto) : asset('images/placeholder-pet.png') }}" 
                                     alt="Foto de {{ $mascota->nombre }}" 
                                     class="profile-avatar">
                                <h2 class="profile-name">{{ $mascota->nombre }}</h2>
                                <p class="profile-species">{{ $mascota->especie }} - {{ $mascota->raza ?? 'Mestizo' }}</p>

                                {{-- Botones de acción --}}
                                <div class="mt-3 d-flex gap-2">
                                    <a href="{{ route('citas.create', ['mascota_id' => $mascota->id]) }}" class="btn btn-primary">
                                        <i class="bi bi-calendar-plus"></i> Agendar Cita
                                    </a>
                                    @if(Auth::user()->isAdmin() || $mascota->user_id === Auth::id())
                                    <a href="{{ route('mascotas.edit', $mascota) }}" class="btn btn-warning">
                                        <i class="bi bi-pencil"></i> Editar
                                    </a>
                                      @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="card shadow-sm border-0">
                            <div class="card-body p-4">
                                <h5 class="mb-4 fw-bold">Datos de Identificación</h5>
                                <div class="info-grid">
                                    <div class="info-box">
                                        <div class="icon"><i class="bi bi-gift"></i></div>
                                        <div class="label">Edad</div>
                                        <div class="value">{{ $mascota->edad !== null ? $mascota->edad . ' años' : 'N/A' }}</div>
                                    </div>
                                    <div class="info-box">
                                        <div class="icon"><i class="bi bi-rulers"></i></div>
                                        <div class="label">Peso</div>
                                        <div class="value">{{ $mascota->peso !== null ? $mascota->peso . ' kg' : 'N/A' }}</div>
                                    </div>
                                    <div class="info-box">
                                        <div class="icon"><i class="bi bi-calendar-heart"></i></div>
                                        <div class="label">Nacimiento</div>
                                        <div class="value">{{ $mascota->fecha_nacimiento ? \Carbon\Carbon::parse($mascota->fecha_nacimiento)->format('d/m/Y') : 'N/A' }}</div>
                                    </div>
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