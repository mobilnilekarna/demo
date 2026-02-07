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
        Schema::create('order_items', function (Blueprint $table) {
            $table->increments('id');
            
            // Vazba na objednávku
            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->onDelete('cascade');
            
            // Stejné sloupce jako basket_items
            $table->unsignedInteger('entity_id')->index();
            $table->unsignedBigInteger('model_id')->index();
            $table->string('type')->default('product')->index();
            
            // Cenové položky
            $table->decimal('price', 8, 2);
            $table->decimal('price_total', 8, 2);
            $table->decimal('vat', 8, 2)->default(0);
            $table->unsignedInteger('quantity')->default(1);
            
            $table->timestamps();
            
            $table->foreign('entity_id')->references('id')->on('entities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
