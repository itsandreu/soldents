<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Analogo_modelo extends Model
{

    protected $fillable = ['nombre'];

    public function analogos()
    {
        return $this->belongsToMany(Analogo::class);
    }
}
