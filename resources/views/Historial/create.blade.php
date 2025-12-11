<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Historial - Pet Care</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
    <link rel="stylesheet" href="{{ asset('css/citas.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="panel-container">     
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title">
                    <h3>Nuevo Registro al Historial</h3>
                    <p>Añade un evento o diagnóstico a la ficha de la mascota.</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('historial.index') }}" class="btn btn-secondary text-white">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </header>

            <div class="panel-content">
                <div class="profile-card">
                    <form action="{{ route('historial.store') }}" method="POST">
                        @csrf
                        
                        <!-- Layout de 2 columnas -->
                        <div class="cita-layout">
                            
                            <!-- COLUMNA 1: DATOS GENERALES -->
                            <div class="cita-col-left">
                                <h5 class="section-title">1. Datos del Evento</h5>

                                <!-- Selección de Mascota -->
                                <div class="form-group">
                                    <label for="mascota_id" class="form-label">Paciente</label>
                                    <select name="mascota_id" id="mascota_id" class="form-control" required>
                                        <option value="" selected disabled>-- Seleccionar Mascota --</option>
                                        @foreach ($mascotas as $mascota)
                                            <option value="{{ $mascota->id }}" @selected(old('mascota_id') == $mascota->id)>
                                                {{ $mascota->nombre }} ({{ $mascota->especie }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('mascota_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                </div>

                                <!-- Fecha -->
                                <div class="form-group">
                                    <label for="fecha" class="form-label">Fecha del Suceso</label>
                                    <input type="date" name="fecha" id="fecha" class="form-control" 
                                           value="{{ old('fecha', now()->format('Y-m-d')) }}" 
                                           max="{{ now()->format('Y-m-d') }}" required>
                                    @error('fecha') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                </div>
                                
                                <!-- Tratamiento Vinculado (Opcional o Requerido según tu lógica) -->
                                <div class="form-group">
                                    <label for="tratamiento_id" class="form-label">Tratamiento Vinculado (Opcional)</label>
                                    <select name="tratamiento_id" id="tratamiento_id" class="form-control">
                                        <option value="">-- Ninguno / Solo Chequeo --</option>
                                        @foreach ($tratamientos as $tratamiento)
                                            {{-- Mostramos el nombre del tratamiento y quizás la fecha --}}
                                            <option value="{{ $tratamiento->id }}" @selected(old('tratamiento_id') == $tratamiento->id)>
                                                {{ $tratamiento->tipo }} - {{ \Str::limit($tratamiento->descripcion, 30) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Si este evento conllevó medicación, selecciónala aquí.</small>
                                    @error('tratamiento_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- COLUMNA 2: DESCRIPCIÓN -->
                            <div class="cita-col-right">
                                <h5 class="section-title">2. Diagnóstico</h5>

                                <!-- Descripción -->
                                <div class="form-group">
                                    <label for="descripcion" class="form-label">Detalles Clínicos</label>
                                    <textarea name="descripcion" id="descripcion" class="form-control" rows="8" 
                                              placeholder="Describe qué le ocurrió a la mascota, síntomas observados y diagnóstico final..." required>{{ old('descripcion') }}</textarea>
                                    @error('descripcion') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- BOTONES -->
                        <div class="d-flex align-items-center mt-4 pt-3 border-top" style="justify-content: flex-end; gap: 15px;">
                            <a href="{{ route('historial.index') }}" class="btn btn-secondary text-white" style="text-decoration: none; padding: 0.75rem 1.5rem; border-radius: 6px; background-color: #6c757d;">Cancelar</a>
                            
                            <button type="submit" class="btn-save">
                                <i class="bi bi-file-earmark-medical"></i> Guardar en Historial
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>