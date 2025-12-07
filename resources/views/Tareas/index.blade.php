<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tareas Internas - Pet Care</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="panel-container">
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title">
                    <h3>Tareas Internas</h3>
                    <p>Gestión de actividades administrativas y bloqueos de agenda.</p>
                </div>
                <div class="header-actions">
                    {{-- Solo Admin puede crear --}}
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('tareas.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Nueva Tarea
                        </a>
                    @endif
                </div>
            </header>

            <div class="panel-content">
                @if(session('success')) 
                    <div class="alert alert-success mb-3">{{ session('success') }}</div> 
                @endif

                <div class="item-list">
                    @forelse ($tareas as $tarea)
                        {{-- TRUCO: Definimos la variable --task-color para que el editor no marque error --}}
                        <div class="item-card border-start border-4" 
                             style="--task-color: {{ $tarea->color }}; border-left-color: var(--task-color) !important;">
                            
                            {{-- Usamos la variable CSS --}}
                            <div class="item-photo d-flex align-items-center justify-content-center text-white" 
                                 style="background-color: var(--task-color); font-size: 1.5rem;">
                                <i class="bi bi-clipboard-check"></i>
                            </div>

                            <div class="item-details">
                                <h4>{{ $tarea->titulo }}</h4>
                                <p class="mb-1 text-muted">
                                    <i class="bi bi-calendar-event"></i> 
                                    {{ $tarea->inicio->format('d/m/Y H:i') }} 
                                    <i class="bi bi-arrow-right-short"></i> 
                                    {{ $tarea->fin->format('H:i') }}
                                </p>
                                <small class="text-secondary">Creado por: {{ $tarea->user->name ?? 'Sistema' }}</small>
                            </div>

                            <div class="item-actions">
                                {{-- Ver Detalle (Todos) --}}
                                <a href="{{ route('tareas.show', $tarea) }}" class="btn btn-secondary btn-sm" title="Ver detalle">
                                    <i class="bi bi-eye"></i>
                                </a>

                                {{-- Editar/Borrar (Solo Admin) --}}
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('tareas.edit', $tarea) }}" class="btn btn-warning btn-sm" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('tareas.destroy', $tarea) }}" method="POST" onsubmit="return confirm('¿Eliminar esta tarea?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="empty-state-card">
                            <i class="bi bi-check2-all"></i>
                            <p>No hay tareas pendientes.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-4">{{ $tareas->links() }}</div>
            </div>
        </main>
    </div>
</body>
</html>