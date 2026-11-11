@extends('encargado.layouts.app')

@section('titulo', 'Administrar categorías')
@section('encabezado', 'Gestión de categorías')

@section('contenido')

<div class="ingreso-wrapper">

    {{-- ENCABEZADO --}}
    <div class="ingreso-heading">
        <div>
            <h2>Registrar nueva categoría</h2>
            <p>
                Administra las categorías utilizadas
                para clasificar los productos.
            </p>
        </div>
    </div>

    {{-- MENSAJE DE ÉXITO --}}
    @if(session('success'))
        <div class="nota-ingreso">
            <span>✅</span>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    {{-- FORMULARIO --}}
    <section class="ingreso-panel">

        <div class="panel-heading">
            <span class="panel-icon">🏷️</span>
            <div>
                <h3>Información de la categoría</h3>
                <p>Completa los siguientes campos.</p>
            </div>
        </div>

        <form
            method="POST"
            action="{{ route('encargado.inventario.categorias.guardar') }}"
        >

            @csrf

            <div class="ingreso-grid">

                <div class="campo-ingreso campo-completo">

                    <label for="nombre_categoria">
                        Nombre de la categoría
                    </label>

                    <input
                        type="text"
                        id="nombre_categoria"
                        name="nombre_categoria"
                        value="{{ old('nombre_categoria') }}"
                        placeholder="Ej. Electrónica"
                        maxlength="100"
                        required
                    >

                    @error('nombre_categoria')
                        <small class="error-text">{{ $message }}</small>
                    @enderror

                </div>

                <div class="campo-ingreso campo-completo">

                    <label for="descripcion_categoria">
                        Descripción
                    </label>

                    <textarea
                        id="descripcion_categoria"
                        name="descripcion_categoria"
                        rows="3"
                        placeholder="Describe brevemente esta categoría..."
                    >{{ old('descripcion_categoria') }}</textarea>

                    @error('descripcion_categoria')
                        <small class="error-text">{{ $message }}</small>
                    @enderror

                </div>

            </div>

            <div class="panel-actions">

                <button type="submit" class="btn-agregar">
                    + Registrar categoría
                </button>

            </div>

        </form>

    </section>

    {{-- LISTADO DE CATEGORÍAS --}}
    <section class="lista-ingreso-panel">

        <div class="panel-heading">
            <span class="panel-icon">📋</span>
            <div>
                <h3>Categorías registradas</h3>
                <p>
                    Consulta las categorías disponibles en el sistema.
                </p>
            </div>
        </div>

        <div class="tabla-contenedor">

            <table class="tabla-ingresos">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Categoría</th>
                        <th>Descripción</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($categorias as $categoria)

                        <tr>
                            <td>{{ $categoria->id }}</td>

                            <td>
                                {{ $categoria->nombre_categoria }}
                            </td>

                            <td>
                                {{ $categoria->descripcion_categoria ?? 'Sin descripción' }}
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="3" class="fila-vacia">
                                Todavía no hay categorías registradas.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </section>

</div>

@endsection
