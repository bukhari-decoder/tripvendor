<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Blog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = ['meta_keywords' => 'array'];

    public function details()
    {
        return $this->hasOne(BlogDetails::class, 'blog_id');
    }
    public function comments()
    {
        return $this->hasMany(BlogComment::class, 'blog_id')->where('parent_comment_id', null);
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class);
    }

    public static function BlogsCountById ($category_id){
        $categoryCount = Blog::where('category_id', $category_id)->where('status', 1)->count();
        return $categoryCount;
    }

    public function getLanguageEditClass($id, $languageId)
    {
        return DB::table('blog_details')->where(['blog_id' => $id, 'language_id' => $languageId])->exists() ? 'bi-check2' : 'bi-pencil';
    }

    public function getMetaRobots()
    {
        $cleaned = str_replace(['[', ']', '"'], '', $this->meta_robots);

        return explode(",", $cleaned);
    }
    public static function getRecentBlogs($existId = null, $limit = 4)
    {
        $blogs = self::orderBy('created_at', 'desc')
            ->when($existId != null, function ($query) use($existId){
                $query->where('id', '!=', $existId);
            })
            ->limit($limit)->get();
        return $blogs;
    }

}
