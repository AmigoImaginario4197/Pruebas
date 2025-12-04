<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda - Pet Care</title>
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
                    <h3>Agenda Diaria</h3>
                    <p>Visualiza tus citas y eventos del día.</p>
                </div>
                @if(!Auth::user()->isCliente())
                    <div class="header-actions">
                        <a href="{{ route('agenda.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Nuevo Evento
                        </a>
                    </div>
                @endif
            </header>

            <div class="panel-content">
                @if (session('success'))
                    <div class="alert alert-success mb-4">{{ session('success') }}</div>
                @endif

                {{-- Selector de Fecha --}}
                <div class="card p-3 mb-4">
                    <form action="{{ route('agenda.index') }}" method="GET" class="d-flex align-items-center gap-3">
                        <label for="fecha" class="form-label mb-0 fw-bold">Ver fecha:</label>
                        <input type="date" name="fecha" id="fecha" class="form-control w-auto" value="{{ $fecha }}" onchange="this.form.submit()">
                        <a href="{{ route('agenda.index') }}" class="btn btn-outline-secondary btn-sm">Hoy</a>
                    </form>
                </div>

                <h4 class="mb-3">Eventos para el {{ \Carbon\Carbon::parse($fecha)->isoFormat('dddd, D [de] MMMM') }}</h4>

                <div class="item-list">
                    {{-- 1. MOSTRAR CITAS (Vienen de la tabla 'citas') --}}
                    @foreach ($citas as $cita)
                        <div class="item-card border-start border-4 border-primary">
                            <div class="item-photo-placeholder bg-primary text-white">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div class="item-details">
                                <h4>{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('H:i') }} - Cita: {{ $cita->mascota->nombre }}</h4>
                                <p class="mb-0 text-muted">
                                    {{ $cita->motivo }} - 
                                    @if(Auth::user()->isCliente()) Dr. {{ $cita->veterinario->name }}
                                    @else Cliente: {{ $cita->cliente->name }} @endif
                                </p>
                            </div>
                            <div class="item-actions">
                                <a href="{{ route('citas.show', $cita) }}" class="btn btn-info btn-sm"><i class="bi bi-eye"></i></a>
                            </div>
                        </div>
                    @endforeach

                    {{-- 2. MOSTRAR EVENTOS DE AGENDA (Solo personal, tabla 'agenda') --}}
                    @if(isset($eventos))
                        @foreach ($eventos as $evento)
                            <div class="item-card border-start border-4 border-warning">
                                <div class="item-photo-placeholder bg-warning text-dark">
                                    <i class="bi bi-calendar-event"></i>
                                </div>
                                <div class="item-details">
                                    <h4>{{ \Carbon\Carbon::parse($evento->hora_inicio)->format('H:i') }} - {{ $evento->actividad }}</h4>
                                    <p class="mb-0 text-muted">
                                        @if($evento->hora_fin) Hasta: {{ \Carbon\Carbon::parse($evento->hora_fin)->format('H:i') }} @endif
                                        @if($evento->mascota) | Mascota: {{ $evento->mascota->nombre }} @endif
                                    </p>
                                </div>
                                <div class="item-actions">
                                    <a href="{{ route('agenda.show', $evento) }}" class="btn btn-info btn-sm"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('agenda.edit', $evento) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('agenda.destroy', $evento) }}" method="POST" onsubmit="return confirm('¿Borrar evento?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    @if($citas->isEmpty() && (empty($eventos) || $eventos->isEmpty()))
                        <div class="empty-state-card">
                            <i class="bi bi-calendar4-week"></i>
                            <p>No hay actividades programadas para este día.</p>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</body>
</html>