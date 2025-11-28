<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Nuevo Medicamento - Pet Care</title>
    
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
                    <h3>Añadir Nuevo Medicamento</h3>
                    <p>Rellena los datos para añadir un nuevo artículo al catálogo.</p>
                </div>
            </header>

            <div class="panel-content">
                <div class="card">
                    {{-- El formulario debe tener 'enctype' para permitir la subida de archivos --}}
                    <form action="{{ route('medicamentos.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger mb-4">
                                    <h5 class="alert-heading">Error de Validación</h5>
                                    <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                                </div>
                            @endif

                            <div class="row">
                                {{-- Nombre del Medicamento --}}
                                <div class="col-12 mb-3">
                                    <label for="nombre" class="form-label">Nombre del Medicamento</label>
                                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                                    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                
                                {{-- Dosis Recomendada --}}
                                <div class="col-12 mb-3">
                                    <label for="dosis_recomendada" class="form-label">Dosis Recomendada</label>
                                    <input type="text" class="form-control @error('dosis_recomendada') is-invalid @enderror" id="dosis_recomendada" name="dosis_recomendada" value="{{ old('dosis_recomendada') }}" placeholder="Ej: 1 comprimido cada 12 horas">
                                    @error('dosis_recomendada')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                {{-- Descripción --}}
                                <div class="col-12 mb-3">
                                    <label for="descripcion" class="form-label">Descripción (Opcional)</label>
                                    <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="4">{{ old('descripcion') }}</textarea>
                                    @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                {{-- Campo para subir la Foto --}}
                                <div class="col-12 mb-3">
                                    <label for="foto" class="form-label">Foto del Medicamento (Opcional)</label>
                                    <input class="form-control @error('foto') is-invalid @enderror" type="file" id="foto" name="foto">
                                    @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <a href="{{ route('medicamentos.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar Medicamento</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>