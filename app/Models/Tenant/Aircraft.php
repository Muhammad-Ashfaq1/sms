<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aircraft extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'equipment_type',
        'type_code',
        'year',
        'make',
        'model',
        'category',
        'class',
        'gear_type',
        'engine_type',
        'complex',
        'high_performance',
        'pressurized',
        'taa',
    ];
}
