<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('titulo', 'Panel del encargado')</title>

    <link rel="stylesheet" href="{{ asset('css/stilos.css') }}">
</head>

<body>

    <!-- MENÚ LATERAL -->
    <aside class="sidebar">

        <div class="sidebar-header">
            <h2>Gestión de Inventarios</h2>
            <p>Panel del encargado</p>
        </div>

        <nav class="sidebar-menu">

            @if(auth()->user()->rol === 'encargado_inventario')

            <a href="{{ route('encargado.inventario.index') }}"
                class="menu-link">
                Inicio
            </a>

            <a href="{{ route('encargado.inventario.index') }}"
                class="menu-link">
                Existencias
            </a>

            <a href="{{ route('encargado.inventario.ingresos') }}"
                class="menu-link">
                Ingresos
            </a>

            <a href="{{ route('encargado.inventario.alertas') }}"
                class="menu-link">
                Alertas de stock
            </a>
            <a href="{{ route('encargado.inventario.categorias') }}"
                class="menu-link">
                Categorías
            </a>

            <a href="{{ route('encargado.inventario.proveedores') }}"
                class="menu-link">
                Proveedores
            </a>

            @elseif(auth()->user()->rol === 'encargado_ventas')

            <a href="{{ route('encargado.ventas.index') }}"
                class="menu-link">
                Nueva venta
            </a>

            @endif

        </nav>

        <div class="sidebar-footer">

            <p>Sesión del encargado</p>

            <p>{{ auth()->user()->nombre }}</p>

            <form method="POST" action="{{ route('encargado.logout') }}">
                @csrf

                <button type="submit">
                    Cerrar sesión
                </button>
            </form>

        </div>

    </aside>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="main-content">

        <header class="topbar">
            <h1>@yield('encabezado', 'Inicio')</h1>
        </header>

        <section class="page-content">
            @yield('contenido')
        </section>

    </main>
    @yield('scripts')
</body>

</html>