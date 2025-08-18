<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';
    protected $primaryKey = 'idmensaje';
    protected $fillable = ['idempresa', 'numero', 'message', 'response'];
}
