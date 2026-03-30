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
        return $this->hasMany(Businesses::class, 'businesses_type_id');
    }
}
