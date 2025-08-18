<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_promotion')->default(false);
            $table->decimal('original_price', 8, 2)->nullable();
            $table->dateTime('promotion_ends_at')->nullable();
            $table->string('promotion_badge')->nullable(); // Por ejemplo: "¡50% OFF!", "¡Oferta!"
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_promotion', 'original_price', 'promotion_ends_at', 'promotion_badge']);
        });
    }
};