<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoTrabajo extends Model
{
    protected $fillable = [
        'nombre',
        'precio'
    ];

    public function trabajos(){
        return $this->belongsToMany(Trabajo::class)->withTimestamps();
    }
}
