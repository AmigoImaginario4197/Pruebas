<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Registro Médico - Pet Care</title>
    
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
                    <h3>Nuevo Registro Médico</h3>
                    <p>Añade un evento al historial de una mascota.</p>
                </div>
            </header>

            <div class="panel-content">
                <div class="card">
                    <form action="{{ route('historial.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger mb-4"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                            @endif

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="mascota_id" class="form-label">Paciente</label>
                                    <select name="mascota_id" id="mascota_id" class="form-select" required>
                                        <option value="">-- Seleccionar Mascota --</option>
                                        @foreach ($mascotas as $mascota)
                                            <option value="{{ $mascota->id }}" @selected(old('mascota_id') == $mascota->id)>
                                                {{ $mascota->nombre }} (Dueño: {{ $mascota->usuario->name }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="fecha" class="form-label">Fecha del Evento</label>
                                    <input type="date" name="fecha" id="fecha" class="form-control" value="{{ old('fecha', now()->format('Y-m-d')) }}" required>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="tipo" class="form-label">Tipo de Evento</label>
                                    <input type="text" name="tipo" id="tipo" class="form-control" placeholder="Ej: Consulta General, Vacunación, Cirugía..." value="{{ old('tipo') }}" required>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="descripcion" class="form-label">Descripción / Diagnóstico</label>
                                    <textarea name="descripcion" id="descripcion" class="form-control" rows="4" placeholder="Detalles del procedimiento o diagnóstico..." required>{{ old('descripcion') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <a href="{{ route('historial.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar Registro</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>