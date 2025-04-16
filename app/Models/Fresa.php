<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Fresa extends Model
{
    protected $fillable = ['tipo', 'material', 'marca', 'diametro','unidades','status'];

    public function trabajos()
    {
        return $this->belongsToMany(Trabajo::class);
    }
}
