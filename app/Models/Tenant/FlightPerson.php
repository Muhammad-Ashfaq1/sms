<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlightPerson extends Model
{
    use HasFactory;

    protected $fillable = [
        'flight_id',
        'name',
        'email',
        'phone',
        'role',
    ];

    public function flight(): BelongsTo
    {
        return $this->belongsTo(Flight::class);
    }

}
