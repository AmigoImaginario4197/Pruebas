<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Cita - Pet Care</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    
    {{-- Vite cargará app.js, que a su vez carga cita-form-manager.js --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                <form action="{{ route('citas.store') }}" method="POST">
                    @csrf
                    
                    <div class="row">
                        {{-- COLUMNA IZQUIERDA: DATOS DE LA CITA --}}
                        <div class="@if(Auth::user()->isCliente()) col-md-8 @else col-md-12 @endif">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Detalles de la Consulta</h5>
                                    
                                    @if ($errors->any())
                                        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                                    @endif

                                    <div class="row">
                                        {{-- Selector de Mascota --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="mascota_id" class="form-label">Paciente (Mascota)</label>
                                            <select name="mascota_id" id="mascota_id" class="form-select" required>
                                                <option value="">-- Seleccionar Mascota --</option>
                                                @foreach ($mascotas as $mascota)
                                                    <option value="{{ $mascota->id }}" @selected(old('mascota_id') == $mascota->id)>
                                                        {{ $mascota->nombre }} ({{ $mascota->especie }})
                                                        @if(!Auth::user()->isCliente()) - Dueño: {{ $mascota->usuario->name }} @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Selector de Servicio (Con data-price para JS) --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="servicio_id" class="form-label">Tipo de Servicio</label>
                                            <select name="servicio_id" id="servicio_id" class="form-select" required>
                                                <option value="" data-price="0">-- Seleccionar Servicio --</option>
                                                @foreach ($servicios as $servicio)
                                                    <option value="{{ $servicio->id }}" data-price="{{ $servicio->precio }}" @selected(old('servicio_id') == $servicio->id)>
                                                        {{ $servicio->nombre }} - ${{ number_format($servicio->precio, 2) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Selector de Veterinario --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="veterinario_id" class="form-label">Veterinario</label>
                                            <select name="veterinario_id" id="veterinario_id" class="form-select" required>
                                                <option value="">-- Seleccionar Doctor --</option>
                                                @foreach ($veterinarios as $veterinario)
                                                    <option value="{{ $veterinario->id }}" @selected(old('veterinario_id') == $veterinario->id)>
                                                        Dr. {{ $veterinario->name }} @if($veterinario->especialidad) ({{ $veterinario->especialidad }}) @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Fecha y Hora --}}
                                        <div class="col-md-6 mb-3">
                                            <label for="fecha_hora" class="form-label">Fecha y Hora</label>
                                            <input type="datetime-local" name="fecha_hora" id="fecha_hora" class="form-control" value="{{ old('fecha_hora') }}" required min="{{ now()->format('Y-m-d\TH:i') }}">
                                        </div>

                                        {{-- Motivo --}}
                                        <div class="col-12 mb-3">
                                            <label for="motivo" class="form-label">Motivo / Notas adicionales</label>
                                            <textarea name="motivo" id="motivo" class="form-control" rows="2" placeholder="Ej: Revisión anual..." required>{{ old('motivo') }}</textarea>
                                        </div>
                                    </div>
                                    
                                    {{-- Botón para Admin/Vet (sin pago visual) --}}
                                    @if(!Auth::user()->isCliente())
                                        <div class="text-end">
                                            <a href="{{ route('citas.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                                            <button type="submit" class="btn btn-primary">Agendar Cita</button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- COLUMNA DERECHA: RESUMEN DE PAGO (SOLO CLIENTES) --}}
                        @if(Auth::user()->isCliente())
                            <div class="col-md-4">
                                <div class="card bg-light border-0 shadow-sm">
                                    <div class="card-body">
                                        <h5 class="card-title">Resumen de Pago</h5>
                                        <hr>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Servicio Base</span>
                                            {{-- El ID es usado por cita-form-manager.js --}}
                                            <strong id="service-price">$0.00</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2"><span class="text-muted">Impuestos</span><span class="text-muted">$0.00</span></div>
                                        <hr>
                                        <div class="d-flex justify-content-between mb-4"><strong class="fs-5">Total a Pagar</strong><strong class="fs-5 text-primary" id="total-price">$0.00</strong></div>

                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-credit-card-2-front"></i> Pagar y Reservar</button>
                                            <a href="{{ route('citas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                                        </div>
                                        <div class="mt-3 text-center text-muted" style="font-size: 0.8rem;"><i class="bi bi-shield-lock"></i> Pago seguro con Stripe</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>