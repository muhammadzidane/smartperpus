<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class AdminChat extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = array();
    protected $dates   = ['created_at'];

    // Relasi antar tabel
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function user_chats()
    {
        return $this->belongsToMany(UserChat::class);
    }
}
