<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'business_id',
        'name',
        'price',
        'duration',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'service_id');
    }
}
