<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $guarded = [];

    use HasFactory;

    public function users()
    {
        $this->belongsToMany(User::class);
    }

    public function books()
    {
        $this->belongsToMany(Book::class);
    }
}
