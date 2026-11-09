<?php

namespace App\Http\Controllers\Encargado;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExistenciasController extends Controller
{
    // ==========================================
    // 1. MOSTRAR EXISTENCIAS
    // ==========================================

    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        $productos = Producto::with('categoria')
            ->when($buscar, function ($query, $buscar) {

                $query->where(function ($q) use ($buscar) {

                    $q->where('codigo_producto', 'LIKE', "%{$buscar}%")
                      ->orWhere('nombre_producto', 'LIKE', "%{$buscar}%")
                      ->orWhereHas('categoria', function ($categoria) use ($buscar) {
                          $categoria->where(
                              'nombre_categoria',
                              'LIKE',
                              "%{$buscar}%"
                          );
                      });

                });

            })
            ->orderBy('nombre_producto')
            ->paginate(10)
            ->withQueryString();

        $totalProductos = Producto::count();

        $totalUnidades = Producto::sum('stock_actual');

        return view('encargado.inventario.existencias', compact(
            'productos',
            'buscar',
            'totalProductos',
            'totalUnidades'
        ));
    }


    // ==========================================
    // 2. MOSTRAR FORMULARIO DE EDICIÓN
    // ==========================================

    public function editar($id)
    {
        $producto = Producto::findOrFail($id);

        $categorias = Categoria::orderBy('nombre_categoria')->get();

        return view('encargado.inventario.editar', compact(
            'producto',
            'categorias'
        ));
    }


    // ==========================================
    // 3. ACTUALIZAR PRODUCTO
    // ==========================================

    public function actualizar(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $datos = $request->validate([

            'codigo_producto' => [
                'required',
                'string',
                'max:50',
                Rule::unique('productos', 'codigo_producto')
                    ->ignore($producto->id)
            ],

            'categoria_id' => [
                'required',
                'exists:categorias,id'
            ],

            'nombre_producto' => [
                'required',
                'string',
                'max:150'
            ],

            'descripcion' => [
                'nullable',
                'string'
            ],

            'precio' => [
                'required',
                'numeric',
                'min:0'
            ],

            'stock_actual' => [
                'required',
                'integer',
                'min:0'
            ],

            'stock_minimo' => [
                'required',
                'integer',
                'min:0'
            ]

        ]);

        $producto->update($datos);

        return redirect()
            ->route('encargado.inventario.existencias')
            ->with('success', '¡Producto actualizado correctamente!');
    }


    // ==========================================
    // 4. ELIMINAR PRODUCTO
    // ==========================================

    public function eliminar($id)
    {
        $producto = Producto::findOrFail($id);

        // Comprobar si tiene detalles de ingresos registrados.
        if ($producto->ingresosDetalle()->exists()) {

            return redirect()
                ->route('encargado.inventario.existencias')
                ->with(
                    'error',
                    'No se puede eliminar este producto porque tiene movimientos de inventario registrados.'
                );
        }

        $producto->delete();

        return redirect()
            ->route('encargado.inventario.existencias')
            ->with('success', '¡Producto eliminado correctamente!');
    }
}
