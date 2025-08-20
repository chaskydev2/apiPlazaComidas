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
                'role' => 'admin', 
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

        $users1 = [
            [
                'usuario' => 'Test Manager',
                'email' => 'manager@koko.com',
                'password' => bcrypt('password123'),
                'role' => 'manager', 
                'idempresa' => 1,
            ],
            [
                'usuario' => 'Client Test',
                'email' => 'client@koquito.ai',
                'password' => bcrypt('pass123'),
                'role' => 'cliente',
                'idempresa' => 2,
            ]
        ];

        foreach ($users1 as $userData1) {
            User::factory()->create($userData1);
        }
    }
}