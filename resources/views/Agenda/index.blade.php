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
</head>
<body class="font-sans antialiased">
    <div class="panel-container">
        
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title">
                    <h3>Agenda Veterinaria</h3>
                </div>
                
                {{-- ACCIONES: Botones y Filtro --}}
                <div class="header-actions d-flex gap-2 align-items-center">
                    
                    {{-- FILTRO DE USUARIOS (Solo Admin) --}}
                    @if(Auth::user()->isAdmin())
                        <form action="{{ route('agenda.index') }}" method="GET" class="d-flex">
                            <select name="user_id" class="form-select form-select-sm" onchange="this.form.submit()" style="cursor: pointer; min-width: 150px;">
                                <option value="">Ver Todo</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                        Agenda de: {{ $u->name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    @endif

                    {{-- BOTONES DE CREACIÓN --}}
                    @if(!Auth::user()->isCliente())
                        <a href="{{ route('tareas.create') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-clipboard-plus"></i> Tarea
                        </a>
                        <a href="{{ route('citas.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-calendar-plus"></i> Cita
                        </a>
                    @endif
                </div>
            </header>

            <div class="panel-content" style="overflow-y: hidden;"> 
                @if (session('success'))
                    <div class="alert alert-success py-2 mb-2">{{ session('success') }}</div>
                @endif

                <div class="d-flex gap-3 mb-2 align-items-center small text-muted">
                    <span><i class="bi bi-circle-fill legend-dot-pending"></i> Pendiente</span>
                    <span><i class="bi bi-circle-fill legend-dot-confirmed"></i> Confirmada</span>
                    <span><i class="bi bi-circle-fill legend-dot-task"></i> Tarea Interna</span>
                </div>

                <div id="calendar" 
                     data-route="{{ route('agenda.data', ['user_id' => request('user_id')]) }}">
                </div>

            </div>
        </main>
    </div>
</body>
</html>