
@extends('layouts.app')

@section('titulo', 'Ingresos | Reportes')

@section('encabezado', 'Ingresos de inventario')

@section('contenido')

<section class="quick-access">

    <div class="section-heading">
        <h2>Historial de ingresos</h2>
        <p>
            Consulta las facturas y los productos
            registrados en cada ingreso.
        </p>
    </div>

    @forelse($ingresos as $ingreso)

        @php
            $totalIngreso = $ingreso->detalles->sum(
                fn($detalle) =>
                    $detalle->cantidad_recibida * $detalle->precio_costo
            );
        @endphp

        <article class="stat-card" style="margin-bottom: 20px;">

            <div style="display:flex; justify-content:space-between; flex-wrap:wrap; gap:10px;">

                <div>
                    <h3>
                        Factura: {{ $ingreso->numero_documento }}
                    </h3>

                    <p>
                        Fecha:
                        {{ \Carbon\Carbon::parse($ingreso->fecha_ingreso)->format('d/m/Y') }}
                    </p>

                    <p>
                        Proveedor:
                        {{ $ingreso->proveedor->nombre_entidad ?? 'No disponible' }}
                    </p>

                    <p>
                        Registrado por:
                        {{ $ingreso->usuario->nombre ?? 'No disponible' }}
                    </p>
                </div>

                <div>
                    <span>Productos: {{ $ingreso->detalles->count() }}</span>
                </div>

            </div>

            <hr>

            <h4>Productos ingresados</h4>

            <div style="overflow-x:auto;">

                <table style="width:100%; text-align:left;">

                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Costo unitario</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($ingreso->detalles as $detalle)

                            <tr>
                                <td>
                                    {{ $detalle->producto->codigo_producto ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $detalle->producto->nombre_producto ?? 'Producto eliminado' }}
                                </td>

                                <td>
                                    {{ $detalle->cantidad_recibida }}
                                </td>

                                <td>
                                    ${{ number_format($detalle->precio_costo, 2) }}
                                </td>

                                <td>
                                    ${{ number_format(
                                        $detalle->cantidad_recibida * $detalle->precio_costo,
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
                    Total del ingreso:
                    ${{ number_format($totalIngreso, 2) }}
                </h3>

            </div>

        </article>

    @empty

        <p>No existen ingresos registrados.</p>

    @endforelse

    <div>
        {{ $ingresos->links() }}
    </div>

</section>

@endsection
