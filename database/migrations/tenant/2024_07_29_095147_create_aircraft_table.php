<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Aircraft Table Schema
     * Columns:
     * ID: Text (Primary Key)
     *
     * Unique identifier for each aircraft.
     * EquipmentType: Text
     *
     * Describes the specific equipment type of the aircraft.
     * TypeCode: Text
     *
     * Code representing the type of aircraft.
     * Year: YYYY
     *
     * Year of manufacture of the aircraft.
     * Make: Text
     *
     * Manufacturer of the aircraft.
     * Model: Text
     *
     * Model name or number of the aircraft.
     * Category: Text
     *
     * Category of the aircraft (e.g., airplane, rotorcraft, glider).
     * Class: Text
     *
     * Class of the aircraft (e.g., single-engine land, multi-engine sea).
     * GearType: Text
     *
     * Type of landing gear (e.g., fixed, retractable).
     * EngineType: Text
     *
     * Type of engine(s) (e.g., piston, turboprop, jet).
     * Complex: Boolean
     *
     * Indicates if the aircraft is complex (e.g., has retractable landing gear, flaps, and a controllable pitch propeller).
     * HighPerformance: Boolean
     *
     * Indicates if the aircraft is high performance (typically having more than 200 horsepower).
     * Pressurized: Boolean
     *
     * Indicates if the aircraft has a pressurized cabin.
     * TAA: Boolean
     *
     * Technologically Advanced Aircraft, indicates if the aircraft is equipped with modern avionics.
     */
    public function up(): void
    {
        Schema::create('aircraft', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('equipment_type')->nullable();
            $table->string('type_code')->nullable();
            $table->year('year');
            $table->string('make')->nullable();
            $table->string('model')->nullable();
            $table->string('category')->nullable();
            $table->string('class')->nullable();
            $table->string('gear_type')->nullable();
            $table->string('engine_type')->nullable();
            $table->boolean('complex')->default(false);
            $table->boolean('high_performance')->default(false);
            $table->boolean('pressurized')->default(false);
            $table->boolean('taa')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aircraft');
    }
};
