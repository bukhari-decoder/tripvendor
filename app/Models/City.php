<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $fillable = ['id','country_id','state_id','name','country_code','status'];

    public function packages(){
        return $this->hasMany(Package::class,'city');
    }
    public function user(){
        return $this->hasMany(User::class,'city', 'name');
    }
    public function country(){
        return $this->belongsTo(Country::class,'country_id');
    }
    public function state(){
        return $this->belongsTo(State::class,'state_id');
    }
    public function destinations(){
        return $this->hasMany(Destination::class,'city');
    }

}
