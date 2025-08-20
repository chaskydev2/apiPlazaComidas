<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image_url',
        'is_available',
        'price',
        'idempresa',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'price'        => 'decimal:2',
    ];

    public function empresa()
    {
        // Relación con Empresa (PK no estándar idempresa)
        return $this->belongsTo(Empresa::class, 'idempresa', 'idempresa');
    }
}