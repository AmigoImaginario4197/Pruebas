<div class="panel-sidebar">
    <div class="sidebar-header">
        <img src="{{ asset('images/logo.png') }}" alt="Pet Care Logo" class="sidebar-logo">
    </div>
    
    <nav class="sidebar-menu">
        <ul>
            {{-- Enlace al Dashboard --}}
            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    Dashboard
                </a>
            </li>

            {{-- Enlace a Mascotas (condicional por rol) --}}
            <li>
                @if(Auth::user()->isVeterinario())
                    <a href="{{ route('veterinario.mascotas.index') }}" class="{{ request()->routeIs('veterinario.mascotas.index') ? 'active' : '' }}"> 
                        <i class="bi bi-heart-pulse"></i>
                        Gestionar Mascotas
                    </a>
                @else
                    <a href="{{ route('mascotas.index') }}" class="{{ request()->routeIs('mascotas.*') ? 'active' : '' }}">
                        <i class="bi bi-heart"></i>
                        Mis Mascotas
                    </a>
                @endif
            </li>

            {{-- Enlace a Citas --}}
            <li>
                <a href="{{ route('citas.index') }}" class="{{ request()->routeIs('citas.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i>
                    Citas
                </a>
            </li>

            <li>
                <a href="{{-- route('historial.index') --}}"> {{-- Comentado hasta que crees la ruta --}}
                    <i class="bi bi-clock-history"></i>
                    Historial
                </a>
            </li>
            <li>
                <a href="#"> {{-- Apunta a '#' porque no tiene una ruta definida aún --}}
                    <i class="bi bi-archive"></i>
                    Archivo
                </a>
            </li>
            
            {{-- Enlace a Tratamientos --}}
            <li>
                <a href="{{ route('tratamientos.index') }}" class="{{ request()->routeIs('tratamientos.*') ? 'active' : '' }}">
                    <i class="bi bi-prescription2"></i>
                    Tratamiento
                </a>
            </li>

            {{-- Enlaces solo para Administradores --}}
            @if(Auth::user()->isAdmin())
                <li><a href="#"><i class="bi bi-list-check"></i> Logs</a></li>
            @endif

            {{-- Enlace universal de "Salir" --}}
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="bi bi-box-arrow-right"></i>
                        Salir
                    </a>
                </form>
            </li>
        </ul>
    </nav>
</div>

{{-- Estilo para el enlace activo --}}
<style>
    .sidebar-menu a.active {
        background-color: rgba(255, 255, 255, 0.1);
        border-left-color: #7CF023; 
        font-weight: 700;
    }
</style>