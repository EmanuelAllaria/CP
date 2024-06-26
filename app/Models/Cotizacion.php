<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Cotizacion extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'client_name',
        'product_ids',
        'service_ids',
        'amount',
        'status',
        'expiration_date',
    ];
}
