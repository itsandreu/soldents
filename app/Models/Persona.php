<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $fillable = [
        'clinica_id',
        'nombre',
        'apellidos',
        'telefono',
        'tipo',
        'nota'
    ];


    public function clinica()
    {
        return $this->belongsTo(Clinica::class);
    }

    public function paciente()
    {
        return $this->hasOne(Paciente::class, 'persona_id');
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'persona_id');
    }
}
