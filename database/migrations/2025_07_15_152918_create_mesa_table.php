<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mesa', function (Blueprint $table) {
            $table->id('idmesa');
            $table->unsignedBigInteger('idempresa');
            $table->integer('numeromesa');
            $table->string('qr');
            $table->timestamps();

            $table->foreign('idempresa')->references('idempresa')->on('empresa')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mesa');
    }
};
