<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedido_producto', function (Blueprint $table) {
            $table->id('idpedido_producto');
            $table->unsignedBigInteger('idpedido');
            $table->unsignedBigInteger('idproducts');
            $table->integer('cantidad');
            $table->decimal('preciototal', 10, 2);
            $table->dateTime('datoRegistro');
            $table->string('estado');
            $table->timestamps();

            // Relaciones foráneas (ajusta nombres si es necesario)
            $table->foreign('idpedido')->references('idpedidosm')->on('pedidos_m')->onDelete('cascade');
            $table->foreign('idproducts')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido_producto');
    }
};
