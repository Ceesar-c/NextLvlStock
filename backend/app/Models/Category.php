<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // EL DEFINIR ESTE TRAIT LE INDICA AL MODELO QUE PUEDE RECIBIR DATOS PARA PRUEBAS DE FORMA MASIVA
    use HasFactory;
    // DEFINIMOS LOS CAMPOS QUE PUEDEN LLENARSE MEDIANTE ASIGNACIÓN MASIVA ($request), es una lista blanca de datos permitidos, permite proteger
    // que puede y que no puede recibir de una petición
    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
