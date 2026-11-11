@extends('encargado.layouts.app')

@section('titulo', 'Editar categoría')
@section('encabezado', 'Editar categoría')

@section('contenido')

<div class="ingreso-wrapper">

    {{-- ENCABEZADO --}}
    <div class="ingreso-heading">
        <div>
            <h2>Modificar categoría</h2>
            <p>
                Actualiza la información de la categoría seleccionada.
            </p>
        </div>
    </div>

    {{-- FORMULARIO --}}
    <section class="ingreso-panel">

        <div class="panel-heading">
            <span class="panel-icon">🏷️</span>

            <div>
                <h3>Información de la categoría</h3>
                <p>Modifica los campos que necesites.</p>
            </div>
        </div>

        <form
            method="POST"
            action="{{ route(
                'encargado.inventario.categorias.actualizar',
                $categoria->id
            ) }}"
        >

            @csrf
            @method('PUT')

            <div class="ingreso-grid">

                {{-- NOMBRE --}}
                <div class="campo-ingreso campo-completo">

                    <label for="nombre_categoria">
                        Nombre de la categoría
                    </label>

                    <input
                        type="text"
                        id="nombre_categoria"
                        name="nombre_categoria"
                        value="{{ old(
                            'nombre_categoria',
                            $categoria->nombre_categoria
                        ) }}"
                        maxlength="100"
                        required
                    >

                    @error('nombre_categoria')
                        <small class="error-text">
                            {{ $message }}
                        </small>
                    @enderror

                </div>

                {{-- DESCRIPCIÓN --}}
                <div class="campo-ingreso campo-completo">

                    <label for="descripcion_categoria">
                        Descripción
                    </label>

                    <textarea
                        id="descripcion_categoria"
                        name="descripcion_categoria"
                        rows="3"
                    >{{ old(
                        'descripcion_categoria',
                        $categoria->descripcion_categoria
                    ) }}</textarea>

                    @error('descripcion_categoria')
                        <small class="error-text">
                            {{ $message }}
                        </small>
                    @enderror

                </div>

            </div>

            {{-- BOTONES --}}
            <div class="panel-actions">

                <button type="submit" class="btn-agregar">
                    Guardar cambios
                </button>

                <a
                    href="{{ route('encargado.inventario.categorias') }}"
                    class="btn-cancelar"
                >
                    Cancelar
                </a>

            </div>

        </form>

    </section>

</div>

@endsection
