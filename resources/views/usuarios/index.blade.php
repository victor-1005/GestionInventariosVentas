
@extends('layouts.app')

@section('titulo', 'Usuarios | Administración')

@section('encabezado', 'Administración de usuarios')

@section('contenido')

<section class="quick-access">

    <div class="section-heading">
        <h2>Gestión de usuarios</h2>
        <p>
            Administra las cuentas y los roles de los encargados
            del sistema.
        </p>
    </div>

    <div class="access-grid">

        <a href="{{ route('usuarios.crear') }}" class="access-card">
            <span class="access-icon">👤</span>
            <h3>Crear usuario</h3>
            <p>
                Registra nuevos usuarios y asigna
                el rol correspondiente.
            </p>
        </a>

        <a href="{{ route('usuarios.listar') }}" class="access-card">
            <span class="access-icon">👥</span>
            <h3>Listar usuarios</h3>
            <p>
                Consulta los usuarios registrados
                y administra su información.
            </p>
        </a>

    </div>

</section>

@endsection
