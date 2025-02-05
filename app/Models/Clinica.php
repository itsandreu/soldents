<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clinica extends Model
{
    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'descripcion'
    ];

    public function persona(){
        return $this->belongsTo(Persona::class);
    }
}
