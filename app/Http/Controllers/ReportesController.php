<?php

namespace App\Http\Controllers;

use App\Models\IngresoInventario;
use App\Models\Venta;

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
}
