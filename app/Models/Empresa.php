<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $table = 'empresa';
    protected $primaryKey = 'idempresa';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'name',
        'normalized_name',
        'description',
        'google_maps_url',
        'idcategoria',
        'image_url',
        'logo_url',
        'is_especial',
        'location',
        'manager_id',
        'stars',
        'open_days',
        'open_hours',
    ];

    protected $casts = [
        'is_especial' => 'boolean',
        'stars'       => 'integer',
        'open_days'   => 'array',
        'open_hours'  => 'array',
    ];

    public function categoria()
    {
        return $this->belongsTo(\App\Models\Category::class, 'idcategoria', 'idcategoria');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'idempresa', 'idempresa');
    }
}