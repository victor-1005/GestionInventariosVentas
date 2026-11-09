
@extends('encargado.layouts.app')

@section('titulo', 'Editar producto')
@section('encabezado', 'Editar producto')

@section('contenido')

<div class="editar-producto-wrapper">

    <div class="editar-producto-heading">
        <h2>Modificar información del producto</h2>
        <p>Actualiza los datos del producto seleccionado.</p>
    </div>

    {{-- ERRORES DE VALIDACIÓN --}}
    @if($errors->any())
        <div class="mensaje-existencia mensaje-error">
            <strong>Verifica los siguientes campos:</strong>

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="editar-producto-panel">

        <div class="panel-heading">
            <span class="panel-icon">📦</span>

            <div>
                <h3>Información del producto</h3>
                <p>
                    Código:
                    {{ $producto->codigo_producto }}
                </p>
            </div>
        </div>

        <form
            action="{{ route(
                'encargado.inventario.existencias.actualizar',
                $producto->id
            ) }}"
            method="POST"
        >

            @csrf
            @method('PUT')

            <div class="ingreso-grid">

                {{-- CÓDIGO --}}
                <div class="campo-ingreso">
                    <label for="codigo_producto">
                        Código del producto
                    </label>

                    <input
                        type="text"
                        id="codigo_producto"
                        name="codigo_producto"
                        value="{{ old('codigo_producto', $producto->codigo_producto) }}"
                        required
                    >
                </div>

                {{-- CATEGORÍA --}}
                <div class="campo-ingreso">
                    <label for="categoria_id">
                        Categoría
                    </label>

                    <select
                        id="categoria_id"
                        name="categoria_id"
                        required
                    >
                        <option value="">Seleccionar categoría</option>

                        @foreach($categorias as $categoria)

                            <option
                                value="{{ $categoria->id }}"
                                {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}
                            >
                                {{ $categoria->nombre_categoria }}
                            </option>

                        @endforeach

                    </select>
                </div>

                {{-- NOMBRE --}}
                <div class="campo-ingreso campo-completo">
                    <label for="nombre_producto">
                        Nombre del producto
                    </label>

                    <input
                        type="text"
                        id="nombre_producto"
                        name="nombre_producto"
                        value="{{ old('nombre_producto', $producto->nombre_producto) }}"
                        required
                    >
                </div>

                {{-- DESCRIPCIÓN --}}
                <div class="campo-ingreso campo-completo">
                    <label for="descripcion">
                        Descripción
                    </label>

                    <textarea
                        id="descripcion"
                        name="descripcion"
                        rows="3"
                    >{{ old('descripcion', $producto->descripcion) }}</textarea>
                </div>

                {{-- PRECIO --}}
                <div class="campo-ingreso">
                    <label for="precio">
                        Precio de venta
                    </label>

                    <input
                        type="number"
                        id="precio"
                        name="precio"
                        step="0.01"
                        min="0"
                        value="{{ old('precio', $producto->precio) }}"
                        required
                    >
                </div>

                {{-- STOCK ACTUAL --}}
                <div class="campo-ingreso">
                    <label for="stock_actual">
                        Stock actual
                    </label>

                    <input
                        type="number"
                        id="stock_actual"
                        name="stock_actual"
                        min="0"
                        step="1"
                        value="{{ old('stock_actual', $producto->stock_actual) }}"
                        required
                    >
                </div>

                {{-- STOCK MÍNIMO --}}
                <div class="campo-ingreso">
                    <label for="stock_minimo">
                        Stock mínimo
                    </label>

                    <input
                        type="number"
                        id="stock_minimo"
                        name="stock_minimo"
                        min="0"
                        step="1"
                        value="{{ old('stock_minimo', $producto->stock_minimo) }}"
                        required
                    >
                </div>

            </div>

            {{-- BOTONES --}}
            <div class="editar-producto-acciones">

                <a
                    href="{{ route('encargado.inventario.existencias') }}"
                    class="btn-regresar"
                >
                    Cancelar
                </a>

                <button type="submit" class="btn-guardar">
                    💾 Guardar cambios
                </button>

            </div>

        </form>

    </section>

</div>

@endsection
