
@extends('layouts.app')

@section('titulo', 'Lista de usuarios')

@section('encabezado', 'Usuarios registrados')

@section('contenido')

<section class="quick-access">

    <div class="section-heading">
        <h2>Usuarios del sistema</h2>
        <p>Consulta y administra las cuentas registradas.</p>
    </div>

    @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-actions">
        <a href="{{ route('usuarios.crear') }}" class="btn-primary">
            + Crear usuario
        </a>
    </div>

    <div class="stat-card users-table-card">

        <div class="table-responsive">

            <table class="users-table">

                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($usuarios as $usuario)

                        <tr>
                            <td>{{ $usuario->id }}</td>

                            <td>{{ $usuario->nombre }}</td>

                            <td>{{ $usuario->usuario }}</td>

                            <td>
                                @if($usuario->rol == 'encargado_inventario')
                                    Encargado de inventario
                                @elseif($usuario->rol == 'encargado_ventas')
                                    Encargado de ventas
                                @else
                                    {{ $usuario->rol }}
                                @endif
                            </td>

                            <td>
                                <a
                                    href="{{ route('usuarios.editar', $usuario->id) }}"
                                    class="btn-edit"
                                >
                                    Editar
                                </a>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="empty-table">
                                No hay usuarios registrados.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <div class="pagination-container">
        {{ $usuarios->links() }}
    </div>

    <div class="table-actions">
        <a href="{{ route('usuarios.index') }}" class="btn-secondary">
            Volver
        </a>
    </div>

</section>

@endsection
