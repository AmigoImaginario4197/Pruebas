<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Historial - Pet Care</title>
    
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
    <link rel="stylesheet" href="{{ asset('css/historial.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="panel-container">     
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title">
                    <h3>Editar Registro #{{ $historial->id }}</h3>
                    <p>Modifica la información del evento clínico.</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('historial.index') }}" class="btn btn-secondary text-white">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </header>

            <div class="panel-content">
                <div class="profile-card">
                    <form action="{{ route('historial.update', $historial->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="historial-layout">
                            
                            <!-- COLUMNA 1 -->
                            <div class="col-left">
                                <h5 class="section-title" style="margin-bottom: 20px; color: #4f46e5; font-size: 1.1rem; font-weight: 600;">1. Datos del Evento</h5>

                                <!-- Paciente (Disabled) -->
                                <div class="form-group">
                                    <label class="form-label">Paciente</label>
                                    <input type="text" class="form-control" value="{{ $historial->mascota->nombre }}" disabled style="background-color: #f3f4f6;">
                                    <small class="text-muted">El paciente no se puede cambiar.</small>
                                </div>

                                <!-- Fecha -->
                                <div class="form-group">
                                    <label for="fecha" class="form-label">Fecha del Suceso</label>
                                    <input type="date" name="fecha" id="fecha" class="form-control" 
                                           value="{{ old('fecha', $historial->fecha) }}" 
                                           max="{{ now()->format('Y-m-d') }}" required>
                                    @error('fecha') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                </div>
                                
                                <!-- Tratamiento -->
                                <div class="form-group">
                                    <label for="tratamiento_id" class="form-label">Tratamiento Vinculado</label>
                                    <select name="tratamiento_id" id="tratamiento_id" class="form-control">
                                        <option value="">-- Ninguno / Solo Chequeo --</option>
                                        @foreach ($tratamientos as $tratamiento)
                                            <option value="{{ $tratamiento->id }}" 
                                                {{ old('tratamiento_id', $historial->tratamiento_id) == $tratamiento->id ? 'selected' : '' }}>
                                                {{ $tratamiento->tipo }} - {{ \Str::limit($tratamiento->descripcion, 30) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('tratamiento_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- COLUMNA 2 -->
                            <div class="col-right">
                                <h5 class="section-title" style="margin-bottom: 20px; color: #4f46e5; font-size: 1.1rem; font-weight: 600;">2. Diagnóstico</h5>

                                <div class="form-group">
                                    <label for="descripcion" class="form-label">Detalles Clínicos</label>
                                    <textarea name="descripcion" id="descripcion" class="form-control" rows="8" required>{{ old('descripcion', $historial->descripcion) }}</textarea>
                                    @error('descripcion') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mt-4 pt-3 border-top" style="justify-content: flex-end; gap: 15px;">
                            <a href="{{ route('historial.index') }}" class="btn btn-secondary text-white" style="text-decoration: none; padding: 0.75rem 1.5rem; border-radius: 6px; background-color: #6c757d;">Cancelar</a>
                            
                            <button type="submit" class="btn-save" style="background-color: #f59e0b;">
                                <i class="bi bi-pencil-square"></i> Actualizar Registro
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>