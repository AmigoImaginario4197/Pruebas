<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar: {{ $medicamento->nombre }} - Pet Care</title>
    
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
                    <h3>Editar Medicamento: {{ $medicamento->nombre }}</h3>
                    <p>Modifica los datos del medicamento.</p>
                </div>
            </header>

            <div class="panel-content">
                <div class="card">
                    {{-- El formulario apunta a 'update' y usa el método PATCH --}}
                    <form action="{{ route('medicamentos.update', $medicamento) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

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
                                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $medicamento->nombre) }}" required>
                                    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                
                                {{-- Dosis Recomendada --}}
                                <div class="col-12 mb-3">
                                    <label for="dosis_recomendada" class="form-label">Dosis Recomendada</label>
                                    <input type="text" class="form-control @error('dosis_recomendada') is-invalid @enderror" id="dosis_recomendada" name="dosis_recomendada" value="{{ old('dosis_recomendada', $medicamento->dosis_recomendada) }}">
                                    @error('dosis_recomendada')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                {{-- Descripción --}}
                                <div class="col-12 mb-3">
                                    <label for="descripcion" class="form-label">Descripción (Opcional)</label>
                                    <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="4">{{ old('descripcion', $medicamento->descripcion) }}</textarea>
                                    @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                {{-- Campo para cambiar la Foto --}}
                                <div class="col-12 mb-3">
                                    <label for="foto" class="form-label">Cambiar Foto (Opcional)</label>
                                    <input class="form-control @error('foto') is-invalid @enderror" type="file" id="foto" name="foto">
                                    @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror

                                    {{-- Muestra la foto actual si existe --}}
                                    @if($medicamento->foto)
                                        <div class="mt-3">
                                            <p class="mb-1"><small>Foto actual:</small></p>
                                            <img src="{{ asset('storage/' . $medicamento->foto) }}" alt="Foto de {{ $medicamento->nombre }}" class="img-thumbnail" style="max-width: 150px;">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <a href="{{ route('medicamentos.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>