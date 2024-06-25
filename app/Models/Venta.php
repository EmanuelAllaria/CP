<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = [
        'cliente_id', 'producto_id', 'cantidad', 'monto'
    ];

    // Relación con cliente (una venta pertenece a un cliente)
    public function cliente()
    {
        return $this->belongsTo(Contact::class);
    }

    // Relación con producto (si es necesario)
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
