<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interfase_altura_g extends Model
{
    protected $table = 'interfase_altura_g';

    protected $fillable = ['valor'];

    public function interfases()
    {
        return $this->belongsToMany(Interfase::class);
    }
}
