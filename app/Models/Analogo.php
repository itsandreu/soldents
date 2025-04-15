<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Analogo extends Model
{
    protected $fillable = ['status', 'analogo_marca_id', 'analogo_modelo_id', 'analogo_diametro_id','referencia','unidades'];
    
    public function trabajos()
    {
        return $this->belongsToMany(Trabajo::class);
    }

    public function marca()
    {
        return $this->belongsTo(Analogo_marca::class,'analogo_marca_id');
    }

    public function modelo()
    {
        return $this->belongsTo(Analogo_modelo::class,'analogo_modelo_id');
    }

    public function diametro()
    {
        return $this->belongsTo(Analogo_diametro::class,'analogo_diametro_id');
    }

}
