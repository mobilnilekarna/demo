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
        Schema::create('shippings', function (Blueprint $table) {
            $table->id();

            // Vazba na dopravu
            $table->foreignId('transport_id')->constrained('transports')->onDelete('cascade');

            // Vazba na platbu
            $table->foreignId('payment_id')->constrained('payments')->onDelete('cascade');

            // Cena pro specifickou kombinaci dopravy a platby (pokud je null, použije se součet cen z transports a payments)
            $table->decimal('price', 8, 2)->nullable();

            $table->timestamps();

            // Unikátní kombinace transport_id a payment_id
            $table->unique(['transport_id', 'payment_id'], 'shipping_transport_payment_unique');

            // Indexy pro rychlejší vyhledávání
            $table->index('transport_id');
            $table->index('payment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shippings');
    }
};
