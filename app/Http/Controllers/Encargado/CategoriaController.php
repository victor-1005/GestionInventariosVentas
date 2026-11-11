<?php

namespace App\Http\Controllers\Encargado;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    // ==========================================
    // 1. MOSTRAR CATEGORÍAS
    // ==========================================

    public function index()
    {
        // Obtener todas las categorías registradas.
        $categorias = Categoria::orderBy(
            'nombre_categoria'
        )->get();

        return view(
            'encargado.inventario.categorias',
            compact('categorias')
        );
    }

    // ==========================================
    // 2. GUARDAR NUEVA CATEGORÍA
    // ==========================================

    public function guardar(Request $request)
    {
        // Validar los datos recibidos.
        $datos = $request->validate([
            'nombre_categoria' => [
                'required',
                'string',
                'max:100',
                'unique:categorias,nombre_categoria'
            ],

            'descripcion_categoria' => [
                'nullable',
                'string'
            ]
        ], [
            'nombre_categoria.required' =>
                'El nombre de la categoría es obligatorio.',

            'nombre_categoria.unique' =>
                'Esta categoría ya se encuentra registrada.',

            'nombre_categoria.max' =>
                'El nombre no puede superar los 100 caracteres.'
        ]);

        // Crear el registro en la base de datos.
        Categoria::create($datos);

        // Regresar a la vista con mensaje de éxito.
        return redirect()
            ->route('encargado.inventario.categorias')
            ->with('success', '¡Categoría registrada correctamente!');
    }
}
