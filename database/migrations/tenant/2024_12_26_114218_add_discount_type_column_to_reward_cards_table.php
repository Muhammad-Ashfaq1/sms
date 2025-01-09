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
        Schema::table('reward_cards', function (Blueprint $table) {
            $table->dropColumn('product_name');
            $table->string('discount_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reward_cards', function (Blueprint $table) {
            $table->string('product_name');
            $table->dropColumn('discount_type');
        });
    }
};
