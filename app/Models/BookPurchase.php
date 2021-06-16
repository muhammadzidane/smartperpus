<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookPurchase extends Model
{
    use HasFactory;

    protected $guarded = array();

    public function user() {
        return $this->belongsToMany('App\Models\User');
    }

    public function book() {
        return $this->belongsToMany('App\Models\Book');
    }
}
