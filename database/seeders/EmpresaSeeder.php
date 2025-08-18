<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;

class EmpresaSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar datos existentes antes de insertar nuevos
        Empresa::query()->delete();
        
        Empresa::create([
            'nombre'    => 'Pollos Koquito',
            'ubicacion' => '-17.396183118217614, -66.14853680060456',
            'direccion' => 'Av. Blanco Galindo esquina 12 de Octubre',
            'sucursal'  => 'Sucursal Central',
            'horario'   => '9:00 AM - 11:00 PM',
        ]);

        Empresa::create([
            'nombre'    => 'Koquito Norte',
            'ubicacion' => '-17.396132543218904, -66.20782889724386',
            'direccion' => 'Av. Héroes del Chaco Nº 302',
            'sucursal'  => 'Sucursal Norte',
            'horario'   => '8:00 AM - 10:00 PM',
        ]);

        Empresa::create([
            'nombre'    => 'Koquito Sur',
            'ubicacion' => '-17.41234567890123, -66.13456789012345',
            'direccion' => 'Av. Circunvalación Sur Nº 150',
            'sucursal'  => 'Sucursal Sur',
            'horario'   => '10:00 AM - 11:30 PM',
        ]);
    }
}
