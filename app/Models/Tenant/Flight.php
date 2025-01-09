<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Flight extends Model
{
    use HasFactory;

    protected $fillable = [
        'flight_date', 'aircraft_id', 'departure_location', 'arrival_location', 'flight_route', 'departure_time',
        'off_duty_time', 'on_duty_time', '	arrival_time', 'on_duty_duration', 'off_duty_duration', 'total_time', 'pic_hours', 'sic_hours',
        'night_flying_hours', 'solo_flying_hours', 'cross_country_hours', '	nvg_hours', 'nvg_ops_hours', 'flight_distance', 'day_takeoffs',
        'day_landings_full_stop', '	night_takeoffs', 'night_landings_full_stop', 'total_landings', 'actual_instrument_time', 'simulated_instrument_time',
        'hobbs_start', 'hobbs_end', 'tachometer_start', 'tachometer_end', 'holds', 'flight_review', 'check_ride', 'ipc', 'nvg_proficiency', 'faa_form_6158',
    ];

    public function aircraft(): BelongsTo
    {
        return $this->belongsTo(Aircraft::class);
    }

    public function flightApproaches()
    {
        return $this->hasMany(FlightApproach::class, 'flight_id');
    }

    public function flightInstructors()
    {
        return $this->hasMany(FlightInstructor::class, 'flight_id');
    }

    public function flightPeoples()
    {
        return $this->hasMany(FlightPerson::class, 'flight_id');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
