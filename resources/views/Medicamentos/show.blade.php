<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles: {{ $medicamento->nombre }} - Pet Care</title>
    
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
                    <h3>{{ $medicamento->nombre }}</h3>
                    <p>Información detallada del medicamento.</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('medicamentos.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Volver al Catálogo
                    </a>
                </div>
            </header>

            <div class="panel-content">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            {{-- Columna para la foto --}}
                            <div class="col-md-4 text-center">
                                @if($medicamento->foto)
                                    <img src="{{ asset('storage/' . $medicamento->foto) }}" alt="Foto de {{ $medicamento->nombre }}" class="img-fluid rounded mb-3" style="max-height: 250px;">
                                @else
                                    <div class="item-photo-placeholder mx-auto" style="width: 200px; height: 200px; font-size: 5rem;">
                                        <i class="bi bi-capsule-pill"></i>
                                    </div>
                                    <p class="text-muted mt-2">Sin foto</p>
                                @endif
                            </div>

                            {{-- Columna para los detalles del texto --}}
                            <div class="col-md-8">
                                <h5 class="card-title">Detalles del Medicamento</h5>
                                <p><strong>Nombre:</strong> {{ $medicamento->nombre }}</p>
                                <p><strong>Dosis Recomendada:</strong> {{ $medicamento->dosis_recomendada ?? 'No especificada' }}</p>
                                
                                <hr>

                                <p><strong>Descripción:</strong></p>
                                <p>{{ $medicamento->descripcion ?? 'No hay descripción disponible.' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    {{-- El pie de página con el botón de editar solo lo ve el Admin --}}
                    @if(Auth::user()->isAdmin())
                        <div class="card-footer text-end">
                            <a href="{{ route('medicamentos.edit', $medicamento) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Editar Medicamento
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</body>
</html>