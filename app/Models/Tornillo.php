<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tornillo extends Model
{
    protected $fillable = ['status', 'tornillo_marca_id', 'tornillo_tipo_id','tornillo_modelo_id','referencia','unidades'];

    public function trabajos()
    {
        return $this->belongsToMany(Trabajo::class);
    }

    public function marca()
    {
        return $this->belongsTo(Tornillo_marca::class,'tornillo_marca_id');
    }

    public function modelo()
    {
        return $this->belongsTo(Tornillo_modelo::class,'tornillo_modelo_id');
    }

    public function tipo()
    {
        return $this->belongsTo(Tornillo_tipo::class,'tornillo_tipo_id');
    }

}
