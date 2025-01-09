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
        Schema::create('flight_people', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_id'); // Flight ID
            $table->string('name'); // Name of the person
            $table->string('email')->nullable(); // Email of the person
            $table->string('phone')->nullable(); // Phone of the person
            $table->string('role'); // Role or description of the person
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flight_people');
    }
};
