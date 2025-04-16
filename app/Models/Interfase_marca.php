<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interfase_marca extends Model
{
    protected $table = 'interfase_marcas';

    protected $fillable = ['nombre'];

    public function interfases()
    {
        return $this->belongsToMany(Interfase::class);
    }
}
