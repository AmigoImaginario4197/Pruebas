<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Care - Dashboard</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    @if(file_exists(public_path('css/dashboard.css')))
        <style>{{ file_get_contents(public_path('css/dashboard.css')) }}</style>
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="dashboard-sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('images/logo.png') }}" alt="Pet Care Logo" class="sidebar-logo">
            </div>
            
             <nav class="sidebar-menu">
                <ul>
                    {{-- 1. ENLACES COMUNES PARA TODOS LOS ROLES --}}
                    <li>
                        <a href="{{route('profile.edit')}}">
                            <i class="bi bi-person"></i>
                            {{ Auth::user()->name }}
                        </a>
                    </li>

                    {{-- 2. ENLACE CONDICIONAL PARA "MASCOTAS" --}}
                    <li>
                        @if(Auth::user()->isVeterinario())
                            {{-- Si es veterinario, apunta a una ruta diferente --}}
                            <a href="{{route('veterinario.mascotas.index')}}"> 
                                <i class="bi bi-heart-pulse"></i> {{-- Icono diferente para distinguir --}}
                                Gestionar Mascotas
                            </a>
                        @else
                            {{-- Para clientes y administradores, apunta a la ruta normal --}}
                            <a href="{{route('mascotas.index')}}">
                                <i class="bi bi-heart"></i>
                                Mis Mascotas
                            </a>
                        @endif
                    </li>

                    {{-- 3. MÁS ENLACES COMUNES --}}
                    <li>
                        <a href="{{route('citas.index')}}">
                            <i class="bi bi-calendar-check"></i>
                            Citas
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-clock-history"></i>
                            Historial
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-archive"></i>
                            Archivo
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('tratamientos.index')}}">
                            <i class="bi bi-prescription2"></i>
                            Tratamiento
                        </a>
                    </li>

                    {{-- 4. ENLACES SOLO PARA ADMINISTRADORES --}}
                    @if(Auth::user()->isAdmin())
                        <li>
                            <a href="#">
                                <i class="bi bi-gear"></i>
                                Configuración
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="bi bi-list-check"></i>
                                Logs
                            </a>
                        </li>
                    @endif

                    {{-- 5. ENLACE UNIVERSAL DE "SALIR" --}}
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); this.closest('form').submit();">
                                <i class="bi bi-box-arrow-right"></i>
                                Salir
                            </a>
                        </form>
                    </li>
                </ul>
            </nav>
           
        </div>

        <!-- Main Content -->
        <div class="dashboard-main">
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

            <main class="dashboard-content">
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
                                    <span><i class="bi bi-calendar-event"></i> {{ $cita->fecha_hora->format('d/m/Y') }}</span>
                                    <span><i class="bi bi-clock"></i> {{ $cita->fecha_hora->format('H:i') }}h</span>
                                </div>
                                <div class="appointment-actions">
                                    <a href="{{-- route('citas.show', $cita->id) --}}" class="btn-accept">Detalles</a>
                                </div>
                            </div>
                        @empty
                            <div class="no-appointments-card" style="text-align: center; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                <p>No tienes citas pendientes.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>