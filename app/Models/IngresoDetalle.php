<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngresoDetalle extends Model
{
    protected $table = 'ingresos_detalle';

    protected $fillable = [
        'ingreso_inventario_id',
        'producto_id',
        'cantidad_recibida',
        'precio_costo'
    ];

    public function ingreso()
    {
        return $this->belongsTo(
            IngresoInventario::class,
            'ingreso_inventario_id'
        );
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}