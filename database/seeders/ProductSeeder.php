<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Empresa;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::query()->delete();

        // Tomamos alguna empresa existente para relacionar productos
        $empresaA = Empresa::query()->orderBy('idempresa')->first();
        $empresaB = Empresa::query()->orderBy('idempresa', 'desc')->first();

        // Si no hay empresas, no podemos crear productos relacionados
        if (!$empresaA) {
            return;
        }

        $defaults = [
            [
                'name'         => 'Refresco vaso',
                'description'  => 'Refresco servido en vaso',
                'image_url'    => 'product_pictures/menu-cuadrado-refresco.png',
                'is_available' => true,
                'price'        => 3.00,
                'idempresa'    => $empresaA->idempresa,
            ],
            [
                'name'         => 'Hamburguesa',
                'description'  => 'Hamburguesa clásica',
                'image_url'    => 'product_pictures/menu-cuadrado-hamburguesa.png',
                'is_available' => true,
                'price'        => 15.00,
                'idempresa'    => ($empresaB?->idempresa ?? $empresaA->idempresa),
            ],
            [
                'name'         => 'Salchipapa',
                'description'  => 'Salchipapa con aderezos',
                'image_url'    => 'product_pictures/menu-cuadrado-salchipapa.png',
                'is_available' => true,
                'price'        => 15.00,
                'idempresa'    => $empresaA->idempresa,
            ],
        ];

        foreach ($defaults as $p) {
            Product::create($p);
        }
    }
}
