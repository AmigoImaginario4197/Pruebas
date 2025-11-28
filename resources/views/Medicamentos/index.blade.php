<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Medicamentos - Pet Care</title>
    
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
                    <h3>Catálogo de Medicamentos</h3>
                    <p>Gestiona los medicamentos disponibles en la clínica.</p>
                </div>
                @if(Auth::user()->isAdmin())
                    <div class="header-actions">
                        <a href="{{ route('medicamentos.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Añadir Medicamento
                        </a>
                    </div>
                @endif
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
                    <form action="{{ route('medicamentos.index') }}" method="GET" class="row gx-3 gy-2 align-items-end">
                        <div class="col-md-10">
                            <label for="search" class="form-label">Buscar Medicamento</label>
                            <input type="text" name="search" id="search" class="form-control" placeholder="Nombre del medicamento..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2 d-flex mt-3 mt-md-0">
                            <button type="submit" class="btn btn-secondary w-100">Buscar</button>
                        </div>
                    </form>
                </div>

                {{-- Lista de Medicamentos en formato de tarjetas --}}
                <div class="item-list">
                    @forelse ($medicamentos as $medicamento)
                        <div class="item-card">
                            <div class="item-photo-placeholder">
                                <i class="bi bi-capsule-pill"></i>
                            </div>
                            
                            <div class="item-details">
                                <h4>{{ $medicamento->nombre }}</h4>
                                <p>{{ $medicamento->dosis_recomendada ?? 'Dosis no especificada' }}</p>
                            </div>

                            <div class="item-actions">
                                <a href="{{ route('medicamentos.show', $medicamento) }}" class="btn btn-secondary btn-sm" title="Ver Detalles">
                                    <i class="bi bi-eye"></i>
                                </a>
                                
                                {{-- ========================================================= --}}
                                {{--  CAMBIO 2: Se reemplaza @can por @if aquí también      --}}
                                {{-- ========================================================= --}}
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('medicamentos.edit', $medicamento) }}" class="btn btn-warning btn-sm" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('medicamentos.destroy', $medicamento) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este medicamento?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="empty-state-card">
                            <i class="bi bi-archive-fill"></i>
                            <p>No se han añadido medicamentos al catálogo.</p>
                            
                            {{-- ========================================================= --}}
                            {{--  CAMBIO 3: Y finalmente aquí                          --}}
                            {{-- ========================================================= --}}
                            @if(Auth::user()->isAdmin())
                                <p>¡Haz clic en "Añadir Medicamento" para empezar!</p>
                            @endif
                        </div>
                    @endforelse
                </div>

                {{-- Paginación --}}
                <div class="mt-4 d-flex justify-content-center">
                    {{ $medicamentos->links() }}
                </div>
            </div>
        </main>
    </div>
</body>
</html>