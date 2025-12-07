<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Editar Tarea - Pet Care</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
</head>
<body class="font-sans antialiased">
    <div class="panel-container">
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title">
                    <h3>Editar Tarea</h3>
                </div>
            </header>

            <div class="panel-content">
                <div class="card shadow-sm border-0 p-4" style="max-width: 800px; margin: 0 auto;">
                    <form action="{{ route('tareas.update', $tarea) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Título</label>
                            <input type="text" name="titulo" class="form-control" value="{{ $tarea->titulo }}" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Fecha Inicio</label>
                                <input type="datetime-local" name="inicio" class="form-control" 
                                       value="{{ $tarea->inicio->format('Y-m-d\TH:i') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Fecha Fin</label>
                                <input type="datetime-local" name="fin" class="form-control" 
                                       value="{{ $tarea->fin->format('Y-m-d\TH:i') }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Color</label>
                            <input type="color" name="color" class="form-control form-control-color w-100" value="{{ $tarea->color }}">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Observaciones</label>
                            <textarea name="observaciones" class="form-control" rows="4">{{ $tarea->observaciones }}</textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('tareas.index') }}" class="btn btn-light">Cancelar</a>
                            <button type="submit" class="btn btn-warning">Actualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>