// resources/views/admin/usuarios/show.blade.php
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Ver Usuario: {{ $user->name }}</title>
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="panel-container">
        @include('layouts.sidebar')
        <main class="panel-main">
            <header class="panel-header"><h3>Detalles de: {{ $user->name }}</h3></header>
            <div class="panel-content">
                <p><strong>Nombre:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Rol:</strong> {{ ucfirst($user->role) }}</p>
                <p><strong>Fecha de registro:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </main>
    </div>
</body>
</html>