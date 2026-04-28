<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $guarded =['id'];

    protected $casts = [
        'rating' => 'object'
    ];

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function reply()
    {
        return $this->hasMany(Review::class, 'parent_review_id')->where('status', 1);
    }
    public function allReplies()
    {
        return $this->hasMany(Review::class, 'parent_review_id');
    }

}
