<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Pet Care</title>
    
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
                    <p>Administra las cuentas del sistema.</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Añadir Usuario
                    </a>
                </div>
            </header>

            <div class="panel-content">
                {{-- Mensajes de éxito o error --}}
                @if (session('success'))
                    <div class="alert alert-success mb-4">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger mb-4">{{ session('error') }}</div>
                @endif

                {{-- Barra de búsqueda y filtros --}}
                <div class="card p-3 mb-4">
                    <form action="{{ route('admin.usuarios.index') }}" method="GET" class="d-flex flex-wrap gap-3">
                        <div class="flex-grow-1">
                            <input type="text" name="search" class="form-control" placeholder="Buscar por nombre..." value="{{ request('search') }}">
                        </div>
                        <div>
                            <select name="role" class="form-select" style="min-width: 150px;">
                                <option value="">Todos los roles</option>
                                <option value="cliente" {{ request('role') == 'cliente' ? 'selected' : '' }}>Cliente</option>
                                <option value="veterinario" {{ request('role') == 'veterinario' ? 'selected' : '' }}>Veterinario</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-info">Filtrar</button>
                            <a href="{{ route('admin.usuarios.index') }}" class="btn btn-secondary">Limpiar</a>
                        </div>
                    </form>
                </div>

                {{-- Tabla de Usuarios --}}
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($user->role == 'admin') bg-danger 
                                            @elseif($user->role == 'veterinario') bg-primary 
                                            @else bg-secondary @endif">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.usuarios.show', $user->id) }}" class="btn btn-sm btn-light"><i class="bi bi-eye"></i></a>
                                        <a href="{{ route('admin.usuarios.edit', $user->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                        <form action="{{ route('admin.usuarios.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar a este usuario?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">No se encontraron usuarios.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                <div class="mt-4">
                    {{ $users->withQueryString()->links() }}
                </div>
            </div>
        </main>
    </div>
</body>
</html>