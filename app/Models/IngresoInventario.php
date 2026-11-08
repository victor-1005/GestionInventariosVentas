<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngresoInventario extends Model
{
    protected $table = 'ingresos_inventario';

    protected $fillable = [
        'numero_documento',
        'fecha_ingreso',
        'proveedor_id',
        'usuario_id'
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function detalles()
    {
        return $this->hasMany(
            IngresoDetalle::class,
            'ingreso_inventario_id'
        );
    }
}