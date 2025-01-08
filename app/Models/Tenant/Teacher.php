<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Teacher extends Model
{
    protected $fillable = [
        'user_id',
        'qualification',
        'specialization',
        'joining_date',
        'status'
    ];

    protected $casts = [
        'joining_date' => 'date',
        'status' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
