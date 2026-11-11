<?php

namespace App\Http\Controllers\Encargado;

use App\Http\Controllers\Controller;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    // ==========================================
    // 1. MOSTRAR PROVEEDORES
    // ==========================================

    public function index()
    {
        // Obtener todos los proveedores registrados.
        $proveedores = Proveedor::orderBy(
            'nombre_entidad'
        )->get();

        return view(
            'encargado.inventario.proveedores',
            compact('proveedores')
        );
    }

    // ==========================================
    // 2. GUARDAR NUEVO PROVEEDOR
    // ==========================================

    public function guardar(Request $request)
    {
        // Validar los datos recibidos.
        $datos = $request->validate([
            'nombre_entidad' => [
                'required',
                'string',
                'max:150'
            ],

            'telefono_contacto' => [
                'nullable',
                'string',
                'max:25'
            ],

            'correo' => [
                'nullable',
                'email',
                'max:150'
            ]
        ], [
            'nombre_entidad.required' =>
                'El nombre del proveedor es obligatorio.',

            'nombre_entidad.max' =>
                'El nombre no puede superar los 150 caracteres.',

            'correo.email' =>
                'Debes ingresar un correo electrónico válido.'
        ]);

        // Registrar el proveedor.
        Proveedor::create($datos);

        return redirect()
            ->route('encargado.inventario.proveedores')
            ->with('success', '¡Proveedor registrado correctamente!');
    }
}
