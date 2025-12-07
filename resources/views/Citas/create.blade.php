<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Cita - Pet Care</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/citas.js'])
</head>
<body class="font-sans antialiased">
    <div class="panel-container">     
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title">
                    <h3>Agendar Nueva Cita</h3>
                    <p>Reserva una consulta para una mascota.</p>
                </div>
            </header>

            <div class="panel-content">
                <div class="card shadow-sm border-0 p-4" style="max-width: 800px; margin: 0 auto;">
                    <form action="{{ route('citas.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 pe-md-4">
                                <h5 class="mb-4 border-bottom pb-2">Detalles de la Cita</h5>

                                {{-- Selector de Veterinario --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Veterinario</label>
                                    <select name="veterinario_id" id="veterinario_id" class="form-select" required>
                                        <option value="" selected disabled>-- Selecciona un Doctor --</option>
                                        @foreach($veterinarios as $vet)
                                            <option value="{{ $vet->id }}" {{ old('veterinario_id') == $vet->id ? 'selected' : '' }}>
                                                Dr. {{ $vet->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Selector de Fecha --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Fecha de la Cita</label>
                                    <input type="date" id="fecha_selector" class="form-control" min="{{ date('Y-m-d') }}" required>
                                    <small class="text-muted">Selecciona un veterinario y una fecha para ver horas disponibles.</small>
                                </div>

                                {{-- Contenedor de Huecos (Slots) --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Horarios Disponibles</label>
                                    <div id="slots-container" class="d-flex flex-wrap gap-2 p-3 border rounded bg-light" style="min-height: 80px;">
                                        <span class="text-muted fst-italic">Esperando datos...</span>
                                    </div>
                                    
                                    {{-- INPUT OCULTO: Aquí se guardará la fecha y hora final para enviar al backend --}}
                                    <input type="hidden" name="fecha_hora" id="fecha_hora_final" required>
                                    @error('fecha_hora')
                                        <div class="text-danger mt-1 small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 ps-md-4 border-start">
                                <h5 class="mb-4 border-bottom pb-2">Información de la Mascota</h5>

                                {{-- Selector de Mascota --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Mascota</label>
                                    <select name="mascota_id" class="form-select" required>
                                        <option value="" selected disabled>-- Selecciona una Mascota --</option>
                                        @foreach($mascotas as $mascota)
                                            <option value="{{ $mascota->id }}" {{ old('mascota_id') == $mascota->id ? 'selected' : '' }}>
                                                {{ $mascota->nombre }} ({{ $mascota->especie }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Selector de Servicio --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Servicio</label>
                                    <select name="servicio_id" id="servicio_id" class="form-select" required>
                                        <option value="" selected disabled>-- Selecciona un Servicio --</option>
                                        @foreach($servicios as $servicio)
                                            <option 
                                                value="{{ $servicio->id }}" 
                                                data-price="{{ $servicio->precio }}"
                                                {{ old('servicio_id') == $servicio->id ? 'selected' : '' }}
                                            >
                                                {{ $servicio->nombre }} - ${{ number_format($servicio->precio, 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Resumen de Precio --}}
                                <div class="mb-3 p-3 bg-light rounded">
                                    <div class="d-flex justify-content-between">
                                        <span>Precio del Servicio:</span>
                                        <strong id="service-price">$0.00</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mt-2">
                                        <span class="fw-bold">Total:</span>
                                        <strong id="total-price" class="text-primary">$0.00</strong>
                                    </div>
                                </div>

                                {{-- Motivo de la Consulta --}}
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Motivo de la Consulta</label>
                                    <textarea name="motivo" class="form-control" rows="3" placeholder="Describe brevemente el motivo de la consulta...">{{ old('motivo') }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- BOTÓN DE ENVÍO --}}
                        <div class="mt-4 text-end">
                            <a href="{{ route('citas.index') }}" class="btn btn-light me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Agendar Cita</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>