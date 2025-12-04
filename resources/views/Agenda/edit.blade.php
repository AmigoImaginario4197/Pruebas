<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Evento - Pet Care</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="panel-container">
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title"><h3>Editar Evento</h3></div>
            </header>

            <div class="panel-content">
                <div class="card">
                    <form action="{{ route('agenda.update', $agenda) }}" method="POST">
                        @csrf @method('PATCH')
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label">Actividad</label>
                                    <input type="text" name="actividad" class="form-control" value="{{ old('actividad', $agenda->actividad) }}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Fecha</label>
                                    <input type="date" name="fecha" class="form-control" value="{{ old('fecha', $agenda->fecha) }}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Hora Inicio</label>
                                    <input type="time" name="hora_inicio" class="form-control" value="{{ old('hora_inicio', \Carbon\Carbon::parse($agenda->hora_inicio)->format('H:i')) }}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Hora Fin</label>
                                    <input type="time" name="hora_fin" class="form-control" value="{{ old('hora_fin', $agenda->hora_fin ? \Carbon\Carbon::parse($agenda->hora_fin)->format('H:i') : '') }}">
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Observaciones</label>
                                    <textarea name="observaciones" class="form-control" rows="3">{{ old('observaciones', $agenda->observaciones) }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <a href="{{ route('agenda.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>