<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Evento - Pet Care</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="panel-container">
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title"><h3>Nuevo Evento de Agenda</h3><p>Añade una tarea o recordatorio.</p></div>
            </header>

            <div class="panel-content">
                <div class="card">
                    <form action="{{ route('agenda.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                            @endif

                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label class="form-label">Actividad / Título</label>
                                    <input type="text" name="actividad" class="form-control" placeholder="Ej: Reunión de equipo, Limpieza..." value="{{ old('actividad') }}" required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Fecha</label>
                                    <input type="date" name="fecha" class="form-control" value="{{ old('fecha', date('Y-m-d')) }}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Hora Inicio</label>
                                    <input type="time" name="hora_inicio" class="form-control" value="{{ old('hora_inicio') }}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Hora Fin (Opcional)</label>
                                    <input type="time" name="hora_fin" class="form-control" value="{{ old('hora_fin') }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Mascota Relacionada (Opcional)</label>
                                    <select name="mascota_id" class="form-select">
                                        <option value="">-- Ninguna --</option>
                                        @foreach ($mascotas as $mascota)
                                            <option value="{{ $mascota->id }}" @selected(old('mascota_id') == $mascota->id)>{{ $mascota->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Observaciones</label>
                                    <textarea name="observaciones" class="form-control" rows="3">{{ old('observaciones') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <a href="{{ route('agenda.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar Evento</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>