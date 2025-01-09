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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('order_number')->unique();
            $table->decimal('total', 10, 2);
            $table->decimal('sub_total', 10, 2);
            $table->decimal('discount', 10, 2)->nullable();
            $table->decimal('tax', 10, 2)->nullable();
            $table->decimal('amount_paid', 10, 2)->nullable();
            $table->decimal('balance_due', 10, 2)->nullable();
            $table->string('paid_status');
            $table->foreign('customer_id')->references('id')->on('customers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
