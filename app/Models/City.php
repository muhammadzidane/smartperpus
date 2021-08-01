<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $guarded = array();

    public function province()
    {
        return $this->belongsTo('App\Models\Province');
    }

    public function districts()
    {
        return $this->hasMany('App\Models\District');
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
