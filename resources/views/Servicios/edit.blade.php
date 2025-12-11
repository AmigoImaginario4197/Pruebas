<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Servicio - Pet Care</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/servicios-form.css') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="panel-container">     
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title">
                    <h3>Editar Servicio</h3>
                    <p>Modificando: <strong>{{ $servicio->nombre }}</strong></p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('servicios.index') }}" class="btn btn-secondary text-white">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </header>

            <div class="panel-content">
                <div class="service-card">
                    
                    <form action="{{ route('servicios.update', $servicio->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="service-header-banner">
                            <h4 style="color: #d97706;">
                                <i class="bi bi-pencil-square" style="font-size: 1.5rem;"></i> 
                                Editar Procedimiento
                            </h4>
                        </div>

                        <div class="service-body">
                            
                            @if ($errors->any())
                                <div class="alert alert-danger mb-4">
                                    <ul class="mb-0 small">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="service-main-grid">
                                
                                <!-- Nombre -->
                                <div>
                                    <label for="nombre" class="form-label">Nombre del Servicio <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text input-icon-text"><i class="bi bi-scissors"></i></span>
                                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $servicio->nombre) }}" required>
                                    </div>
                                </div>

                                <!-- Precio -->
                                <div>
                                    <label for="precio" class="form-label">Costo ($) <span class="text-danger">*</span></label>
                                    <div class="input-group price-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" class="form-control @error('precio') is-invalid @enderror" id="precio" name="precio" value="{{ old('precio', $servicio->precio) }}" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Descripción -->
                            <div class="mb-2">
                                <label for="descripcion" class="form-label">Detalles de lo que incluye</label>
                                <textarea class="form-control desc-area @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion">{{ old('descripcion', $servicio->descripcion) }}</textarea>
                            </div>

                        </div>

                        <div class="service-footer">
                            <a href="{{ route('servicios.index') }}" class="btn btn-light border">Cancelar</a>
                            <button type="submit" class="btn btn-warning" style="padding-left: 2rem; padding-right: 2rem; color: #000; font-weight: 600;">
                                <i class="bi bi-check-circle me-1"></i> Actualizar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>