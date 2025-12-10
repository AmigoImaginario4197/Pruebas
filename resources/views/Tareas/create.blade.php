<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Tarea - Pet Care</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
</head>
<body class="font-sans antialiased">
    <div class="panel-container">
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title">
                    <h3>Nueva Tarea Interna</h3>
                    <p>Crea una nueva actividad para el equipo o para bloquear la agenda.</p>
                </div>
                {{-- ============================================= --}}
                {{--    NUEVO: BOTÓN PARA VOLVER AL LISTADO        --}}
                {{-- ============================================= --}}
                <div class="header-actions">
                    <a href="{{ route('tareas.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left-circle"></i> Volver al Listado
                    </a>
                </div>
            </header>

            <div class="panel-content">
                <div class="card shadow-sm border-0 p-4" style="max-width: 800px; margin: 0 auto;">
                    {{-- Usamos la directiva @error para mostrar un resumen de errores al principio --}}
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <strong>¡Ups! Hay algunos errores:</strong>
                            <ul class="mt-2 mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('tareas.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="titulo" class="form-label fw-bold">Título de la Actividad</label>
                            <input type="text" name="titulo" id="titulo" class="form-control @error('titulo') is-invalid @enderror" placeholder="Ej: Reunión de equipo, Mantenimiento..." value="{{ old('titulo') }}" required>
                            @error('titulo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="inicio" class="form-label fw-bold">Fecha y Hora de Inicio</label>
                                <input type="datetime-local" name="inicio" id="inicio" class="form-control @error('inicio') is-invalid @enderror" value="{{ old('inicio') }}" required>
                                @error('inicio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="fin" class="form-label fw-bold">Fecha y Hora de Fin</label>
                                <input type="datetime-local" name="fin" id="fin" class="form-control @error('fin') is-invalid @enderror" value="{{ old('fin') }}" required>
                                @error('fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="color" class="form-label fw-bold">Color (para la Agenda)</label>
                            <input type="color" name="color" id="color" class="form-control form-control-color w-100" value="{{ old('color', '#6c757d') }}">
                        </div>

                        {{-- ============================================= --}}
                        {{--    NUEVA SECCIÓN: ASIGNACIÓN DE LA TAREA      --}}
                        {{-- ============================================= --}}
                        <div class="card bg-light border p-3 mb-4">
                            <h6 class="mb-3 fw-bold">Asignación (Opcional)</h6>
                            <p class="text-muted small mb-3">Deja ambos campos vacíos para crear una tarea general visible para todos los veterinarios. Si seleccionas una opción, la otra se anulará.</p>
                            
                            <div class="mb-3">
                                <label for="asignado_a_id" class="form-label">Asignar a un Veterinario Específico</o>
                                <select name="asignado_a_id" id="asignado_a_id" class="form-select @error('asignado_a_id') is-invalid @enderror">
                                    <option value="">-- Sin asignación individual --</option>
                                    @foreach($veterinarios as $veterinario)
                                        <option value="{{ $veterinario->id }}" @selected(old('asignado_a_id') == $veterinario->id)>
                                            {{ $veterinario->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('asignado_a_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                    
                            <div class="mb-2">
                                <label for="especialidad_asignada" class="form-label">Asignar por Especialidad</label>
                                <select name="especialidad_asignada" id="especialidad_asignada" class="form-select @error('especialidad_asignada') is-invalid @enderror">
                                    <option value="">-- Sin asignación por especialidad --</option>
                                    @foreach($especialidades as $especialidad)
                                        <option value="{{ $especialidad }}" @selected(old('especialidad_asignada') == $especialidad)>
                                            {{ $especialidad }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('especialidad_asignada')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="observaciones" class="form-label fw-bold">Observaciones / Detalles</label>
                            <textarea name="observaciones" id="observaciones" class="form-control" rows="4">{{ old('observaciones') }}</textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('tareas.index') }}" class="btn btn-light border">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar Tarea</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    {{-- Script para que los campos de asignación sean mutuamente excluyentes --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const vetSelect = document.getElementById('asignado_a_id');
            const espSelect = document.getElementById('especialidad_asignada');
    
            vetSelect.addEventListener('change', function() {
                if (this.value !== '') {
                    espSelect.value = '';
                }
            });
    
            espSelect.addEventListener('change', function() {
                if (this.value !== '') {
                    vetSelect.value = '';
                }
            });
        });
    </script>
</body>
</html>