<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightApproach extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'flight_id',
        'name',
        'type',
        'runway',
        'airport',
        'comments',
    ];

    public function flight()
    {
        return $this->belongsTo(Flight::class, 'flight_id');
    }

}


