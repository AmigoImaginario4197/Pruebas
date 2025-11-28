<div class="panel-sidebar">
    <div class="sidebar-header">
        <img src="{{ asset('images/logo.png') }}" alt="Pet Care Logo" class="sidebar-logo">
    </div>
    
    <nav class="sidebar-menu">
        <ul>
            {{-- Enlace al Perfil del Usuario --}}
            <li>
                <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <i class="bi bi-person"></i>
                    {{ Auth::user()->name }}
                </a>
            </li>
            
            {{-- Enlace al Dashboard --}}
            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    Dashboard
                </a>
            </li>

            {{-- ========================================================== --}}
            {{--                CORRECCIÓN CLAVE AQUÍ                    --}}
            {{-- ========================================================== --}}
            {{-- Enlace a Usuarios solo para Admin (ahora apunta a 'users.*') --}}
            @if(Auth::user()->isAdmin())
                <li>
                    {{-- CAMBIO 1: La ruta ahora es 'users.index' --}}
                    {{-- CAMBIO 2: La comprobación de ruta activa es 'users.*' --}}
                    <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        Usuarios
                    </a>
                </li>
            @endif

            {{-- Lógica para el enlace a Mascotas (ya estaba correcta) --}}
            <li>
                @if(Auth::user()->isVeterinario())
                    <a href="{{ route('veterinario.mascotas.index') }}" class="{{ request()->routeIs('veterinario.mascotas.index') ? 'active' : '' }}"> 
                        <i class="bi bi-heart-pulse"></i>
                        Mascotas
                    </a>
                @elseif(Auth::user()->isAdmin())
                     <a href="{{ route('mascotas.index') }}" class="{{ request()->routeIs('mascotas.*') ? 'active' : '' }}">
                        <i class="bi bi-heart"></i>
                        Mascotas
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
                <a href="{{ route('historial.index') }}" class="{{ request()->routeIs('historial.*') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i>
                    Historial Médico
                </a>
            </li>

            <li>
                <a href="#">
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

{{-- El estilo para el enlace activo se queda igual, es correcto --}}
<style>
    .sidebar-menu a.active {
        background-color: rgba(255, 255, 255, 0.1);
        border-left-color: #7CF023; 
        font-weight: 700;
    }
</style>