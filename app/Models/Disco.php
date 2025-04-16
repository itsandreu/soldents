<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Disco extends Model
{
    protected $fillable = ['material', 'marca', 'color', 'translucidez', 'dimensiones', 'reduccion', 'lote','status','unidades'];

    public function trabajos()
    {
        return $this->belongsToMany(Trabajo::class);
    }
}
