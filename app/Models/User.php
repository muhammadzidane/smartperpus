<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    // Relasi antar tabel
    public function books()
    {
        return $this->belongsToMany(Book::class);
    }

    public function customer()
    {
        return $this->hasMany('App\Models\Customer');
    }

    public function user_chats()
    {
        if (Auth::user()->role !== 'guest') {
            return $this->hasMany(UserChat::class)->withTrashed();
        } else {
            return $this->hasMany(UserChat::class);
        }
    }

    public function admin_chats()
    {
        return $this->hasMany(AdminChat::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
