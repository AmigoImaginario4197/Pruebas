<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cita - Pet Care</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- CSS: Panel, Perfil y Citas -->
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
    <link rel="stylesheet" href="{{ asset('css/citas.css') }}">
    
    <!-- JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/citas.js'])
</head>
<body class="font-sans antialiased">
    <div class="panel-container">     
        
        @include('layouts.sidebar')

        <main class="panel-main">
            
            <header class="panel-header">
                <div class="header-title">
                    <h3>Editar Cita #{{ $cita->id }}</h3>
                    <p>Modifica los detalles de la consulta programada.</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('citas.index') }}" class="btn btn-secondary text-white">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </header>

            <div class="panel-content">
                <div class="profile-card">
                    
                    <form action="{{ route('citas.update', $cita->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- DIV OCULTO PARA ERRORES --}}
                        @if ($errors->any())
                            <div id="backend-errors" style="display: none;">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="alert alert-danger mb-4">
                                <i class="bi bi-exclamation-triangle-fill"></i> Por favor revisa los campos.
                            </div>
                        @endif

                        <div class="row" style="display: flex; flex-wrap: wrap; gap: 30px;">
                            
                            {{-- COLUMNA 1: DETALLES --}}
                            <div style="flex: 1; min-width: 300px;">
                                <h5 style="color: #4f46e5; border-bottom: 2px solid #eef2ff; padding-bottom: 10px; margin-bottom: 20px;">
                                    1. Detalles de la Consulta
                                </h5>
                                
                                {{-- Mascota --}}
                                <div class="form-group">
                                    <label for="mascota_id" class="form-label">Paciente (Mascota)</label>
                                    <select name="mascota_id" class="form-control" required 
                                            {{ !Auth::user()->isAdmin() ? 'disabled' : '' }}>
                                        @foreach($mascotas as $mascota)
                                            <option value="{{ $mascota->id }}" 
                                                {{ $cita->mascota_id == $mascota->id ? 'selected' : '' }}>
                                                {{ $mascota->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    {{-- Si está disabled, necesitamos enviar el valor oculto --}}
                                    @if(!Auth::user()->isAdmin())
                                        <input type="hidden" name="mascota_id" value="{{ $cita->mascota_id }}">
                                    @endif
                                </div>

                                {{-- Servicio --}}
                                <div class="form-group">
                                    <label for="servicio_id" class="form-label">Servicio</label>
                                    <select name="servicio_id" id="servicio_id" class="form-control" required
                                            {{ !Auth::user()->isAdmin() ? 'disabled' : '' }}>
                                        @foreach($servicios as $servicio)
                                            <option value="{{ $servicio->id }}" 
                                                    data-price="{{ $servicio->precio }}" 
                                                    {{ $cita->servicio_id == $servicio->id ? 'selected' : '' }}>
                                                {{ $servicio->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if(!Auth::user()->isAdmin())
                                        <input type="hidden" name="servicio_id" value="{{ $cita->servicio_id }}">
                                    @endif
                                </div>
                                
                                {{-- Motivo (Siempre editable) --}}
                                <div class="form-group">
                                    <label for="motivo" class="form-label">Motivo</label>
                                    <textarea name="motivo" class="form-control" rows="4" required>{{ old('motivo', $cita->motivo) }}</textarea>
                                </div>
                            </div>
                            
                            {{-- COLUMNA 2: AGENDA Y ESTADO --}}
                            <div style="flex: 1; min-width: 300px; padding-left: 15px; border-left: 1px solid #f1f5f9;">
                                <h5 style="color: #4f46e5; border-bottom: 2px solid #eef2ff; padding-bottom: 10px; margin-bottom: 20px;">
                                    2. Agenda y Estado
                                </h5>

                                {{-- Veterinario --}}
                                <div class="form-group">
                                    <label for="veterinario_id" class="form-label">Veterinario</label>
                                    <select name="veterinario_id" id="veterinario_id" class="form-control" required
                                            {{ !Auth::user()->isAdmin() ? 'disabled' : '' }}>
                                        @foreach($veterinarios as $vet)
                                            <option value="{{ $vet->id }}" {{ $cita->veterinario_id == $vet->id ? 'selected' : '' }}>
                                                {{ $vet->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if(!Auth::user()->isAdmin())
                                        <input type="hidden" name="veterinario_id" value="{{ $cita->veterinario_id }}">
                                    @endif
                                </div>

                                {{-- Fecha --}}
                                <div class="form-group">
                                    <label for="fecha_selector" class="form-label">Fecha</label>
                                    <input type="date" id="fecha_selector" class="form-control" 
                                           value="{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('Y-m-d') }}" 
                                           min="{{ date('Y-m-d') }}" required>
                                    <small class="text-muted">Si cambias la fecha, selecciona un nuevo horario abajo.</small>
                                </div>

                                {{-- Slots (JS) --}}
                                <div class="form-group">
                                    <label class="form-label">Horarios Disponibles</label>
                                    <div id="slots-container" class="slots-container">
                                        {{-- Aquí puedes mostrar la hora actual como botón seleccionado si quieres, 
                                             pero tu JS probablemente recargará los slots al cambiar la fecha --}}
                                        <div class="slot-message">Cargando disponibilidad...</div>
                                    </div>
                                    
                                    {{-- Input Oculto --}}
                                    <input type="hidden" name="fecha_hora" id="fecha_hora_final" value="{{ $cita->fecha_hora }}" required>
                                </div>

                                {{-- Estado (Solo Admin/Vet) --}}
                                @if(Auth::user()->rol !== 'cliente')
                                    <div class="form-group mt-4 pt-3 border-top">
                                        <label for="estado" class="form-label fw-bold">Cambiar Estado</label>
                                        <select name="estado" class="form-control" style="border-color: #f59e0b;">
                                            <option value="pendiente" {{ $cita->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="confirmada" {{ $cita->estado == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                                            <option value="cancelada" {{ $cita->estado == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                            <option value="completada" {{ $cita->estado == 'completada' ? 'selected' : '' }}>Completada</option>
                                        </select>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- BOTONES --}}
                        <div class="d-flex align-items-center mt-4 pt-3 border-top" style="justify-content: flex-end; gap: 15px;">
                            <a href="{{ route('citas.index') }}" class="btn btn-secondary text-white" style="text-decoration: none; padding: 0.75rem 1.5rem; border-radius: 6px; background-color: #6c757d;">Cancelar</a>
                            
                            <button type="submit" class="btn-save" style="background-color: #f59e0b;">
                                <i class="bi bi-pencil-square"></i> Actualizar Cita
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>