<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    protected $fillable = [
        'business_id',
        'name',
        'price',
        'duration',
    ];

    public function business()
    {
        return $this->belongsTo(Businesses::class, 'business_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'service_id');
    }
}
