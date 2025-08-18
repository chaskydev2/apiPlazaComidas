<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    protected $table = 'mesa';
    protected $primaryKey = 'idmesa';
    protected $fillable = ['idempresa', 'numeromesa', 'qr'];
}
