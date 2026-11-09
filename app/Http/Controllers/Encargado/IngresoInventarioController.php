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
    // Para guardar los productos:

    public function guardar(Request $request): JsonResponse
    {
        // 1. Validar los datos recibidos desde JavaScript.
        $datos = $request->validate([

            'numero_documento' => [
                'required',
                'string',
                'max:50',
                'unique:ingresos_inventario,numero_documento'
            ],

            'fecha_ingreso' => [
                'required',
                'date'
            ],

            'proveedor_id' => [
                'required',
                'exists:proveedores,id'
            ],

            'productos' => [
                'required',
                'array',
                'min:1'
            ],

            'productos.*.codigo' => [
                'required',
                'string',
                'max:50',
                'distinct'
            ],

            'productos.*.nombre' => [
                'required',
                'string',
                'max:150'
            ],

            'productos.*.categoriaId' => [
                'nullable',
                'integer',
                'exists:categorias,id'
            ],

            'productos.*.descripcion' => [
                'nullable',
                'string'
            ],

            'productos.*.cantidad' => [
                'required',
                'integer',
                'min:1'
            ],

            'productos.*.precioCosto' => [
                'required',
                'numeric',
                'min:0'
            ],

            'productos.*.precioVenta' => [
                'required',
                'numeric',
                'min:0'
            ],

            'productos.*.stockMinimo' => [
                'nullable',
                'integer',
                'min:0'
            ],

        ]);

        // 2. Guardar todo dentro de una transacción.
        $ingreso = DB::transaction(function () use ($datos) {

            // Crear encabezado del ingreso.
            $ingreso = IngresoInventario::create([
                'numero_documento' => $datos['numero_documento'],
                'fecha_ingreso' => $datos['fecha_ingreso'],
                'proveedor_id' => $datos['proveedor_id'],
                'usuario_id' => auth()->id(),
            ]);

            // Recorrer los productos recibidos.
            foreach ($datos['productos'] as $item) {

                $codigo = trim($item['codigo']);

                // Buscar producto existente y bloquear su registro.
                $producto = Producto::where(
                    'codigo_producto',
                    $codigo
                )->lockForUpdate()->first();

                if ($producto) {

                    // PRODUCTO EXISTENTE:
                    // Conservar sus datos y aumentar existencias.

                    $producto->increment(
                        'stock_actual',
                        $item['cantidad']
                    );
                } else {

                    // PRODUCTO NUEVO:
                    // Verificar que tenga categoría.
                    if (empty($item['categoriaId'])) {
                        throw ValidationException::withMessages([
                            'productos' => [
                                "El producto {$codigo} necesita una categoría."
                            ]
                        ]);
                    }

                    // Crear producto con su stock inicial.
                    $producto = Producto::create([
                        'codigo_producto' => $codigo,
                        'categoria_id' => $item['categoriaId'],
                        'nombre_producto' => $item['nombre'],
                        'descripcion' => $item['descripcion'] ?? null,
                        'precio' => $item['precioVenta'],
                        'stock_actual' => $item['cantidad'],
                        'stock_minimo' => $item['stockMinimo'] ?? 5,
                    ]);
                }

                // Registrar detalle del ingreso.
                $ingreso->detalles()->create([
                    'producto_id' => $producto->id,
                    'cantidad_recibida' => $item['cantidad'],
                    'precio_costo' => $item['precioCosto'],
                ]);
            }

            return $ingreso;
        });

        // 3. Respuesta para JavaScript.
        return response()->json([
            'success' => true,
            'mensaje' => '¡Ingreso de inventario guardado correctamente!',
            'ingreso_id' => $ingreso->id,
        ], 201);
    }
}
