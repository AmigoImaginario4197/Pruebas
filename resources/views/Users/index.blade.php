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
    
    <link rel="stylesheet" href="{{ asset('css/panel.css') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="panel-container">     
        
        @include('layouts.sidebar')

        <main class="panel-main">
            <header class="panel-header">
                <div class="header-title">
                    <h3>Gestión de Usuarios</h3>
                    <p>Administra las cuentas del sistema (solo para administradores).</p>
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

                {{-- Formulario de Búsqueda --}}
                <div class="card p-3 mb-4">
                    <form action="{{ route('users.index') }}" method="GET" class="row gx-3 gy-2 align-items-end">
                        <div class="col-md-5">
                            <label for="search" class="form-label">Buscar Usuario</label>
                            <input type="text" name="search" id="search" class="form-control" placeholder="Nombre o email..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="rol" class="form-label">Rol</label>
                            <select name="rol" id="rol" class="form-select">
                                <option value="">Todos los roles</option>
                                <option value="cliente" {{ request('rol') == 'cliente' ? 'selected' : '' }}>Cliente</option>
                                <option value="veterinario" {{ request('rol') == 'veterinario' ? 'selected' : '' }}>Veterinario</option>
                                <option value="admin" {{ request('rol') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex mt-3 mt-md-0">
                            <button type="submit" class="btn btn-secondary w-100 me-2">Buscar</button>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary w-100">Limpiar</a>
                        </div>
                    </form>
                </div>

                {{-- Tabla de Usuarios --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr><th>Nombre</th><th>Email</th><th>Rol</th><th class="text-end">Acciones</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td><span class="badge @if($user->rol == 'admin') bg-danger @elseif($user->rol == 'veterinario') bg-primary @else bg-secondary @endif">{{ ucfirst($user->rol) }}</span></td>
                                    <td class="text-end">
                                        
                                        {{-- =============================================== --}}
                                        {{--      AQUÍ ESTÁ LA SOLUCIÓN DEFINITIVA           --}}
                                        {{-- Usamos la misma estructura que en 'mascotas'     --}}
                                        {{-- =============================================== --}}
                                        <div class="item-actions justify-content-end">
                                            <a href="{{ route('users.show', $user) }}" class="btn btn-sm btn-secondary" title="Ver"><i class="bi bi-eye"></i></a>
                                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning" title="Editar"><i class="bi bi-pencil"></i></a>
                                            <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('¿Estás seguro?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>

                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center py-4">No se encontraron usuarios con los criterios de búsqueda.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                <div class="mt-4 d-flex justify-content-center">
                    {{ $users->links() }}
                </div>
            </div>
        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>