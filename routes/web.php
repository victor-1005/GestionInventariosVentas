<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\EncargadoLoginController;
use App\Http\Controllers\Encargado\IngresoInventarioController;
use App\Http\Controllers\Encargado\ExistenciasController;
use App\Http\Controllers\Encargado\AlertasController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\Encargado\VentasController;
use App\Http\Controllers\Encargado\CategoriaController;
use App\Http\Controllers\Encargado\ProveedorController;

// Página principal pública.
Route::get('/', [InicioController::class, 'index'])
    ->name('inicio');

// Rutas de autenticación del encargado.
Route::prefix('encargado')->name('encargado.')->group(function () {

    Route::get('/login', [EncargadoLoginController::class, 'create'])
        ->name('login');

    Route::post('/login', [EncargadoLoginController::class, 'store'])
        ->name('login.store');

    Route::post('/logout', [EncargadoLoginController::class, 'destroy'])
        ->middleware('auth')
        ->name('logout');
});

// ==========================================
// RUTAS PRIVADAS DE INVENTARIOS
// ==========================================
Route::middleware(['auth', 'rol:encargado_inventario'])
    ->prefix('encargado/inventario')
    ->name('encargado.inventario.')
    ->group(function () {

        Route::get('/', function () {
            return view('encargado.inventario.index');
        })->name('index');

        // Mostrar módulo de ingresos.
        Route::get('/ingresos', [
            IngresoInventarioController::class,
            'index'
        ])->name('ingresos');

        // Buscar producto por código.
        Route::get('/productos/buscar/{codigo}', [
            IngresoInventarioController::class,
            'buscarProducto'
        ])->name('productos.buscar');

        // Guardar ingreso de inventario.
        Route::post('/ingresos/guardar', [
            IngresoInventarioController::class,
            'guardar'
        ])->name('ingresos.guardar');

        //Para las alertas de stock
        Route::get('/alertas', [
            AlertasController::class,
            'index'
        ])->name('alertas');

        // Para las existencias
        Route::get('/existencias', [
            ExistenciasController::class,
            'index'
        ])->name('existencias');

        //Editar
        Route::get('/existencias/{id}/editar', [
            ExistenciasController::class,
            'editar'
        ])->name('existencias.editar');

        //Actualizar
        Route::put('/existencias/{id}', [
            ExistenciasController::class,
            'actualizar'
        ])->name('existencias.actualizar');

        //Eliminar
        Route::delete('/existencias/{id}', [
            ExistenciasController::class,
            'eliminar'
        ])->name('existencias.eliminar');

        //Para las categorias. 
        // Mostrar categorías.
        Route::get('/categorias', [
            CategoriaController::class,
            'index'
        ])->name('categorias');

        // Guardar nueva categoría.
        Route::post('/categorias/guardar', [
            CategoriaController::class,
            'guardar'
        ])->name('categorias.guardar');

        // PROVEEDORES
        // Mostrar proveedores.
        Route::get('/proveedores', [
            ProveedorController::class,
            'index'
        ])->name('proveedores');

        // Guardar nuevo proveedor.
        Route::post('/proveedores/guardar', [
            ProveedorController::class,
            'guardar'
        ])->name('proveedores.guardar');
    });


// ==========================================
// RUTAS PRIVADAS DE VENTAS
// ==========================================
Route::middleware(['auth', 'rol:encargado_ventas'])
    ->prefix('encargado/ventas')
    ->name('encargado.ventas.')
    ->group(function () {

        // Pantalla principal de ventas.
        Route::get('/', [
            VentasController::class,
            'index'
        ])->name('index');

        // Buscar producto por código.
        Route::get('/productos/buscar/{codigo}', [
            VentasController::class,
            'buscarProducto'
        ])->name('productos.buscar');

        // Guardar transacción.
        Route::post('/guardar', [
            VentasController::class,
            'guardar'
        ])->name('guardar');
    });
