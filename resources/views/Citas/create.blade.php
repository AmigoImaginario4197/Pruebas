<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Cita - Pet Care</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- CSS Separados -->
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
    <link rel="stylesheet" href="{{ asset('css/citas.css') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/citas.js'])
</head>
<body class="font-sans antialiased">
    <div class="panel-container">     
        @include('layouts.sidebar')
        <main class="panel-main">
            
            <header class="panel-header">
                <div class="header-title">
                    <h3>Agendar Nueva Cita</h3>
                    <p>Reserva una consulta con nuestros especialistas.</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('citas.index') }}" class="btn btn-secondary text-white">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </header>

            <div class="panel-content">
                <div class="profile-card">
                    <form action="{{ route('citas.store') }}" method="POST">
                        @csrf

                        <!-- Errores (Oculto para JS y Visible para usuario) -->
                        @if ($errors->any())
                            <div id="backend-errors" style="display: none;">
                                <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                            </div>
                            <div class="alert alert-danger mb-4">
                                <i class="bi bi-exclamation-triangle-fill"></i> Por favor revisa los campos marcados.
                            </div>
                        @endif

                        <div class="cita-layout">
                            
                            <!-- COLUMNA 1 -->
                            <div class="cita-col-left">
                                <h5 class="section-title">1. Detalles de la Consulta</h5>
                                
                                <div class="form-group">
                                    <label for="mascota_id" class="form-label">Paciente (Mascota)</label>
                                    <select name="mascota_id" id="mascota_id" class="form-control" required>
                                        <option value="" selected disabled>-- Selecciona --</option>
                                        @foreach($mascotas as $mascota)
                                            <option value="{{ $mascota->id }}" {{ old('mascota_id') == $mascota->id ? 'selected' : '' }}>
                                                {{ $mascota->nombre }} ({{ $mascota->especie }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('mascota_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="servicio_id" class="form-label">Servicio Requerido</label>
                                    <select name="servicio_id" id="servicio_id" class="form-control" required>
                                        <option value="" selected disabled>-- Selecciona --</option>
                                        @foreach($servicios as $servicio)
                                            <option value="{{ $servicio->id }}" data-price="{{ $servicio->precio }}" {{ old('servicio_id') == $servicio->id ? 'selected' : '' }}>
                                                {{ $servicio->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('servicio_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                </div>
                                
                                <div class="form-group">
                                    <label for="motivo" class="form-label">Motivo de la consulta</label>
                                    <textarea name="motivo" id="motivo" class="form-control" rows="4" placeholder="Describe brevemente..." required>{{ old('motivo') }}</textarea>
                                    @error('motivo') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            
                            <!-- COLUMNA 2 -->
                            <div class="cita-col-right">
                                <h5 class="section-title">2. Disponibilidad</h5>

                                <div class="form-group">
                                    <label for="veterinario_id" class="form-label">Veterinario</label>
                                    <select name="veterinario_id" id="veterinario_id" class="form-control" required>
                                        <option value="" selected disabled>-- Selecciona un Doctor --</option>
                                        @foreach($veterinarios as $vet)
                                            <option value="{{ $vet->id }}" {{ old('veterinario_id') == $vet->id ? 'selected' : '' }}>
                                                {{ $vet->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('veterinario_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                </div>

                                <div class="form-group">
                                    <label for="fecha_selector" class="form-label">Fecha</label>
                                    <input type="date" id="fecha_selector" class="form-control" min="{{ date('Y-m-d') }}" required disabled>
                                    <small class="text-muted d-block mt-1">Selecciona veterinario primero.</small>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Horarios Disponibles</label>
                                    <div id="slots-container" class="slots-container">
                                        <div class="slot-message">Selecciona doctor y fecha.</div>
                                    </div>
                                    <input type="hidden" name="fecha_hora" id="fecha_hora_final" required>
                                    @error('fecha_hora') <div class="text-danger mt-1 fw-bold">Selecciona una hora.</div> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- RESUMEN PRECIO (Usa clases de citas.css) -->
                        <div class="price-ticket" style="margin-top: 20px; display: flex; justify-content: space-between;">
                            <div>
                                <span class="label">Servicio</span>
                                <div class="text-muted" id="service-name-display">--</div>
                            </div>
                            <div class="text-end">
                                <span class="label">Total</span>
                                <div class="value" id="total-price">0.00 €</div>
                                <span id="service-price" style="display:none;">0</span>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mt-4 pt-3 border-top" style="justify-content: flex-end; gap: 15px;">
                            <a href="{{ route('citas.index') }}" class="btn btn-secondary text-white" style="text-decoration: none; padding: 0.75rem 1.5rem; border-radius: 6px; background-color: #6c757d;">Cancelar</a>
                            <button type="submit" class="btn-save"><i class="bi bi-calendar-check"></i> Confirmar Cita</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>