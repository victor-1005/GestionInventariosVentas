<?php

namespace App\Http\Controllers\Encargado;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VentasController extends Controller
{
    // ==========================================
    // 1. MOSTRAR PANTALLA DE VENTAS
    // ==========================================

    public function index()
    {
        // Obtener productos con stock bajo o agotado.
        $productosBajoStock = Producto::whereColumn(
            'stock_actual',
            '<=',
            'stock_minimo'
        )
        ->orderBy('stock_actual')
        ->limit(8)
        ->get();

        return view(
            'encargado.ventas.index',
            compact('productosBajoStock')
        );
    }


    // ==========================================
    // 2. BUSCAR PRODUCTO POR CÓDIGO
    // ==========================================

    public function buscarProducto($codigo)
    {
        $codigo = trim($codigo);

        $producto = Producto::where(
            'codigo_producto',
            $codigo
        )->first();

        // Si el producto no existe.
        if (!$producto) {

            return response()->json([

                'existe' => false,

                'mensaje' => 'Producto no encontrado en la base de datos.'

            ], 200);

        }

        // Si el producto existe.
        return response()->json([

            'existe' => true,

            'mensaje' => 'Producto encontrado. Datos recuperados.',

            'producto' => [

                'id' => $producto->id,

                'codigo' => $producto->codigo_producto,

                'nombre' => $producto->nombre_producto,

                'precio' => (float) $producto->precio,

                'stock_actual' => (int) $producto->stock_actual,

                'stock_minimo' => (int) $producto->stock_minimo

            ]

        ], 200);
    }

    public function buscarProductosAutocompletado(Request $request)
    {
        $query = $request->input('q');
        
        // Si no hay texto, devolvemos un array vacío
        if (empty(trim($query))) {
            return response()->json([]);
        }

        // Buscar por nombre O por código (coincidencia parcial)
        $productos = Producto::where('nombre_producto', 'LIKE', "%{$query}%")
                            ->orWhere('codigo_producto', 'LIKE', "%{$query}%")
                            ->limit(10) // Límite para no saturar el DOM
                            ->get();

        // Formatear la respuesta para que el JS la entienda fácilmente
        $resultados = $productos->map(function ($producto) {
            return [
                'id' => $producto->id,
                'codigo' => $producto->codigo_producto,
                'nombre' => $producto->nombre_producto,
                'precio' => (float) $producto->precio,
                'stock_actual' => (int) $producto->stock_actual,
                'stock_minimo' => (int) $producto->stock_minimo
            ];
        });

        return response()->json($resultados);
    }
    // ==========================================
    // 3. REGISTRAR TRANSACCIÓN
    // ==========================================

    public function guardar(Request $request)
    {
        // Validar los datos recibidos.
        $datos = $request->validate([

            'productos' => [
                'required',
                'array',
                'min:1'
            ],

            'productos.*.producto_id' => [
                'required',
                'integer',
                'distinct',
                'exists:productos,id'
            ],

            'productos.*.cantidad' => [
                'required',
                'integer',
                'min:1'
            ]

        ], [

            'productos.required' =>
                'Debes agregar productos a la venta.',

            'productos.min' =>
                'La venta debe contener al menos un producto.',

            'productos.*.producto_id.exists' =>
                'Uno de los productos seleccionados ya no existe.',

            'productos.*.producto_id.distinct' =>
                'Hay productos duplicados en la solicitud.',

            'productos.*.cantidad.min' =>
                'La cantidad debe ser como mínimo una unidad.',

            'productos.*.cantidad.integer' =>
                'La cantidad debe ser un número entero.'

        ]);

        try {

            // ==========================================
            // INICIAR TRANSACCIÓN DE BASE DE DATOS
            // ==========================================

            $venta = DB::transaction(function () use (
                $datos,
                $request
            ) {

                // Obtener los identificadores.
                $ids = collect($datos['productos'])
                    ->pluck('producto_id')
                    ->map(fn($id) => (int) $id)
                    ->sort()
                    ->values();

                // Bloquear los productos mientras se procesa
                // la venta para evitar problemas de concurrencia.
                $productos = Producto::whereIn('id', $ids)
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                // Comprobar que todos los productos siguen disponibles.
                if ($productos->count() !== $ids->count()) {

                    throw ValidationException::withMessages([

                        'productos' => [
                            'Uno de los productos ya no está disponible.'
                        ]

                    ]);

                }

                $total = 0;

                // ==========================================
                // VALIDAR STOCK Y CALCULAR TOTAL
                // ==========================================

                foreach ($datos['productos'] as $detalle) {

                    $producto = $productos->get(
                        (int) $detalle['producto_id']
                    );

                    $cantidad = (int) $detalle['cantidad'];

                    // Verificar existencias.
                    if ($producto->stock_actual < $cantidad) {

                        throw ValidationException::withMessages([

                            'productos' => [

                                "Existencias insuficientes para {$producto->nombre_producto}. Stock disponible: {$producto->stock_actual}."

                            ]

                        ]);

                    }

                    // Calcular subtotal.
                    $subtotal = round(
                        $cantidad * (float) $producto->precio,
                        2
                    );

                    $total += $subtotal;

                }

                $total = round($total, 2);

                // ==========================================
                // CREAR CABECERA DE VENTA
                // ==========================================

                $venta = Venta::create([

                    'usuario_id' => $request->user()->id,

                    'total' => $total

                ]);

                // ==========================================
                // GUARDAR DETALLES Y DESCONTAR STOCK
                // ==========================================

                foreach ($datos['productos'] as $detalle) {

                    $producto = $productos->get(
                        (int) $detalle['producto_id']
                    );

                    $cantidad = (int) $detalle['cantidad'];

                    // Registrar detalle de la venta.
                    $venta->detalles()->create([

                        'producto_id' => $producto->id,

                        'cantidad_vendida' => $cantidad,

                        'precio' => $producto->precio

                    ]);

                    // Descontar existencias.
                    $producto->decrement(
                        'stock_actual',
                        $cantidad
                    );

                }

                return $venta;

            });

            // ==========================================
            // RESPUESTA EXITOSA
            // ==========================================

            return response()->json([

                'exito' => true,

                'mensaje' =>
                    '¡Transacción realizada correctamente!',

                'venta_id' => $venta->id,

                'total' => (float) $venta->total

            ], 200);

        } catch (ValidationException $e) {

            // Laravel manejará los errores de validación.
            throw $e;

        } catch (\Throwable $e) {

            // Registrar el error para poder identificarlo.
            report($e);

            return response()->json([

                'exito' => false,

                'mensaje' =>
                    'No fue posible registrar la transacción. Intenta nuevamente.'

            ], 500);

        }
    }
}
