<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Desactivar restricciones de clave foránea
        \Schema::disableForeignKeyConstraints();

        // Limpiar tabla users
        \DB::table('users')->truncate();

        // Crear usuarios base
        $users = [
            [
                'usuario' => 'Test User',
                'email' => 'admin@koquito.com',
                'password' => bcrypt('password123'),
            ],
            [
                'usuario' => 'Chatbot',
                'email' => 'chatbot@koquito.ai',
                'password' => bcrypt('chatbot123'),
            ]
        ];

        foreach ($users as $userData) {
            User::factory()->create($userData);
        }

        // Reactivar restricciones de clave foránea
        \Schema::enableForeignKeyConstraints();

        // Ejecutar seeders en orden correcto
        $this->call([
            CategoriaSeeder::class, // primero categorías
            EmpresaSeeder::class,   // luego empresas que dependen de categorías
            ProductSeeder::class,   // luego productos
            PedidosMSeeder::class,  // luego pedidos que dependen de productos
        ]);
    }
}