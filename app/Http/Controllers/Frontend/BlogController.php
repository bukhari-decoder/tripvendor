<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogDetails;
use App\Models\Content;
use App\Models\ContentDetails;
use App\Models\Page;
use App\Models\PageDetail;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function blog(Request $request)
    {

        $search = $request->search;
        $tag = $request->tag;
        $seoData = Page::where('name', 'news')->select(['page_title','meta_title','meta_keywords','meta_description','og_description','meta_robots','meta_image_driver','meta_image','breadcrumb_status','breadcrumb_image','breadcrumb_image_driver'])->first();

        $data['pageSeo'] = [
            'page_title' => $seoData->page_title ?? '',
            'meta_title' => $seoData->meta_title,
            'meta_keywords' => implode(',', $seoData->meta_keywords ?? []),
            'meta_description' => $seoData->meta_description,
            'og_description' => $seoData->og_description,
            'meta_robots' => $seoData->meta_robots,
            'meta_image' => $seoData
                ? getFile($seoData->meta_image_driver, $seoData->meta_image)
                : null,
            'breadcrumb_status' => $seoData->breadcrumb_status ?? null,
            'breadcrumb_image' => $seoData->breadcrumb_status
                ? getFile($seoData->breadcrumb_image_driver, $seoData->breadcrumb_image)
                : null,
        ];

        $data['blogs'] = Blog::with('category', 'details','comments')
            ->orderBy('id', 'desc')
            ->where('status', 1)
            ->withCount('comments')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('details', function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%");
                });
            })
            ->when($request->category, function ($query) use ($request) {
                $query->whereHas('category', function ($query) use ($request) {
                    $query->where('slug', '=' , $request->category);
                });
            })
            ->when($tag, function ($query) use ($tag) {
                $query->whereJsonContains('meta_keywords', $tag);
            })
            ->paginate(10);

        $data['tags'] = Blog::pluck('meta_keywords')
            ->filter()
            ->flatMap(fn($keywords) => is_array($keywords) ? $keywords : explode(',', $keywords))
            ->map(fn($keyword) => trim($keyword))
            ->unique()
            ->values()
            ->toArray();

        $data['recent'] = Blog::where('status', 1)->orderBy('created_at', 'desc')->take(3)->get();
        $data['categories'] = BlogCategory::withCount('blogs')->where('status', 1)->get();

        return view(template().'frontend.blogs.list', $data);
    }

    public function blogDetails($slug)
    {
        try {
            $data['blogDetails'] = BlogDetails::whereHas('blog', function ($query) use ($slug) {
                $query->where('slug', $slug)->where('status', 1);
            })
            ->with(['blog.comments.user','blog.comments.replies'])
            ->with(['blog' => function ($query) {
                $query->withCount('comments');
            }])
            ->firstOr(function () {
                throw new \Exception('This Blog is not available now');
            });

            $data['pageSeo'] = [
                'page_title' => $data['blogDetails']->blog->page_title ?? '',
                'meta_title' => $data['blogDetails']->blog->meta_title,
                'meta_keywords' => implode(',', $data['blogDetails']->blog->meta_keywords ?? []),
                'meta_description' => $data['blogDetails']->blog->meta_description,
                'og_description' => $data['blogDetails']->blog->og_description,
                'meta_robots' => $data['blogDetails']->blog->meta_robots,
                'meta_image' => $data['blogDetails']->blog
                    ? getFile($data['blogDetails']->blog->meta_image_driver, $data['blogDetails']->blog->meta_image)
                    : null,
                'breadcrumb_status' => $data['blogDetails']->blog->breadcrumb_status ?? null,
                'breadcrumb_image' => $data['blogDetails']->blog->breadcrumb_status
                    ? getFile($data['blogDetails']->blog->breadcrumb_image_driver, $data['blogDetails']->blog->breadcrumb_image)
                    : null,
            ];

            $blogs = Blog::with(['category', 'details','comments'])
                ->orderByDesc('created_at')
                ->orderByDesc('total_view')
                ->where('status', 1)
                ->get();

            $data['tags'] = Blog::pluck('meta_keywords')
                ->filter()
                ->flatMap(fn($keywords) => is_array($keywords) ? $keywords : explode(',', $keywords))
                ->map(fn($keyword) => trim($keyword))
                ->unique()
                ->values()
                ->toArray();

            $data['recent'] = $blogs->sortByDesc('created_at')->take(3);
            $data['categories'] = BlogCategory::withCount('blogs')->where('status', 1)->get();

            return view(template().'frontend.blogs.details', $data);
        }catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
    }
    public function updateComment(Request $request){
        $request->validate([
            'blog_id' => 'required|integer|exists:blogs,id',
            'comment' => 'required|string',
        ]);

        try {
            $blog = Blog::where('id', $request->blog_id)->where('status', 1)->firstOr(function () {
                throw new \Exception('This Blog is not available now');
            });

            $comment = new BlogComment();
            $comment->blog_id = $blog->id;
            $comment->user_id = auth()->id();
            $comment->comment = $request->comment;
            $comment->save();

            return back()->with('success', 'Comment added successfully');
        }catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
    }
    public function updateCommentReply(Request $request){
        try {
            $blog = Blog::where('id', $request->blog_id)->where('status', 1)->firstOr(function () {
                throw new \Exception('This Blog is not available now');
            });

            $comment = new BlogComment();
            $comment->blog_id = $blog->id;
            $comment->user_id = auth()->id();
            $comment->parent_comment_id = $request->parent_comment_id ?? null;
            $comment->comment = $request->reply;
            $comment->save();

            return back()->with('success', 'Comment replied successfully.');
        }catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
    }

}
