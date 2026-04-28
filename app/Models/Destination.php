<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    protected $casts = [
        'place' => 'object'
    ];

    public function bookings()
    {
        return $this->hasManyThrough(Booking::class, Package::class, 'destination_id', 'package_id');
    }

    public function visitor()
    {
        return $this->hasMany(DestinationVisitor::class, 'destination_id');
    }
    public function packages()
    {
        return $this->hasMany(Package::class, 'destination_id');
    }
    public function countryTake()
    {
        return $this->belongsTo(Country::class, 'country', 'id');
    }

    public function stateTake()
    {
        return $this->belongsTo(State::class, 'state', 'id');
    }

    public function cityTake()
    {
        return $this->belongsTo(City::class, 'city', 'id');
    }
}
