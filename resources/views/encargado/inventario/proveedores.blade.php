@extends('encargado.layouts.app')

@section('titulo', 'Administrar proveedores')
@section('encabezado', 'Gestión de proveedores')

@section('contenido')

<div class="ingreso-wrapper">

    {{-- ENCABEZADO --}}
    <div class="ingreso-heading">
        <div>
            <h2>Registrar nuevo proveedor</h2>
            <p>
                Administra las entidades que suministran
                productos al inventario.
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
            <span class="panel-icon">🏢</span>
            <div>
                <h3>Información del proveedor</h3>
                <p>Ingresa los datos de contacto de la entidad.</p>
            </div>
        </div>

        <form
            method="POST"
            action="{{ route('encargado.inventario.proveedores.guardar') }}"
        >

            @csrf

            <div class="ingreso-grid">

                <div class="campo-ingreso campo-completo">

                    <label for="nombre_entidad">
                        Nombre de la entidad
                    </label>

                    <input
                        type="text"
                        id="nombre_entidad"
                        name="nombre_entidad"
                        value="{{ old('nombre_entidad') }}"
                        placeholder="Ej. Distribuidora Central"
                        maxlength="150"
                        required
                    >

                    @error('nombre_entidad')
                        <small class="error-text">{{ $message }}</small>
                    @enderror

                </div>

                <div class="campo-ingreso">

                    <label for="telefono_contacto">
                        Teléfono de contacto
                    </label>

                    <input
                        type="text"
                        id="telefono_contacto"
                        name="telefono_contacto"
                        value="{{ old('telefono_contacto') }}"
                        placeholder="Ej. 2222-2222"
                        maxlength="25"
                        required
                    >

                    @error('telefono_contacto')
                        <small class="error-text">{{ $message }}</small>
                    @enderror

                </div>

                <div class="campo-ingreso">

                    <label for="correo">
                        Correo electrónico
                    </label>

                    <input
                        type="email"
                        id="correo"
                        name="correo"
                        value="{{ old('correo') }}"
                        placeholder="proveedor@ejemplo.com"
                        maxlength="150"
                        required
                    >

                    @error('correo')
                        <small class="error-text">{{ $message }}</small>
                    @enderror

                </div>

            </div>

            <div class="panel-actions">

                <button type="submit" class="btn-agregar">
                    + Registrar proveedor
                </button>

            </div>

        </form>

    </section>

    {{-- LISTADO DE PROVEEDORES --}}
    <section class="lista-ingreso-panel">

        <div class="panel-heading">
            <span class="panel-icon">📋</span>
            <div>
                <h3>Proveedores registrados</h3>
                <p>Consulta las entidades disponibles.</p>
            </div>
        </div>

        <div class="tabla-contenedor">

            <table class="tabla-ingresos">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Proveedor</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($proveedores as $proveedor)

                        <tr>

                            <td>{{ $proveedor->id }}</td>

                            <td>
                                {{ $proveedor->nombre_entidad }}
                            </td>

                            <td>
                                {{ $proveedor->telefono_contacto ?? 'No registrado' }}
                            </td>

                            <td>
                                {{ $proveedor->correo ?? 'No registrado' }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="fila-vacia">
                                Todavía no hay proveedores registrados.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </section>

</div>

@endsection
