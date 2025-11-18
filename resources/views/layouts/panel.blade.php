<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Care</title>
    
    <!-- Fonts y CSS -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Cargamos tus estilos y Vite -->
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="panel-container">
        
        {{-- Incluimos el sidebar desde el archivo parcial --}}
        @include('layouts.partials.sidebar')

        {{-- Creamos el área para el contenido principal --}}
        <main class="panel-content">
            
            {{-- Aquí se inyectará el contenido específico de cada página --}}
            @yield('content')

        </main>
    </div>

    {{-- Aquí es donde se inyectaraá el script --}}
    @stack('scripts')
</body>
</html>