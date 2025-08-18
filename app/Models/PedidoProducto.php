<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoProducto extends Model
{
    protected $table = 'pedido_producto';
    protected $primaryKey = 'idpedido_producto';
    protected $fillable = ['idpedido', 'idproducts', 'cantidad', 'preciototal', 'datoRegistro', 'estado'];
}
