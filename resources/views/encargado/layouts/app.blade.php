
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('titulo', 'Panel de Inventario')</title>

    <link rel="stylesheet" href="{{ asset('css/stilos.css') }}">
</head>

<body>

    <!-- Menú privado del encargado -->
    <aside class="sidebar">

        <div class="sidebar-header">
            <h2>Gestión de Inventarios</h2>
            <p>Panel del encargado</p>
        </div>

        <nav class="sidebar-menu">

            <a href="{{ route('encargado.inventario.index') }}" class="menu-link">
                Inicio
            </a>

            <a href="{{ route('encargado.inventario.index') }}" class="menu-link">
                Existencias
            </a>

            <a href="{{ route('encargado.inventario.ingresos') }}" class="menu-link">
                Ingresos
            </a>

            <a href="{{ route('encargado.inventario.alertas') }}" class="menu-link">
                Alertas de stock
            </a>

        </nav>

        <div class="sidebar-footer">

            <p>Sesión del encargado</p>

            <form method="POST" action="{{ route('encargado.logout') }}">
                @csrf
                <button type="submit">Cerrar sesión</button>
            </form>

        </div>

    </aside>

    <!-- Contenido principal -->
    <main class="main-content">

        <header class="topbar">
            <h1>@yield('encabezado', 'Inventario')</h1>
        </header>

        <section class="page-content">
            @yield('contenido')
        </section>

    </main>

</body>
</html>
