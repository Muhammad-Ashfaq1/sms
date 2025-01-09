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
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Name: Category name
            $table->text('description')->nullable(); // Description: Brief description of the category
            $table->foreignId('parent_id')->nullable()->constrained('product_categories')->onDelete('cascade'); // Parent ID: Reference to the parent category
            $table->boolean('status')->default(true); // Status: Active or inactive flag

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_categories');
    }
};
