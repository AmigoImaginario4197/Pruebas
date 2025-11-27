// resources/views/admin/usuarios/create.blade.php
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Añadir Nuevo Usuario</title>
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="panel-container">
        @include('layouts.sidebar')
        <main class="panel-main">
            <header class="panel-header"><h3>Añadir Nuevo Usuario</h3></header>
            <div class="panel-content">
                <p>Aquí irá el formulario para crear un nuevo usuario.</p>
                {{-- Próximamente: formulario de creación --}}
            </div>
        </main>
    </div>
</body>
</html>