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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Interní údaje
            $table->string('order_number')->unique()->index();
            $table->string('status', 20)->default('pending')->index(); // pending, processing, completed, cancelled
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            // Doprava a platba - detailní informace pro zachování historie
            $table->unsignedInteger('transport_id')->index();
            $table->string('transport_type', 20)->nullable()->index();
            $table->decimal('transport_price', 8, 2)->default(0);
            $table->decimal('transport_vat', 5, 2)->default(0);

            $table->unsignedInteger('payment_id')->index();
            $table->string('payment_type', 20)->nullable()->index();
            $table->decimal('payment_price', 8, 2)->default(0); // Finální cena z Shipping vazby (transport + payment kombinace)
            $table->decimal('payment_vat', 5, 2)->default(0);

            // Základní údaje
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->index();
            $table->string('phone');

            // Fakturační údaje
            $table->string('street');
            $table->string('city');
            $table->string('zip', 10);
            $table->string('country', 2)->default('CZ');

            // Firemní údaje
            $table->boolean('is_company')->default(false);
            $table->string('company_name')->nullable();
            $table->string('company_id', 20)->nullable();
            $table->string('company_vat_id', 20)->nullable();

            // Dodací údaje
            $table->boolean('has_delivery_address')->default(false);
            $table->string('delivery_first_name')->nullable();
            $table->string('delivery_last_name')->nullable();
            $table->string('delivery_street')->nullable();
            $table->string('delivery_city')->nullable();
            $table->string('delivery_zip', 10)->nullable();
            $table->string('delivery_country', 2)->nullable();

            // Poznámka
            $table->text('note')->nullable();

            // Cenové údaje
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('vat_total', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);

            // Časové údaje
            $table->timestamp('payment_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
