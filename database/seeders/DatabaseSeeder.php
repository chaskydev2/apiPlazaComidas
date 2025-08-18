<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([EmpresaSeeder::class, ServicioSeeder::class]);
        // Desactivar restricciones de clave foránea
        \Schema::disableForeignKeyConstraints();

        // Limpiar la tabla users
        \DB::table('users')->truncate();

        // Crear usuarios
        $users = [
            [
                'name' => 'Test User',
                'email' => 'admin@koquito.com',
                'password' => bcrypt('password123'),
            ],
            [
                'name' => 'Chatbot',
                'email' => 'chatbot@koquito.ai',
            ]
        ];

        foreach ($users as $userData) {
            User::factory()->create($userData);
        }

        // Reactivar restricciones de clave foránea
        \Schema::enableForeignKeyConstraints();

        // Ejecutar otros seeders
        $this->call(ProductSeeder::class);
    }
}
