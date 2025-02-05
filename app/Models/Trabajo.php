<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trabajo extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion',
        'paciente_id'
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    public function inventario()
    {
        return $this->belongsToMany(Inventario::class, 'inventario_trabajo')->withTimestamps();
    }
}
