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
        Schema::create('charges', function (Blueprint $table) {
            $table->id();

            $table->decimal("from",10,2)->index();
            $table->decimal("to",10,2)->index();

            $table->unsignedInteger("value")->default(0)->comment("Value in percentage");

            $table->tinyInteger("default")->default(1)->index();
            $table->tinyInteger("active")->default(1)->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charges');
    }
};
