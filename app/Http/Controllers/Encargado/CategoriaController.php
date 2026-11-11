<?php

namespace App\Http\Controllers\Encargado;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

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

        return redirect()
            ->route('encargado.inventario.categorias')
            ->with('success', '¡Categoría registrada correctamente!');
    }

    // ==========================================
    // 3. MOSTRAR FORMULARIO DE EDICIÓN
    // ==========================================

    public function editar($id)
    {
        // Buscar la categoría por su ID.
        $categoria = Categoria::findOrFail($id);

        // Mostrar el formulario con sus datos actuales.
        return view(
            'encargado.inventario.editar-categoria',
            compact('categoria')
        );
    }

    // ==========================================
    // 4. ACTUALIZAR CATEGORÍA
    // ==========================================

    public function actualizar(Request $request, $id)
    {
        // Buscar la categoría que queremos modificar.
        $categoria = Categoria::findOrFail($id);

        // Validar los datos.
        $datos = $request->validate([
            'nombre_categoria' => [
                'required',
                'string',
                'max:100',
                Rule::unique('categorias', 'nombre_categoria')
                    ->ignore($categoria->id)
            ],

            'descripcion_categoria' => [
                'nullable',
                'string'
            ]
        ], [
            'nombre_categoria.required' =>
                'El nombre de la categoría es obligatorio.',

            'nombre_categoria.unique' =>
                'Ya existe otra categoría con ese nombre.',

            'nombre_categoria.max' =>
                'El nombre no puede superar los 100 caracteres.'
        ]);

        // Actualizar los datos.
        $categoria->update($datos);

        return redirect()
            ->route('encargado.inventario.categorias')
            ->with('success', '¡Categoría actualizada correctamente!');
    }

    // ==========================================
    // 5. ELIMINAR CATEGORÍA
    // ==========================================

    public function eliminar($id)
    {
        // Buscar la categoría.
        $categoria = Categoria::findOrFail($id);

        try {

            // Intentar eliminar el registro.
            $categoria->delete();

            return redirect()
                ->route('encargado.inventario.categorias')
                ->with('success', '¡Categoría eliminada correctamente!');

        } catch (QueryException $e) {

            // Si tiene productos relacionados,
            // la base de datos puede impedir su eliminación.
            return redirect()
                ->route('encargado.inventario.categorias')
                ->with(
                    'error',
                    'No se puede eliminar esta categoría porque tiene registros relacionados.'
                );
        }
    }
}
