<?php

namespace App\Http\Controllers\Encargado;

use App\Http\Controllers\Controller;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

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
                'max:150',
                'unique:proveedores,nombre_entidad'
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

            'nombre_entidad.unique' =>
                'Este proveedor ya se encuentra registrado.',

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

    // ==========================================
    // 3. MOSTRAR FORMULARIO DE EDICIÓN
    // ==========================================

    public function editar($id)
    {
        // Buscar proveedor.
        $proveedor = Proveedor::findOrFail($id);

        return view(
            'encargado.inventario.editar-proveedor',
            compact('proveedor')
        );
    }

    // ==========================================
    // 4. ACTUALIZAR PROVEEDOR
    // ==========================================

    public function actualizar(Request $request, $id)
    {
        // Buscar proveedor.
        $proveedor = Proveedor::findOrFail($id);

        // Validar los datos.
        $datos = $request->validate([
            'nombre_entidad' => [
                'required',
                'string',
                'max:150',
                Rule::unique('proveedores', 'nombre_entidad')
                    ->ignore($proveedor->id)
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

            'nombre_entidad.unique' =>
                'Ya existe otro proveedor con ese nombre.',

            'nombre_entidad.max' =>
                'El nombre no puede superar los 150 caracteres.',

            'correo.email' =>
                'Debes ingresar un correo electrónico válido.'
        ]);

        // Actualizar información.
        $proveedor->update($datos);

        return redirect()
            ->route('encargado.inventario.proveedores')
            ->with('success', '¡Proveedor actualizado correctamente!');
    }

    // ==========================================
    // 5. ELIMINAR PROVEEDOR
    // ==========================================

    public function eliminar($id)
    {
        // Buscar proveedor.
        $proveedor = Proveedor::findOrFail($id);

        try {

            // Intentar eliminarlo.
            $proveedor->delete();

            return redirect()
                ->route('encargado.inventario.proveedores')
                ->with('success', '¡Proveedor eliminado correctamente!');

        } catch (QueryException $e) {

            // Puede tener ingresos relacionados.
            return redirect()
                ->route('encargado.inventario.proveedores')
                ->with(
                    'error',
                    'No se puede eliminar este proveedor porque tiene registros relacionados.'
                );
        }
    }
}
