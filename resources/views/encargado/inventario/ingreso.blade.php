@extends('encargado.layouts.app')

@section('titulo', 'Registrar ingreso de inventario')
@section('encabezado', 'Registrar ingreso de inventario')

@section('contenido')

{{-- ESTILOS PARA DARLE EL TOQUE MODERNO DE LA IMAGEN --}}
<style>
    /* Banner superior con gradiente sutil */
    .ingreso-heading {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-left: 5px solid #4e73df;
        padding: 1.5rem 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
    }
    
    /* Tarjetas de los paneles */
    .ingreso-panel, .lista-ingreso-panel {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        border: 1px solid #f0f0f0;
        transition: transform 0.2s ease;
    }
    
    .ingreso-panel:hover {
        transform: translateY(-2px);
    }

    /* Iconos dentro de los paneles */
    .panel-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 45px;
        height: 45px;
        background: #eef2ff; /* Azul muy claro */
        color: #4e73df; /* Azul principal */
        border-radius: 10px;
        font-size: 1.4rem;
        margin-right: 15px;
    }

    /* Botones con gradiente */
    .btn-agregar {
        background: linear-gradient(to right, #4e73df, #224abe);
        color: white;
        border: none;
        padding: 0.6rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(78, 115, 223, 0.3);
    }

    .btn-agregar:hover {
        background: linear-gradient(to right, #224abe, #4e73df);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(78, 115, 223, 0.4);
    }
</style>

<div class="ingreso-wrapper">

    {{-- ENCABEZADO DE LA PANTALLA (Estilo Banner) --}}
    <div class="ingreso-heading">
        <div>
            <h2 style="margin: 0; color: #224abe; font-weight: 700;">Nuevo ingreso de productos</h2>
            <p style="margin: 0.5rem 0 0 0; color: #6c757d;">Registra los productos recibidos de tus proveedores.</p>
        </div>
    </div>

    {{-- FORMULARIO GENERAL --}}
    <form id="formIngreso" method="POST" action="{{ route('encargado.inventario.ingresos.guardar') }}">
        @csrf

        <div class="ingreso-paneles">

            {{-- PANEL 1: INFORMACIÓN DEL PRODUCTO --}}
            <section class="ingreso-panel">

                <div class="panel-heading" style="display: flex; align-items: center; border-bottom: 1px solid #f0f0f0; padding-bottom: 1rem; margin-bottom: 1.5rem;">
                    <span class="panel-icon"><i class="bi bi-box-seam"></i></span>
                    <div>
                        <h3 style="margin: 0; font-size: 1.2rem; color: #333;">Información del producto</h3>
                        <p style="margin: 0; font-size: 0.9rem; color: #6c757d;">Busca un producto existente o registra uno nuevo.</p>
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
                        <select id="categoria_id" name="categoria_id" >
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
                            value="0" readonly style="background-color: #f8f9fa;">
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
                        <i class="bi bi-plus-circle"></i> Agregar producto a la lista
                    </button>
                </div>

            </section>

            {{-- PANEL 2: DOCUMENTO Y PROVEEDOR --}}
            <section class="ingreso-panel">

                <div class="panel-heading" style="display: flex; align-items: center; border-bottom: 1px solid #f0f0f0; padding-bottom: 1rem; margin-bottom: 1.5rem;"> 
                    <span class="panel-icon"><i class="bi bi-file-earmark-text"></i></span>
                    <div>
                        <h3 style="margin: 0; font-size: 1.2rem; color: #333;">Información del ingreso</h3>
                        <p style="margin: 0; font-size: 0.9rem; color: #6c757d;">Datos de la factura o documento recibido.</p>
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

                <div class="nota-ingreso" style="background: #f8f9fa; padding: 1rem; border-radius: 8px; display: flex; align-items: center; margin-top: 1.5rem;">
                    <span style="margin-right: 10px; color: #4e73df; font-size: 1.2rem;"><i class="bi bi-lightbulb"></i></span>
                    <p style="margin: 0; font-size: 0.9rem; color: #6c757d;">
                        Los datos del documento se utilizarán para registrar todos los productos agregados a esta compra.
                    </p>
                </div>

            </section>

        </div>

        {{-- PANEL 3: LISTA DE PRODUCTOS --}}
        <section class="lista-ingreso-panel">

            <div class="panel-heading" style="display: flex; align-items: center; border-bottom: 1px solid #f0f0f0; padding-bottom: 1rem; margin-bottom: 1.5rem;">
                <span class="panel-icon"><i class="bi bi-card-checklist"></i></span>
                <div>
                    <h3 style="margin: 0; font-size: 1.2rem; color: #333;">Productos del ingreso</h3>
                    <p style="margin: 0; font-size: 0.9rem; color: #6c757d;">Revisa los productos antes de guardar el ingreso.</p>
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
            <div class="resumen-ingreso" style="background: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 2rem;">
                <div class="resumen-total">
                    <span>Total de productos:</span>
                    <strong id="totalUnidades">0</strong>
                </div>

                <div class="resumen-total resumen-principal">
                    <span style="font-weight: 600; color: #224abe;">Total del ingreso:</span>
                    <strong id="totalIngreso" style="font-size: 1.3rem; color: #224abe;">$0.00</strong>
                </div>
            </div>

            {{-- ACCIONES DEL INGRESO --}}
            <div class="panel-actions" style="margin-top: 1.5rem; text-align: right;">
                <button type="submit" id="btnGuardarIngreso" class="btn-agregar" style="padding: 0.8rem 2rem; font-size: 1rem;">
                    <i class="bi bi-save"></i> Guardar ingreso de inventario
                </button>
            </div>
        </section>


    </form>

</div>

@endsection

<!-- para el js -->
<script src="{{ asset('js/ingresos.js') }}"></script>