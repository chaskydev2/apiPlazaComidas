<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category; // tu modelo para la tabla `categoria`

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar antes de insertar
        Category::query()->delete();

        Category::create([
            'category'     => 'Comida rápida',
            'description'  => 'Hamburguesas, pizzas y otros platos rápidos',
            'image_url'    => 'https://midominio.com/img/fastfood.png',
        ]);

        Category::create([
            'category'     => 'Bebidas',
            'description'  => 'Refrescos, jugos y bebidas frías',
            'image_url'    => 'https://midominio.com/img/drinks.png',
        ]);

        Category::create([
            'category'     => 'Postres',
            'description'  => 'Helados, tortas y repostería',
            'image_url'    => 'https://midominio.com/img/desserts.png',
        ]);
    }
}