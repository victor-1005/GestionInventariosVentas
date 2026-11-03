
@extends('layouts.app')

@section('titulo', 'Acceso | Encargado de Inventario')

@section('encabezado', 'Acceso al sistema')

@section('contenido')

<div class="login-wrapper">

    <div class="login-container">

        <div class="login-icon">🔐</div>

        <span class="login-label">MÓDULO PRIVADO</span>

        <h2>Bienvenido</h2>

        <p class="login-description">
            Ingresa tus credenciales para acceder al módulo de inventario.
        </p>

        @if ($errors->any())
            <div class="login-alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('encargado.login.store') }}">

            @csrf

            <div class="login-form-group">
                <label for="usuario">Nombre de usuario</label>

                <input
                    type="text"
                    id="usuario"
                    name="usuario"
                    value="{{ old('usuario') }}"
                    placeholder="Ingresa tu usuario"
                    autocomplete="username"
                    required
                    autofocus
                >
            </div>

            <div class="login-form-group">
                <label for="contrasena">Contraseña</label>

                <input
                    type="password"
                    id="contrasena"
                    name="contrasena"
                    placeholder="Ingresa tu contraseña"
                    autocomplete="current-password"
                    required
                >
            </div>

            <button type="submit" class="login-button">
                Iniciar sesión
                <span>→</span>
            </button>

        </form>

        <a href="{{ route('inicio') }}" class="login-back">
            ← Volver a la página principal
        </a>

    </div>

</div>

@endsection
