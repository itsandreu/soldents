<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Resina extends Model
{
    protected $fillable = ['tipo', 'marca', 'litros', 'lote','status','unidades'];

    public function trabajos()
    {
        return $this->belongsToMany(Trabajo::class);
    }
}
