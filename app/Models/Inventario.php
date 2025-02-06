<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'cantidad',
    ];

    public function trabajos()
    {
        return $this->belongsToMany(Trabajo::class, 'inventario_trabajo')->withTimestamps();
    }
}
