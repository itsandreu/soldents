<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tornillo_marca extends Model
{
    protected $fillable = ['nombre'];

    public function tornillos()
    {
        return $this->belongsToMany(Tornillo::class);
    }
}
