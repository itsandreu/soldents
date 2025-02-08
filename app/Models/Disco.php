<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Disco extends Model
{
    protected $fillable = ['material', 'marca', 'color', 'translucidez', 'dimensiones', 'reduccion', 'lote'];

    public function inventario(): MorphOne {
        return $this->morphOne(Inventario::class, 'inventariable');
    }
}
