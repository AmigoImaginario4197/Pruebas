<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda - Clínica Veterinaria</title>
    
    <!-- Fuentes e Iconos -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Tu CSS Personalizado -->
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    
    <!-- Vite cargando CSS global, JS global y el JS ESPECÍFICO de la agenda -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/agenda.js'])

    <style>
        /* Ajustes específicos para que FullCalendar se vea bien en tu panel */
        #calendar {
            background: white;
            padding: 20px;
            border-radius: 15px; /* Bordes redondeados como tus tarjetas */
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            min-height: 700px; /* Altura mínima para que no se vea aplastado */
        }
        
        /* Ajuste de la fuente del calendario para coincidir con Figtree */
        .fc {
            font-family: 'Figtree', sans-serif;
        }
        
        /* Personalización de la toolbar del calendario */
        .fc-toolbar-title {
            font-size: 1.5rem !important;
            color: #333;
        }
        
        .fc-button-primary {
            background-color: #4f46e5 !important; /* Color primario similar a Tailwind indigo */
            border-color: #4f46e5 !important;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="panel-container">
        
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title">
                    <h3>Agenda Veterinaria</h3>
                    <p>Visualiza y gestiona las citas programadas.</p>
                </div>
                
                <!-- Leyenda de Colores (Tip Pro) -->
                <div class="d-flex align-items-center gap-2">
                    <span class="badge rounded-pill bg-warning text-dark border border-warning">
                        <i class="bi bi-clock"></i> Pendiente
                    </span>
                    <span class="badge rounded-pill bg-success border border-success">
                        <i class="bi bi-check-circle"></i> Confirmada
                    </span>
                    <span class="badge rounded-pill bg-secondary border border-secondary">
                        <i class="bi bi-archive"></i> Completada
                    </span>
                </div>

                <!-- Botón de Acción -->
                <div class="header-actions ms-3">
                    <!-- Asumiendo que tienes una ruta para crear citas, si no, quítalo -->
                    <a href="{{ route('citas.create') }}" class="btn btn-primary">
                        <i class="bi bi-calendar-plus"></i> Nueva Cita
                    </a>
                </div>
            </header>

            <div class="panel-content">
                {{-- Mensajes de sesión --}}
                @if (session('success'))
                    <div class="alert alert-success mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- 
                    CONTENEDOR DEL CALENDARIO 
                    El atributo data-route es vital para que JS sepa de dónde sacar los datos
                -->
                <div id="calendar" data-route="{{ route('agenda.index') }}"></div>
            </div>
        </main>
    </div>

    {{-- MODAL PARA DETALLES DE LA CITA (Adaptado a Bootstrap 5) --}}
    <!-- Este modal se abrirá automáticamente desde agenda.js al hacer click en un evento -->
    <div class="modal fade" id="citaModal" tabindex="-1" aria-labelledby="citaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="citaModalLabel">Detalles de la Cita</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-light p-3 rounded-circle me-3 text-primary">
                            <i class="bi bi-calendar-event fs-3"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold" id="modalTitulo"></h5>
                            <small class="text-muted" id="modalFecha"></small>
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded mb-3">
                        <p class="mb-1"><small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">Cliente</small></p>
                        <p class="mb-0 fw-medium" id="modalCliente"></p>
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <div class="p-3 border rounded h-100">
                                <p class="mb-1"><small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">Estado</small></p>
                                <span id="modalEstadoBadge" class="badge bg-secondary"></span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 border rounded h-100">
                                <p class="mb-1"><small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">Motivo</small></p>
                                <p class="mb-0 fw-medium text-truncate" id="modalMotivo"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <!-- Aquí podrías poner un botón para ir a editar la cita -->
                    <a href="#" id="btnEditarCita" class="btn btn-primary">Ir a detalle</a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>