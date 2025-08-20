<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pedidos_m', function (Blueprint $table) {
            $table->id('idpedidosm');

            // FKs reales
            $table->unsignedBigInteger('idempresa');
            $table->unsignedBigInteger('idusuario');

            // Nuevos campos
            $table->text('notas')->nullable();
            $table->decimal('total', 10, 2)->default(0);
            $table->string('ordernumber')->unique();
            $table->string('status')->default('pending');

            // Mantienes "estado" si aún lo usas
            $table->string('estado')->nullable();

            $table->timestamps();

            // Claves foráneas
            $table->foreign('idempresa')
                ->references('idempresa')->on('empresa')
                ->onDelete('cascade');

            $table->foreign('idusuario')
                ->references('idusuario')->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos_m');
    }
};
