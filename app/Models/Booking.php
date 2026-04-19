<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'business_id',
        'service_id',
        'booking_date',
        'start_time',
        'end_time',
        'status',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function payment()
    {
        return $this->hasOne(Payments::class, 'booking_id');
    }
}
