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
                <span class="panel-icon"><i class="bi bi-box-seam" style="font-size: 1.5rem;"></i></span>
                <div>
                    <h3>Información del producto</h3>
                    <p>Escribe el nombre o código para buscar un producto.</p>
                </div>
            </div>

            <div class="ingreso-grid">

                {{-- NUEVO BUSCADOR INTELIGENTE --}}
                <div class="campo-ingreso campo-completo" style="position: relative;">
                    <label for="buscador_producto">Buscar producto (Nombre o Código)</label>
                    
                    <div style="position: relative;">
                        <i class="bi bi-search" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #6c757d;"></i>
                        <input
                            type="text"
                            id="buscador_producto"
                            placeholder="Ej. Cuaderno o PROD-001"
                            autocomplete="off"
                            style="padding-left: 35px; width: 100%;"
                        >
                    </div>
                    
                    {{-- Aquí se inyectarán los resultados de la búsqueda --}}
                    <ul id="resultados_busqueda" class="lista-autocompletado"></ul>
                    
                    {{-- Inputs ocultos que guardarán los datos reales para el formulario --}}
                    <input type="hidden" id="codigo_producto" name="codigo_producto">
                    <input type="hidden" id="nombre_producto" name="nombre_producto">
                </div>

                <div class="campo-ingreso">
                    <label for="stock_actual">Stock disponible</label>
                    <input type="number" id="stock_actual" value="0" readonly style="background-color: #e9ecef;">
                </div>

                <div class="campo-ingreso">
                    <label for="cantidad_vendida">Cantidad a vender</label>
                    <input type="number" id="cantidad_vendida" min="1" placeholder="0">
                </div>

                <div class="campo-ingreso">
                    <label for="precio">Precio de venta</label>
                    <input type="number" id="precio" min="0" step="0.01" placeholder="0.00" readonly style="background-color: #e9ecef;">
                </div>

            </div>

            <div class="panel-actions">
                <button type="button" id="btnAgregarProducto" class="btn-agregar">
                    + Agregar producto a la lista
                </button>
            </div>

        </section>

            {{-- PANEL 2: ALERTAS DE STOCK --}}
            <section class="ingreso-panel">

                <div class="panel-heading">
                    <!-- Icono de triángulo de advertencia -->
                    <span class="panel-icon"><i class="bi bi-exclamation-triangle" style="font-size: 1.5rem;"></i></span>

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
                            <!-- Icono de check / verificado -->
                            <span><i class="bi bi-check-circle" style="font-size: 1.5rem; color: #198754;"></i></span>
                            <p>No hay productos con stock bajo.</p>
                        </div>

                    @endforelse

                </div>

                <div class="nota-ingreso">
                    <!-- Icono de bombilla / idea -->
                    <span><i class="bi bi-lightbulb" style="font-size: 1.2rem; color: #ffc107;"></i></span>
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
                <!-- Icono de lista de verificación / portapapeles -->
                <span class="panel-icon"><i class="bi bi-card-checklist" style="font-size: 1.5rem;"></i></span>

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