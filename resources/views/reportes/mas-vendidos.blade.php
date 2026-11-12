
@extends('layouts.app')

@section('titulo', 'Productos más vendidos | Reportes')

@section('encabezado', 'Productos más vendidos')

@section('contenido')

<section class="quick-access">

    <div class="section-heading">
        <h2>Ranking de productos más vendidos</h2>

        <p>
            Consulta los 10 productos con mayor cantidad
            de unidades vendidas.
        </p>
    </div>

    <article class="stat-card">

        <h3>Listado de productos</h3>

        <p>
            Los productos se muestran ordenados
            desde el más vendido hasta el menos vendido.
        </p>

        <hr>

        <div style="overflow-x:auto;">

            <table style="width:100%; text-align:left;">

                <thead>
                    <tr>
                        <th>Posición</th>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Unidades vendidas</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($productos as $index => $producto)

                        <tr>

                            <td>
                                @if($index == 0)
                                    🥇 1
                                @elseif($index == 1)
                                    🥈 2
                                @elseif($index == 2)
                                    🥉 3
                                @else
                                    {{ $index + 1 }}
                                @endif
                            </td>

                            <td>
                                {{ $producto->codigo_producto }}
                            </td>

                            <td>
                                {{ $producto->nombre_producto }}
                            </td>

                            <td>
                                <strong>
                                    {{ $producto->total_vendido }}
                                </strong>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" style="text-align:center; padding:20px;">
                                No hay ventas registradas todavía.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <hr>

        <div style="text-align:right;">

            <h3>
                Total de productos diferentes:
                {{ $productos->count() }}
            </h3>

        </div>

    </article>

    <div style="margin-top:20px;">

        <a href="{{ route('reportes.index') }}" class="btn-volver">
            Volver a reportes
        </a>

    </div>

</section>

@endsection
