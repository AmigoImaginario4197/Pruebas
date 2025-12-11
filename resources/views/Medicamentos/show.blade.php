<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles: {{ $medicamento->nombre }} - Pet Care</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    
    <!-- IMPORTANTE: Tu CSS personalizado -->
    <link rel="stylesheet" href="{{ asset('css/medicamentos-form.css') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Pequeños ajustes solo para lectura (para que el texto se vea bonito) -->
    <style>
        .view-label {
            color: #9ca3af; /* Gris suave */
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
            display: block;
        }
        .view-text {
            color: #374151; /* Gris oscuro */
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }
        .dose-badge {
            background-color: #f0fdfa; /* Fondo turquesa suave (coincide con tu tema) */
            color: #0d9488; /* Texto turquesa */
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            border: 1px solid #ccfbf1;
            display: inline-block;
        }
        /* Ajuste para que la caja de imagen parezca de "solo lectura" y no un input */
        .read-only-image {
            cursor: default !important;
            border-style: solid !important; /* Borde sólido en vez de dashed */
            background-color: #fff !important;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="panel-container">     
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title">
                    <h3>Detalles del Producto</h3>
                    <p>Ficha técnica: <strong>{{ $medicamento->nombre }}</strong></p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('medicamentos.index') }}" class="btn btn-secondary text-white">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </header>

            <div class="panel-content">
                
                <!-- Usamos la tarjeta de tu CSS -->
                <div class="med-card">
                    
                    <div class="med-header">
                        <h4><i class="bi bi-info-circle"></i> Información Clínica</h4>
                    </div>

                    <!-- Usamos tu Grid Layout (Datos Izq / Foto Der) -->
                    <div class="med-grid-layout">
                        
                        <!-- COLUMNA 1: Datos -->
                        <div>
                            <!-- Nombre -->
                            <div class="mb-4">
                                <span class="view-label">Nombre Comercial</span>
                                <h2 style="margin: 0; color: #111827;">{{ $medicamento->nombre }}</h2>
                            </div>

                            <!-- Dosis -->
                            <div class="mb-4">
                                <span class="view-label"><i class="bi bi-prescription2"></i> Dosis Recomendada</span>
                                @if($medicamento->dosis_recomendada)
                                    <div class="dose-badge">
                                        {{ $medicamento->dosis_recomendada }}
                                    </div>
                                @else
                                    <span class="text-muted fst-italic">No especificada</span>
                                @endif
                            </div>

                            <!-- Descripción -->
                            <div class="mb-4">
                                <span class="view-label">Descripción / Indicaciones</span>
                                <div class="view-text">
                                    {{ $medicamento->descripcion ?? 'Sin descripción disponible.' }}
                                </div>
                            </div>

                            <!-- Meta info -->
                            <div class="pt-3 border-top">
                                <small class="text-muted">
                                    Registrado el: {{ $medicamento->created_at->format('d/m/Y') }}
                                </small>
                            </div>
                        </div>

                        <!-- COLUMNA 2: Foto (Reutilizando .image-upload-zone con estilo fijo) -->
                        <div>
                            <span class="view-label text-center">Fotografía del Producto</span>
                            
                            <!-- Añadimos clase .read-only-image para quitar el aspecto de "click para subir" -->
                            <div class="image-upload-zone read-only-image">
                                @if($medicamento->foto)
                                    <img src="{{ asset('storage/' . $medicamento->foto) }}" class="preview-img" style="display: block; position: static;">
                                @else
                                    <!-- Placeholder si no hay foto -->
                                    <div style="opacity: 0.5;">
                                        <i class="bi bi-capsule-pill" style="font-size: 4rem; color: #d1d5db;"></i>
                                        <p class="mt-2 text-muted fw-bold">Sin imagen</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>

                    <!-- FOOTER (Solo para Admin) -->
                    @if(Auth::check() && Auth::user()->role === 'admin')
                        <div class="med-footer">
                            
                            <form action="{{ route('medicamentos.destroy', $medicamento->id) }}" method="POST" onsubmit="return confirm('¿Eliminar permanentemente este medicamento?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </form>

                            <a href="{{ route('medicamentos.edit', $medicamento) }}" class="btn btn-warning" style="font-weight: 600;">
                                <i class="bi bi-pencil-square"></i> Editar Medicamento
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </main>
    </div>
</body>
</html>