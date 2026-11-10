<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;

class InicioController extends Controller
{
    public function index()
    {
        // Total de productos registrados.
        $totalProductos = Producto::count();

        // Total de categorías registradas.
        $totalCategorias = Categoria::count();

        // Productos cuyo stock es igual o menor al mínimo.
        $totalAlertas = Producto::whereColumn(
            'stock_actual',
            '<=',
            'stock_minimo'
        )->count();

        return view('inicio', compact(
            'totalProductos',
            'totalCategorias',
            'totalAlertas'
        ));
    }
}