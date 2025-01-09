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
        Schema::create('flight_approaches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_id'); // Flight ID
            $table->string('name');
            $table->string('type');
            $table->string('runway');
            $table->string('airport');
            $table->string('comments');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flight_approaches');
    }
};
