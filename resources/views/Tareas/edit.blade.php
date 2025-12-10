<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Editar Tarea - Pet Care</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">

    {{-- Cargamos el nuevo script task-form.js --}}
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/task-form.js'])
</head>
<body class="font-sans antialiased">
    <div class="panel-container">
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title">
                    <h3>Editar Tarea: {{ $tarea->titulo }}</h3>
                    <p>Modifica los detalles de la tarea interna.</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('tareas.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left-circle"></i> Volver al Listado
                    </a>
                </div>
            </header>

            <div class="panel-content">
                <div class="card shadow-sm border-0 p-4" style="max-width: 800px; margin: 0 auto;">
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

                    <form action="{{ route('tareas.update', $tarea) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="titulo" class="form-label fw-bold">Título</label>
                            <input type="text" name="titulo" id="titulo" class="form-control @error('titulo') is-invalid @enderror" value="{{ old('titulo', $tarea->titulo) }}" required>
                            @error('titulo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="inicio" class="form-label fw-bold">Fecha Inicio</label>
                                <input type="datetime-local" name="inicio" id="inicio" class="form-control @error('inicio') is-invalid @enderror" 
                                       value="{{ old('inicio', $tarea->inicio->format('Y-m-d\TH:i')) }}" required>
                                @error('inicio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="fin" class="form-label fw-bold">Fecha Fin</label>
                                <input type="datetime-local" name="fin" id="fin" class="form-control @error('fin') is-invalid @enderror" 
                                       value="{{ old('fin', $tarea->fin->format('Y-m-d\TH:i')) }}" required>
                                @error('fin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="color" class="form-label fw-bold">Color</label>
                            <input type="color" name="color" id="color" class="form-control form-control-color w-100" value="{{ old('color', $tarea->color) }}">
                        </div>

                        {{-- SECCIÓN DE ASIGNACIÓN --}}
                        <div class="card bg-light border p-3 mb-4">
                            <h6 class="mb-3 fw-bold">Asignación</h6>
                            <div class="mb-3">
                                <label for="asignado_a_id" class="form-label">Asignar a un Veterinario Específico</label>
                                <select name="asignado_a_id" id="asignado_a_id" class="form-select @error('asignado_a_id') is-invalid @enderror">
                                    <option value="">-- Sin asignación individual --</option>
                                    @foreach($veterinarios as $veterinario)
                                        <option value="{{ $veterinario->id }}" @selected(old('asignado_a_id', $tarea->asignado_a_id) == $veterinario->id)>
                                            {{ $veterinario->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('asignado_a_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                    
                            <div class="mb-2">
                                <label for="especialidad_asignada" class="form-label">Asignar por Especialidad</label>
                                <select name="especialidad_asignada" id="especialidad_asignada" class="form-select @error('especialidad_asignada') is-invalid @enderror">
                                    <option value="">-- Sin asignación por especialidad --</option>
                                    @foreach($especialidades as $especialidad)
                                        <option value="{{ $especialidad }}" @selected(old('especialidad_asignada', $tarea->especialidad_asignada) == $especialidad)>
                                            {{ $especialidad }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('especialidad_asignada') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="observaciones" class="form-label fw-bold">Observaciones</label>
                            <textarea name="observaciones" id="observaciones" class="form-control" rows="4">{{ old('observaciones', $tarea->observaciones) }}</textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('tareas.index') }}" class="btn btn-light border">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Actualizar Tarea</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>