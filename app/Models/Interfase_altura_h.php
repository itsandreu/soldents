<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interfase_altura_h extends Model
{
    protected $table = 'interfase_altura_h';
    
    protected $fillable = ['nombre'];

    public function interfases()
    {
        return $this->belongsToMany(Interfase::class);
    }
}
