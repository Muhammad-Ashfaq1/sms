<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlightInstructor extends Model
{
    use HasFactory;

    protected $fillable = [
        'flight_id',
        'name',
        'comments',
    ];

    public function flight(): BelongsTo
    {
        return $this->belongsTo(Flight::class);
    }

}
