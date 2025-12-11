<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Disponibilidad - Pet Care</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
    <link rel="stylesheet" href="{{ asset('css/disponibilidad.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/disponibilidad.js'])
</head>
<body class="font-sans antialiased">
    <div class="panel-container">
        
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title">
                    <h3>Editar Bloque de Disponibilidad</h3>
                    <p>Modifica el horario asignado.</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('disponibilidad.index') }}" class="btn btn-secondary text-white">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </header>

            <div class="panel-content">
                <div class="profile-card">
                    <!-- Action apunta a UPDATE -->
                    <form action="{{ route('disponibilidad.update', $disponibilidad->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <i class="bi bi-exclamation-triangle-fill"></i> Revisa los campos.
                                <ul class="mb-0 mt-2">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                            </div>
                        @endif

                        <div class="disp-layout">
                            
                            <!-- COLUMNA 1 -->
                            <div class="disp-col">
                                <h5 class="section-title" style="color: #4f46e5; border-bottom: 2px solid #eef2ff; margin-bottom: 20px;">
                                    1. Datos Generales
                                </h5>

                                <div class="form-group">
                                    <label for="veterinario_id" class="form-label">Veterinario</label>
                                    <select name="veterinario_id" id="veterinario_id" class="form-control" required>
                                        @foreach ($veterinarios as $veterinario)
                                            <option value="{{ $veterinario->id }}" 
                                                @selected(old('veterinario_id', $disponibilidad->veterinario_id) == $veterinario->id)>
                                                {{ $veterinario->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('veterinario_id')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                </div>

                                <div class="form-group">
                                    <label for="fecha" class="form-label">Fecha</label>
                                    <input type="date" name="fecha" id="fecha" class="form-control" 
                                           value="{{ old('fecha', $disponibilidad->fecha) }}" 
                                           required min="{{ now()->format('Y-m-d') }}">
                                    @error('fecha')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <!-- COLUMNA 2 -->
                            <div class="disp-col disp-col-right">
                                <h5 class="section-title" style="color: #4f46e5; border-bottom: 2px solid #eef2ff; margin-bottom: 20px;">
                                    2. Configuración de Hora
                                </h5>

                                <div class="time-input-group">
                                    <div class="form-group">
                                        <label for="hora_inicio" class="time-label">Hora de Inicio</label>
                                        <input type="time" name="hora_inicio" id="hora_inicio" class="form-control" 
                                               value="{{ old('hora_inicio', $disponibilidad->hora_inicio) }}" required>
                                        @error('hora_inicio')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                    </div>
    
                                    <div class="form-group mt-3">
                                        <label for="hora_fin" class="time-label">Hora de Fin</label>
                                        <input type="time" name="hora_fin" id="hora_fin" class="form-control" 
                                               value="{{ old('hora_fin', $disponibilidad->hora_fin) }}" required>
                                        @error('hora_fin')<div class="text-danger mt-1">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- BOTONES -->
                        <div class="d-flex align-items-center mt-4 pt-3 border-top" style="justify-content: flex-end; gap: 15px;">
                            <a href="{{ route('disponibilidad.index') }}" class="btn btn-secondary text-white" style="text-decoration: none; padding: 0.75rem 1.5rem; border-radius: 6px; background-color: #6c757d;">Cancelar</a>
                            
                            <button type="submit" class="btn-save" style="background-color: #f59e0b;">
                                <i class="bi bi-pencil-square"></i> Guardar Cambios
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>