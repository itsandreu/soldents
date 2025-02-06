<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trabajo extends Model
{
    protected $fillable = [
        'descripcion',
        'paciente_id',
        'entrada',
        'salida',
        'estado_id',
        'color_boca',
        'tipo_trabajo_id',
        'piezas'
    ];

    protected $casts = [
        'piezas' => 'array'
    ];

    public function estado(){
        return $this->belongsTo(Estado::class);
    }

    public function tipoTrabajo(){
        return $this->belongsTo(TipoTrabajo::class);
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    public function inventario()
    {
        return $this->belongsToMany(Inventario::class, 'inventario_trabajo')->withTimestamps();
    }
}
