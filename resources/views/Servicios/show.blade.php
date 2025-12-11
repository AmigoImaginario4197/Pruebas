<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle: {{ $servicio->nombre }} - Pet Care</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    <!-- Reutilizamos el CSS de Servicios -->
    <link rel="stylesheet" href="{{ asset('css/servicios-form.css') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Estilos específicos para la vista de lectura (SHOW) */
        .detail-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 40px;
        }
        .detail-label {
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.75rem;
            font-weight: 700;
            color: #9ca3af; /* Gris claro */
            margin-bottom: 0.5rem;
            display: block;
        }
        .detail-text {
            font-size: 1.1rem;
            color: #374151;
            line-height: 1.6;
        }
        
        /* Caja del Precio Destacada */
        .price-box {
            background-color: #f0fdf4; /* Verde muy pálido */
            border: 2px dashed #86efac; /* Borde punteado verde */
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .price-value {
            font-size: 3rem; /* Muy grande */
            font-weight: 800;
            color: #166534; /* Verde oscuro */
            line-height: 1;
        }
        .price-currency {
            font-size: 1.5rem;
            vertical-align: super;
            opacity: 0.7;
        }

        @media (max-width: 768px) {
            .detail-grid { grid-template-columns: 1fr; }
            .price-box { padding: 1.5rem; margin-top: 1rem; }
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="panel-container">     
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title">
                    <h3>Detalles del Servicio</h3>
                    <p>Ficha técnica del procedimiento.</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('servicios.index') }}" class="btn btn-secondary text-white">
                        <i class="bi bi-arrow-left"></i> Volver al Catálogo
                    </a>
                </div>
            </header>

            <div class="panel-content">
                <!-- Usamos la misma tarjeta .service-card -->
                <div class="service-card">
                    
                    <!-- Banner de Título -->
                    <div class="service-header-banner">
                        <h4 style="font-size: 1.5rem; color: #1f2937;">
                            <!-- Icono dinámico simple -->
                            <i class="bi bi-tag-fill text-primary"></i> 
                            {{ $servicio->nombre }}
                        </h4>
                    </div>

                    <div class="service-body">
                        
                        <!-- Grid Personalizado: Detalles a la izquierda, Precio a la derecha -->
                        <div class="detail-grid">
                            
                            <!-- COLUMNA IZQUIERDA: Descripción -->
                            <div>
                                <div class="mb-4">
                                    <span class="detail-label"><i class="bi bi-info-circle"></i> Descripción del Procedimiento</span>
                                    <div class="detail-text">
                                        @if($servicio->descripcion)
                                            {{ $servicio->descripcion }}
                                        @else
                                            <span class="text-muted fst-italic">No se ha proporcionado una descripción detallada para este servicio.</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <span class="detail-label"><i class="bi bi-upc-scan"></i> ID de Referencia</span>
                                    <div class="detail-text font-monospace text-muted">
                                        SRV-{{ str_pad($servicio->id, 4, '0', STR_PAD_LEFT) }}
                                    </div>
                                </div>
                            </div>

                            <!-- COLUMNA DERECHA: Precio Destacado -->
                            <div>
                                <div class="price-box">
                                    <span class="detail-label" style="color: #166534;">Costo Actual</span>
                                    <div class="price-value">
                                        <span class="price-currency">$</span>{{ number_format($servicio->precio, 2) }}
                                    </div>
                                    <small class="text-muted mt-2">Precio por unidad/sesión</small>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Footer con Botones -->
                    <div class="service-footer">
                        
                        <!-- Botón Borrar (Visible solo para Admin) -->
                        @if(Auth::check() && Auth::user()->rol === 'admin')
                            <form action="{{ route('servicios.destroy', $servicio->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este servicio?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('servicios.edit', $servicio) }}" class="btn btn-warning" style="color:#000; font-weight:600;">
                            <i class="bi bi-pencil-square"></i> Editar Servicio
                        </a>
                    </div>

                </div>
            </div>
        </main>
    </div>
</body>
</html>