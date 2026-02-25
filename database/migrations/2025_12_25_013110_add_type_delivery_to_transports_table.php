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
        Schema::table('transports', function (Blueprint $table) {
            // Typ doručení (pickup = výdejní místo, address = na adresu)
            $table->string('type_delivery', 20)->nullable()->after('type');
            $table->index('type_delivery');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transports', function (Blueprint $table) {
            $table->dropIndex(['type_delivery']);
            $table->dropColumn('type_delivery');
        });
    }
};
