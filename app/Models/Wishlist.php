<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\This;

class Wishlist extends Model
{
    use HasFactory;

    protected $guarded = array();

    public function users()
    {
        $this->belongsToMany(User::class);
    }

    public function books()
    {
        $this->belongsToMany(Book::class);
    }
}
