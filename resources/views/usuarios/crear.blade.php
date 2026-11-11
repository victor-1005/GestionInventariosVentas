
@extends('layouts.app')

@section('titulo', 'Crear usuario')

@section('encabezado', 'Crear usuario')

@section('contenido')

<section class="quick-access">

    <div class="section-heading">
        <h2>Registrar nuevo usuario</h2>
        <p>
            Completa la información para crear una nueva cuenta
            en el sistema.
        </p>
    </div>

    <div class="stat-card form-card">

        <form action="{{ route('usuarios.guardar') }}" method="POST">

            @csrf

            <div class="form-grid">

                <div class="form-group">
                    <label for="nombre">Nombre completo</label>

                    <input
                        type="text"
                        name="nombre"
                        id="nombre"
                        value="{{ old('nombre') }}"
                        placeholder="Ingrese el nombre completo"
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
                        value="{{ old('usuario') }}"
                        placeholder="Ingrese un nombre de usuario"
                        required
                    >

                    @error('usuario')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="contrasena">Contraseña</label>

                    <input
                        type="password"
                        name="contrasena"
                        id="contrasena"
                        placeholder="Mínimo 6 caracteres"
                        required
                    >

                    @error('contrasena')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="contrasena_confirmation">
                        Confirmar contraseña
                    </label>

                    <input
                        type="password"
                        name="contrasena_confirmation"
                        id="contrasena_confirmation"
                        placeholder="Repita la contraseña"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="rol">Rol del usuario</label>

                    <select name="rol" id="rol" required>

                        <option value="">Seleccione un rol</option>

                        <option
                            value="encargado_inventario"
                            {{ old('rol') == 'encargado_inventario' ? 'selected' : '' }}
                        >
                            Encargado de inventario
                        </option>

                        <option
                            value="encargado_ventas"
                            {{ old('rol') == 'encargado_ventas' ? 'selected' : '' }}
                        >
                            Encargado de ventas
                        </option>

                    </select>

                    @error('rol')
                        <small class="error-message">{{ $message }}</small>
                    @enderror
                </div>

            </div>

            <div class="form-buttons">

                <button type="submit" class="btn-primary">
                    Registrar usuario
                </button>

                <a
                    href="{{ route('usuarios.index') }}"
                    class="btn-secondary"
                >
                    Cancelar
                </a>

            </div>

        </form>

    </div>

</section>

@endsection
