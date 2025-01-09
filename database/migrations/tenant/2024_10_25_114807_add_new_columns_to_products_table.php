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
        Schema::table('products', function (Blueprint $table) {
            $table->string('product_id')->unique();
            $table->string('alu')->nullable();
            $table->string('barcode')->nullable();
            $table->integer('notify_limit')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('product_id');
            $table->dropColumn('alu');
            $table->dropColumn('barcode');
            $table->dropColumn('notify_limit');
        });
    }
};
