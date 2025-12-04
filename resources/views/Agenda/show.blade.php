<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Evento - Pet Care</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="panel-container">
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title"><h3>Detalle del Evento</h3></div>
                <div class="header-actions"><a href="{{ route('agenda.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver</a></div>
            </header>

            <div class="panel-content">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-warning">{{ $agenda->actividad }}</h5>
                        <hr>
                        <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($agenda->fecha)->format('d/m/Y') }}</p>
                        <p><strong>Horario:</strong> {{ \Carbon\Carbon::parse($agenda->hora_inicio)->format('H:i') }} 
                           @if($agenda->hora_fin) - {{ \Carbon\Carbon::parse($agenda->hora_fin)->format('H:i') }} @endif
                        </p>
                        @if($agenda->mascota)
                            <p><strong>Mascota:</strong> {{ $agenda->mascota->nombre }}</p>
                        @endif
                        @if($agenda->observaciones)
                            <div class="alert alert-light border mt-3">{{ $agenda->observaciones }}</div>
                        @endif
                    </div>
                    <div class="card-footer text-end">
                        <a href="{{ route('agenda.edit', $agenda) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Editar</a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>