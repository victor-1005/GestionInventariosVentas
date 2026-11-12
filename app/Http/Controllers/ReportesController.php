<?php

namespace App\Http\Controllers;

use App\Models\IngresoInventario;
use App\Models\Venta;
use Illuminate\Support\Facades\DB;

class ReportesController extends Controller
{
    public function index()
    {
        return view('reportes.index');
    }

    // Reporte de ingresos
    public function ingresos()
    {
        $ingresos = IngresoInventario::with([
            'proveedor',
            'usuario',
            'detalles.producto'
        ])
            ->orderByDesc('fecha_ingreso')
            ->orderByDesc('id')
            ->paginate(10);

        return view('reportes.ingresos', compact('ingresos'));
    }

    // Reporte de salidas (ventas)
    public function salidas()
    {
        $ventas = Venta::with([
            'usuario',
            'detalles.producto'
        ])
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->paginate(10);

        return view('reportes.salidas', compact('ventas'));
    }
    //Producto mas vendido// Reporte de productos más vendidos
    public function productosMasVendidos()
    {
        $productos = DB::table('ventas_detalle')
            ->join('productos', 'ventas_detalle.producto_id', '=', 'productos.id')
            ->select(
                'productos.id',
                'productos.codigo_producto',
                'productos.nombre_producto',
                DB::raw('SUM(ventas_detalle.cantidad_vendida) as total_vendido')
            )
            ->groupBy(
                'productos.id',
                'productos.codigo_producto',
                'productos.nombre_producto'
            )
            ->orderByDesc('total_vendido')
            ->limit(10)
            ->get();

        return view('reportes.mas-vendidos', compact('productos'));
    }
}
