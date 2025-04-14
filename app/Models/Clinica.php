<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clinica extends Model
{
    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'descripcion',
        'foto'
    ];

    public function persona(){
        return $this->hasMany(Persona::class);
    }
}
