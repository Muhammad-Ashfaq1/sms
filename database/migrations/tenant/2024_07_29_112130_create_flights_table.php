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
        Schema::create('flights', function (Blueprint $table) {
            $table->id(); // Auto number, unique identifier
            $table->date('flight_date'); // Flight date
            $table->foreignId('aircraft_id'); // Aircraft ID
            $table->string('departure_location')->nullable(); // Departure location
            $table->string('arrival_location')->nullable(); // Arrival location
            $table->string('flight_route')->nullable(); // Flight route
            $table->time('departure_time')->nullable(); // Time of departure
            $table->time('off_duty_time')->nullable(); // Time off duty
            $table->time('on_duty_time')->nullable(); // Time on duty
            $table->time('arrival_time')->nullable(); // Time of arrival
            $table->time('on_duty_duration')->nullable(); // On duty time
            $table->time('off_duty_duration')->nullable(); // Off duty time
            $table->decimal('total_time', 8, 2)->nullable(); // Total flight time
            $table->decimal('pic_hours', 8, 2)->nullable(); // Pilot in command
            $table->decimal('sic_hours', 8, 2)->nullable(); // Second in command
            $table->decimal('night_flying_hours', 8, 2)->nullable(); // Night flying hours
            $table->decimal('solo_flying_hours', 8, 2)->nullable(); // Solo flying hours
            $table->decimal('cross_country_hours', 8, 2)->nullable(); // Cross-country flying hours
            $table->decimal('nvg_hours', 8, 2)->nullable(); // Night Vision Goggles time
            $table->decimal('nvg_ops_hours', 8, 2)->nullable(); // NVG operations time
            $table->decimal('flight_distance', 8, 2)->nullable(); // Flight distance
            $table->integer('day_takeoffs')->nullable(); // Day takeoffs
            $table->integer('day_landings_full_stop')->nullable(); // Day landings full stop
            $table->integer('night_takeoffs')->nullable(); // Night takeoffs
            $table->integer('night_landings_full_stop')->nullable(); // Night landings full stop
            $table->integer('total_landings')->nullable(); // Total landings
            $table->decimal('actual_instrument_time', 8, 2)->nullable(); // Actual instrument time
            $table->decimal('simulated_instrument_time', 8, 2)->nullable(); // Simulated instrument time
            $table->decimal('hobbs_start', 8, 2)->nullable(); // Hobbs meter start
            $table->decimal('hobbs_end', 8, 2)->nullable(); // Hobbs meter end
            $table->decimal('tachometer_start', 8, 2)->nullable(); // Tachometer start
            $table->decimal('tachometer_end', 8, 2)->nullable(); // Tachometer end
            $table->integer('holds')->nullable(); // Holds
            $table->boolean('flight_review')->default(0); // Flight review indicator
            $table->boolean('check_ride')->default(0); // Check ride indicator
            $table->boolean('ipc')->default(0); // Instrument Proficiency Check indicator
            $table->boolean('nvg_proficiency')->default(0); // NVG proficiency indicator
            $table->boolean('faa_form_6158')->default(0); // FAA Form 6158 indicator

            $table->timestamps(); // Created at & Updated at

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
