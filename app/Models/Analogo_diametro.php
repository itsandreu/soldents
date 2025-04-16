<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Analogo_diametro extends Model
{

    protected $fillable = ['valor'];

    public function analogos()
    {
        return $this->belongsToMany(Analogo::class);
    }
}
