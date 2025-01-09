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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Name: Product name
            $table->string('sku')->unique(); // SKU: Unique identifier for inventory
            $table->text('description')->nullable(); // Description: Detailed description
            $table->foreignId('category_id')->constrained('product_categories'); // Category ID: Foreign key to product categories
            $table->decimal('price', 10, 2); // Price: Base price of the product
            $table->integer('quantity_in_stock'); // Quantity in Stock: Current stock level
            $table->json('images')->nullable(); // Images: JSON array of image URLs or paths
            $table->boolean('status')->default(true); // Status: Active or inactive flag

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
