<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';

    protected $fillable = [
        'nombre_entidad',
        'telefono_contacto',
        'correo'
    ];

    public function ingresos()
    {
        return $this->hasMany(
            IngresoInventario::class,
            'proveedor_id'
        );
    }
}
