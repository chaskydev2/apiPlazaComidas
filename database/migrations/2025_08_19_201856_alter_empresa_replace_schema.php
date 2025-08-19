<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('empresa', function (Blueprint $table) {
            // 🔻 Elimina columnas antiguas
            foreach (['nombre','ubicacion','direccion','sucursal','horario'] as $old) {
                if (Schema::hasColumn('empresa', $old)) {
                    $table->dropColumn($old);
                }
            }

            // 🔺 Agrega el nuevo esquema
            $table->string('name')->nullable()->after('idempresa');
            $table->string('normalized_name')->nullable()->after('name');
            $table->text('description')->nullable()->after('normalized_name');
            $table->string('google_maps_url')->nullable()->after('description');
            $table->string('id_categoria_food')->nullable()->after('google_maps_url');
            $table->string('image_url')->nullable()->after('id_categoria_food');
            $table->string('logo_url')->nullable()->after('image_url');
            $table->boolean('is_especial')->default(false)->after('logo_url');
            $table->string('location')->nullable()->after('is_especial');
            $table->string('manager_id')->nullable()->after('location');
            $table->unsignedTinyInteger('stars')->nullable()->after('manager_id'); // 0..10 recomendado
            $table->json('open_days')->nullable()->after('stars');     // array de strings
            $table->json('open_hours')->nullable()->after('open_days'); // {opening, closing}
        });
    }

    public function down(): void
    {
        Schema::table('empresa', function (Blueprint $table) {
            // 🔻 Revierte a las columnas antiguas
            $table->string('nombre')->nullable();
            $table->string('ubicacion')->nullable();
            $table->string('direccion')->nullable();
            $table->string('sucursal')->nullable();
            $table->string('horario')->nullable();

            // 🔺 Elimina las nuevas
            $table->dropColumn([
                'name','normalized_name','description','google_maps_url','id_categoria_food',
                'image_url','logo_url','is_especial','location','manager_id','stars',
                'open_days','open_hours'
            ]);
        });
    }
};