<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('empresa', function (Blueprint $table) {
            $table->id('idempresa');

            // Nuevo esquema (sin id_categoria_food)
            $table->string('name');
            $table->string('normalized_name')->nullable();
            $table->text('description')->nullable();
            $table->string('google_maps_url')->nullable();

            // FK correcta a categoria.idcategoria
            $table->unsignedBigInteger('idcategoria')->nullable();
            $table->foreign('idcategoria', 'fk_empresa__idcategoria')
                  ->references('idcategoria')->on('categoria')
                  ->cascadeOnUpdate()
                  ->nullOnDelete();

            $table->string('image_url')->nullable();
            $table->string('logo_url')->nullable();
            $table->boolean('is_especial')->default(false);
            $table->string('location')->nullable();
            $table->string('manager_id')->nullable();
            $table->unsignedTinyInteger('stars')->nullable(); // 0..10

            // arrays JSON
            $table->json('open_days')->nullable();   // ["Lun","Mar",...]
            $table->json('open_hours')->nullable();  // {"opening":"08:00","closing":"20:00"}

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('empresa', function (Blueprint $table) {
            try { $table->dropForeign('fk_empresa__idcategoria'); } catch (\Throwable $e) {}
        });
        Schema::dropIfExists('empresa');
    }
};
