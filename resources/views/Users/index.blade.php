<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Pet Care</title>
    
    {{-- Dependencias de Estilos --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    {{-- Tus archivos CSS --}}
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/users.css') }}"> {{-- Archivo nuevo --}}
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-light">
    <div class="panel-container">     
        
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title">
                    <h3>Gestión de Usuarios</h3>
                    <p>Administra las cuentas del sistema.</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('users.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Añadir Usuario
                    </a>
                </div>
            </header>

            <div class="panel-content">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Formulario de Búsqueda dentro de una Tarjeta --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <form action="{{ route('users.index') }}" method="GET" class="row gx-3 align-items-end">
                            <div class="col-md-5">
                                <label for="search" class="form-label">Buscar por nombre o email</label>
                                <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="rol" class="form-label">Filtrar por Rol</label>
                                <select name="rol" id="rol" class="form-select">
                                    <option value="">Todos los roles</option>
                                    <option value="cliente" {{ request('rol') == 'cliente' ? 'selected' : '' }}>Cliente</option>
                                    <option value="veterinario" {{ request('rol') == 'veterinario' ? 'selected' : '' }}>Veterinario</option>
                                    <option value="admin" {{ request('rol') == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex mt-3 mt-md-0">
                                <button type="submit" class="btn btn-secondary w-100 me-2">Filtrar</button>
                                <a href="{{ route('users.index') }}" class="btn btn-light w-100 border">Limpiar</a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Lista de Usuarios en formato Tarjeta --}}
                <div class="item-list">
                    @forelse ($users as $user)
                        <div class="item-card">
                            {{-- Avatar de Usuario --}}
                            <div class="user-avatar me-3">
                                <i class="bi bi-person-circle"></i>
                            </div>
                            
                            {{-- Detalles del Usuario --}}
                            <div class="item-details">
                                <h4 class="mb-0">{{ $user->name }}</h4>
                                <p class="text-muted mb-0">{{ $user->email }}</p>
                            </div>

                            {{-- Badge de Rol (alineado a la derecha antes de las acciones) --}}
                            <div class="ms-auto me-4">
                                @php
                                    $roleClass = 'bg-secondary';
                                    if ($user->rol == 'admin') $roleClass = 'bg-danger';
                                    if ($user->rol == 'veterinario') $roleClass = 'bg-primary';
                                @endphp
                                <span class="badge rounded-pill {{ $roleClass }} role-badge">{{ ucfirst($user->rol) }}</span>
                            </div>

                            {{-- Acciones --}}
                            <div class="item-actions">
                                <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-secondary" title="Ver"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning" title="Editar"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar a este usuario?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state-card">
                            <i class="bi bi-people"></i>
                            <p>No se encontraron usuarios con los filtros aplicados.</p>
                            <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary mt-2">Limpiar filtros</a>
                        </div>
                    @endforelse
                </div>

                {{-- Paginación --}}
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </main>
    </div>
</body>
</html>