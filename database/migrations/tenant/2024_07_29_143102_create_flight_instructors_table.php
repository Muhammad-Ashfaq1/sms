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
        Schema::create('flight_instructors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_id'); // Flight ID
            $table->string('name'); // Instructor's name
            $table->string('comments')->nullable(); // Comments from instructor
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flight_instructors');
    }
};
