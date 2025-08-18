<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
//use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'mensajes';

    protected $fillable = [
        'texto', 'origen', 'tipo', 'timestamp'
    ];
}
