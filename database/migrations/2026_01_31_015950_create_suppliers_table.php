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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name',60)->index();
            $table->string('type', 60)->nullable()->index();

            $table->string('format',10)->index();
            $table->smallInteger('priority')->default(0)->index();

            $table->string('bg_color',10)->nullable();
            $table->string('text_color',10)->nullable();

            $table->unsignedInteger('interval')->default(1440)->comment('Interval in minutes');

            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();

            $table->tinyInteger('active')->default(1)->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
