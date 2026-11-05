@extends('encargado.layouts.app')

@section('titulo','Panel de Inventario')

@section('encabezado','Panel de Ingresos')

@section('contenido')
<div class="forms-wrapper">
    <div class="forms-container">

        <div class="forms-icon">🔖</div>

        <span class="forms-label">MODULO DE INGRESOS DE INVENTARIO</span>

        <h2>Ingresa tus productos de entrada</h2>

        <p class="forms-description">
            Ingresa los productos que estas recibiendo de tu proveedor.
        </p>
        @if ($errors->any())
        <div class="login-alert">
            @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form action="#">
            <div class="forms-form-group">
                <label for="codigoProducto">Ingresa el codigo del producto</label>

                <input
                    type="text"
                    id="codigoProducto"
                    name="codigoProducto"
                    value="{{ old('usuario') }}"
                    placeholder="Ingresa el codigo del producto"
                    required
                    focus>
            </div>

            <div class="forms-form-group">
                <label for="categoriaProducto">Ingresa la categoria del producto</label>
                <select name="categoriaProducto" id="categoriaProducto" size="3">
                    <option value="Seleccionar una opción">Seleccionar una opción</option>
                </select>
                <!-- <input
                        type="text"
                        id="categoriaProducto"
                        name="categoriaProducto"
                        value="{{ old('usuario') }}"
                        placeholder="Ingresa la categoria del producto"
                        required
                    > -->
            </div>

            <div class="forms-form-group">
                <label for="nombreProducto">Ingresa el nombre del producto</label>

                <input
                    type="text"
                    id="nombreProducto"
                    name="nombreProducto"
                    value="{{ old('usuario') }}"
                    placeholder="Ingresa el nombre del producto"
                    required>
            </div>

            <div class="forms-form-group">
                <label for="descripcionProducto">Ingresa la descripción del producto</label>

                <input
                    type="text"
                    id="descripcionProducto"
                    name="descripcionProducto"
                    value="{{ old('usuario') }}"
                    placeholder="Ingresa la descripción del producto"
                    required>
            </div>

            <div class="forms-form-group">
                <label for="precioProducto">Ingresa el precio del producto</label>

                <input
                    type="text"
                    id="precioProducto"
                    name="precioProducto"
                    value="{{ old('usuario') }}"
                    placeholder="Ingresa el precio del producto"
                    required>
            </div>

            <div class="forms-form-group">
                <label for="stockProducto">Stock actual del producto</label>

                <input
                    type="text"
                    id="stockProducto"
                    name="stockProducto"
                    value="{{ old('usuario') }}"
                    placeholder="Stock actual del producto"
                    readonly>
            </div>

            <div class="forms-form-group">
                <label for="stockIngresoProducto">Ingrese la cantidad a añadir del producto</label>

                <input
                    type="text"
                    id="stockIngresoProducto"
                    name="stockIngresoProducto"
                    value="{{ old('usuario') }}"
                    placeholder="Adición del producto"
                    >
            </div>

            <div class="forms-form-group">
                <label for="stockMinimoProducto">Ingrese el Stock minimo de reserva del producto</label>

                <input
                    type="text"
                    id="stockMinimoProducto"
                    name="stockMinimoProducto"
                    value="{{ old('usuario') }}"
                    placeholder="Stock minimo del producto"
                    required>
            </div>

            <button type="submit" class="forms-button">
                Guardar
                <span>💾</span>
            </button>
        </form>

        <a href="{{ route('encargado.inventario.index') }}" class="forms-back">
            ← Volver a la menu de inventario
        </a>

    </div>

</div>
@endsection