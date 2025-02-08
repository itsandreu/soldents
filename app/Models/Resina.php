<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Resina extends Model
{
    protected $fillable = ['tipo', 'marca', 'litros', 'lote'];

    public function inventario(): MorphOne {
        return $this->morphOne(Inventario::class, 'inventariable');
    }
}
