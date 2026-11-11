
@extends('encargado.layouts.app')

@section('titulo', 'Editar proveedor')
@section('encabezado', 'Editar proveedor')

@section('contenido')

<div class="ingreso-wrapper">

    {{-- ENCABEZADO --}}
    <div class="ingreso-heading">
        <div>
            <h2>Modificar proveedor</h2>
            <p>
                Actualiza los datos de contacto del proveedor.
            </p>
        </div>
    </div>

    {{-- FORMULARIO --}}
    <section class="ingreso-panel">

        <div class="panel-heading">
            <span class="panel-icon">🏢</span>

            <div>
                <h3>Información del proveedor</h3>
                <p>Modifica los datos que necesites.</p>
            </div>
        </div>

        <form
            method="POST"
            action="{{ route(
                'encargado.inventario.proveedores.actualizar',
                $proveedor->id
            ) }}"
        >

            @csrf
            @method('PUT')

            <div class="ingreso-grid">

                {{-- NOMBRE --}}
                <div class="campo-ingreso campo-completo">

                    <label for="nombre_entidad">
                        Nombre de la entidad
                    </label>

                    <input
                        type="text"
                        id="nombre_entidad"
                        name="nombre_entidad"
                        value="{{ old(
                            'nombre_entidad',
                            $proveedor->nombre_entidad
                        ) }}"
                        maxlength="150"
                        required
                    >

                    @error('nombre_entidad')
                        <small class="error-text">
                            {{ $message }}
                        </small>
                    @enderror

                </div>

                {{-- TELÉFONO --}}
                <div class="campo-ingreso">

                    <label for="telefono_contacto">
                        Teléfono de contacto
                    </label>

                    <input
                        type="text"
                        id="telefono_contacto"
                        name="telefono_contacto"
                        value="{{ old(
                            'telefono_contacto',
                            $proveedor->telefono_contacto
                        ) }}"
                        maxlength="25"
                    >

                    @error('telefono_contacto')
                        <small class="error-text">
                            {{ $message }}
                        </small>
                    @enderror

                </div>

                {{-- CORREO --}}
                <div class="campo-ingreso">

                    <label for="correo">
                        Correo electrónico
                    </label>

                    <input
                        type="email"
                        id="correo"
                        name="correo"
                        value="{{ old(
                            'correo',
                            $proveedor->correo
                        ) }}"
                        maxlength="150"
                    >

                    @error('correo')
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
                    href="{{ route('encargado.inventario.proveedores') }}"
                    class="btn-cancelar"
                >
                    Cancelar
                </a>

            </div>

        </form>

    </section>

</div>

@endsection
