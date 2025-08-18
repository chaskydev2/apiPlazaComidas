<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Carbon\Carbon;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultImage = 'https://via.placeholder.com/500x300?text=Imagen+No+Disponible';
        
        $products = [
            [
                'name' => 'Refresco vaso',
                'description' => 'Refresco servido en vaso',
                'price' => 3.00,
                'image_url' => asset('product_pictures/menu-cuadrado-refresco.png')
            ],
            [
                'name' => 'Refresco botella',
                'description' => 'Refresco en botella personal',
                'price' => 12.00,
                'image_url' => asset('product_pictures/menu-cuadrado-refresco.png')
            ],
            [
                'name' => 'Salchikono',
                'description' => 'Delicioso Salchikono con aderezos',
                'price' => 16.00,
                'image_url' => asset('product_pictures/menu-cuadrado-salchikono.png')
            ],
            [
                'name' => 'SuperKono',
                'description' => 'SuperKono con ingredientes especiales',
                'price' => 16.00,
                'image_url' => asset('product_pictures/menu-cuadrado-salchikono.png')
            ],
            [
                'name' => 'Milanesa',
                'description' => 'Milanesa con guarnición',
                'price' => 16.00,
                'image_url' => asset('product_pictures/menu-cuadrado-milanesa.png')
            ],
            [
                'name' => 'Silpancho',
                'description' => 'Silpancho tradicional',
                'price' => 16.00,
                'image_url' => asset('product_pictures/menu-cuadrado-silpancho.png')
            ],
            [
                'name' => 'Salchiburger',
                'description' => 'Hamburguesa con salchicha',
                'price' => 20.00,
                'image_url' => asset('product_pictures/menu-cuadrado-salchiburguer.png')
            ],
            [
                'name' => 'Hamburguesa',
                'description' => 'Hamburguesa clásica',
                'price' => 15.00,
                'image_url' => asset('product_pictures/menu-cuadrado-hamburguesa.png')
            ],
            [
                'name' => 'Salchipapa',
                'description' => 'Salchipapa con aderezos',
                'price' => 15.00,
                'image_url' => asset('product_pictures/menu-cuadrado-salchipapa.png')
            ],
            [
                'name' => 'Pollo entero Broaster',
                'description' => 'Pollo entero preparado al estilo Broaster',
                'price' => 80.00,
                'image_url' => asset('product_pictures/menu-cuadrado-broaster.png')
            ],
            [
                'name' => 'Pollo entero Spiedo',
                'description' => 'Pollo entero preparado al estilo Spiedo',
                'price' => 70.00,
                'image_url' => asset('product_pictures/menu-cuadrado-spiedo.png')
            ],
            [
                'name' => 'Familiar Broaster',
                'description' => 'Plato familiar de pollo Broaster con guarniciones',
                'price' => 33.00,
                'image_url' => asset('product_pictures/menu-cuadrado-broaster.png')
            ],
            [
                'name' => 'Familiar Spiedo',
                'description' => 'Plato familiar de pollo al Spiedo con guarniciones',
                'price' => 33.00,
                'image_url' => asset('product_pictures/menu-cuadrado-spiedo.png')
            ],
            [
                'name' => 'Dúo Broaster',
                'description' => 'Plato para dos personas de pollo Broaster con guarniciones',
                'price' => 24.00,
                'image_url' => asset('product_pictures/menu-cuadrado-broaster.png')
            ],
            [
                'name' => 'Dúo Spiedo',
                'description' => 'Plato para dos personas de pollo al Spiedo con guarniciones',
                'price' => 24.00,
                'image_url' => asset('product_pictures/menu-cuadrado-spiedo.png')
            ],
            [
                'name' => 'Económico Broaster',
                'description' => 'Plato económico de pollo Broaster con guarniciones',
                'price' => 16.00,
                'image_url' => asset('product_pictures/menu-cuadrado-broaster.png')
            ],
            [
                'name' => 'Económico Spiedo',
                'description' => 'Plato económico de pollo al Spiedo con guarniciones',
                'price' => 16.00,
                'image_url' => asset('product_pictures/menu-cuadrado-spiedo.png')
            ],
            [
                'name' => '🔥 COMBO GASOLINA 🔥',
                'description' => '2 Pollos económicos (broaster o spiedo) + 1 refresco (hervido o mini)',
                'price' => 35.00,
                'original_price' => 48.00, // Precio original: 2 pollos económicos (32) + refresco (3)
                'is_promotion' => true,
                'promotion_badge' => '¡OFERTA ESPECIAL!',
                'promotion_ends_at' => Carbon::create(2025, 6, 8, 23, 59, 59),
                'image_url' => asset('product_pictures/combo_gasolina.jpg')
            ],
        ];

        foreach ($products as $product) {
            // Guardamos la ruta relativa en la base de datos
            if (str_contains($product['image_url'], 'product_pictures/')) {
                $product['image_url'] = str_replace('http://localhost/', '', $product['image_url']);
            } else {
                $product['image_url'] = $defaultImage;
            }
            Product::create($product);
        }
    }
}
