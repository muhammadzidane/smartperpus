<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserChat extends Model
{
    use HasFactory;

    protected $guarded = array();
    protected $dates = array('created_at');

    // Relasi antar tabel
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin_chats()
    {
        return $this->belongsToMany(AdminChat::class);
    }
}
