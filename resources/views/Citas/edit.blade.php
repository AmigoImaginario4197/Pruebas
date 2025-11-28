<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cita - Pet Care</title>
    
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
                    <h3>Editar / Reprogramar Cita</h3>
                    <p>Modifica los datos de la consulta para <strong>{{ $cita->mascota->nombre }}</strong>.</p>
                </div>
            </header>

            <div class="panel-content">
                <div class="card">
                    <form action="{{ route('citas.update', $cita) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                            @endif

                            <div class="row">
                                {{-- Fecha y Hora --}}
                                <div class="col-md-6 mb-3">
                                    <label for="fecha_hora" class="form-label">Fecha y Hora</label>
                                    {{-- IMPORTANTE: El formato para datetime-local debe ser Y-m-d\TH:i --}}
                                    <input type="datetime-local" name="fecha_hora" id="fecha_hora" class="form-control" 
                                           value="{{ old('fecha_hora', \Carbon\Carbon::parse($cita->fecha_hora)->format('Y-m-d\TH:i')) }}" required>
                                </div>

                                {{-- Motivo --}}
                                <div class="col-md-6 mb-3">
                                    <label for="motivo" class="form-label">Motivo</label>
                                    <input type="text" name="motivo" id="motivo" class="form-control" value="{{ old('motivo', $cita->motivo) }}" required>
                                </div>

                                {{-- Estado (SOLO ADMIN Y VETERINARIO) --}}
                                @if(Auth::user()->isAdmin() || Auth::user()->isVeterinario())
                                    <div class="col-md-6 mb-3">
                                        <label for="estado" class="form-label">Estado de la Cita</label>
                                        <select name="estado" id="estado" class="form-select">
                                            <option value="pendiente" @selected(old('estado', $cita->estado) == 'pendiente')>Pendiente</option>
                                            <option value="confirmada" @selected(old('estado', $cita->estado) == 'confirmada')>Confirmada</option>
                                            <option value="completada" @selected(old('estado', $cita->estado) == 'completada')>Completada (Atendida)</option>
                                            <option value="cancelada" @selected(old('estado', $cita->estado) == 'cancelada')>Cancelada</option>
                                        </select>
                                    </div>
                                @endif

                                {{-- Veterinario (Informativo, difícil de cambiar por disponibilidad, mejor solo lectura) --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted">Veterinario Asignado</label>
                                    <input type="text" class="form-control" value="Dr. {{ $cita->veterinario->name }}" disabled>
                                    <small class="text-muted">Para cambiar de doctor, es mejor cancelar y crear una nueva cita.</small>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <a href="{{ route('citas.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>