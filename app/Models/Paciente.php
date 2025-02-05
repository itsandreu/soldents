<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $fillable = [
        'persona_id',
    ];
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

}
