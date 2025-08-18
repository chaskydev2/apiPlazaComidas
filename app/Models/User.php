<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens; 

class User extends Authenticatable
{
    use HasFactory, Notifiable,HasApiTokens;

    // Definir clave primaria personalizada
    protected $primaryKey = 'idusuario';

    // Laravel asumirá que la clave primaria no se autoincrementa si no es "id"
    public $incrementing = true;

    // Si tu clave no es un entero, deberías definir el tipo (en este caso no es necesario porque es BIGINT por default)
    protected $keyType = 'int';

    // Campos asignables en masa
    protected $fillable = [
        'usuario',         // tu campo personalizado (en vez de 'name')
        'email',
        'password',
        'idempresa'
    ];

    // Campos ocultos en serializaciones (JSON, API, etc.)
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Cast automático de campos
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 🔒 Si usas hashing automático en Laravel 10+ para `password`:
    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => bcrypt($value),
        );
    }

    // Relación con Empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'idempresa');
    }
}
