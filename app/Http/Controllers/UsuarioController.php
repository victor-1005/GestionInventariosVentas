<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    // Menú principal
    public function index()
    {
        return view('usuarios.index');
    }

    // Mostrar formulario de creación
    public function crear()
    {
        return view('usuarios.crear');
    }

    // Guardar usuario
    public function guardar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'usuario' => 'required|string|max:50|unique:usuarios,usuario',
            'contrasena' => 'required|string|min:6|confirmed',
            'rol' => [
                'required',
                Rule::in([
                    'encargado_inventario',
                    'encargado_ventas'
                ])
            ]
        ]);

        User::create([
            'nombre' => $request->nombre,
            'usuario' => $request->usuario,
            'contrasena' => Hash::make($request->contrasena),
            'rol' => $request->rol
        ]);

        return redirect()
            ->route('usuarios.listar')
            ->with('success', 'Usuario registrado correctamente.');
    }

    // Mostrar usuarios registrados
    public function listar()
    {
        $usuarios = User::orderBy('id', 'desc')->paginate(10);

        return view('usuarios.listar', compact('usuarios'));
    }

    // Mostrar formulario de edición
    public function editar(User $usuario)
    {
        return view('usuarios.editar', compact('usuario'));
    }

    // Actualizar usuario
    public function actualizar(Request $request, User $usuario)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',

            'usuario' => [
                'required',
                'string',
                'max:50',
                Rule::unique('usuarios', 'usuario')->ignore($usuario->id)
            ],

            'rol' => [
                'required',
                Rule::in([
                    'encargado_inventario',
                    'encargado_ventas'
                ])
            ],

            'contrasena' => 'nullable|string|min:6|confirmed'
        ]);

        $usuario->nombre = $request->nombre;
        $usuario->usuario = $request->usuario;
        $usuario->rol = $request->rol;

        // Solo cambiar contraseña si se escribió una nueva
        if ($request->filled('contrasena')) {
            $usuario->contrasena = Hash::make($request->contrasena);
        }

        $usuario->save();

        return redirect()
            ->route('usuarios.listar')
            ->with('success', 'Usuario actualizado correctamente.');
    }
}
