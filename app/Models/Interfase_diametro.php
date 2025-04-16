<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interfase_diametro extends Model
{
    protected $table = 'interfase_diametro';

    protected $fillable = ['valor'];

    public function interfases()
    {
        return $this->belongsToMany(Interfase::class);
    }
}
