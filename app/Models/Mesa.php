<?php

// app/Models/Mesa.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    protected $table = 'mesa';
    protected $primaryKey = 'idmesa';
    protected $fillable = ['idempresa', 'numeromesa', 'qr'];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'idempresa', 'idempresa');
    }
}
