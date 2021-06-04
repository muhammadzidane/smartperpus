<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $guarded = array();

    public function books() {
        return $this->hasOne('App\Models\Book');
    }

    // Mutators
    public function setNameAttribute($value) {
        $this->attributes['name'] = ucwords($value);
    }
}
