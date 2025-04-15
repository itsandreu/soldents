<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interfase_tipo extends Model
{
    protected $table = 'interfase_tipo';

    protected $fillable = ['nombre'];

    public function interfases()
    {
        return $this->belongsToMany(Interfase::class);
    }
}
