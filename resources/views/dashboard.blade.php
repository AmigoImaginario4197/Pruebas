<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Care - Dashboard</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    {{-- CSS para la estructura general del panel (Sidebar, etc.) --}}
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    
    {{-- AÑADIDO: Estilos específicos para el contenido del Dashboard (tarjetas, etc.) --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="panel-container">
        
        {{-- Incluimos el sidebar reutilizable --}}
        @include('layouts.sidebar')

        {{-- El contenido del dashboard usará las clases de dashboard.css --}}
        <main class="panel-main">
            <header class="dashboard-header">
                <div class="user-info">
                    <div class="user-avatar">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="user-details">
                        <h3>{{ Auth::user()->name }}</h3>
                        <p>Bienvenido de nuevo</p>
                    </div>
                </div>
                
                <div class="header-actions">
                    <span class="text-gray-600">{{ now()->translatedFormat('d M Y') }}</span>
                </div>
            </header>

            {{-- Usamos 'dashboard-content' para que los estilos de padding, etc., apliquen --}}
            <div class="dashboard-content">
                <!-- Welcome Card -->
                <div class="welcome-card">
                    <h1>Dashboard</h1>
                    <p>¡Has iniciado sesión!</p>
                </div>

                <!-- Stats Grid -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number">{{ $conteoMascotas }}</div>
                        <div class="stat-label">Mascotas Activas</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ $conteoCitasHoy }}</div>
                        <div class="stat-label">Citas Hoy</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ $conteoTratamientos }}</div>
                        <div class="stat-label">Tratamientos Activos</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ $conteoCitasCompletadas }}</div>
                        <div class="stat-label">Total Citas Completadas</div>
                    </div>
                </div>

                <!-- Appointments Section -->
                <div class="appointments-section">
                    <h2 class="section-title">Próximas Citas</h2>
                    
                    <div class="appointment-cards">
                        @forelse ($proximasCitas as $cita)
                            <div class="appointment-card">
                                <div class="appointment-title">
                                    {{ $cita->servicio->nombre }} para <strong>{{ $cita->mascota->nombre }}</strong>
                                </div>
                                <div class="appointment-time">
                                    <span><i class="bi bi-calendar-event"></i> {{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d/m/Y') }}</span>
                                    <span><i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($cita->fecha_hora)->format('H:i') }}h</span>
                                </div>
                                <div class="appointment-actions">
                                    <a href="{{ route('citas.show', $cita->id) }}" class="btn-accept">Detalles</a>
                                </div>
                            </div>
                        @empty
                            <div class="no-appointments-card" style="text-align: center; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                <p>No tienes citas pendientes.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>