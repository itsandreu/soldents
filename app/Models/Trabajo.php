<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    protected static function booted()
    {
        static::creating(function ($trabajo) {
            if (empty($trabajo->qr_token)) {
                $trabajo->qr_token = Str::uuid();
            }
        });
    }

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

    public function discos()
    {
        return $this->belongsToMany(Disco::class, 'disco_trabajo')->withTimestamps();
    }

    public function fresas()
    {
        return $this->belongsToMany(Fresa::class, 'fresa_trabajo')->withTimestamps();
    }

    public function resinas()
    {
        return $this->belongsToMany(Resina::class, 'resina_trabajo')->withTimestamps();
    }

    public function interfases()
    {
        return $this->belongsToMany(Interfase::class, 'interfase_trabajo')->withTimestamps();
    }

    public function tornillos()
    {
        return $this->belongsToMany(Tornillo::class, 'tornillo_trabajo')->withTimestamps();
    }

    public function analogos()
    {
        return $this->belongsToMany(Analogo::class, 'analogo_trabajo')->withTimestamps();
    }
}
