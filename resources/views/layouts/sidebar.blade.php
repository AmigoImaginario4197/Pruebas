<div class="panel-sidebar">
    <div class="sidebar-header">
        <img src="{{ asset('images/logo.png') }}" alt="Pet Care Logo" class="sidebar-logo">
    </div>
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
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
                @if(Auth::user()->isVeterinario() || Auth::user()->isAdmin())
                    {{-- Ruta específica para veterinarios si existe, sino usa la general --}}
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
                    {{ Auth::user()->isCliente() ? 'Mis Citas' : 'Gestión de Citas' }}
                </a>
            </li>

            {{-- AGENDA (NUEVO PARA CLIENTES) --}}
            @if(Auth::user()->isCliente())
            <li>
                <a href="{{ route('agenda.index') }}" class="{{ request()->routeIs('agenda.index') ? 'active' : '' }}">
                    <i class="bi bi-calendar3"></i> Mi Agenda
                </a>
            </li>
            @endif

            {{-- Historial Médico --}}
           
                <li>
                    <a href="{{ route('historial.index') }}" class="{{ request()->routeIs('historial.*') ? 'active' : '' }}">
                        <i class="bi bi-collection"></i> Historiales Médicos
                    </a>
                </li>
           

            {{-- Tratamientos (Visible para todos) --}}
            <li>
                <a href="{{ route('tratamientos.index') }}" class="{{ request()->routeIs('tratamientos.*') ? 'active' : '' }}">
                    <i class="bi bi-prescription2"></i>
                    {{ Auth::user()->isCliente() ? 'Mis Tratamientos' : 'Tratamientos' }}
                </a>
            </li>

            {{-- MEDICAMENTOS (NUEVO PARA CLIENTES) --}}
            @if(Auth::user()->isCliente())
                <li>
                    <a href="{{ route('medicamentos.index') }}" class="{{ request()->routeIs('medicamentos.*') ? 'active' : '' }}">
                        <i class="bi bi-capsule-pill"></i> Catálogo de Medicamentos
                    </a>
                </li>
            @endif

            {{-- === HERRAMIENTAS DE GESTIÓN INTERNA (SOLO PERSONAL: Admin + Vet) === --}}
            @if(Auth::user()->isVeterinario() || Auth::user()->isAdmin())
                <li>
                    <a href="{{ route('agenda.index') }}" class="{{ request()->routeIs('agenda.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-event"></i> Agenda General
                    </a>
                </li>
                <li>
                    <a href="{{ route('tareas.index') }}" class="{{ request()->routeIs('tareas.*') ? 'active' : '' }}">
                        <i class="bi bi-clipboard-check"></i> Tareas Internas
                    </a>
                </li>
                <li>
                    <a href="{{ route('disponibilidad.index') }}" class="{{ request()->routeIs('disponibilidad.*') ? 'active' : '' }}">
                        <i class="bi bi-clock-fill"></i> Disponibilidad
                    </a>
                </li>
                <li>
                    <a href="{{ route('medicamentos.index') }}" class="{{ request()->routeIs('medicamentos.*') ? 'active' : '' }}">
                        <i class="bi bi-capsule-pill"></i> Gestión de Medicamentos
                    </a>
                </li>
                {{-- NUEVO: SERVICIOS AHORA VISIBLE PARA VETS (SOLO LECTURA) --}}
                <li>
                    <a href="{{ route('servicios.index') }}" class="{{ request()->routeIs('servicios.*') ? 'active' : '' }}">
                        <i class="bi bi-tags-fill"></i> Servicios
                    </a>
                </li>
            @endif

            {{-- === PANEL ADMIN === --}}
            @if(Auth::user()->isAdmin())
                <li>
                    <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i> Usuarios
                    </a>
                </li>
                <li>
                    {{-- Cambiado el texto para que sea más claro --}}
                    <a href="{{ route('logs.index') }}" class="{{ request()->routeIs('logs.index') ? 'active' : '' }}">
                        <i class="bi bi-list-check"></i> Logs del Sistema
                    </a>
                </li>
            @endif

            {{-- === MI ACTIVIDAD (LOGS PERSONALES) === --}}
            {{-- MODIFICADO: Ahora visible para Cliente Y Veterinario --}}
            @if(Auth::user()->isCliente() || Auth::user()->isVeterinario())
                 <li>
                    <a href="{{ route('logs.index') }}" class="{{ request()->routeIs('logs.index') ? 'active' : '' }}">
                        <i class="bi bi-list-check"></i> Mi Actividad
                    </a>
                </li>
            @endif

            {{-- === SALIR === --}}
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="text-danger">
                        <i class="bi bi-box-arrow-right"></i> Salir
                    </a>
                </form>
            </li>
        </ul>
    </nav>
</div>