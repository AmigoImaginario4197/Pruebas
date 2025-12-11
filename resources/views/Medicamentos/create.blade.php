<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Medicamento - Pet Care</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    
    <!-- 1. Enlazamos nuestro CSS separado -->
    <link rel="stylesheet" href="{{ asset('css/medicamentos-form.css') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="panel-container">     
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title">
                    <h3>Registrar Medicamento</h3>
                    <p>Añade un nuevo producto al inventario farmacéutico.</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('medicamentos.index') }}" class="btn btn-secondary text-white">
                        <i class="bi bi-arrow-left"></i> Cancelar
                    </a>
                </div>
            </header>

            <div class="panel-content">
                <div class="med-card">
                    {{-- enctype es OBLIGATORIO para subir fotos --}}
                    <form action="{{ route('medicamentos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="med-header">
                            <h4><i class="bi bi-capsule"></i> Datos del Producto</h4>
                        </div>

                        <!-- Errores generales -->
                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <ul class="mb-0 small">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- GRID: DATOS (Izquierda) - IMAGEN (Derecha) -->
                        <div class="med-grid-layout">
                            
                            <!-- COLUMNA 1: FORMULARIO -->
                            <div class="form-section">
                                
                                <!-- Nombre -->
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre Comercial <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" placeholder="Ej: Amoxicilina 500mg" required>
                                    </div>
                                    @error('nombre')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>

                                <!-- Dosis -->
                                <div class="mb-3">
                                    <label for="dosis_recomendada" class="form-label">Dosis / Posología</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-prescription2"></i></span>
                                        <input type="text" class="form-control @error('dosis_recomendada') is-invalid @enderror" id="dosis_recomendada" name="dosis_recomendada" value="{{ old('dosis_recomendada') }}" placeholder="Ej: 1 pastilla cada 8 horas">
                                    </div>
                                    <small class="text-muted">Instrucciones generales de uso.</small>
                                </div>

                                <!-- Descripción -->
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción Detallada</label>
                                    <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="5" placeholder="Composición, indicaciones...">{{ old('descripcion') }}</textarea>
                                </div>
                            </div>

                            <!-- COLUMNA 2: PREVIEW IMAGEN -->
                            <div class="image-section">
                                <label class="form-label d-block text-center">Fotografía del Producto</label>
                                
                                <!-- El label actúa como botón para el input file oculto -->
                                <label for="foto" class="image-upload-zone">
                                    
                                    <!-- Imagen vacía por defecto (se llena con JS) -->
                                    <img id="preview" src="#" alt="Vista previa" class="preview-img">
                                    
                                    <!-- Texto de ayuda (se oculta con JS) -->
                                    <div class="upload-placeholder">
                                        <i class="bi bi-cloud-arrow-up upload-icon"></i>
                                        <p class="mb-0 fw-bold">Haz clic para subir imagen</p>
                                        <small class="text-muted">Formatos: JPG, PNG</small>
                                    </div>

                                    <!-- Input oculto -->
                                    <input type="file" id="foto" name="foto" accept="image/*" class="d-none">
                                </label>
                                @error('foto')<div class="text-danger small mt-1 text-center">{{ $message }}</div>@enderror
                            </div>

                        </div>

                        <!-- FOOTER -->
                        <div class="med-footer">
                            <a href="{{ route('medicamentos.index') }}" class="btn btn-light border">Descartar</a>
                            <button type="submit" class="btn btn-primary" style="background-color: #0d9488; border:none;">
                                <i class="bi bi-save me-1"></i> Guardar Medicamento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <!-- 2. Enlazamos nuestro JS separado al final del body -->
    <script src="{{ asset('js/medicamentos-preview.js') }}"></script>
</body>
</html>