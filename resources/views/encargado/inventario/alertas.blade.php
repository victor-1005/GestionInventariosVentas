@extends('encargado.layouts.app')

@section('titulo', 'Alertas de inventario')

@section('encabezado', 'Alertas de inventario')

@section('contenido')

<div class="existencias-wrapper">

    <div class="existencias-heading">
        <div>
            <h2>Productos con stock bajo</h2>
            <p>
                Consulta los productos que necesitan reposición
                de inventario.
            </p>
        </div>

        <a href="{{ route('encargado.inventario.index') }}"
           class="btn-existencia-regresar">
            Regresar
        </a>
    </div>

    {{-- Resumen de alertas --}}
    <div class="existencias-estadisticas">

        <div class="existencia-stat">
            <span>Total de productos en alerta</span>
            <strong>{{ $totalAlertas }}</strong>
        </div>

    </div>

    <div class="existencias-panel">

        <div class="existencias-panel-heading">
            <h3>Listado de alertas</h3>
        </div>

        {{-- Buscador --}}
        <form action="{{ route('encargado.inventario.alertas') }}"
              method="GET"
              class="buscador-existencias">

            <input
                type="text"
                name="buscar"
                placeholder="Buscar por código, producto o categoría..."
                value="{{ $buscar }}"
            >

            <button type="submit">
                Buscar
            </button>

            @if($buscar)
                <a href="{{ route('encargado.inventario.alertas') }}">
                    Limpiar
                </a>
            @endif

        </form>

        {{-- Tabla de productos --}}
        <div class="tabla-contenedor">

            <table class="tabla-existencias">

                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Stock actual</th>
                        <th>Stock mínimo</th>
                        <th>Estado</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($productos as $producto)

                        <tr>
                            <td>
                                {{ $producto->codigo_producto }}
                            </td>

                            <td>
                                {{ $producto->nombre_producto }}
                            </td>

                            <td>
                                {{ $producto->categoria?->nombre_categoria ?? 'Sin categoría' }}
                            </td>

                            <td>
                                {{ $producto->stock_actual }}
                            </td>

                            <td>
                                {{ $producto->stock_minimo }}
                            </td>

                            <td>
                                <span class="estado-stock estado-bajo">
                                    Stock bajo
                                </span>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="existencias-vacias">

                                @if($buscar)
                                    No se encontraron productos en alerta
                                    que coincidan con "{{ $buscar }}".
                                @else
                                    ¡Excelente! No hay productos con stock bajo.
                                @endif

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- Paginación --}}
        <div class="paginacion-existencias">
            {{ $productos->links() }}
        </div>

    </div>

</div>

@endsection
