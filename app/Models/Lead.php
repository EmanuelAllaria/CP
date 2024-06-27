<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'nombre', 'user_id', 'email', 'telefono', 'monto', 'cliente_id', 'producto_id', 'cantidad', 'estado'
    ];

    // Relación con cliente (un lead pertenece a un cliente después de ser convertido)
    public function cliente()
    {
        return $this->belongsTo(Contact::class);
    }
}