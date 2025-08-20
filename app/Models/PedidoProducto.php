<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidoProducto extends Model
{
    protected $table = 'pedido_producto';
    protected $primaryKey = 'idpedido_producto';

    protected $fillable = [
        'idpedido',
        'idproducts',
        'cantidad',
        'precio',
        'notas',
    ];

    public function pedido()
    {
        return $this->belongsTo(PedidosM::class, 'idpedido', 'idpedidosm');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'idproducts', 'id');
    }
}