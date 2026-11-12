@extends('layouts.app')

@section('titulo', 'Reportes | Gestión de Inventarios')

@section('encabezado', 'Reportes')

@section('contenido')

<section class="quick-access">

    <div class="section-heading">
        <h2>Consulta de movimientos</h2>

        <p>
            Selecciona el tipo de movimiento que deseas consultar.
        </p>
    </div>

    <div class="access-grid">

        <!-- Reporte de ingresos -->
        <a href="{{ route('reportes.ingresos') }}"
            class="access-card">

            <span class="access-icon">📥</span>

            <h3>Ingresos de inventario</h3>

            <p>
                Consulta los productos que han ingresado
                al inventario, sus cantidades, costos,
                proveedores y fechas de registro.
            </p>

        </a>


        <!-- Reporte de salidas -->
        <a href="{{ route('reportes.salidas') }}"
            class="access-card">

            <span class="access-icon">📤</span>

            <h3>Salidas de inventario</h3>

            <p>
                Consulta los productos que han salido
                del inventario, sus cantidades,
                motivos y fechas de registro.
            </p>

        </a>
        
        <!-- Reporte de productos más vendidos -->
        <a href="{{ route('reportes.mas-vendidos') }}"
            class="access-card">

            <span class="access-icon">🏆</span>

            <h3>Productos más vendidos</h3>

            <p>
                Consulta los productos con mayor cantidad
                de unidades vendidas y conoce cuáles tienen
                mayor movimiento en el negocio.
            </p>

        </a>

    </div>

</section>

@endsection