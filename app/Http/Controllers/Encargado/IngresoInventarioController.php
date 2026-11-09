<?php

namespace App\Http\Controllers\Encargado;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Proveedor;
use Illuminate\Http\JsonResponse;
use App\Models\IngresoInventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class IngresoInventarioController extends Controller
{
    // Mostrar formulario de ingresos
    public function index()
    {
        $categorias = Categoria::orderBy('nombre_categoria')->get();

        $proveedores = Proveedor::orderBy('nombre_entidad')->get();

        return view('encargado.inventario.ingreso', compact(
            'categorias',
            'proveedores'
        ));
    }

    // Buscar producto por código
    public function buscarProducto(string $codigo): JsonResponse
    {
        $producto = Producto::with('categoria')
            ->where('codigo_producto', $codigo)
            ->first();

        if (!$producto) {
            return response()->json([
                'existe' => false,
                'mensaje' => 'Producto no encontrado.'
            ]);
        }

        return response()->json([
            'existe' => true,
            'producto' => [
                'id' => $producto->id,
                'codigo' => $producto->codigo_producto,
                'categoria_id' => $producto->categoria_id,
                'categoria' => $producto->categoria?->nombre_categoria,
                'nombre' => $producto->nombre_producto,
                'descripcion' => $producto->descripcion,
                'precio_venta' => $producto->precio,
                'stock_actual' => $producto->stock_actual,
                'stock_minimo' => $producto->stock_minimo,
            ]
        ]);
    }
}
