<?php

namespace App\Http\Controllers\Encargado;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

class AlertasController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        // Consultar solamente productos con stock bajo.
        $consulta = Producto::with('categoria')
            ->whereColumn('stock_actual', '<=', 'stock_minimo');

        // Aplicar el buscador.
        $consulta->when($buscar, function ($query) use ($buscar) {
            $query->where(function ($q) use ($buscar) {
                $q->where('codigo_producto', 'like', "%{$buscar}%")
                    ->orWhere('nombre_producto', 'like', "%{$buscar}%")
                    ->orWhereHas('categoria', function ($categoria) use ($buscar) {
                        $categoria->where(
                            'nombre_categoria',
                            'like',
                            "%{$buscar}%"
                        );
                    });
            });
        });

        $totalAlertas = (clone $consulta)->count();

        $productos = $consulta
            ->orderBy('stock_actual', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('encargado.inventario.alertas', compact(
            'productos',
            'buscar',
            'totalAlertas'
        ));
    }
}
