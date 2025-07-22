<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $fillable = ['nombre', 'file', 'precio', 'fecha_factura', 'supplier_id'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

}
