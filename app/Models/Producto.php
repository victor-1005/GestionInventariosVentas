<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';

    protected $fillable = [
        'codigo_producto',
        'categoria_id',
        'nombre_producto',
        'descripcion',
        'precio',
        'stock_actual',
        'stock_minimo'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function ingresosDetalle()
    {
        return $this->hasMany(IngresoDetalle::class);
    }
}