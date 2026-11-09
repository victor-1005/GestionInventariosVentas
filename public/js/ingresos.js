document.addEventListener('DOMContentLoaded', function () {

    // ==========================================
    // 1. ELEMENTOS DEL FORMULARIO
    // ==========================================

    const codigo = document.getElementById('codigo_producto');
    const categoria = document.getElementById('categoria_id');
    const nombre = document.getElementById('nombre_producto');
    const descripcion = document.getElementById('descripcion');
    const stockActual = document.getElementById('stock_actual');
    const stockMinimo = document.getElementById('stock_minimo');
    const cantidad = document.getElementById('cantidad_recibida');
    const precioCosto = document.getElementById('precio_costo');
    const precioVenta = document.getElementById('precio_venta');

    const btnAgregar = document.getElementById('btnAgregarProducto');
    const listaProductos = document.getElementById('listaProductos');

    const formIngreso = document.getElementById('formIngreso');
    const btnGuardarIngreso = document.getElementById('btnGuardarIngreso');

    const totalUnidades = document.getElementById('totalUnidades');
    const totalIngreso = document.getElementById('totalIngreso');

    // ==========================================
    // 2. VARIABLES DE CONTROL
    // ==========================================

    // Arreglo temporal donde guardaremos los productos.
    let productosIngreso = [];

    // Identifica si estamos editando un producto de la lista.
    let indiceEdicion = null;

    // Identifica si el producto existe en MySQL.
    let productoEncontrado = false;
    let idProductoEncontrado = null;

    // Guarda el último código que fue consultado.
    let codigoConsultado = '';

    // ==========================================
    // 3. BUSCAR PRODUCTOS EN LARAVEL
    // ==========================================

    // Buscar producto cuando se presiona Enter.
    codigo.addEventListener('keydown', function (event) {

        if (event.key === 'Enter') {
            event.preventDefault();
            buscarProducto();
        }

    });

    // Detectar cuando cambia el código del producto.
    codigo.addEventListener('input', function () {

        if (codigo.value.trim() !== codigoConsultado) {

            productoEncontrado = false;
            idProductoEncontrado = null;

            limpiarDatosProducto();

        }

    });

    // ==========================================
    // 3.1 CONSULTAR PRODUCTO POR CÓDIGO
    // ==========================================

    async function buscarProducto() {

        const valorCodigo = codigo.value.trim();

        if (!valorCodigo) {
            return;
        }

        try {

            const respuesta = await fetch(
                `/encargado/inventario/productos/buscar/${encodeURIComponent(valorCodigo)}`
            );

            if (!respuesta.ok) {
                throw new Error('Error al consultar el producto.');
            }

            const datos = await respuesta.json();

            codigoConsultado = valorCodigo;

            if (datos.existe) {

                const producto = datos.producto;

                // Guardamos el ID del producto existente.
                productoEncontrado = true;
                idProductoEncontrado = producto.id;

                // Recuperamos sus datos.
                categoria.value = producto.categoria_id;
                nombre.value = producto.nombre;
                descripcion.value = producto.descripcion ?? '';
                stockActual.value = producto.stock_actual;
                stockMinimo.value = producto.stock_minimo;
                precioVenta.value = producto.precio_venta;

                alert('Producto encontrado. Datos recuperados.');

            } else {

                productoEncontrado = false;
                idProductoEncontrado = null;

                limpiarDatosProducto();

                alert('Producto no encontrado. Puedes registrar uno nuevo.');

            }

        } catch (error) {

            console.error(error);

            alert('No se pudo consultar el producto.');

        }

    }

    // ==========================================
    // 3.2 LIMPIAR DATOS DEL PRODUCTO
    // ==========================================

    function limpiarDatosProducto() {

        categoria.value = '';
        nombre.value = '';
        descripcion.value = '';
        stockActual.value = 0;
        stockMinimo.value = 5;
        precioVenta.value = '';

    }

    // ==========================================
    // 4. AGREGAR PRODUCTO A LA LISTA
    // ==========================================

    btnAgregar.addEventListener('click', function () {

        // Recuperamos los valores del formulario.
        const producto = {

            // ID de MySQL. Será null si es un producto nuevo.
            productoId: productoEncontrado
                ? idProductoEncontrado
                : null,

            codigo: codigo.value.trim(),

            categoriaId: categoria.value,

            categoriaNombre: categoria.options[categoria.selectedIndex]?.text || '',

            nombre: nombre.value.trim(),

            descripcion: descripcion.value.trim(),

            stockActual: Number(stockActual.value),

            stockMinimo: Number(stockMinimo.value),

            cantidad: Number(cantidad.value),

            precioCosto: Number(precioCosto.value),

            precioVenta: Number(precioVenta.value)

        };

        // ==========================================
        // 4.1 VALIDAR CAMPOS OBLIGATORIOS
        // ==========================================

        if (
            !producto.codigo ||
            !producto.categoriaId ||
            !producto.nombre ||

            cantidad.value.trim() === '' ||
            precioCosto.value.trim() === '' ||
            precioVenta.value.trim() === '' ||

            !Number.isInteger(producto.cantidad) ||
            producto.cantidad < 1 ||

            !Number.isFinite(producto.precioCosto) ||
            producto.precioCosto < 0 ||

            !Number.isFinite(producto.precioVenta) ||
            producto.precioVenta < 0 ||

            !Number.isInteger(producto.stockActual) ||
            producto.stockActual < 0 ||

            !Number.isInteger(producto.stockMinimo) ||
            producto.stockMinimo < 0
        ) {

            alert('Completa correctamente los datos del producto.');

            return;

        }

        // ==========================================
        // 4.2 EVITAR CÓDIGOS DUPLICADOS
        // ==========================================

        const codigoDuplicado = productosIngreso.some(
            (item, index) =>
                item.codigo.toLowerCase() === producto.codigo.toLowerCase()
                && index !== indiceEdicion
        );

        if (codigoDuplicado) {

            alert('Este producto ya está agregado a la lista.');

            return;

        }

        // ==========================================
        // 4.3 AGREGAR O ACTUALIZAR PRODUCTO
        // ==========================================

        if (indiceEdicion !== null) {

            // Actualizamos el producto seleccionado.
            productosIngreso[indiceEdicion] = producto;

            indiceEdicion = null;

            btnAgregar.textContent = '+ Agregar producto a la lista';

        } else {

            // Agregamos un producto nuevo al arreglo.
            productosIngreso.push(producto);

        }

        // Actualizamos la tabla y los totales.
        mostrarProductos();

        // Limpiamos el formulario.
        limpiarFormulario();

    });

    // ==========================================
    // 5. MOSTRAR PRODUCTOS EN LA TABLA
    // ==========================================

    function mostrarProductos() {

        // Limpiamos el contenido anterior.
        listaProductos.innerHTML = '';

        // Si no hay productos, mostramos un mensaje.
        if (productosIngreso.length === 0) {

            const fila = document.createElement('tr');

            fila.id = 'filaVacia';

            const celda = document.createElement('td');

            celda.colSpan = 6;

            celda.className = 'fila-vacia';

            celda.textContent = 'Todavía no has agregado productos.';

            fila.appendChild(celda);

            listaProductos.appendChild(fila);

        } else {

            // Recorremos los productos agregados.
            productosIngreso.forEach(function (producto, index) {

                const subtotal = producto.cantidad * producto.precioCosto;

                const fila = document.createElement('tr');

                // Código del producto.
                agregarCelda(fila, producto.codigo);

                // Nombre del producto.
                agregarCelda(fila, producto.nombre);

                // Cantidad recibida.
                agregarCelda(fila, producto.cantidad);

                // Costo unitario.
                agregarCelda(fila, formatoMoneda(producto.precioCosto));

                // Subtotal.
                agregarCelda(fila, formatoMoneda(subtotal));

                // ==========================================
                // ACCIONES DE LA TABLA
                // ==========================================

                const celdaAcciones = document.createElement('td');

                // Botón editar.
                const btnEditar = document.createElement('button');

                btnEditar.type = 'button';

                btnEditar.textContent = 'Editar';

                btnEditar.className = 'btn-tabla btn-editar';

                btnEditar.addEventListener('click', function () {

                    editarProducto(index);

                });

                // Botón eliminar.
                const btnEliminar = document.createElement('button');

                btnEliminar.type = 'button';

                btnEliminar.textContent = 'Quitar';

                btnEliminar.className = 'btn-tabla btn-eliminar';

                btnEliminar.addEventListener('click', function () {

                    eliminarProducto(index);

                });

                celdaAcciones.appendChild(btnEditar);

                celdaAcciones.appendChild(btnEliminar);

                fila.appendChild(celdaAcciones);

                listaProductos.appendChild(fila);

            });

        }

        // Actualizamos los totales.
        calcularTotales();

    }

    // ==========================================
    // 5.1 AGREGAR CELDAS A LA TABLA
    // ==========================================

    function agregarCelda(fila, valor) {

        const celda = document.createElement('td');

        celda.textContent = valor;

        fila.appendChild(celda);

    }

    // ==========================================
    // 6. CALCULAR TOTALES
    // ==========================================

    function calcularTotales() {

        let unidades = 0;

        let total = 0;

        productosIngreso.forEach(function (producto) {

            unidades += producto.cantidad;

            total += producto.cantidad * producto.precioCosto;

        });

        totalUnidades.textContent = unidades;

        totalIngreso.textContent = formatoMoneda(total);

    }

    // ==========================================
    // 6.1 FORMATO DE MONEDA
    // ==========================================

    function formatoMoneda(valor) {

        return new Intl.NumberFormat('en-US', {

            style: 'currency',

            currency: 'USD'

        }).format(valor);

    }

    // ==========================================
    // 7. EDITAR PRODUCTO DE LA LISTA
    // ==========================================

    function editarProducto(index) {

        const producto = productosIngreso[index];

        // Recuperamos el identificador de MySQL.
        productoEncontrado = producto.productoId != null;

        idProductoEncontrado = productoEncontrado
            ? producto.productoId
            : null;

        codigoConsultado = producto.codigo;

        // Recuperamos los datos en el formulario.
        codigo.value = producto.codigo;

        categoria.value = producto.categoriaId;

        nombre.value = producto.nombre;

        descripcion.value = producto.descripcion;

        stockActual.value = producto.stockActual;

        stockMinimo.value = producto.stockMinimo;

        cantidad.value = producto.cantidad;

        precioCosto.value = producto.precioCosto;

        precioVenta.value = producto.precioVenta;

        // Guardamos el índice que estamos editando.
        indiceEdicion = index;

        btnAgregar.textContent = 'Actualizar producto de la lista';

        // Llevamos al usuario al formulario.
        codigo.focus();

    }

    // ==========================================
    // 8. ELIMINAR PRODUCTO DE LA LISTA
    // ==========================================

    function eliminarProducto(index) {

        const confirmar = confirm(
            '¿Estás seguro de quitar este producto de la lista?'
        );

        if (!confirmar) {
            return;
        }

        // Eliminamos el producto del arreglo.
        productosIngreso.splice(index, 1);

        // Si eliminamos el producto que se estaba editando,
        // cancelamos la edición.
        if (indiceEdicion === index) {

            indiceEdicion = null;

            btnAgregar.textContent = '+ Agregar producto a la lista';

            limpiarFormulario();

        } else if (indiceEdicion !== null && indiceEdicion > index) {

            indiceEdicion--;

        }

        // Actualizamos la tabla.
        mostrarProductos();

    }

    // ==========================================
    // 9. LIMPIAR FORMULARIO
    // ==========================================

    function limpiarFormulario() {

        codigo.value = '';

        categoria.value = '';

        nombre.value = '';

        descripcion.value = '';

        stockActual.value = 0;

        stockMinimo.value = 5;

        cantidad.value = '';

        precioCosto.value = '';

        precioVenta.value = '';

        // Reiniciamos la identificación del producto.
        productoEncontrado = false;

        idProductoEncontrado = null;

        codigoConsultado = '';

        codigo.focus();

    }


    // ==========================================
    // 10. GUARDAR INGRESO EN LA BASE DE DATOS
    // ==========================================

    formIngreso.addEventListener('submit', async function (event) {

        event.preventDefault();

        // Evitar guardar una lista vacía.
        if (productosIngreso.length === 0) {
            alert('Agrega al menos un producto a la lista.');
            return;
        }

        // Recuperar información general del ingreso.
        const numeroDocumento = document.getElementById('numero_documento').value.trim();
        const fechaIngreso = document.getElementById('fecha_ingreso').value;
        const proveedorId = document.getElementById('proveedor_id').value;

        // Validar encabezado.
        if (!numeroDocumento || !fechaIngreso || !proveedorId) {
            alert('Completa los datos del documento y proveedor.');
            return;
        }

        // Obtener token CSRF.
        const token = formIngreso.querySelector('input[name="_token"]').value;

        // Preparar información para Laravel.
        const datosIngreso = {
            numero_documento: numeroDocumento,
            fecha_ingreso: fechaIngreso,
            proveedor_id: proveedorId,
            productos: productosIngreso.map(producto => ({
                codigo: producto.codigo,
                categoriaId: producto.categoriaId,
                nombre: producto.nombre,
                descripcion: producto.descripcion,
                cantidad: producto.cantidad,
                precioCosto: producto.precioCosto,
                precioVenta: producto.precioVenta,
                stockMinimo: producto.stockMinimo
            }))
        };

        try {

            // Deshabilitar botón mientras se guarda.
            btnGuardarIngreso.disabled = true;
            btnGuardarIngreso.textContent = 'Guardando ingreso...';

            const respuesta = await fetch(formIngreso.action, {

                method: 'POST',

                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },

                body: JSON.stringify(datosIngreso)

            });

            const resultado = await respuesta.json();

            // Si Laravel devuelve errores de validación.
            if (!respuesta.ok) {

                if (respuesta.status === 422 && resultado.errors) {

                    const mensajes = Object.values(resultado.errors)
                        .flat()
                        .join('\n');

                    alert('No se pudo guardar el ingreso:\n' + mensajes);

                } else {

                    alert(resultado.mensaje || 'Ocurrió un error al guardar el ingreso.');

                }

                return;
            }

            // Confirmar guardado.
            alert(resultado.mensaje);

            // Limpiar lista provisional.
            productosIngreso = [];

            indiceEdicion = null;

            mostrarProductos();

            limpiarFormulario();

            // Limpiar información del documento.
            document.getElementById('numero_documento').value = '';
            document.getElementById('fecha_ingreso').value = '';
            document.getElementById('proveedor_id').value = '';

            btnAgregar.textContent = '+ Agregar producto a la lista';

        } catch (error) {

            console.error('Error al guardar ingreso:', error);

            alert('No fue posible comunicarse con el servidor.');

        } finally {

            btnGuardarIngreso.disabled = false;
            btnGuardarIngreso.textContent = '💾 Guardar ingreso en la BD';

        }

    });


    // ==========================================
    // 11. INICIALIZAR LA TABLA
    // ==========================================

    mostrarProductos();

});


