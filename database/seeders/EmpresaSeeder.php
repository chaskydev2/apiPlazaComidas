<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;

class EmpresaSeeder extends Seeder
{
    public function run(): void
    {
        Empresa::query()->delete();

        Empresa::create([
            'name'            => 'Koko pollo',
            'normalized_name' => 'koko pollo',
            'description'     => 'una sucursal de koko',
            'google_maps_url' => '',
            'image_url'       => null,
            'logo_url'        => null,
            'is_especial'     => true,
            'location'        => ' Av López y C salomon',
            'manager_id'      => 'ZI5GDNk0rLSnKPdmvWfZw08aMPx1',
            'stars'           => 8,
            'open_days'       => ['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'],
            'open_hours'      => ['opening' => '03:12', 'closing' => '20:11'],
            'idcategoria'     => 1,
        ]);

        Empresa::create([
            'name'            => 'Burguer King',
            'normalized_name' => 'burguer king',
            'description'     => 'Una sucursal de Burguer King',
            'google_maps_url' => 'https://maps.app.goo.gl/h7xBeN2ge5UEDxyJ6',
            'image_url'       => null,
            'logo_url'        => null,
            'is_especial'     => true,
            'location'        => 'AV. Lopez. C. Salamanca',
            'manager_id'      => 'Z6049MAD8rV4GvHyuP97igTiDRG3',
            'stars'           => 2,
            'open_days'       => ['Lun','Mar','Mié','Jue','Vie'],
            'open_hours'      => ['opening' => '10:00 AM', 'closing' => '8:00 PM'],
            'idcategoria'     => 1,
        ]);
    }
}
