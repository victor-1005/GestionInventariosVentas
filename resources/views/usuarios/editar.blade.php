
@extends('layouts.app')

@section('titulo', 'Editar usuario')

@section('encabezado', 'Editar usuario')

@section('contenido')

<section class="quick-access">

    <div class="section-heading">
        <h2>Modificar información del usuario</h2>
        <p>Actualiza los datos de la cuenta seleccionada.</p>
    </div>

    <div class="stat-card form-card">

        <form
            action="{{ route('usuarios.actualizar', $usuario->id) }}"
            method="POST"
        >

            @csrf
            @method('PUT')

            <div class="form-grid">

                <div class="form-group">
                    <label for="nombre">Nombre completo</label>

                    <input
                        type="text"
                        name="nombre"
                        id="nombre"
                        value="{{ old('nombre', $usuario->nombre) }}"
                        required
                    >

                    @error('nombre')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="usuario">Nombre de usuario</label>

                    <input
                        type="text"
                        name="usuario"
                        id="usuario"
                        value="{{ old('usuario', $usuario->usuario) }}"
                        required
                    >

                    @error('usuario')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="rol">Rol del usuario</label>

                    <select name="rol" id="rol" required>

                        <option
                            value="encargado_inventario"
                            {{ old('rol', $usuario->rol) == 'encargado_inventario' ? 'selected' : '' }}
                        >
                            Encargado de inventario
                        </option>

                        <option
                            value="encargado_ventas"
                            {{ old('rol', $usuario->rol) == 'encargado_ventas' ? 'selected' : '' }}
                        >
                            Encargado de ventas
                        </option>

                    </select>

                    @error('rol')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>

            </div>

            <div class="form-divider"></div>

            <div class="form-heading">
                <h3>Cambiar contraseña</h3>
                <p>
                    Si no deseas cambiarla, deja estos campos vacíos.
                </p>
            </div>

            <div class="form-grid">

                <div class="form-group">
                    <label for="contrasena">Nueva contraseña</label>

                    <input
                        type="password"
                        name="contrasena"
                        id="contrasena"
                        placeholder="Ingrese una nueva contraseña"
                    >

                    @error('contrasena')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="contrasena_confirmation">
                        Confirmar nueva contraseña
                    </label>

                    <input
                        type="password"
                        name="contrasena_confirmation"
                        id="contrasena_confirmation"
                        placeholder="Repita la nueva contraseña"
                    >
                </div>

            </div>

            <div class="form-buttons">

                <button type="submit" class="btn-primary">
                    Guardar cambios
                </button>

                <a href="{{ route('usuarios.listar') }}" class="btn-secondary">
                    Cancelar
                </a>

            </div>

        </form>

    </div>

</section>

@endsection
