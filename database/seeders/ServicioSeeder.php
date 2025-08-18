<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Servicio;

class ServicioSeeder extends Seeder
{
    public function run(): void
    {
        $servicios = [
            ['tipodeservicio' => 'comer en sucursal'],
            ['tipodeservicio' => 'para llevar'],
        ];

        foreach ($servicios as $servicio) {
            \App\Models\Servicio::create($servicio);
        }
    }
}
