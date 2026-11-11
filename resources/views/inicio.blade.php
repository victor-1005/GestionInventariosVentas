
@extends('layouts.app')

{{-- Título de la pestaña del navegador --}}
@section('titulo', 'Inicio | Gestión de Inventarios y Ventas')

{{-- Título que aparece en el encabezado --}}
@section('encabezado', 'Inicio')

{{-- Contenido principal de la página --}}
@section('contenido')

<!-- Bienvenida -->
<section class="welcome-card">

    <div class="welcome-text">

        <span class="welcome-label">SISTEMA WEB</span>

        <h2>¡Bienvenido a nuestro sistema!</h2>

        <p>
            Gestiona tus productos, controla las existencias,
            registra ventas y consulta la información de tu negocio
            desde un solo lugar.
        </p>

        <a href="#accesos" class="btn-primary">
            Comenzar
        </a>

    </div>

    <div class="welcome-icon">
        <span>📦</span>
        <span>📊</span>
    </div>

</section>


<!-- Tarjetas de resumen -->
<section class="statistics">

    <article class="stat-card">

        <div class="stat-icon">📦</div>

        <h3>Total de productos</h3>

        <p class="stat-number">{{ $totalProductos }}</p>

        <span>Productos registrados</span>

    </article>


    <article class="stat-card">

        <div class="stat-icon">🏷️</div>

        <h3>Categorías</h3>

        <p class="stat-number">{{ $totalCategorias }}</p>

        <span>Categorías registradas</span>

    </article>


    <article class="stat-card">

        <div class="stat-icon">🛒</div>

        <h3>Ventas</h3>

        <p class="stat-number">0</p>

        <span>Ventas registradas</span>

    </article>


    <article class="stat-card">

        <div class="stat-icon">⚠️</div>

        <h3>Stock bajo</h3>

        <p class="stat-number">{{ $totalAlertas }}</p>

        <span>Productos por revisar</span>

    </article>

</section>


<!-- Accesos rápidos -->
<section class="quick-access" id="accesos">

    <div class="section-heading">

        <h2>Accesos rápidos</h2>

        <p>
            Selecciona un módulo para comenzar a trabajar.
        </p>

    </div>


    <div class="access-grid">

        <!-- Inventario -->
        <a href="{{ route('encargado.login') }}" class="access-card">

            <span class="access-icon">🏭</span>

            <h3>Inventario</h3>

            <p>
                Administra productos, proveedores y controla
                las entradas y salidas de inventario.
            </p>

        </a>


        <!-- Ventas -->
        <a href="{{ route('encargado.login') }}" class="access-card">

            <span class="access-icon">🛒</span>

            <h3>Ventas</h3>

            <p>
                Registra y administra las ventas
                realizadas en el negocio.
            </p>

        </a>


        <!-- Reportes -->
        <a href="{{ route('reportes.index') }}" class="access-card">

            <span class="access-icon">📈</span>

            <h3>Reportes</h3>

            <p>
                Consulta los movimientos de inventario,
                ingresos y salidas de productos.
            </p>

        </a>


        <!-- Crear usuarios -->
        <a href="#" class="access-card">

            <span class="access-icon">👤</span>

            <h3>Crear usuarios</h3>

            <p>
                Registra nuevos usuarios para que puedan
                acceder al sistema.
            </p>

        </a>

    </div>

</section>

@endsection
