<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookUser extends Model
{
    use HasFactory;

    // Mengubah nama tabel menjadi 'book_user'
    public $table = "book_user";

    // Otomatis mengubah request kolom 'payment_deadline' menjadi Carbon Object
    protected $dates   = ['payment_deadline'];

    protected $guarded = array();

    // Relasi antar tabel
    public function books() {
        return $this->hasMany(Book::class);
    }

    public function users() {
        return $this->belongsToMany(User::class);
    }
}
