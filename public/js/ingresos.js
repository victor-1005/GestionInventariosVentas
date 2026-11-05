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

    const totalUnidades = document.getElementById('totalUnidades');
    const totalIngreso = document.getElementById('totalIngreso');

    // ==========================================
    // 2. ARREGLO TEMPORAL DE PRODUCTOS
    // ==========================================

    let productosIngreso = [];

    // Esta variable nos permitirá saber si estamos
    // agregando un producto nuevo o editando uno de la lista.
    let indiceEdicion = null;

    // ==========================================
    // 3. AGREGAR PRODUCTO A LA LISTA
    // ==========================================

    btnAgregar.addEventListener('click', function () {

        // Recuperamos los valores del formulario.
        const producto = {
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

        // Validamos los campos obligatorios.
        if (
            !producto.codigo ||
            !producto.categoriaId ||
            !producto.nombre ||
            !producto.cantidad ||
            !producto.precioCosto ||
            !producto.precioVenta ||
            !Number.isInteger(producto.cantidad) ||
            producto.cantidad < 1 ||
            producto.precioCosto < 0 ||
            producto.precioVenta < 0 ||
            !Number.isInteger(producto.stockMinimo) ||
            producto.stockMinimo < 0
        ) {
            alert('Completa correctamente los datos del producto.');
            return;
        }

        // Evitamos agregar dos veces el mismo código.
        const codigoDuplicado = productosIngreso.some(
            (item, index) =>
                item.codigo.toLowerCase() === producto.codigo.toLowerCase()
                && index !== indiceEdicion
        );

        if (codigoDuplicado) {
            alert('Este producto ya está agregado a la lista.');
            return;
        }

        // Si estamos editando, reemplazamos el producto.
        if (indiceEdicion !== null) {
            productosIngreso[indiceEdicion] = producto;
            indiceEdicion = null;
            btnAgregar.textContent = '+ Agregar producto a la lista';
        } else {
            // Si es nuevo, lo agregamos al arreglo.
            productosIngreso.push(producto);
        }

        // Actualizamos la tabla y los totales.
        mostrarProductos();
        limpiarFormulario();
    });

    // ==========================================
    // 4. MOSTRAR PRODUCTOS EN LA TABLA
    // ==========================================

    function mostrarProductos() {

        // Limpiamos el contenido anterior.
        listaProductos.innerHTML = '';

        // Si no hay productos, mostramos el mensaje.
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

            // Recorremos todos los productos agregados.
            productosIngreso.forEach(function (producto, index) {

                const subtotal = producto.cantidad * producto.precioCosto;

                const fila = document.createElement('tr');

                // Código
                agregarCelda(fila, producto.codigo);

                // Nombre del producto
                agregarCelda(fila, producto.nombre);

                // Cantidad recibida
                agregarCelda(fila, producto.cantidad);

                // Costo unitario
                agregarCelda(fila, formatoMoneda(producto.precioCosto));

                // Subtotal
                agregarCelda(fila, formatoMoneda(subtotal));

                // Acciones
                const celdaAcciones = document.createElement('td');

                const btnEditar = document.createElement('button');
                btnEditar.type = 'button';
                btnEditar.textContent = 'Editar';
                btnEditar.className = 'btn-tabla btn-editar';

                btnEditar.addEventListener('click', function () {
                    editarProducto(index);
                });

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

        // Actualizamos los totales generales.
        calcularTotales();
    }

    // Función auxiliar para agregar celdas de texto.
    function agregarCelda(fila, valor) {

        const celda = document.createElement('td');
        celda.textContent = valor;

        fila.appendChild(celda);
    }

    // ==========================================
    // 5. CALCULAR TOTALES
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

    // Formato de moneda.
    function formatoMoneda(valor) {

        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(valor);
    }

    // ==========================================
    // 6. EDITAR PRODUCTO DE LA LISTA
    // ==========================================

    function editarProducto(index) {

        const producto = productosIngreso[index];

        codigo.value = producto.codigo;
        categoria.value = producto.categoriaId;
        nombre.value = producto.nombre;
        descripcion.value = producto.descripcion;
        stockActual.value = producto.stockActual;
        stockMinimo.value = producto.stockMinimo;
        cantidad.value = producto.cantidad;
        precioCosto.value = producto.precioCosto;
        precioVenta.value = producto.precioVenta;

        indiceEdicion = index;

        btnAgregar.textContent = 'Actualizar producto de la lista';

        // Llevamos al usuario al formulario.
        codigo.focus();

    }

    // ==========================================
    // 7. ELIMINAR PRODUCTO DE LA LISTA
    // ==========================================

    function eliminarProducto(index) {

        const confirmar = confirm(
            '¿Estás seguro de quitar este producto de la lista?'
        );

        if (!confirmar) {
            return;
        }

        productosIngreso.splice(index, 1);

        // Si se elimina el producto que se estaba editando,
        // cancelamos la edición.
        if (indiceEdicion === index) {
            indiceEdicion = null;
            btnAgregar.textContent = '+ Agregar producto a la lista';
            limpiarFormulario();
        } else if (indiceEdicion !== null && indiceEdicion > index) {
            indiceEdicion--;
        }

        mostrarProductos();
    }

    // ==========================================
    // 8. LIMPIAR FORMULARIO
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

        codigo.focus();
    }

    // ==========================================
    // 9. INICIALIZAR LA TABLA
    // ==========================================

    mostrarProductos();

});

