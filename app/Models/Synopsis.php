<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Synopsis extends Model
{
    use HasFactory;

    protected $guarded = array();

    // Relasi antar tabel
    public function book() {
        return $this->belongsTo('App\Models\Book');
    }
}
