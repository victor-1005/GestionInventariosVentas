@extends('encargado.layouts.app')

@section('titulo', 'Panel de Inventario')

@section('encabezado', 'Panel de inventario')

@section('contenido')

<!-- Mensaje de bienvenida -->
<section class="inventory-welcome">

    <div>
        <span class="inventory-label">MÓDULO DE ADMINISTRACIÓN</span>

        <h2>¡Bienvenido al inventario!</h2>

        <p>
            Desde este panel puedes supervisar las existencias,
            registrar ingresos de productos y revisar las alertas de stock.
        </p>
    </div>

    <div class="inventory-welcome-icon">
        📦
    </div>

</section>


<!-- Resumen de funciones -->
<section class="inventory-statistics">

    <article class="inventory-stat-card">
        <div class="inventory-stat-icon blue">📦</div>

        <div>
            <span>Existencias</span>
            <h3>Consultar productos</h3>
            <p>Revisa las cantidades disponibles.</p>
        </div>
    </article>

    <article class="inventory-stat-card">
        <div class="inventory-stat-icon green">📥</div>

        <div>
            <span>Ingresos</span>
            <h3>Registrar entradas</h3>
            <p>Registra nuevos productos recibidos.</p>
        </div>
    </article>

    <article class="inventory-stat-card">
        <div class="inventory-stat-icon orange">⚠️</div>

        <div>
            <span>Alertas</span>
            <h3>Controlar stock</h3>
            <p>Identifica productos con existencias bajas.</p>
        </div>
    </article>

</section>


<!-- Accesos del módulo -->
<section class="inventory-section">

    <div class="inventory-section-heading">
        <h2>Gestión de inventario</h2>

        <p>
            Selecciona una operación para continuar.
        </p>
    </div>

    <div class="inventory-actions">

        <a href="{{ route('encargado.inventario.existencias') }}"
            class="inventory-action-card">

            <span class="inventory-action-icon">📋</span>

            <h3>Existencias</h3>

            <p>
                Consulta los productos y sus cantidades disponibles.
            </p>

            <span class="inventory-action-link">
                Consultar módulo →
            </span>

        </a>


        <a href="{{ route('encargado.inventario.ingresos') }}"
            class="inventory-action-card">

            <span class="inventory-action-icon">📥</span>

            <h3>Ingresos de inventario</h3>

            <p>
                Registra las entradas de productos al inventario.
            </p>

            <span class="inventory-action-link">
                Registrar ingreso →
            </span>

        </a>


        <a href="{{ route('encargado.inventario.alertas') }}"
            class="inventory-action-card">

            <span class="inventory-action-icon">⚠️</span>

            <h3>Alertas de stock</h3>

            <p>
                Revisa los productos que necesitan reposición.
            </p>

            <span class="inventory-action-link">
                Revisar alertas →
            </span>

        </a>

        {{-- TARJETA: CATEGORÍAS --}}

        <a href="{{ route('encargado.inventario.categorias') }}"
            class="inventory-action-card">

            <span class="inventory-action-icon">🏷️</span>

            <h3>Administrar categorías</h3>

            <p>
                Registra y consulta las categorías utilizadas
                para clasificar los productos.
            </p>

            <span class="inventory-action-link">
                Gestionar categorías →
            </span>

        </a>


        {{-- TARJETA: PROVEEDORES --}}

        <a href="{{ route('encargado.inventario.proveedores') }}"
            class="inventory-action-card">

            <span class="inventory-action-icon">🏢</span>

            <h3>Administrar proveedores</h3>

            <p>
                Registra y consulta las entidades que
                suministran productos al inventario.
            </p>

            <span class="inventory-action-link">
                Gestionar proveedores →
            </span>

        </a>


    </div>

</section>

@endsection