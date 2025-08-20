<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pedido_producto', function (Blueprint $table) {
            $table->id('idpedido_producto');

            // FKs
            $table->unsignedBigInteger('idpedido');   // -> pedidos_m.idpedidosm
            $table->unsignedBigInteger('idproducts'); // -> products.id

            // Nuevos campos
            $table->integer('cantidad');
            $table->decimal('precio', 10, 2); // precio unitario al momento de compra
            $table->text('notas')->nullable();

            $table->timestamps();

            $table->foreign('idpedido')
                ->references('idpedidosm')->on('pedidos_m')
                ->onDelete('cascade');

            $table->foreign('idproducts')
                ->references('id')->on('products')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedido_producto');
    }
};