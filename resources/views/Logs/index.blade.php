<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auditoría - Pet Care</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    {{-- Cargamos el CSS externo --}}
    <link rel="stylesheet" href="{{ asset('css/logs.css') }}">
    
    {{-- Cargamos JS Global y el JS específico de Logs --}}
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/logs.js'])
</head>
<body class="font-sans antialiased">
    <div class="panel-container">
        
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title">
                    <h3>Registro de Actividad</h3>
                    <p>Historial de acciones realizadas en el sistema.</p>
                </div>
            </header>

            <div class="panel-content">
                
                <div class="item-list">
                    @forelse ($logs as $log)
                        {{-- Lógica de colores (PHP) --}}
                        @php
                            $color = '#6c757d'; 
                            $icon = 'bi-hdd-stack';
                            $txt = strtolower($log->accion);
                            
                            if(str_contains($txt, 'cre')) { $color = '#198754'; $icon = 'bi-plus-lg'; }
                            if(str_contains($txt, 'elimin')) { $color = '#dc3545'; $icon = 'bi-trash'; }
                            if(str_contains($txt, 'edit') || str_contains($txt, 'actualiz')) { $color = '#ffc107'; $icon = 'bi-pencil'; }
                            if(str_contains($txt, 'sesi')) { $color = '#0d6efd'; $icon = 'bi-person-badge'; }
                        @endphp

                        <div class="item-card">
                            {{-- Pasamos el color como Variable CSS --}}
                            <div class="log-icon-placeholder me-3" style="--log-color: {{ $color }};">
                                <i class="bi {{ $icon }}"></i>
                            </div>
                            
                            <div class="item-details">
                                <h4>{{ $log->accion }}</h4>
                                <p class="mb-1">
                                    <strong>{{ $log->user->name ?? 'Sistema' }}</strong>
                                    <span class="text-muted mx-1">|</span>
                                    <span class="text-muted small">{{ $log->fecha->format('d/m/Y H:i') }}</span>
                                </p>
                                <small class="text-muted text-truncate-multiline">{{ $log->detalle }}</small>
                            </div>

                            <div class="item-actions">
                                {{-- Botón Ver --}}
                                <button type="button" class="btn btn-secondary" 
                                    data-accion="{{ $log->accion }}"
                                    data-usuario="{{ $log->user->name ?? 'Usuario Eliminado' }}"
                                    data-fecha="{{ $log->fecha->format('d/m/Y H:i:s') }}"
                                    data-detalle="{{ $log->detalle }}"
                                    onclick="openLogModal(this)">
                                    <i class="bi bi-eye"></i> Ver
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state-card">
                            <i class="bi bi-clipboard-check"></i>
                            <p>No hay actividad registrada.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Paginación --}}
                <div class="mt-4">
                    {{ $logs->links() }}
                </div>
            </div>
        </main>
    </div>

    {{-- MODAL DE DETALLES --}}
    <div class="modal fade" id="logModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Detalles del Evento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                            <i class="bi bi-clock-history fs-2 text-secondary"></i>
                        </div>
                    </div>
                    <ul class="list-group list-group-flush mb-3">
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Acción:</strong> <span id="modalAccion" class="text-primary fw-bold"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Usuario:</strong> <span id="modalUsuario"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Fecha:</strong> <span id="modalFecha"></span>
                        </li>
                    </ul>
                    
                    <p class="mb-1 px-2"><strong>Detalle Completo:</strong></p>
                    <div class="p-3 bg-light rounded border mx-2">
                        <span id="modalDetalle" style="word-break: break-word;"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>