<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Tabla y PK personalizados
    protected $table = 'categoria';
    protected $primaryKey = 'idcategoria';
    public $incrementing = true;
    protected $keyType = 'int';

    // Asignación masiva
    protected $fillable = [
        'category',
        'description',
        'image_url', // almacenamos en snake_case
    ];
}