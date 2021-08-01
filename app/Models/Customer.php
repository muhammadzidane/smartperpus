<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = array();

    public function user()
    {
        return $this->belongsToMany('App\Models\User');
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }


    // Mutators
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
    }
}
