
@extends('encargado.layouts.app')

@section('titulo', 'Registrar venta')
@section('encabezado', 'Registrar venta de productos')

@section('contenido')

<div class="ingreso-wrapper">

    {{-- ENCABEZADO --}}
    <div class="ingreso-heading">
        <div>
            <h2>Nueva venta de productos</h2>
            <p>Registra los productos vendidos y realiza la transacción.</p>
        </div>
    </div>

    {{-- FORMULARIO --}}
    <form
        id="formVenta"
        method="POST"
        action="{{ route('encargado.ventas.guardar') }}"
        data-buscar-url="{{ url('/encargado/ventas/productos/buscar') }}"
    >

        @csrf

        <div class="ingreso-paneles">

            {{-- PANEL 1: INFORMACIÓN DEL PRODUCTO --}}
            <section class="ingreso-panel">

                <div class="panel-heading">
                    <span class="panel-icon">📦</span>

                    <div>
                        <h3>Información del producto</h3>
                        <p>Busca un producto existente para agregarlo a la venta.</p>
                    </div>
                </div>

                <div class="ingreso-grid">

                    <div class="campo-ingreso campo-completo">
                        <label for="codigo_producto">
                            Código del producto
                        </label>

                        <input
                            type="text"
                            id="codigo_producto"
                            placeholder="Ej. PROD-001"
                            autocomplete="off"
                        >

                        <button
                            type="button"
                            id="btnBuscarProducto"
                            class="btn-agregar"
                            style="margin-top: 10px;"
                        >
                            Buscar producto
                        </button>
                    </div>

                    <div class="campo-ingreso campo-completo">
                        <label for="nombre_producto">
                            Nombre del producto
                        </label>

                        <input
                            type="text"
                            id="nombre_producto"
                            placeholder="Nombre del producto"
                            readonly
                        >
                    </div>

                    <div class="campo-ingreso">
                        <label for="stock_actual">
                            Stock disponible
                        </label>

                        <input
                            type="number"
                            id="stock_actual"
                            value="0"
                            readonly
                        >
                    </div>

                    <div class="campo-ingreso">
                        <label for="cantidad_vendida">
                            Cantidad a vender
                        </label>

                        <input
                            type="number"
                            id="cantidad_vendida"
                            min="1"
                            placeholder="0"
                        >
                    </div>

                    <div class="campo-ingreso">
                        <label for="precio">
                            Precio de venta
                        </label>

                        <input
                            type="number"
                            id="precio"
                            min="0"
                            step="0.01"
                            placeholder="0.00"
                            readonly
                        >
                    </div>

                </div>

                <div class="panel-actions">

                    <button
                        type="button"
                        id="btnAgregarProducto"
                        class="btn-agregar"
                    >
                        + Agregar producto a la lista
                    </button>

                </div>

            </section>

            {{-- PANEL 2: ALERTAS DE STOCK --}}
            <section class="ingreso-panel">

                <div class="panel-heading">
                    <span class="panel-icon">⚠️</span>

                    <div>
                        <h3>Alertas de stock</h3>
                        <p>Productos con existencias bajas o agotadas.</p>
                    </div>
                </div>

                <div class="alertas-stock-lista">

                    @forelse($productosBajoStock as $producto)

                        <div class="alerta-stock-item
                            {{ $producto->stock_actual == 0
                                ? 'alerta-stock-agotado'
                                : 'alerta-stock-bajo' }}">

                            <div>
                                <strong>
                                    {{ $producto->nombre_producto }}
                                </strong>

                                <small>
                                    Código: {{ $producto->codigo_producto }}
                                </small>
                            </div>

                            <span class="stock-estado">

                                @if($producto->stock_actual == 0)
                                    Agotado
                                @else
                                    {{ $producto->stock_actual }} unidades
                                @endif

                            </span>

                        </div>

                    @empty

                        <div class="alertas-sin-datos">
                            <span>✅</span>
                            <p>No hay productos con stock bajo.</p>
                        </div>

                    @endforelse

                </div>

                <div class="nota-ingreso">
                    <span>💡</span>
                    <p>
                        El sistema verificará las existencias antes de
                        confirmar la venta y descontará las unidades
                        correspondientes.
                    </p>
                </div>

            </section>

        </div>

        {{-- PANEL 3: PRODUCTOS DE LA VENTA --}}
        <section class="lista-ingreso-panel">

            <div class="panel-heading">
                <span class="panel-icon">📋</span>

                <div>
                    <h3>Productos de la venta</h3>
                    <p>Revisa los productos antes de realizar la transacción.</p>
                </div>
            </div>

            <div class="tabla-contenedor">

                <table class="tabla-ingresos">

                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio unitario</th>
                            <th>Subtotal</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody id="listaProductos">

                        <tr>
                            <td colspan="6" class="fila-vacia">
                                Todavía no has agregado productos.
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

            {{-- RESUMEN --}}
            <div class="resumen-ingreso">

                <div class="resumen-total">
                    <span>Total de unidades:</span>
                    <strong id="totalUnidades">0</strong>
                </div>

                <div class="resumen-total resumen-principal">
                    <span>Total de la venta:</span>
                    <strong id="totalVenta">$0.00</strong>
                </div>

            </div>

            {{-- GUARDAR --}}
            <div class="ingreso-footer">

                <button
                    type="submit"
                    id="btnGuardarVenta"
                    class="btn-guardar"
                >
                    Realizar transacción
                </button>

            </div>

        </section>

    </form>

</div>

@endsection

@section('scripts')
<script src="{{ asset('js/ventas.js') }}" defer></script>
@endsection
