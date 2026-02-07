<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\CodeType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('catalog', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('external_system_id')->nullable()->index();
            $table->string('code', CodeType::PDK->length())->nullable()->index();
            $table->string('name',191)->nullable()->index();
            $table->decimal('price',10,2)->nullable()->index();
            $table->decimal('price_purchase',10,2)->nullable()->index();
            $table->decimal('vat',5,2)->nullable()->index();

            $table->tinyInteger('active')->default(1)->index();

            $table->timestamps();
            $table->comment('Catalog of products - Unique IDs for codes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogs');
    }
};
