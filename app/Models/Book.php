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
        return $this->belongsToMany('App\Models\User')->withTimestamps();
    }

    public function categories()
    {
        return $this->belongsToMany('App\Models\Category')->withTimestamps();
    }

    public function synopsis()
    {
        return $this->hasOne('App\Models\Synopsis');
    }

    // Accessors
    // public function getPriceAttribute($value) {
    //     return 'Rp' . number_format($value, null, null, '.');
    // }

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
