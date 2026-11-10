<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre',
        'usuario',
        'contrasena',
        'rol',
    ];

    protected $hidden = [
        'contrasena',
    ];

    public function getAuthPasswordName()
    {
        return 'contrasena';
    }
     public function ventas()
    {
        return $this->hasMany(Venta::class, 'usuario_id');
    }
}
