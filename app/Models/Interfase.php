<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interfase extends Model
{
    protected $fillable = ['interfase_marca_id', 'interfase_tipo_id', 'interfase_diametro_id','interfase_altura_g_id','interfase_altura_h_id','rotacion','referencia','unidades'];
    
    public function trabajos()
    {
        return $this->belongsToMany(Trabajo::class);
    }

    public function marca()
    {
        return $this->belongsTo(Interfase_marca::class, 'interfase_marca_id');
    }

    public function tipo()
    {
        return $this->belongsTo(Interfase_tipo::class,'interfase_tipo_id');
    }

    public function diametro()
    {
        return $this->belongsTo(Interfase_diametro::class,'interfase_diametro_id');
    }

    public function alturaH()
    {
        return $this->belongsTo(Interfase_altura_h::class, 'interfase_altura_h_id');
    }

    public function alturaG()
    {
        return $this->belongsTo(Interfase_altura_g::class,'interfase_altura_g_id');
    }
}
