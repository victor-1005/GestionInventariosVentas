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
    <!-- Icono de caja (Box) -->
    <div class="inventory-welcome-icon">
        <i class="bi bi-box-seam" style="font-size: 3rem;"></i>
    </div>
</section>


<!-- Resumen de funciones -->
<section class="inventory-statistics">

    <article class="inventory-stat-card">
        <!-- Icono de inventario/lista -->
        <div class="inventory-stat-icon blue"><i class="bi bi-clipboard-data"></i></div>
        <div>
            <span>Existencias</span>
            <h3>Consultar productos</h3>
            <p>Revisa las cantidades disponibles.</p>
        </div>
    </article>

    <article class="inventory-stat-card">
        <!-- Icono de entrada/descarga -->
        <div class="inventory-stat-icon green"><i class="bi bi-box-arrow-in-down"></i></div>
        <div>
            <span>Ingresos</span>
            <h3>Registrar entradas</h3>
            <p>Registra nuevos productos recibidos.</p>
        </div>
    </article>

    <article class="inventory-stat-card">
        <!-- Icono de alerta/triángulo -->
        <div class="inventory-stat-icon orange"><i class="bi bi-exclamation-triangle"></i></div>
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
        <p>Selecciona una operación para continuar.</p>
    </div>

    <div class="inventory-actions">

        <a href="{{ route('encargado.inventario.existencias') }}" class="inventory-action-card">
            <!-- Icono de lista -->
            <span class="inventory-action-icon"><i class="bi bi-card-list"></i></span>
            <h3>Existencias</h3>
            <p>Consulta los productos y sus cantidades disponibles.</p>
            <span class="inventory-action-link">Consultar módulo <i class="bi bi-arrow-right"></i></span>
        </a>

        <a href="{{ route('encargado.inventario.ingresos') }}" class="inventory-action-card">
            <!-- Icono de camión o caja entrante -->
            <span class="inventory-action-icon"><i class="bi bi-truck"></i></span>
            <h3>Ingresos de inventario</h3>
            <p>Registra las entradas de productos al inventario.</p>
            <span class="inventory-action-link">Registrar ingreso <i class="bi bi-arrow-right"></i></span>
        </a>

        <a href="{{ route('encargado.inventario.alertas') }}" class="inventory-action-card">
            <!-- Icono de campana o alerta -->
            <span class="inventory-action-icon"><i class="bi bi-bell"></i></span>
            <h3>Alertas de stock</h3>
            <p>Revisa los productos que necesitan reposición.</p>
            <span class="inventory-action-link">Revisar alertas <i class="bi bi-arrow-right"></i></span>
        </a>

        {{-- TARJETA: CATEGORÍAS --}}
        <a href="{{ route('encargado.inventario.categorias') }}" class="inventory-action-card">
            <!-- Icono de etiquetas -->
            <span class="inventory-action-icon"><i class="bi bi-tags"></i></span>
            <h3>Administrar categorías</h3>
            <p>Registra y consulta las categorías utilizadas para clasificar los productos.</p>
            <span class="inventory-action-link">Gestionar categorías <i class="bi bi-arrow-right"></i></span>
        </a>

        {{-- TARJETA: PROVEEDORES --}}
        <a href="{{ route('encargado.inventario.proveedores') }}" class="inventory-action-card">
            <!-- Icono de edificio/empresa -->
            <span class="inventory-action-icon"><i class="bi bi-building"></i></span>
            <h3>Administrar proveedores</h3>
            <p>Registra y consulta las entidades que suministran productos al inventario.</p>
            <span class="inventory-action-link">Gestionar proveedores <i class="bi bi-arrow-right"></i></span>
        </a>

    </div>

</section>

@endsection