<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // id
            $table->string('name'); // nombre del producto
            $table->text('description'); // descripción
            $table->string('image_url')->nullable(); // puede ser URL o ruta local
            $table->boolean('is_available')->default(true); // disponibilidad
            $table->decimal('price', 10, 2); // precio

            // FK a empresa.idempresa
            $table->unsignedBigInteger('idempresa');
            $table->foreign('idempresa')
                  ->references('idempresa')->on('empresa')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete(); // si borras la empresa, se borran sus productos

            $table->timestamps(); // created_at / updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
