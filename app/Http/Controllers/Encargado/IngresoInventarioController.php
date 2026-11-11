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
use App\Models\IngresoDetalle;
use Illuminate\Support\Facades\Auth;

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

    // ==========================================
    // GUARDAR INGRESO DE INVENTARIO
    // ==========================================
    public function guardar(Request $request): JsonResponse
    {
        // 1. VALIDAR LOS DATOS RECIBIDOS
        $datos = $request->validate([
            'numero_documento' => 'required|string',
            'fecha_ingreso' => 'required|date',
            'proveedor_id' => 'required|exists:proveedores,id',

            'productos' => 'required|array|min:1',

            'productos.*.codigo' => 'required|string',
            'productos.*.categoriaId' => 'required|exists:categorias,id',
            'productos.*.nombre' => 'required|string',
            'productos.*.descripcion' => 'nullable|string',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precioCosto' => 'required|numeric|min:0',
            'productos.*.precioVenta' => 'required|numeric|min:0',
            'productos.*.stockMinimo' => 'required|integer|min:0',
        ]);

        try {

            // 2. INICIAR TRANSACCIÓN
            DB::beginTransaction();

            // 3. GUARDAR ENCABEZADO DEL INGRESO
            $ingreso = IngresoInventario::create([
                'numero_documento' => $datos['numero_documento'],
                'fecha_ingreso' => $datos['fecha_ingreso'],
                'proveedor_id' => $datos['proveedor_id'],
                'usuario_id' => auth::id(),
            ]);

            // 4. RECORRER LOS PRODUCTOS RECIBIDOS
            foreach ($datos['productos'] as $item) {

                // Buscar si el producto ya existe mediante su código.
                $producto = Producto::where(
                    'codigo_producto',
                    $item['codigo']
                )->lockForUpdate()->first();

                if ($producto) {

                    // 5. SI EXISTE, AUMENTAR SUS EXISTENCIAS.
                    $producto->increment(
                        'stock_actual',
                        $item['cantidad']
                    );
                } else {

                    // 6. SI NO EXISTE, REGISTRARLO.
                    $producto = Producto::create([
                        'codigo_producto' => $item['codigo'],
                        'categoria_id' => $item['categoriaId'],
                        'nombre_producto' => $item['nombre'],
                        'descripcion' => $item['descripcion'] ?? null,
                        'precio' => $item['precioVenta'],
                        'stock_actual' => $item['cantidad'],
                        'stock_minimo' => $item['stockMinimo'],
                    ]);
                }

                // 7. REGISTRAR EL DETALLE DEL INGRESO.
                IngresoDetalle::create([
                    'ingreso_inventario_id' => $ingreso->id,
                    'producto_id' => $producto->id,
                    'cantidad_recibida' => $item['cantidad'],
                    'precio_costo' => $item['precioCosto'],
                ]);
            }

            // 8. CONFIRMAR TODOS LOS CAMBIOS.
            DB::commit();

            return response()->json([
                'exito' => true,
                'mensaje' => '¡Ingreso de inventario registrado correctamente!',
                'ingreso_id' => $ingreso->id,
            ], 200);
        } catch (\Throwable $e) {

            // 9. SI ALGO FALLA, DESHACER LOS CAMBIOS.
            DB::rollBack();

            // Guardar el error real en laravel.log.
            report($e);

            return response()->json([
                'exito' => false,
                'mensaje' => 'Ocurrió un error al guardar el ingreso.',
            ], 500);
        }
    }
}
