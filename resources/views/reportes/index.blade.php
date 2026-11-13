@extends('layouts.app')

@section('titulo', 'Reportes | Gestión de Inventarios')

@section('encabezado', 'Reportes')

@section('contenido')

{{-- Estilos para mejorar las tarjetas de acceso --}}
<style>
    .access-card {
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
        border-radius: 12px;
        padding: 2rem;
        display: block;
        text-decoration: none;
        color: inherit;
        background: #fff;
    }

    .access-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08);
        border-color: #4e73df; /* Borde azul al pasar el mouse */
    }

    .access-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        background: #eef2ff; /* Fondo azul muy claro */
        color: #4e73df; /* Icono azul */
        border-radius: 15px;
        font-size: 1.8rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .access-card:hover .access-icon {
        background: #4e73df;
        color: #fff;
    }
</style>

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

            <span class="access-icon"><i class="bi bi-box-arrow-in-down"></i></span>

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

            <span class="access-icon"><i class="bi bi-box-arrow-up"></i></span>

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

            <span class="access-icon"><i class="bi bi-trophy"></i></span>

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