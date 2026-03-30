<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedules extends Model
{
    use HasFactory;

    protected $fillable = ['business_id', 'day_of_week', 'start_time', 'end_time', 'is_day_off'];

    protected $casts = [
        'is_day_off' => 'boolean',
    ];

    public function business()
    {
        return $this->belongsTo(Businesses::class, 'business_id');
    }
}
