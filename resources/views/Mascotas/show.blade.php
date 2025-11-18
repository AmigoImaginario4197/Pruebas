<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- El título es dinámico --}}
    <title>Detalles de {{ $mascota->nombre }} - Pet Care</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="panel-container">
        
        @include('layouts.sidebar')

        <main class="panel-main">
            {{-- Header de la página --}}
            <header class="panel-header">
                <div class="header-title">
                    <h3>Detalles de {{ $mascota->nombre }}</h3>
                    <p>Aquí puedes ver toda la información de tu mascota.</p>
                </div>
                <div class="header-actions">
                    {{-- Botón para volver al listado --}}
                    <a href="{{ route('mascotas.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left-circle"></i> Volver al listado
                    </a>
                </div>
            </header>

            {{-- Contenido principal de la página --}}
            <div class="panel-content">
                <div class="card p-4">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img src="{{ $mascota->foto ? asset('storage/' . $mascota->foto) : asset('images/placeholder-pet.png') }}" 
                                 alt="Foto de {{ $mascota->nombre }}" 
                                 class="img-fluid rounded-circle" 
                                 style="max-height: 200px; max-width: 200px; object-fit: cover; border: 4px solid #f8f9fa;">
                        </div>
                        <div class="col-md-8">
                            <h3>{{ $mascota->nombre }}</h3>
                            <hr>
                            <p><strong>Especie:</strong> {{ $mascota->especie }}</p>
                            <p><strong>Raza:</strong> {{ $mascota->raza ?? 'No especificada' }}</p>
                            <p><strong>Edad:</strong> {{ $mascota->edad !== null ? $mascota->edad . ' años' : 'No especificada' }}</p>
                            <p><strong>Peso:</strong> {{ $mascota->peso !== null ? $mascota->peso . ' kg' : 'No especificado' }}</p>
                            <p><strong>Fecha de Nacimiento:</strong> {{ $mascota->fecha_nacimiento ? \Carbon\Carbon::parse($mascota->fecha_nacimiento)->format('d/m/Y') : 'No especificada' }}</p>
                            
                            <div class="mt-4">
                                <a href="{{ route('mascotas.edit', $mascota) }}" class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> Editar Perfil
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>