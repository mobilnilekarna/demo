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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            
            // Název platby
            $table->string('name');
            
            // Popis platby
            $table->text('description')->nullable();
            
            // Cena platby (poplatek za způsob platby)
            $table->decimal('price', 8, 2)->default(0);
            
            // Pořadí zobrazení
            $table->integer('order')->default(0)->index();
            
            // Aktivnost (zda je platba aktivní)
            $table->boolean('active')->default(true)->index();
            
            // Typ platby (cod, cash, card, bank, benefit_card)
            $table->string('type', 20)->index();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
