<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PedidosM;
use App\Models\PedidoProducto;
use Illuminate\Support\Str;

class PedidosMSeeder extends Seeder
{
    public function run(): void
    {
        // Pedido 1
        $pedido1 = PedidosM::create([
            'idempresa'   => 1, // asegúrate que exista empresa con ID 1
            'idusuario'   => 1, // asegúrate que exista usuario con ID 1
            'notas'       => 'Pedido de prueba - mesa 4',
            'total'       => 0, // se recalcula abajo
            'ordernumber' => 'ORD-'.now()->format('Ymd-His').'-'.Str::upper(Str::random(4)),
            'status'      => 'pending',
            'estado'      => 'activo',
        ]);

        $items1 = [
            ['idproducts' => 1, 'cantidad' => 2, 'precio' => 15.00, 'notas' => 'sin sal'],
            ['idproducts' => 2, 'cantidad' => 1, 'precio' => 12.00],
        ];

        $total1 = 0;
        foreach ($items1 as $item) {
            PedidoProducto::create([
                'idpedido'   => $pedido1->idpedidosm,
                'idproducts' => $item['idproducts'],
                'cantidad'   => $item['cantidad'],
                'precio'     => $item['precio'],
                'notas'      => $item['notas'] ?? null,
            ]);
            $total1 += $item['cantidad'] * $item['precio'];
        }
        $pedido1->update(['total' => $total1]);

        // Pedido 2
        $pedido2 = PedidosM::create([
            'idempresa'   => 1,
            'idusuario'   => 1,
            'notas'       => 'Pedido rápido para llevar',
            'total'       => 0,
            'ordernumber' => 'ORD-'.now()->format('Ymd-His').'-'.Str::upper(Str::random(4)),
            'status'      => 'paid',
            'estado'      => 'activo',
        ]);

        $items2 = [
            ['idproducts' => 3, 'cantidad' => 1, 'precio' => 20.00],
        ];

        $total2 = 0;
        foreach ($items2 as $item) {
            PedidoProducto::create([
                'idpedido'   => $pedido2->idpedidosm,
                'idproducts' => $item['idproducts'],
                'cantidad'   => $item['cantidad'],
                'precio'     => $item['precio'],
                'notas'      => $item['notas'] ?? null,
            ]);
            $total2 += $item['cantidad'] * $item['precio'];
        }
        $pedido2->update(['total' => $total2]);
    }
}
