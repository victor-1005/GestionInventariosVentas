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
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\UsuarioController;


// Página principal pública.
Route::get('/', [InicioController::class, 'index'])
    ->name('inicio');

// ==========================================
// Rutas de Reporteria.
// ==========================================

// Pagina de reportes
Route::get('/reportes', [ReportesController::class, 'index'])
    ->name('reportes.index');

//Pagina de ingresos de reporteria
Route::get('/reportes/ingresos',
    [ReportesController::class, 'ingresos']
)->name('reportes.ingresos');

//Pagina de salidas de reporteria
Route::get('/reportes/salidas',
    [ReportesController::class, 'salidas']
)->name('reportes.salidas');
// Reporte de productos más vendidos
Route::get('/reportes/productos-mas-vendidos',
    [ReportesController::class, 'productosMasVendidos']
)->name('reportes.mas-vendidos');

// ==========================================
// Rutas de autenticación del encargado.
// ==========================================
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

        // Guardar categoría.
        Route::post('/categorias/guardar', [
            CategoriaController::class,
            'guardar'
        ])->name('categorias.guardar');

        // Mostrar formulario de edición.
        Route::get('/categorias/{id}/editar', [
            CategoriaController::class,
            'editar'
        ])->name('categorias.editar');

        // Actualizar categoría.
        Route::put('/categorias/{id}', [
            CategoriaController::class,
            'actualizar'
        ])->name('categorias.actualizar');

        // Eliminar categoría.
        Route::delete('/categorias/{id}', [
            CategoriaController::class,
            'eliminar'
        ])->name('categorias.eliminar');

        // PROVEEDORES
        // Mostrar proveedores.
        Route::get('/proveedores', [
            ProveedorController::class,
            'index'
        ])->name('proveedores');

        // Guardar proveedor.
        Route::post('/proveedores/guardar', [
            ProveedorController::class,
            'guardar'
        ])->name('proveedores.guardar');

        // Mostrar formulario de edición.
        Route::get('/proveedores/{id}/editar', [
            ProveedorController::class,
            'editar'
        ])->name('proveedores.editar');

        // Actualizar proveedor.
        Route::put('/proveedores/{id}', [
            ProveedorController::class,
            'actualizar'
        ])->name('proveedores.actualizar');

        // Eliminar proveedor.
        Route::delete('/proveedores/{id}', [
            ProveedorController::class,
            'eliminar'
        ])->name('proveedores.eliminar');
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


                // NUEVA RUTA: Autocompletado (Búsqueda por nombre o código parcial)
        // Usamos '/productos/autocompletado' para que no choque con '/productos/buscar/{codigo}'
        Route::get('/productos/autocompletado', [
            VentasController::class,
            'buscarProductosAutocompletado'
        ])->name('productos.autocompletado');

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

// ==========================================
// RUTAS De PAGINA DE USUARIOS
// ==========================================
// Administración de usuarios
Route::get('/usuarios', [UsuarioController::class, 'index'])
    ->name('usuarios.index');

Route::get('/usuarios/crear', [UsuarioController::class, 'crear'])
    ->name('usuarios.crear');

Route::post('/usuarios', [UsuarioController::class, 'guardar'])
    ->name('usuarios.guardar');

Route::get('/usuarios/listar', [UsuarioController::class, 'listar'])
    ->name('usuarios.listar');

Route::get('/usuarios/{usuario}/editar', [UsuarioController::class, 'editar'])
    ->name('usuarios.editar');

Route::put('/usuarios/{usuario}', [UsuarioController::class, 'actualizar'])
    ->name('usuarios.actualizar');