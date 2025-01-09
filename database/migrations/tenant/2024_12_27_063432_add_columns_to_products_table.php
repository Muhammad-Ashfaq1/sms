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
            $table->unsignedBigInteger('purchased_units');
            $table->string('current')->nullable();
            $table->boolean('receive')->default(0);
            $table->decimal('cost_price', 8, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('purchased_units');
            $table->dropColumn('current');
            $table->dropColumn('receive');
            $table->dropColumn('cost_price');
        });
    }
};
