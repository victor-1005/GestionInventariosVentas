@extends('layouts.app')

@section('titulo', 'Acceso | Encargado de Inventario')
@section('encabezado', 'Acceso al sistema')

@section('contenido')

<style>
    /* --- ANIMACIÓN DE FONDO (GRADIENTE EN MOVIMIENTO) --- */
    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* Estilos específicos para la tarjeta de login dividida */
    .login-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 2rem 1rem;
        min-height: 70vh;
    }

    .login-card-split {
        display: flex;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.15); /* Sombra un poco más pronunciada */
        overflow: hidden;
        width: 100%;
        max-width: 900px;
        min-height: 500px;
        /* Transición suave al cargar */
        animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Panel Izquierdo (Gradiente Azul -> Morado) */
    .login-left-panel {
        flex: 1;
        /* Gradiente animado: Azul oscuro, Morado y Azul brillante */
        background: linear-gradient(-45deg, #1e3c72, #2a5298, #6a11cb, #2575fc);
        background-size: 300% 300%;
        animation: gradientShift 8s ease infinite; /* Animación infinita de 8s */
        
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 3rem 2rem;
        text-align: center;
        position: relative;
    }

    /* Un pequeño brillo decorativo en el fondo */
    .login-left-panel::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 60%);
        pointer-events: none;
    }

    .login-left-panel .icon-big {
        font-size: 5rem;
        margin-bottom: 1.5rem;
        color: #ffffff;
        text-shadow: 0 0 15px rgba(255,255,255,0.5); /* Efecto de brillo en el icono */
    }

    .login-left-panel h3 {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        letter-spacing: 1px;
    }

    .login-left-panel p {
        font-size: 0.95rem;
        opacity: 0.9;
        max-width: 250px;
        line-height: 1.5;
    }

    /* Panel Derecho (Formulario) */
    .login-right-panel {
        flex: 1;
        padding: 3rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: #ffffff;
    }

    .login-right-panel h2 {
        font-size: 2rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .login-right-panel .subtitle {
        color: #6c757d;
        margin-bottom: 2rem;
        font-size: 0.95rem;
    }

    /* Estilos de los inputs */
    .custom-input-group {
        margin-bottom: 1.5rem;
    }

    .custom-input-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #495057;
        font-size: 0.9rem;
        transition: color 0.3s ease;
    }

    .custom-input-group input {
        width: 100%;
        padding: 0.8rem 1rem;
        border: 1px solid #ced4da;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease; /* Transición para todos los cambios */
        background-color: #f8f9fa;
    }

    /* EFECTO AL HACER FOCO EN EL INPUT (Celeste/RGB) */
    .custom-input-group input:focus {
        outline: none;
        background-color: #ffffff;
        border-color: #00d2ff; /* Cian brillante */
        box-shadow: 0 0 0 4px rgba(0, 210, 255, 0.15); /* Sombra cian */
    }

    /* Cuando el input tiene foco, la etiqueta cambia de color */
    .custom-input-group input:focus + label,
    .custom-input-group:focus-within label {
        color: #00d2ff; /* El label se vuelve celeste al escribir */
    }

    /* Botón de login con Gradiente */
    .btn-login-submit {
        width: 100%;
        padding: 0.9rem;
        /* Gradiente de Azul a Morado */
        background: linear-gradient(to right, #0d6efd, #6a11cb);
        background-size: 200% auto;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: 0.5s; /* Transición suave para el hover */
        margin-top: 1rem;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3); /* Sombra azul */
    }

    /* EFECTO HOVER DEL BOTÓN (Desplaza el gradiente y agranda la sombra) */
    .btn-login-submit:hover {
        background-position: right center; /* Mueve el gradiente */
        box-shadow: 0 6px 20px rgba(106, 17, 203, 0.4); /* Sombra morada */
        transform: translateY(-2px); /* Se eleva un poquito */
    }

    /* Enlace de registro */
    .login-register-link {
        text-align: center;
        margin-top: 1.5rem;
        font-size: 0.9rem;
        color: #6c757d;
    }

    .login-register-link a {
        color: #6a11cb; /* Morado en lugar de azul */
        font-weight: 600;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .login-register-link a:hover {
        color: #2575fc; /* Cambia a azul al pasar el mouse */
        text-decoration: underline;
    }

    /* Alerta de errores */
    .login-alert {
        background-color: #f8d7da;
        color: #842029;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
        border-left: 4px solid #dc3545; /* Detalle visual */
    }
    .login-alert p { margin: 0; }

    /* Responsive */
    @media (max-width: 768px) {
        .login-card-split {
            flex-direction: column;
        }
        .login-left-panel {
            padding: 2rem 1rem;
        }
        .login-left-panel .icon-big {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .login-right-panel {
            padding: 2rem 1.5rem;
        }
    }
</style>

<div class="login-wrapper">

    <div class="login-card-split">

        {{-- PANEL IZQUIERDO: DISEÑO VISUAL --}}
        <div class="login-left-panel">
            <!-- Icono de Escudo/Verificado (Bootstrap Icon) -->
            <div class="icon-big">
                <i class="bi bi-shield-check"></i>
            </div>
            <h3>Inicio de sesion</h3>
            <p>Ingresar al sistema de gestión y control de inventario</p>
        </div>

        {{-- PANEL DERECHO: FORMULARIO --}}
        <div class="login-right-panel">
            
            <h2>Hola de nuevo</h2>
            <p class="subtitle">Ingrese sus datos de sesion.</p>

            @if ($errors->any())
                <div class="login-alert">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('encargado.login.store') }}">
                @csrf

                <div class="custom-input-group">
                    <label for="usuario">Nombre de usuario</label>
                    <input
                        type="text"
                        id="usuario"
                        name="usuario"
                        value="{{ old('usuario') }}"
                        placeholder="ejemplo@correo.com"
                        autocomplete="username"
                        required
                        autofocus
                    >
                </div>

                <div class="custom-input-group">
                    <label for="contrasena">Contraseña</label>
                    <input
                        type="password"
                        id="contrasena"
                        name="contrasena"
                        placeholder="••••••••"
                        autocomplete="current-password"
                        required
                    >
                </div>

                <button type="submit" class="btn-login-submit">
                    Iniciar sesión
                    <i class="bi bi-arrow-right"></i>
                </button>

            </form>

            <div class="login-register-link">
                ¿No tienes credenciales? <a href="{{ route('inicio') }}">Regístrarse</a>
            </div>

        </div>

    </div>

</div>

@endsection