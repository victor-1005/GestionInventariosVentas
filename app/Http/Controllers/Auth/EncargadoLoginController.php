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

            if (Auth::user()->rol !== 'encargado_inventario') {
                Auth::logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'usuario' => 'No tienes permiso para acceder al módulo de inventario.',
                ])->onlyInput('usuario');
            }

            $request->session()->regenerate();

            return redirect()->route('encargado.inventario.index');
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
