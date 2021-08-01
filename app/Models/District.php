<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $guarded = array();

    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
