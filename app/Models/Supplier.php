<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = ['nombre', 'cif_nif', 'email', 'telefono', 'iban', 'direccion', 'codigo_postal', 'ciudad', 'provincia', 'pais', 'observaciones'];

    public function facturas()
    {
        return $this->hasMany(Factura::class);
    }
}
