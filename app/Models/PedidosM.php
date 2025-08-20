<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PedidosM extends Model
{
    protected $table = 'pedidos_m';
    protected $primaryKey = 'idpedidosm';

    protected $fillable = [
        'idempresa',
        'idusuario',
        'notas',
        'total',
        'ordernumber',
        'status',
        'estado',
    ];

    // Relaciones
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'idempresa', 'idempresa');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'idusuario', 'idusuario');
    }

    public function items()
    {
        return $this->hasMany(PedidoProducto::class, 'idpedido', 'idpedidosm');
    }

    // (Opcional) Acceso directo a productos con info del pivot
    public function products()
    {
        return $this->belongsToMany(Product::class, 'pedido_producto', 'idpedido', 'idproducts')
            ->withPivot(['cantidad', 'precio', 'notas'])
            ->withTimestamps();
    }
}
