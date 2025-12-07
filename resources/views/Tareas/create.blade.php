<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Nueva Tarea - Pet Care</title>
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
                    <h3>Nueva Tarea Interna</h3>
                </div>
            </header>

            <div class="panel-content">
                <div class="card shadow-sm border-0 p-4" style="max-width: 800px; margin: 0 auto;">
                    <form action="{{ route('tareas.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Título de la Actividad</label>
                            <input type="text" name="titulo" class="form-control" placeholder="Ej: Reunión de equipo, Mantenimiento..." required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Fecha Inicio</label>
                                <input type="datetime-local" name="inicio" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Fecha Fin</label>
                                <input type="datetime-local" name="fin" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Color (para la Agenda)</label>
                            <input type="color" name="color" class="form-control form-control-color w-100" value="#6c757d">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Observaciones / Detalles</label>
                            <textarea name="observaciones" class="form-control" rows="4"></textarea>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('tareas.index') }}" class="btn btn-light">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar Tarea</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>