<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Invoice extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'servicio_ids',
        'producto_ids',
        'client_id',
        'amount',
        'due_date',
        'status',
    ];
}
