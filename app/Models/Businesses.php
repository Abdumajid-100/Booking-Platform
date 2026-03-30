<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Businesses extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','businesses_type_id','name','description','address','phone','day_of_week','image','start_time','end_time'
    ];

    public function type()
    {
        return $this->belongsTo(BusinessesTypes::class, 'businesses_type_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function services()
    {
        return $this->hasMany(Services::class, 'business_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedules::class, 'business_id');
    }
}
