<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Producto extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
    ];
}
