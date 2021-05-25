<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintedBookStock extends Model
{
    use HasFactory;

    protected $guarded = array();

    public function book() {
        return $this->belongsTo('App\Models\Book');
    }
}
