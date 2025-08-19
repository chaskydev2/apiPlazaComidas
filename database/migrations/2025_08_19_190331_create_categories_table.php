<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Nota: usamos 'categoria' (singular) para ser consistente con 'empresa'
        Schema::create('categoria', function (Blueprint $table) {
            $table->id('idcategoria');         // PK custom
            $table->string('category');        // nombre visible (ej: "sopas")
            $table->string('description');     // o ->text() si quieres largo
            $table->string('image_url')->nullable(); // string ya guardado
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categoria');
    }
};