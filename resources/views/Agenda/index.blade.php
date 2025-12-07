<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda - Pet Care</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/agenda.js'])

    <style>
        #calendar {
            background: white;
            padding: 15px;
            border-radius: 10px;
            height: calc(100vh - 180px); 
            min-height: 600px;
        }
        .fc-toolbar-title { font-size: 1.2rem !important; color: #333; }
        .fc-button { font-size: 0.85rem !important; }
        .fc-event { cursor: pointer; border: none; }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="panel-container">
        
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title">
                    <h3>Agenda Veterinaria</h3>
                </div>
                
                @if(!Auth::user()->isCliente())
                    <div class="header-actions d-flex gap-2">
                        {{-- Botón Tarea: ID del target actualizado a createTaskModal --}}
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#createTaskModal">
                            <i class="bi bi-clipboard-plus"></i> Tarea Interna
                        </button>
                        <a href="{{ route('citas.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-calendar-plus"></i> Nueva Cita
                        </a>
                    </div>
                @endif
            </header>

            <div class="panel-content" style="overflow-y: hidden;"> 
                @if (session('success'))
                    <div class="alert alert-success py-2 mb-2">{{ session('success') }}</div>
                @endif

                {{-- Leyenda con tu diseño original y colores correctos --}}
                <div class="d-flex gap-3 mb-2 align-items-center small text-muted">
                    <span><i class="bi bi-circle-fill text-warning"></i> Pendiente</span>
                    <span><i class="bi bi-circle-fill text-success"></i> Confirmada</span>
                    <span><i class="bi bi-circle-fill text-secondary"></i> Tarea Interna</span>
                </div>

                {{-- CALENDARIO: Apuntando a la ruta 'agenda.data' --}}
                <div id="calendar" data-route="{{ route('agenda.data') }}"></div>

            </div>
        </main>
    </div>

    {{-- =================================================================== --}}
    {{-- MODAL 1: CREAR TAREA (ID actualizado: createTaskModal) --}}
    {{-- =================================================================== --}}
    @if(!Auth::user()->isCliente())
    <div class="modal fade" id="createTaskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('tareas.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header py-2">
                    <h5 class="modal-title fw-bold">Nueva Tarea Interna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Título</label>
                        <input type="text" name="titulo" class="form-control" required placeholder="Ej: Reunión...">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label">Inicio</label>
                            <input type="datetime-local" name="inicio" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Fin</label>
                            <input type="datetime-local" name="fin" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notas</label>
                        <textarea name="observaciones" class="form-control" rows="2"></textarea>
                    </div>
                    {{-- Color gris forzado (Hidden) --}}
                    <input type="hidden" name="color" value="#6c757d">
                </div>
                <div class="modal-footer py-2">
                    <button type="submit" class="btn btn-secondary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- =================================================================== --}}
    {{-- MODAL 2: VER DETALLES (ID actualizado: eventDetailModal) --}}
    {{-- =================================================================== --}}
    <div class="modal fade" id="eventDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Detalle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    {{-- IDs internos en inglés para coincidir con agenda.js --}}
                    <h4 id="modalTitle" class="fw-bold mb-1">...</h4>
                    <p id="modalTime" class="text-muted small mb-3">...</p>
                    
                    <div class="p-2 bg-light rounded mb-2">
                        <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">Responsable / Cliente</small>
                        <div id="modalResponsible" class="fw-medium">...</div>
                    </div>
                    <div class="p-2 border rounded">
                        <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">Descripción</small>
                        <div id="modalDescription">...</div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0" id="modalActions">
                    {{-- Botones inyectados por JS --}}
                </div>
            </div>
        </div>
    </div>

</body>
</html>