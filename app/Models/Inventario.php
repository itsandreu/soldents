<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Inventario extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'cantidad',
        'inventariable_type',
        'inventariable_id'
    ];

    public function inventariable(): MorphTo {
        return $this->morphTo();
    }
    
    public function trabajos()
    {
        return $this->belongsToMany(Trabajo::class, 'inventario_trabajo')->withTimestamps();
    }
}
