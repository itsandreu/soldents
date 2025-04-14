<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{   
    protected $table = 'estados';
    protected $fillable = [
        'nombre',
        'color'
    ];

    public function persona(){
        return $this->belongsToMany(Trabajo::class);
    }
}
