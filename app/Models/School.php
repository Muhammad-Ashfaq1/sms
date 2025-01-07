<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = ['name', 'address', 'database', 'domain', 'admin_name', 'admin_email', 'status'];
}
