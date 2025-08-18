<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos_m', function (Blueprint $table) {
            $table->id('idpedidosm');
            $table->unsignedBigInteger('idmesa')->nullable();
            $table->unsignedBigInteger('idservicio');
            $table->unsignedBigInteger('idempresa');
            $table->unsignedBigInteger('idusuario');
            $table->unsignedBigInteger('idcliente');
            $table->decimal('total', 10, 2);
            $table->dateTime('dateRegistro');
            $table->string('estado');
            $table->timestamps();

            // Relaciones foráneas (puedes quitar las que no estés usando aún)
            $table->foreign('idmesa')->references('idmesa')->on('mesa')->onDelete('cascade');
            $table->foreign('idservicio')->references('id')->on('servicios')->onDelete('cascade'); // Asegúrate del nombre real de la tabla
            $table->foreign('idempresa')->references('idempresa')->on('empresa')->onDelete('cascade');
            $table->foreign('idusuario')->references('idusuario')->on('users')->onDelete('cascade');
            $table->foreign('idcliente')->references('idcliente')->on('cliente')->onDelete('cascade'); // Cambia si tienes otra tabla
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos_m');
    }
};
