<?php

namespace App\Http\Controllers;

use App\Models\IngresoInventario;

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
}
