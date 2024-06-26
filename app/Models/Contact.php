<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Contact extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'user_id', 'company', 'email', 'phone', 'active'];

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }
}
