<nav>
    <a href="/miPortal/home">Home</a>
    <a href="/elGrupo">El equipo</a>
    <a href="{{ route('productos.index') }}">Productos</a>
    <a href="{{ route('puestos.index') }}">Puestos</a>
    <a href="{{ route('productores.index') }}">Productores</a>
    <a href="/miPortal/acerca">Acerca de</a>

    {{-- Práctica 4: menú desplegable. Cada miembro añade su <a> dentro del <div class="submenu"> --}}
    <div class="desplegable">
        <span class="desplegable-titulo">Práctica4</span>
        <div class="submenu">
            <a href="{{ route('practica4.basicoRMC') }}">Básico RMC</a>
            <a href="{{ route('practica4.sensoresRMC') }}">Sensores RMC</a>
            <a href="{{ route('practica4.graficosRMC') }}">Gráficos RMC</a>
        </div>
    </div>
</nav>
