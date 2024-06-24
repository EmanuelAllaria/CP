<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Proyecto extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = ['nombre', 'descripcion', 'fecha_inicio', 'fecha_fin', 'presupuesto'];
}
