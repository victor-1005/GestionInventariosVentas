
document.addEventListener('DOMContentLoaded', function () {

    // ==========================================
    // 1. ELEMENTOS DEL FORMULARIO
    // ==========================================

    const formVenta = document.getElementById('formVenta');

    const codigo = document.getElementById('codigo_producto');
    const nombre = document.getElementById('nombre_producto');
    const stockActual = document.getElementById('stock_actual');
    const cantidad = document.getElementById('cantidad_vendida');
    const precio = document.getElementById('precio');

    const btnBuscar = document.getElementById('btnBuscarProducto');
    const btnAgregar = document.getElementById('btnAgregarProducto');
    const btnGuardar = document.getElementById('btnGuardarVenta');

    const listaProductos = document.getElementById('listaProductos');

    const totalUnidades = document.getElementById('totalUnidades');
    const totalVenta = document.getElementById('totalVenta');


    // ==========================================
    // 2. VERIFICAR ELEMENTOS
    // ==========================================

    if (
        !formVenta ||
        !codigo ||
        !nombre ||
        !stockActual ||
        !cantidad ||
        !precio ||
        !btnBuscar ||
        !btnAgregar ||
        !btnGuardar ||
        !listaProductos ||
        !totalUnidades ||
        !totalVenta
    ) {

        console.error(
            'No se encontraron todos los elementos del formulario de ventas.'
        );

        return;

    }


    // ==========================================
    // 3. VARIABLES DE CONTROL
    // ==========================================

    let productosVenta = [];

    let productoSeleccionado = null;

    let indiceEdicion = null;

    let codigoConsultado = '';

    let buscandoProducto = false;

    let guardandoVenta = false;


    // ==========================================
    // 4. BUSCAR PRODUCTO
    // ==========================================

    // Buscar presionando Enter.
    codigo.addEventListener('keydown', function (event) {

        if (event.key === 'Enter') {

            event.preventDefault();

            buscarProducto();

        }

    });


    // Buscar presionando el botón.
    btnBuscar.addEventListener('click', function () {

        buscarProducto();

    });


    // Limpiar información cuando cambia el código.
    codigo.addEventListener('input', function () {

        if (codigo.value.trim() !== codigoConsultado) {

            productoSeleccionado = null;

            limpiarDatosProducto();

        }

    });


    // ==========================================
    // 5. FUNCIÓN DE BÚSQUEDA
    // ==========================================

    async function buscarProducto() {

        const valorCodigo = codigo.value.trim();

        if (!valorCodigo) {

            alert('Ingresa el código del producto.');

            codigo.focus();

            return;

        }

        if (buscandoProducto) {
            return;
        }

        try {

            buscandoProducto = true;

            btnBuscar.disabled = true;

            btnBuscar.textContent = 'Buscando...';

            const url = formVenta.dataset.buscarUrl;

            if (!url) {

                throw new Error(
                    'No se encontró la dirección de búsqueda.'
                );

            }

            const respuesta = await fetch(
                `${url}/${encodeURIComponent(valorCodigo)}`,
                {
                    method: 'GET',

                    headers: {
                        'Accept': 'application/json'
                    }
                }
            );

            if (!respuesta.ok) {

                throw new Error(
                    `Error HTTP ${respuesta.status}.`
                );

            }

            const datos = await respuesta.json();

            codigoConsultado = valorCodigo;

            // Producto inexistente.
            if (!datos.existe) {

                productoSeleccionado = null;

                limpiarDatosProducto();

                alert(
                    datos.mensaje ||
                    'Producto no encontrado en la base de datos.'
                );

                codigo.focus();

                return;

            }

            // Producto encontrado.
            const producto = datos.producto;

            productoSeleccionado = {

                id: producto.id,

                codigo: producto.codigo,

                nombre: producto.nombre,

                stockActual: Number(producto.stock_actual),

                precio: Number(producto.precio)

            };

            nombre.value = producto.nombre;

            stockActual.value = producto.stock_actual;

            precio.value = Number(
                producto.precio
            ).toFixed(2);

            cantidad.value = 1;

            alert(
                datos.mensaje ||
                'Producto encontrado. Datos recuperados.'
            );

            cantidad.focus();

        } catch (error) {

            console.error(
                'Error al buscar producto:',
                error
            );

            alert(
                'No se pudo consultar el producto. Revisa la consola del navegador.'
            );

        } finally {

            buscandoProducto = false;

            btnBuscar.disabled = false;

            btnBuscar.textContent = 'Buscar producto';

        }

    }


    // ==========================================
    // 6. LIMPIAR DATOS DEL PRODUCTO
    // ==========================================

    function limpiarDatosProducto() {

        nombre.value = '';

        stockActual.value = 0;

        cantidad.value = '';

        precio.value = '';

    }


    // ==========================================
    // 7. AGREGAR PRODUCTO A LA LISTA
    // ==========================================

    btnAgregar.addEventListener('click', function () {

        if (!productoSeleccionado) {

            alert(
                'Primero busca un producto existente.'
            );

            codigo.focus();

            return;

        }

        const cantidadIngresada = cantidad.value.trim();

        const cantidadNueva = Number(cantidadIngresada);

        if (
            cantidadIngresada === '' ||
            !Number.isInteger(cantidadNueva) ||
            cantidadNueva < 1
        ) {

            alert('Ingresa una cantidad válida.');

            cantidad.focus();

            return;

        }


        // ==========================================
        // 7.1 VALIDAR STOCK
        // ==========================================

        let cantidadReservada = 0;

        productosVenta.forEach(function (item, index) {

            if (
                item.productoId === productoSeleccionado.id &&
                index !== indiceEdicion
            ) {

                cantidadReservada += item.cantidad;

            }

        });

        if (
            cantidadNueva + cantidadReservada >
            productoSeleccionado.stockActual
        ) {

            alert(
                'La cantidad supera las existencias disponibles.'
            );

            return;

        }


        // ==========================================
        // 7.2 CREAR OBJETO DEL PRODUCTO
        // ==========================================

        const producto = {

            productoId: productoSeleccionado.id,

            codigo: productoSeleccionado.codigo,

            nombre: productoSeleccionado.nombre,

            stockActual: productoSeleccionado.stockActual,

            cantidad: cantidadNueva,

            precio: productoSeleccionado.precio

        };


        // ==========================================
        // 7.3 EDITAR PRODUCTO
        // ==========================================

        if (indiceEdicion !== null) {

            const duplicado = productosVenta.findIndex(
                (item, index) =>
                    item.productoId === producto.productoId &&
                    index !== indiceEdicion
            );

            if (duplicado !== -1) {

                alert(
                    'Este producto ya se encuentra en la lista.'
                );

                return;

            }

            productosVenta[indiceEdicion] = producto;

            indiceEdicion = null;

        } else {

            // Buscar si ya existe en la lista.
            const existente = productosVenta.find(
                item =>
                    item.productoId === producto.productoId
            );

            if (existente) {

                existente.cantidad += cantidadNueva;

            } else {

                productosVenta.push(producto);

            }

        }


        // ==========================================
        // 7.4 ACTUALIZAR INTERFAZ
        // ==========================================

        mostrarProductos();

        limpiarFormulario();

    });


    // ==========================================
    // 8. MOSTRAR PRODUCTOS EN LA TABLA
    // ==========================================

    function mostrarProductos() {

        listaProductos.innerHTML = '';

        if (productosVenta.length === 0) {

            const fila = document.createElement('tr');

            const celda = document.createElement('td');

            celda.colSpan = 6;

            celda.className = 'fila-vacia';

            celda.textContent =
                'Todavía no has agregado productos.';

            fila.appendChild(celda);

            listaProductos.appendChild(fila);

        } else {

            productosVenta.forEach(function (producto, index) {

                const subtotal =
                    producto.cantidad * producto.precio;

                const fila = document.createElement('tr');

                agregarCelda(fila, producto.codigo);

                agregarCelda(fila, producto.nombre);

                agregarCelda(fila, producto.cantidad);

                agregarCelda(
                    fila,
                    formatoMoneda(producto.precio)
                );

                agregarCelda(
                    fila,
                    formatoMoneda(subtotal)
                );


                // ==========================================
                // ACCIONES
                // ==========================================

                const celdaAcciones =
                    document.createElement('td');


                // Botón editar.
                const btnEditar =
                    document.createElement('button');

                btnEditar.type = 'button';

                btnEditar.textContent = 'Editar';

                btnEditar.className =
                    'btn-tabla btn-editar';

                btnEditar.addEventListener('click', function () {

                    editarProducto(index);

                });


                // Botón quitar.
                const btnEliminar =
                    document.createElement('button');

                btnEliminar.type = 'button';

                btnEliminar.textContent = 'Quitar';

                btnEliminar.className =
                    'btn-tabla btn-quitar';

                btnEliminar.addEventListener('click', function () {

                    eliminarProducto(index);

                });


                celdaAcciones.appendChild(btnEditar);

                celdaAcciones.appendChild(btnEliminar);

                fila.appendChild(celdaAcciones);

                listaProductos.appendChild(fila);

            });

        }

        calcularTotales();

    }


    // ==========================================
    // 9. AGREGAR CELDAS
    // ==========================================

    function agregarCelda(fila, valor) {

        const celda = document.createElement('td');

        celda.textContent = valor;

        fila.appendChild(celda);

    }


    // ==========================================
    // 10. CALCULAR TOTALES
    // ==========================================

    function calcularTotales() {

        let unidades = 0;

        let total = 0;

        productosVenta.forEach(function (producto) {

            unidades += producto.cantidad;

            total += producto.cantidad * producto.precio;

        });

        totalUnidades.textContent = unidades;

        totalVenta.textContent = formatoMoneda(total);

    }


    // ==========================================
    // 11. FORMATO DE MONEDA
    // ==========================================

    function formatoMoneda(valor) {

        return new Intl.NumberFormat('en-US', {

            style: 'currency',

            currency: 'USD'

        }).format(valor);

    }


    // ==========================================
    // 12. EDITAR PRODUCTO
    // ==========================================

    function editarProducto(index) {

        const producto = productosVenta[index];

        productoSeleccionado = {

            id: producto.productoId,

            codigo: producto.codigo,

            nombre: producto.nombre,

            stockActual: producto.stockActual,

            precio: producto.precio

        };

        codigo.value = producto.codigo;

        nombre.value = producto.nombre;

        stockActual.value = producto.stockActual;

        cantidad.value = producto.cantidad;

        precio.value = producto.precio.toFixed(2);

        codigoConsultado = producto.codigo;

        indiceEdicion = index;

        btnAgregar.textContent =
            'Actualizar producto de la lista';

        codigo.focus();

    }


    // ==========================================
    // 13. ELIMINAR PRODUCTO
    // ==========================================

    function eliminarProducto(index) {

        if (
            !confirm(
                '¿Deseas quitar este producto de la venta?'
            )
        ) {

            return;

        }

        productosVenta.splice(index, 1);

        if (indiceEdicion === index) {

            indiceEdicion = null;

            limpiarFormulario();

        } else if (
            indiceEdicion !== null &&
            indiceEdicion > index
        ) {

            indiceEdicion--;

        }

        mostrarProductos();

    }


    // ==========================================
    // 14. LIMPIAR FORMULARIO
    // ==========================================

    function limpiarFormulario() {

        codigo.value = '';

        limpiarDatosProducto();

        productoSeleccionado = null;

        codigoConsultado = '';

        indiceEdicion = null;

        btnAgregar.textContent =
            '+ Agregar producto a la lista';

        codigo.focus();

    }


    // ==========================================
    // 15. MOSTRAR ERRORES DEL SERVIDOR
    // ==========================================

    function mostrarErrores(resultado) {

        if (resultado.errors) {

            const mensajes = Object.values(
                resultado.errors
            )
            .flat()
            .join('\n');

            alert(
                'No se pudo realizar la venta:\n\n' +
                mensajes
            );

        } else {

            alert(
                resultado.mensaje ||
                'Ocurrió un error al realizar la transacción.'
            );

        }

    }


    // ==========================================
    // 16. GUARDAR TRANSACCIÓN
    // ==========================================

    formVenta.addEventListener('submit', async function (event) {

        event.preventDefault();

        if (guardandoVenta) {
            return;
        }

        if (productosVenta.length === 0) {

            alert(
                'Agrega al menos un producto a la venta.'
            );

            return;

        }

        if (indiceEdicion !== null) {

            alert(
                'Primero actualiza o cancela la edición del producto.'
            );

            return;

        }

        const token = formVenta.querySelector(
            'input[name="_token"]'
        )?.value;

        if (!token) {

            alert(
                'No se encontró el token de seguridad CSRF.'
            );

            return;

        }


        // Preparar los datos para Laravel.
        const datosVenta = {

            productos: productosVenta.map(producto => ({

                producto_id: producto.productoId,

                cantidad: producto.cantidad

            }))

        };


        try {

            guardandoVenta = true;

            btnGuardar.disabled = true;

            btnGuardar.textContent =
                'Procesando transacción...';


            const respuesta = await fetch(
                formVenta.action,
                {

                    method: 'POST',

                    headers: {

                        'Content-Type': 'application/json',

                        'Accept': 'application/json',

                        'X-CSRF-TOKEN': token

                    },

                    body: JSON.stringify(datosVenta)

                }
            );


            // Interpretar respuesta.
            const resultado = await respuesta.json();


            // Si ocurre algún error.
            if (!respuesta.ok) {

                mostrarErrores(resultado);

                return;

            }


            // ==========================================
            // TRANSACCIÓN EXITOSA
            // ==========================================

            alert(

                resultado.mensaje +

                '\nNúmero de transacción: ' +
                resultado.venta_id +

                '\nTotal: ' +
                formatoMoneda(Number(resultado.total))

            );


            // Actualizar pantalla, stock y alertas.
            window.location.reload();


        } catch (error) {

            console.error(
                'Error al guardar venta:',
                error
            );

            alert(
                'No fue posible comunicarse con el servidor.'
            );

        } finally {

            guardandoVenta = false;

            btnGuardar.disabled = false;

            btnGuardar.textContent =
                'Realizar transacción';

        }

    });


    // ==========================================
    // 17. INICIALIZAR
    // ==========================================

    mostrarProductos();

});
