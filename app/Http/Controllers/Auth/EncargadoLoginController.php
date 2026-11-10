<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EncargadoLoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'usuario' => ['required', 'string'],
            'contrasena' => ['required', 'string'],
        ]);

        $credenciales = [
            'usuario' => $request->usuario,
            'password' => $request->contrasena,
        ];

        if (Auth::attempt($credenciales)) {

            $usuario = Auth::user();

            // Redireccionar según el rol.
            if ($usuario->rol === 'encargado_inventario') {

                $request->session()->regenerate();

                return redirect()->route(
                    'encargado.inventario.index'
                );
            }

            if ($usuario->rol === 'encargado_ventas') {

                $request->session()->regenerate();

                return redirect()->route(
                    'encargado.ventas.index'
                );
            }

            // Si el usuario no tiene un rol autorizado.
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'usuario' => 'No tienes un rol autorizado para ingresar.',
            ])->onlyInput('usuario');
        }

        return back()->withErrors([
            'usuario' => 'El usuario o la contraseña son incorrectos.',
        ])->onlyInput('usuario');
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('inicio');
    }
}
