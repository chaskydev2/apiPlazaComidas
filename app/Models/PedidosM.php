<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidosM extends Model
{
    protected $table = 'pedidos_m';
    protected $primaryKey = 'idpedidosm';
    protected $fillable = ['idmesa', 'idservicio','idempresa', 'idusuario', 'idcliente', 'total', 'dateRegistro', 'estado'];
}
