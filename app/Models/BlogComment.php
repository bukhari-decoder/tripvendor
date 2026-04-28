<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogComment extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = ['meta_keywords' => 'array'];

    public function blog()
    {
        return $this->belongsTo(Blog::class, 'blog_id');
    }
    public function replies()
    {
        return $this->hasMany(BlogComment::class, 'parent_comment_id')
            ->where('status', 1);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
