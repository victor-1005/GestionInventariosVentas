<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\EncargadoLoginController;
use App\Http\Controllers\Encargado\IngresoInventarioController;
use App\Http\Controllers\Encargado\ExistenciasController;

// Página principal pública.
Route::get('/', function () {
    return view('inicio');
})->name('inicio');

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

// Rutas privadas del inventario.
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

        Route::get('/alertas', function () {
            return view('encargado.inventario.alertas');
        })->name('alertas');

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
    });
