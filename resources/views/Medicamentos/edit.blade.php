<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar: {{ $medicamento->nombre }} - Pet Care</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    
    <!-- 1. Reutilizamos el CSS de medicamentos -->
    <link rel="stylesheet" href="{{ asset('css/medicamentos-form.css') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="panel-container">     
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title">
                    <h3>Editar Medicamento</h3>
                    <p>Modificando datos de: <strong>{{ $medicamento->nombre }}</strong></p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('medicamentos.index') }}" class="btn btn-secondary text-white">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </header>

            <div class="panel-content">
                <div class="med-card">
                    
                    <form action="{{ route('medicamentos.update', $medicamento) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="med-header">
                            <!-- Color naranja para indicar edición -->
                            <h4 style="color: #d97706;"><i class="bi bi-pencil-square"></i> Editar Datos</h4>
                        </div>

                        <!-- Errores -->
                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <ul class="mb-0 small">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                            </div>
                        @endif

                        <div class="med-grid-layout">
                            
                            <!-- COLUMNA 1: FORMULARIO -->
                            <div class="form-section">
                                
                                <!-- Nombre -->
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre del Medicamento <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $medicamento->nombre) }}" required>
                                    </div>
                                    @error('nombre')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>
                                
                                <!-- Dosis -->
                                <div class="mb-3">
                                    <label for="dosis_recomendada" class="form-label">Dosis Recomendada</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-prescription2"></i></span>
                                        <input type="text" class="form-control @error('dosis_recomendada') is-invalid @enderror" id="dosis_recomendada" name="dosis_recomendada" value="{{ old('dosis_recomendada', $medicamento->dosis_recomendada) }}">
                                    </div>
                                    @error('dosis_recomendada')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>

                                <!-- Descripción -->
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción</label>
                                    <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="5">{{ old('descripcion', $medicamento->descripcion) }}</textarea>
                                    @error('descripcion')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <!-- COLUMNA 2: IMAGEN (Lógica inteligente) -->
                            <div class="image-section">
                                <label class="form-label d-block text-center">Fotografía</label>

                                <label for="foto" class="image-upload-zone">
                                    
                                    {{-- LÓGICA: Si ya hay foto, la mostramos y ocultamos el placeholder --}}
                                    @if($medicamento->foto)
                                        <img id="preview" src="{{ asset('storage/' . $medicamento->foto) }}" class="preview-img" style="display: block;">
                                        
                                        {{-- El placeholder se renderiza con opacity 0 para que no estorbe --}}
                                        <div class="upload-placeholder" style="opacity: 0;">
                                    @else
                                        {{-- Si no hay foto, comportamiento normal --}}
                                        <img id="preview" src="#" class="preview-img">
                                        <div class="upload-placeholder">
                                    @endif
                                            
                                        <i class="bi bi-cloud-arrow-up upload-icon"></i>
                                        <p class="mb-0 fw-bold">Cambiar Imagen</p>
                                        <small class="text-muted">Haz clic para reemplazar</small>
                                    </div>

                                    <input type="file" id="foto" name="foto" accept="image/*" class="d-none">
                                </label>
                                @error('foto')<div class="text-danger small mt-1 text-center">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <!-- FOOTER -->
                        <div class="med-footer">
                            <a href="{{ route('medicamentos.index') }}" class="btn btn-light border">Cancelar</a>
                            
                            <button type="submit" class="btn btn-warning" style="font-weight: 600;">
                                <i class="bi bi-check-circle me-1"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <!-- 2. Cargamos el mismo JS. Funciona porque busca el ID 'foto' y el ID 'preview' que hemos mantenido -->
    <script src="{{ asset('js/medicamentos-preview.js') }}"></script>
</body>
</html>