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


    protected $dates = array(
        'date_of_birth'
    );

    // Relasi antar tabel
    public function books()
    {
        $pivot_columns = array(
            'invoice',
            'book_version',
            'amount',
            'courier_name',
            'courier_service',
            'shipping_cost',
            'note',
            'insurance',
            'unique_code',
            'total_payment',
            'payment_method',
            'payment_status',
            'completed_date',
            'payment_deadline',
            'upload_payment_image',
            'confirmed_payment',
        );

        return $this->belongsToMany(Book::class)->withPivot($pivot_columns)->withTimestamps();
    }

    public function customers()
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

    public function checkouts()
    {
        return $this->hasMany(Checkout::class);
    }

    public function users() {
        return $this->hasMany(User::class);
    }

    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = ucwords($value);
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = ucwords($value);
    }
}
