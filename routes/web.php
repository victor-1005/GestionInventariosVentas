<?php

use Illuminate\Support\Facades\Route;

//Pagina principal del sistema sin autenticación
Route::get('/',function(){
    return view('inicio');//buscamos en "inicio.blade.php"
})->name('inicio');//asignamos un nombre a la ruta
