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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Name: Brand name
            $table->string('logo')->nullable(); // Logo: URL or path to the logo image
            $table->text('description')->nullable(); // Description: Brief description of the brand
            $table->string('country_of_origin'); // Country of Origin: The country where the brand originated
            $table->boolean('status')->default(true); // Status: Active or inactive flag

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
