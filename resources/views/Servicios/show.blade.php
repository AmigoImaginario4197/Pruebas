<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Servicio - Pet Care</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="panel-container">     
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title">
                    <h3>{{ $servicio->nombre }}</h3>
                    <p>Información detallada del servicio.</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('servicios.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver al Catálogo
                    </a>
                </div>
            </header>

            <div class="panel-content">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h5 class="card-title">Detalles del Servicio</h5>
                                
                                <div class="mb-3">
                                    <label class="text-muted text-uppercase small fw-bold">Nombre del Servicio</label>
                                    <p class="fs-5">{{ $servicio->nombre }}</p>
                                </div>

                                <div class="mb-3">
                                    <label class="text-muted text-uppercase small fw-bold">Descripción</label>
                                    <p>{{ $servicio->descripcion ?? 'Sin descripción.' }}</p>
                                </div>
                            </div>

                            <div class="col-md-4 border-start">
                                <div class="mb-3">
                                    <label class="text-muted text-uppercase small fw-bold">Precio Actual</label>
                                    <p class="fs-2 text-success fw-bold">${{ number_format($servicio->precio, 2) }}</p>
                                </div>
                                
                                <div class="d-grid gap-2 mt-4">
                                    <a href="{{ route('servicios.edit', $servicio) }}" class="btn btn-warning">
                                        <i class="bi bi-pencil"></i> Editar Servicio
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>