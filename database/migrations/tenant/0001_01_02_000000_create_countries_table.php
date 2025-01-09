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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Country name
            $table->string('code', 3)->nullable(); // Country code (ISO 3166-1 alpha-3)
            $table->string('iso_code', 2)->nullable(); // Country ISO code (ISO 3166-1 alpha-2)
            $table->string('phone_code', 10)->nullable(); // Country phone code (e.g., +92)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
