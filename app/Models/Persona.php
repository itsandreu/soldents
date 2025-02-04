<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $fillable = [
        'clinica_id',
        'nombre',
        'apellidos',
        'telefono'
    ];
}
