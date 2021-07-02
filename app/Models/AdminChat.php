<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminChat extends Model
{
    use HasFactory;

    protected $guarded = array();
    protected $dates   = ['created_at'];

    // Relasi antar tabel
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function user_chats() {
        return $this->belongsToMany(UserChat::class);
    }
}
