<!-- Plantilla base para crear el programa -->
 
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('titulo', 'Gestión de Inventarios y Ventas')</title>

    <!-- Hoja de estilos del sistema -->
   <link rel="stylesheet" href="{{ asset('css/stilos.css') }}">
</head>

<body>

    <!-- Menú lateral -->
    <aside class="sidebar">

        <div class="sidebar-header">
            <h2>Gestión de Inventarios y Ventas</h2>
        </div>

        <nav class="sidebar-menu">
            <a href="{{ route('inicio') }}" class="menu-link">
                Inicio
            </a>

            <a href="#" class="menu-link">
                Productos
            </a>

            <a href="#" class="menu-link">
                Categorías
            </a>

            <a href="#" class="menu-link">
                Inventario
            </a>

            <a href="#" class="menu-link">
                Ventas
            </a>

            <a href="#" class="menu-link">
                Reportes
            </a>
        </nav>

        <div class="sidebar-footer">
            <p>Sistema de gestión</p>
        </div>

    </aside>

    <!-- Contenido principal -->
    <main class="main-content">

        <!-- Encabezado superior -->
        <header class="topbar">
            <h1>@yield('encabezado', 'Inicio')</h1>
        </header>

        <!-- Aquí irá el contenido de cada página -->
        <section class="page-content">
            @yield('contenido')
        </section>

    </main>

</body>
</html>
