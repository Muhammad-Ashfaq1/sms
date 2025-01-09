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
            $table->string('unique_number')->unique()->nullable();
            $table->string('qr_code')->unique()->nullable();
            $table->boolean('availed')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reward_cards', function (Blueprint $table) {
            $table->dropColumn('unique_number');
            $table->dropColumn('qr_code');
            $table->dropColumn('availed');
        });
    }
};
