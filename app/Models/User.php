<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens; 
use Illuminate\Database\Eloquent\Casts\Attribute; 

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'users'; // opcional, por claridad
    protected $primaryKey = 'idusuario';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'usuario',
        'email',
        'password',
        'idempresa',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 🔐 Mutador para hashear SIEMPRE la contraseña
    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => bcrypt($value),
        );
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'idempresa');
    }
}