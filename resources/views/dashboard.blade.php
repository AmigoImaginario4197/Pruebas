<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Care - Dashboard</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Styles -->
    <style>
        /* Tus estilos existentes de app.css pueden ir aquí */
        
        /* Agregamos el nuevo CSS del dashboard */
        {{ file_get_contents(public_path('css/dashboard.css')) }}
    </style>
    
    <!-- Scripts -->
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
                    <li>
                        <a href="#">
                            <i class="bi bi-person"></i>
                            Sergio Rojas
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <i class="bi bi-heart"></i>
                            Mascotas
                        </a>
                    </li>
                    <li>
                        <a href="#">
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
                        <a href="#">
                            <i class="bi bi-prescription2"></i>
                            Tratamiento
                        </a>
                    </li>
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
                    <span class="text-gray-600">{{ now()->format('d M Y') }}</span>
                </div>
            </header>

            <main class="dashboard-content">
                <!-- Welcome Card -->
                <div class="welcome-card">
                    <h1>Dashboard</h1>
                    <p>You're logged in!</p>
                </div>

                <!-- Stats Grid -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number">12</div>
                        <div class="stat-label">Mascotas Activas</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">5</div>
                        <div class="stat-label">Citas Hoy</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">3</div>
                        <div class="stat-label">Tratamientos</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">28</div>
                        <div class="stat-label">Total Citas</div>
                    </div>
                </div>

                <!-- Appointments Section -->
                <div class="appointments-section">
                    <h2 class="section-title">Próximas Citas</h2>
                    
                    <div class="appointment-cards">
                        <!-- Appointment Card 1 -->
                        <div class="appointment-card">
                            <div class="appointment-title">Cita 1</div>
                            <div class="appointment-time">14:00-15:00</div>
                            <div class="appointment-actions">
                                <button class="btn-accept">Aceptar</button>
                                <button class="btn-deny">Denegar</button>
                            </div>
                        </div>
                        
                        <!-- Puedes agregar más citas aquí -->
                        <div class="appointment-card">
                            <div class="appointment-title">Cita 2</div>
                            <div class="appointment-time">16:00-17:00</div>
                            <div class="appointment-actions">
                                <button class="btn-accept">Aceptar</button>
                                <button class="btn-deny">Denegar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>