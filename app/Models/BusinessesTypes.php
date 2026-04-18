<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessesTypes extends Model
{
    protected $fillable = [
        'name',
    ];

    public function businesses()
    {
        return $this->hasMany(Business::class, 'businesses_type_id');
    }
}
