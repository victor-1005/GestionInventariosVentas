@extends('layouts.app')

@section('titulo', 'Salidas | Reportes')

@section('encabezado', 'Salidas de inventario')

@section('contenido')

<section class="quick-access">

    <div class="section-heading">
        <h2>Historial de ventas</h2>

        <p>
            Consulta las ventas realizadas y los productos
            que salieron del inventario.
        </p>
    </div>

    @forelse($ventas as $venta)

        <article class="stat-card" style="margin-bottom: 20px;">

            <div style="display:flex; justify-content:space-between; flex-wrap:wrap; gap:10px;">

                <div>

                    <h3>
                        Venta #{{ $venta->id }}
                    </h3>

                    <p>
                        Fecha:
                        {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i:s') }}
                    </p>

                    <p>
                        Registrado por:
                        {{ $venta->usuario->nombre ?? 'No disponible' }}
                    </p>

                </div>

                <div>
                    <span>
                        Productos: {{ $venta->detalles->count() }}
                    </span>

                    <br>

                    <span>
                        Unidades vendidas:
                        {{ $venta->detalles->sum('cantidad_vendida') }}
                    </span>
                </div>

            </div>

            <hr>

            <h4>Productos vendidos</h4>

            <div style="overflow-x:auto;">

                <table style="width:100%; text-align:left;">

                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio unitario</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($venta->detalles as $detalle)

                            <tr>

                                <td>
                                    {{ $detalle->producto->codigo_producto ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $detalle->producto->nombre_producto ?? 'Producto eliminado' }}
                                </td>

                                <td>
                                    {{ $detalle->cantidad_vendida }}
                                </td>

                                <td>
                                    ${{ number_format($detalle->precio, 2) }}
                                </td>

                                <td>
                                    ${{ number_format(
                                        $detalle->cantidad_vendida * $detalle->precio,
                                        2
                                    ) }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="5">
                                    No hay productos registrados.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <hr>

            <div style="text-align:right;">

                <h3>
                    Total de la venta:
                    ${{ number_format($venta->total, 2) }}
                </h3>

            </div>

        </article>

    @empty

        <p>No existen ventas registradas.</p>

    @endforelse

    <div>
        {{ $ventas->links() }}
    </div>

</section>

@endsection
