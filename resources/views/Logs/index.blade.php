<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auditoría - Pet Care</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    {{-- Tus estilos --}}
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/logs.css') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                
                {{-- Filtro de usuarios (Solo Admin) --}}
                <div class="header-actions">
                    @if(Auth::user()->isAdmin())
                        <form action="{{ route('logs.index') }}" method="GET">
                            <select name="user_id" class="form-select form-select-sm" onchange="this.form.submit()" style="cursor: pointer;">
                                <option value="">Todos los Usuarios</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                        {{ $u->name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    @endif
                </div>
            </header>

            <div class="panel-content">
                <div class="item-list">
                    @forelse ($logs as $log)
                        {{-- Lógica de colores --}}
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
                            {{-- Icono de color --}}
                            <div class="log-icon-placeholder me-3" style="--log-color: {{ $color }};">
                                <i class="bi {{ $icon }}"></i>
                            </div>
                            
                            {{-- Detalles completos (Sin botón, sin modal) --}}
                            <div class="item-details">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h4 class="mb-0">{{ $log->accion }}</h4>
                                    <small class="text-muted">{{ $log->fecha->format('d/m/Y H:i') }}</small>
                                </div>
                                
                                <p class="mb-1 text-primary fw-medium" style="font-size: 0.9rem;">
                                    <i class="bi bi-person"></i> {{ $log->user->name ?? 'Sistema' }}
                                </p>
                                
                                {{-- Detalle completo visible --}}
                                <p class="mb-0 text-muted small text-break">
                                    {{ $log->detalle }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state-card">
                            <i class="bi bi-clipboard-check"></i>
                            <p>No hay actividad registrada.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-4">{{ $logs->links() }}</div>
            </div>
        </main>
    </div>
</body>
</html>