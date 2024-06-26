<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Tarea extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = ['proyecto_id', 'user_id', 'nombre', 'descripcion', 'fecha_limite', 'completada'];
}
