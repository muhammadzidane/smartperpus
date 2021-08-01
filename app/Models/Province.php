<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $guarded = array();

    public function cities()
    {
        return $this->hasMany('App\Models\City');
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
