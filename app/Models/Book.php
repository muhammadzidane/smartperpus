<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $guarded = array();

    // Relasi antar tabel
    public function author()
    {
        return $this->belongsTo('App\Models\Author');
    }

    public function users()
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

        return $this->belongsToMany('App\Models\User')->withPivot($pivot_columns)->withTimestamps();
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function synopsis()
    {
        return $this->hasOne('App\Models\Synopsis');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function book_user()
    {
        return $this->hasMany(BookUser::class);
    }

    public function book_images()
    {
        return $this->hasMany(BookImage::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    // Accessors
    public function getReleaseDateAttribute($value)
    {
        return Carbon::create($value)->isoFormat('D MMMM YYYY');
    }

    // Mutators
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
    }

    public function setImageAttribute($value)
    {
        $this->attributes['image'] = strtolower($value);
    }
}
