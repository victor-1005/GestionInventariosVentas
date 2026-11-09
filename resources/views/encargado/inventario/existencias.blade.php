
@extends('encargado.layouts.app')

@section('titulo', 'Existencias')
@section('encabezado', 'Existencias de inventario')

@section('contenido')

<div class="existencias-wrapper">

    {{-- ENCABEZADO --}}
    <div class="existencias-heading">
        <div>
            <h2>Control de existencias</h2>
            <p>Consulta, administra y actualiza los productos registrados.</p>
        </div>

        <a href="{{ route('encargado.inventario.index') }}"
           class="btn-existencia-regresar">
            ← Volver al inventario
        </a>
    </div>

    {{-- MENSAJES --}}
    @if(session('success'))
        <div class="mensaje-existencia mensaje-exito">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mensaje-existencia mensaje-error">
            {{ session('error') }}
        </div>
    @endif

    {{-- ESTADÍSTICAS --}}
    <div class="existencias-estadisticas">

        <div class="existencia-stat">
            <span>📦</span>
            <div>
                <p>Productos registrados</p>
                <h3>{{ $totalProductos }}</h3>
            </div>
        </div>

        <div class="existencia-stat">
            <span>📊</span>
            <div>
                <p>Unidades en inventario</p>
                <h3>{{ $totalUnidades }}</h3>
            </div>
        </div>

    </div>

    {{-- TABLA DE PRODUCTOS --}}
    <section class="existencias-panel">

        <div class="existencias-panel-heading">
            <div>
                <h3>Productos registrados</h3>
                <p>Administra la información de tus productos.</p>
            </div>
        </div>

        {{-- BUSCADOR --}}
        <form method="GET"
              action="{{ route('encargado.inventario.existencias') }}"
              class="buscador-existencias">

            <input
                type="text"
                name="buscar"
                value="{{ $buscar }}"
                placeholder="Buscar por código, nombre o categoría..."
            >

            <button type="submit">
                🔍 Buscar
            </button>

            @if($buscar)
                <a href="{{ route('encargado.inventario.existencias') }}">
                    Limpiar
                </a>
            @endif

        </form>

        {{-- TABLA --}}
        <div class="tabla-contenedor">

            <table class="tabla-existencias">

                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Precio</th>
                        <th>Stock actual</th>
                        <th>Stock mínimo</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($productos as $producto)

                        <tr>

                            <td>
                                <strong>{{ $producto->codigo_producto }}</strong>
                            </td>

                            <td>
                                {{ $producto->nombre_producto }}
                            </td>

                            <td>
                                {{ $producto->categoria?->nombre_categoria ?? 'Sin categoría' }}
                            </td>

                            <td>
                                ${{ number_format($producto->precio, 2) }}
                            </td>

                            <td>
                                <strong>{{ $producto->stock_actual }}</strong>
                            </td>

                            <td>
                                {{ $producto->stock_minimo }}
                            </td>

                            <td>
                                @if($producto->stock_actual <= $producto->stock_minimo)
                                    <span class="estado-stock estado-bajo">
                                        Stock bajo
                                    </span>
                                @else
                                    <span class="estado-stock estado-disponible">
                                        Disponible
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="acciones-existencia">

                                    {{-- EDITAR --}}
                                    <a href="{{ route(
                                        'encargado.inventario.existencias.editar',
                                        $producto->id
                                    ) }}"
                                       class="btn-accion-editar">
                                        ✏️ Editar
                                    </a>

                                    {{-- ELIMINAR --}}
                                    <form
                                        action="{{ route(
                                            'encargado.inventario.existencias.eliminar',
                                            $producto->id
                                        ) }}"
                                        method="POST"
                                        onsubmit="return confirm('¿Estás seguro de eliminar este producto?');"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn-accion-eliminar">
                                            🗑 Eliminar
                                        </button>

                                    </form>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="existencias-vacias">
                                No se encontraron productos registrados.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- PAGINACIÓN --}}
        <div class="paginacion-existencias">
            {{ $productos->links() }}
        </div>

    </section>

</div>

@endsection
