<div class="panel-sidebar">
    <div class="sidebar-header">
        <img src="{{ asset('images/logo.png') }}" alt="Pet Care Logo" class="sidebar-logo">
    </div>
    
    <nav class="sidebar-menu">
        <ul>
            {{-- === ENLACES COMUNES PARA TODOS === --}}
            <li>
                <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <i class="bi bi-person"></i>
                    {{ Auth::user()->name }}
                </a>
            </li>
            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    Dashboard
                </a>
            </li>

            {{-- === ENLACES DE GESTIÓN PARA CLIENTES Y PERSONAL === --}}
            {{-- Mascotas --}}
            <li>
                @if(Auth::user()->isVeterinario())
                    <a href="{{ route('veterinario.mascotas.index') }}" class="{{ request()->routeIs('veterinario.mascotas.index') ? 'active' : '' }}"> 
                        <i class="bi bi-heart-pulse"></i> Mascotas
                    </a>
                @else
                    <a href="{{ route('mascotas.index') }}" class="{{ request()->routeIs('mascotas.*') ? 'active' : '' }}">
                        <i class="bi bi-heart"></i>
                        {{ Auth::user()->isCliente() ? 'Mis Mascotas' : 'Mascotas' }}
                    </a>
                @endif
            </li>
            {{-- Citas --}}
            <li>
                <a href="{{ route('citas.index') }}" class="{{ request()->routeIs('citas.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i>
                    {{ Auth::user()->isCliente() ? 'Mis Citas' : 'Agenda de Citas' }}
                </a>
            </li>
            {{-- Historial Médico (Solo personal clínico) --}}
            @if(Auth::user()->isVeterinario() || Auth::user()->isAdmin())
                <li>
                    <a href="{{ route('historial.index') }}" class="{{ request()->routeIs('historial.*') ? 'active' : '' }}">
                        <i class="bi bi-collection"></i> Historiales Médicos
                    </a>
                </li>
            @endif

            {{-- Tratamientos (Visible para todos, el texto cambia) --}}
            <li>
                <a href="{{ route('tratamientos.index') }}" class="{{ request()->routeIs('tratamientos.*') ? 'active' : '' }}">
                    <i class="bi bi-prescription2"></i>
                    {{ Auth::user()->isCliente() ? 'Mis Tratamientos' : 'Tratamientos' }}
                </a>
            </li>

            {{-- === HERRAMIENTAS DE GESTIÓN INTERNA (SOLO PERSONAL) === --}}
            @if(Auth::user()->isVeterinario() || Auth::user()->isAdmin())
                <li>
                    <a href="{{ route('agenda.index') }}" class="{{ request()->routeIs('agenda.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-event"></i> Agenda 
                    </a>
                </li>
                <li>
                    <a href="{{ route('disponibilidad.index') }}" class="{{ request()->routeIs('disponibilidad.*') ? 'active' : '' }}">
                        <i class="bi bi-clock-fill"></i> Disponibilidad
                    </a>
                </li>
                <li>
                    <a href="{{ route('medicamentos.index') }}" class="{{ request()->routeIs('medicamentos.*') ? 'active' : '' }}">
                        <i class="bi bi-capsule-pill"></i> Catálogo Medicamentos
                    </a>
                </li>
            @endif

            {{-- === PANEL DE ADMINISTRACIÓN (SOLO ADMIN) === --}}
            @if(Auth::user()->isAdmin())
                <li>
                    <a href="{{ route('servicios.index') }}" class="{{ request()->routeIs('servicios.*') ? 'active' : '' }}">
                        <i class="bi bi-tags-fill"></i> Servicios
                    </a>
                </li>
                <li>
                    <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i> Usuarios
                    </a>
                </li>
                <li>
                    <a href="#"><i class="bi bi-list-check"></i> Logs</a>
                </li>
            @endif

            {{-- === SALIR (UNIVERSAL) === --}}
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="bi bi-box-arrow-right"></i> Salir
                    </a>
                </form>
            </li>
        </ul>
    </nav>
</div>

<style>
    .sidebar-menu a.active {
        background-color: rgba(255, 255, 255, 0.1);
        border-left-color: #7CF023; 
        font-weight: 700;
    }
</style>