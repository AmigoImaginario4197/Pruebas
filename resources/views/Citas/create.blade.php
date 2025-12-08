<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Cita - Pet Care</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/citas.css') }}">
    
    {{-- Llamamos a citas.js, que ahora maneja toda la lógica --}}
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/citas.js'])
</head>
<body class="font-sans antialiased">
    <div class="panel-container">     
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <h3>Agendar Nueva Cita</h3>
            </header>

            <div class="panel-content">
                <div class="card shadow-sm border-0 p-4" style="max-width: 800px; margin: 0 auto;">
                    
                    <form action="{{ route('citas.store') }}" method="POST">
                        @csrf

                        {{-- DIV OCULTO PARA ERRORES DEL BACKEND (Leído por JS) --}}
                        @if ($errors->any())
                            <div id="backend-errors" style="display: none;">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            {{-- COLUMNA IZQUIERDA --}}
                            <div class="col-md-6 pe-md-4">
                                <h5 class="mb-4 border-bottom pb-2">1. Detalles de la Cita</h5>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Mascota</label>
                                    <select name="mascota_id" class="form-select" required>
                                        <option value="" selected disabled>-- Elige una Mascota --</option>
                                        @foreach($mascotas as $mascota)
                                            <option value="{{ $mascota->id }}">{{ $mascota->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Servicio</label>
                                    <select name="servicio_id" id="servicio_id" class="form-select" required>
                                        <option value="" selected disabled>-- Elige un Servicio --</option>
                                        @foreach($servicios as $servicio)
                                            <option value="{{ $servicio->id }}" data-price="{{ $servicio->precio }}">{{ $servicio->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Motivo</label>
                                    <textarea name="motivo" class="form-control" rows="4" placeholder="Describe brevemente el motivo..." required>{{ old('motivo') }}</textarea>
                                </div>
                            </div>
                            
                            {{-- COLUMNA DERECHA --}}
                            <div class="col-md-6 ps-md-4 border-start">
                                <h5 class="mb-4 border-bottom pb-2">2. Horario y Profesional</h5>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Veterinario</label>
                                    <select name="veterinario_id" id="veterinario_id" class="form-select" required>
                                        <option value="" selected disabled>-- Elige un Doctor --</option>
                                        @foreach($veterinarios as $vet)
                                            <option value="{{ $vet->id }}">{{ $vet->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Fecha</label>
                                    <input type="date" id="fecha_selector" class="form-control" min="{{ date('Y-m-d') }}" required disabled>
                                    <small class="text-muted">Elige un veterinario para habilitar la fecha.</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Horarios Disponibles</label>
                                    <div id="slots-container" class="slots-container">
                                        <span class="text-muted fst-italic w-100 text-center">Esperando...</span>
                                    </div>
                                    <input type="hidden" name="fecha_hora" id="fecha_hora_final" required>
                                </div>
                            </div>
                        </div>

                        @if(Auth::user()->isCliente())
                        <div class="mt-3 p-3 bg-light rounded d-flex justify-content-between align-items-center">
                            <span>Precio del Servicio: <strong id="service-price">$0.00</strong></span>
                            <h5 class="mb-0">Total a Pagar: <strong id="total-price" class="text-primary">$0.00</strong></h5>
                        </div>
                        @endif

                        <div class="mt-4 text-end">
                            <a href="{{ route('citas.index') }}" class="btn btn-light me-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Agendar y Proceder al Pago</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>