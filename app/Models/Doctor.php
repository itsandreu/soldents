<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{

    protected $table = 'doctores';
    protected $fillable = [
        'persona_id',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }
}
