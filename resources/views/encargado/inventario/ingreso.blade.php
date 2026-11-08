@extends('encargado.layouts.app')

@section('titulo', 'Registrar ingreso de inventario')
@section('encabezado', 'Registrar ingreso de inventario')

@section('contenido')

<div class="ingreso-wrapper">

    {{-- ENCABEZADO DE LA PANTALLA --}}
    <div class="ingreso-heading">
        <div>
            <h2>Nuevo ingreso de productos</h2>
            <p>Registra los productos recibidos de tus proveedores.</p>
        </div>
    </div>

    {{-- FORMULARIO GENERAL --}}
    <form id="formIngreso" method="POST" action="#">
        @csrf

        <div class="ingreso-paneles">

            {{-- PANEL 1: INFORMACIÓN DEL PRODUCTO --}}
            <section class="ingreso-panel">

                <div class="panel-heading">
                    <span class="panel-icon">📦</span>
                    <div>
                        <h3>Información del producto</h3>
                        <p>Busca un producto existente o registra uno nuevo.</p>
                    </div>
                </div>

                <div class="ingreso-grid">

                    <div class="campo-ingreso">
                        <label for="codigo_producto">Código del producto</label>
                        <input type="text" id="codigo_producto"
                            placeholder="Ej. PROD-001">
                    </div>

                    <div class="campo-ingreso">
                        <label for="categoria_id">Categoría</label>
                        <select id="categoria_id" name="categoria_id" required>
                            <option value="">Seleccionar categoría</option>

                            @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}">
                                {{ $categoria->nombre_categoria }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="campo-ingreso campo-completo">
                        <label for="nombre_producto">Nombre del producto</label>
                        <input type="text" id="nombre_producto"
                            placeholder="Nombre del producto">
                    </div>

                    <div class="campo-ingreso campo-completo">
                        <label for="descripcion">Descripción</label>
                        <textarea id="descripcion" rows="2"
                            placeholder="Descripción del producto"></textarea>
                    </div>

                    <div class="campo-ingreso">
                        <label for="stock_actual">Stock actual</label>
                        <input type="number" id="stock_actual"
                            value="0" readonly>
                    </div>

                    <div class="campo-ingreso">
                        <label for="stock_minimo">Stock mínimo</label>
                        <input type="number" id="stock_minimo"
                            min="0" value="5">
                    </div>

                    <div class="campo-ingreso">
                        <label for="cantidad_recibida">Cantidad recibida</label>
                        <input type="number" id="cantidad_recibida"
                            min="1" placeholder="0">
                    </div>

                    <div class="campo-ingreso">
                        <label for="precio_costo">Precio de costo</label>
                        <input type="number" id="precio_costo"
                            min="0" step="0.01" placeholder="0.00">
                    </div>

                    <div class="campo-ingreso">
                        <label for="precio_venta">Precio de venta</label>
                        <input type="number" id="precio_venta"
                            min="0" step="0.01" placeholder="0.00">
                    </div>

                </div>

                <div class="panel-actions">
                    <button type="button" id="btnAgregarProducto"
                        class="btn-agregar">
                        + Agregar producto a la lista
                    </button>
                </div>

            </section>

            {{-- PANEL 2: DOCUMENTO Y PROVEEDOR --}}
            <section class="ingreso-panel">

                <div class="panel-heading">
                    <span class="panel-icon">📄</span>
                    <div>
                        <h3>Información del ingreso</h3>
                        <p>Datos de la factura o documento recibido.</p>
                    </div>
                </div>

                <div class="ingreso-grid">

                    <div class="campo-ingreso campo-completo">
                        <label for="numero_documento">Número de documento</label>
                        <input type="text" id="numero_documento"
                            name="numero_documento"
                            placeholder="Ej. FAC-001"
                            required>
                    </div>

                    <div class="campo-ingreso campo-completo">
                        <label for="fecha_ingreso">Fecha de ingreso</label>
                        <input type="date" id="fecha_ingreso"
                            name="fecha_ingreso"
                            required>
                    </div>

                    <div class="campo-ingreso campo-completo">
                        <label for="proveedor_id">Proveedor</label>
                        <select id="proveedor_id" name="proveedor_id" required>
                            <option value="">Seleccionar proveedor</option>

                            @foreach($proveedores as $proveedor)
                            <option value="{{ $proveedor->id }}">
                                {{ $proveedor->nombre_entidad }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="nota-ingreso">
                    <span>💡</span>
                    <p>
                        Los datos del documento se utilizarán para registrar
                        todos los productos agregados a esta compra.
                    </p>
                </div>

            </section>

        </div>

        {{-- PANEL 3: LISTA DE PRODUCTOS --}}
        <section class="lista-ingreso-panel">

            <div class="panel-heading">
                <span class="panel-icon">📋</span>
                <div>
                    <h3>Productos del ingreso</h3>
                    <p>Revisa los productos antes de guardar el ingreso.</p>
                </div>
            </div>


            <div class="tabla-contenedor">
                <table class="tabla-ingresos">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Costo unitario</th>
                            <th>Subtotal</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody id="listaProductos">
                        <tr id="filaVacia">
                            <td colspan="6" class="fila-vacia">
                                Todavía no has agregado productos.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- RESUMEN DEL INGRESO --}}
            <div class="resumen-ingreso">
                <div class="resumen-total">
                    <span>Total de productos:</span>
                    <strong id="totalUnidades">0</strong>
                </div>

                <div class="resumen-total resumen-principal">
                    <span>Total del ingreso:</span>
                    <strong id="totalIngreso">$0.00</strong>
                </div>
            </div>


        </section>

    </form>

</div>

@endsection
<!-- para el js -->
<script src="{{ asset('js/ingresos.js') }}"></script>