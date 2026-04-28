@extends(template() . 'layouts.app')
@section('title',trans('News Details'))
@section('content')
    <section class="blog-wrapper news-wrapper section-padding">
        <div class="container">
            <div class="news-area">
                <div class="row">
                    <div class="col-12 col-xl-8 col-lg-7">
                        <div class="blog-post-details border-wrap mt-0">
                            <div class="single-blog-post post-details mt-0">
                                <div class="post-content pt-0">
                                    <img src="{{ getFile(optional($blogDetails->blog)->blog_image_driver, optional($blogDetails->blog)->blog_image) }}" alt="blog__img" class="single-post-image">
                                    <h2>
                                        {{ $blogDetails->title ?? '' }}
                                    </h2>
                                    <div class="post-meta mt-3">
                                        <span><i class="fal fa-user"></i>@lang('Admin')</span>
                                        <span><i class="fal fa-comments"></i>{{ $blogDetails->blog->comments_count ?? '' }} @lang('Comments')</span>
                                        <span><i class="fal fa-calendar-alt"></i>{{ dateTime($blogDetails->created_at) }}</span>
                                    </div>
                                    {!! $blogDetails->description !!}
                                </div>
                            </div>
                            <div class="row tag-share-wrap">
                                <div class="col-12 mt-3 mt-lg-0">
                                    <h4>@lang('Social Share')</h4>
                                    <div class="social-share">
                                        <div id="shareBlock">
                                            <div class="fb-share-button" data-href=""
                                                 data-layout="button_count">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="fb-comments" data-href="{{ url()->current() }}" data-width="100%" data-numposts="5"></div>

                                    <div id="fb-root"></div>
                                </div>
                            </div>
                            <div class="comments-section-wrap pt-40">
                                @if($blogDetails->blog->comments_count > 0)
                                    <div class="comments-heading">
                                        <h3>{{ $blogDetails->blog->comments_count ?? '0' }} @lang('Comments')</h3>
                                    </div>

                                    <ul class="comments-list">
                                        @foreach($blogDetails->blog->comments as $comment)
                                            <li class="comment-item">
                                                <div class="comment-card">
                                                    <div class="comment-header">
                                                        <div class="comment-author">
                                                            <img class="author-avatar" src="{{ getFile($comment->user?->image_driver, $comment->user?->image) }}" alt="{{ $comment->user->firstname.' '.$comment->user->lastname }}">
                                                            <div class="author-details">
                                                                <h5 class="author-name">{{ $comment->user->firstname.' '.$comment->user->lastname }}</h5>
                                                                <span class="comment-date">{{ dateTime($comment->created_at) }}</span>
                                                            </div>
                                                        </div>
                                                        <button class="reply-btn" onclick="toggleReplyBox({{ $comment->id }})">
                                                            <i class="fas fa-reply"></i> @lang('Reply')
                                                        </button>
                                                    </div>

                                                    <div class="comment-body">
                                                        <p class="comment-text">{{ $comment->comment }}</p>
                                                    </div>
                                                    <div class="reply-box d-none" id="replyBox{{ $comment->id }}">
                                                        <form action="{{ route('user.comments.reply') }}" method="POST" class="reply-form">
                                                            @csrf
                                                            <input type="hidden" name="parent_comment_id" value="{{ $comment->id }}" />
                                                            <input type="hidden" name="blog_id" value="{{ $blogDetails->blog?->id }}" />

                                                            <div class="form-group">
                                                                <textarea name="reply" class="reply-textarea" rows="3" placeholder="Write your reply here..." required></textarea>
                                                                <div class="reply-actions">
                                                                    <button type="button" class="cancel-reply" onclick="toggleReplyBox({{ $comment->id }})">Cancel</button>
                                                                    <button type="submit" class="submit-reply">
                                                                        <i class="fas fa-paper-plane"></i> @lang('Reply')
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>

                                                    @if($comment->replies->count() > 0)
                                                        <div class="replies-container">
                                                            <button class="btn btn-link btn-sm toggle-replies" id="toggle-replies{{ $comment->id }}" type="button" data-bs-toggle="collapse" data-bs-target="#repliesCollapse{{ $comment->id }}" aria-expanded="false" aria-controls="repliesCollapse{{ $comment->id }}" data-replies-count="{{ $comment->replies->count() }}">
                                                                <span id="replyToggletext{{ $comment->id }}">@lang('Show Replies') ({{ $comment->replies->count() }})</span>
                                                            </button>

                                                            <div class="collapse" id="repliesCollapse{{ $comment->id }}">
                                                                <ul class="replies-list">
                                                                    @foreach($comment->replies as $reply)
                                                                        <li class="reply-item">
                                                                            <div class="reply-card">
                                                                                <div class="comment-author">
                                                                                    <img class="author-avatar" src="{{ getFile($reply->user?->image_driver, $reply->user?->image) }}" alt="{{ $reply->user->firstname.' '.$reply->user->lastname }}">
                                                                                    <div class="author-details">
                                                                                        <h5 class="author-name">{{ $reply->user->firstname.' '.$reply->user->lastname }}</h5>
                                                                                        <span class="comment-date">{{ dateTime($reply->created_at) }}</span>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="comment-body">
                                                                                    <p class="comment-text">@lang($reply->comment)</p>
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                            <div class="comment-form-wrap d-block pt-5">
                                <h3>@lang('Post Comment')</h3>
                                <form action="{{ route('user.blog.comment') }}" class="comment-form" method="POST">
                                    @csrf

                                    <div class="single-form-input">
                                        <textarea placeholder="Type your comments...." name="comment"></textarea>
                                    </div>
                                    <input type="hidden" name="blog_id" id="blog_id" value="{{ $blogDetails->blog?->id }}" />
                                    <button class="theme-btn center" type="submit">
                                        <span>@lang('Submit')</span> <i class="far fa-long-arrow-right"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-4 col-lg-5">
                        <div class="main-sidebar sticky-style">
                            <div class="single-sidebar-widget">
                                <div class="wid-title">
                                    <h3>@lang('Search')</h3>
                                </div>
                                <div class="search_widget">
                                    <form action="{{ route('news') }}" method="get">
                                        <input type="text" placeholder="Keywords here...." name="search">
                                        <button type="submit"><i class="fal fa-search"></i></button>
                                    </form>
                                </div>
                            </div>
                            <div class="single-sidebar-widget">
                                <div class="wid-title">
                                    <h3>@lang('Recent Feeds')</h3>
                                </div>
                                <div class="popular-posts">
                                    @foreach($recent as $item)
                                        <div class="single-post-item mb-0">
                                            <div class="thumb bg-cover" style="background-image: url({{ getFile($item->blog_image_driver ,$item->blog_image) }});"></div>
                                            <div class="post-content">
                                                <h5><a href="{{ route('news.details', $item->slug) }}">
                                                        {{ $item->details->title ?? '' }}
                                                    </a>
                                                </h5>
                                                <div class="post-date">
                                                    <i class="far fa-calendar-alt"></i>{{ dateTime($item->created_at) }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="single-sidebar-widget">
                                <div class="wid-title">
                                    <h3>@lang('Categories')</h3>
                                </div>
                                <div class="widget_categories">
                                    <ul>
                                        @foreach($categories as $item)
                                            <li><a href="{{ route('news', ['category' => $item->slug]) }}">{{ $item->name }}<span>{{ $item->blogs_count }}</span></a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <div class="single-sidebar-widget">
                                <div class="wid-title">
                                    <h3>@lang('Popular Tags')</h3>
                                </div>
                                <div class="tagcloud">
                                    @foreach($tags as $tag)
                                        <a href="{{ route('news', ['tag' => $tag]) }}">{{ $tag }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if($blogDetails->blog->comments_count > 0)
        <div class="modal fade" id="replyModal{{ $comment->id }}" tabindex="-1" aria-labelledby="replyModalLabel{{ $comment->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('user.comments.reply') }}" method="POST">
                        @csrf
                        <input type="hidden" name="parent_comment_id" value="{{ $comment->id }}" />
                        <input type="hidden" name="blog_id" value="{{ $blogDetails->blog?->id }}" />
                        <div class="modal-header">
                            <h5 class="modal-title" id="replyModalLabel{{ $comment->id }}">@lang('Reply to') <span class="text-primary">{{ $comment->user?->firstname.' '.$comment->user?->lastname }}</span></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <textarea name="reply" class="form-control" rows="4" placeholder="Write your reply here..." required></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Cancel')</button>
                            <button type="submit" class="btn btn-primary">@lang('Reply')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset(template(true) . 'css/icomoon.css') }}">

    <style>
        #shareBlock{
            padding-top: 4px;
        }
        .share{
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 50px 0 20px;
        }
        .share h6{
            font-size: 16px;
            font-weight: 500;
            line-height: 24px;
        }
        .blog-box-large .content-area h4{
            font-size: 18px;
        }

        .comments-list {
            list-style: none;
            padding: 0;
            max-width: 800px;
            margin: 0 auto;
        }

        .comment-item {
            margin-bottom: 1.5rem;
        }

        .comment-card, .reply-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            transition: all 0.3s ease;
        }

        .comment-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .comment-author {
            display: flex;
            align-items: center;
        }

        .author-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 12px;
        }

        .author-name {
            margin: 0;
            font-size: 1rem;
            font-weight: 600;
            color: #333;
        }

        .comment-date {
            font-size: 0.8rem;
            color: #777;
        }

        .reply-btn {
            background: none;
            border: none;
            color: #4a6bdf;
            font-size: 0.85rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            padding: 5px 10px;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .reply-btn:hover {
            background: #f0f4ff;
        }

        .reply-btn i {
            margin-right: 5px;
        }

        .comment-body {
            margin-left: 52px;
        }

        .comment-text {
            margin: 0;
            color: #444;
            line-height: 1.6;
        }

        .replies-container {
            margin-top: 1.5rem;
            padding-left: 20px;
            border-left: 2px solid #eee;
        }

        .replies-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .reply-item {
            margin-top: 1rem;
        }

        .reply-card {
            background: #f9fafc;
            padding: 1rem;
        }

        @media (max-width: 768px) {
            .comment-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .reply-btn {
                margin-top: 10px;
                margin-left: 52px;
            }

            .comment-body {
                margin-left: 0;
                margin-top: 10px;
            }
        }
        .reply-box {
            margin-top: 1rem;
            margin-left: 52px;
            animation: fadeIn 0.3s ease;
        }

        .reply-form {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            border: 1px solid #e9ecef;
        }

        .reply-textarea {
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 12px;
            font-size: 0.95rem;
            resize: vertical;
            min-height: 80px;
            transition: all 0.3s ease;
        }

        .reply-textarea:focus {
            outline: none;
            border-color: #4a6bdf;
            box-shadow: 0 0 0 2px rgba(74, 107, 223, 0.2);
        }

        .reply-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 10px;
        }

        .submit-reply {
            background: #4a6bdf;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 0.9rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
        }

        .submit-reply:hover {
            background: #3a5ad4;
            transform: translateY(-1px);
        }

        .cancel-reply {
            background: transparent;
            color: #6c757d;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .cancel-reply:hover {
            background: #f1f1f1;
            color: #495057;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .reply-box {
                margin-left: 0;
            }
        }
    </style>
@endpush

@push('script')
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v13.0"></script>
    <script src="{{ asset(template(true).'js/socialSharing.js')}}"></script>

    <script>
        $("#shareBlock").socialSharingPlugin({
            urlShare: window.location.href,
            description: $("meta[name=description]").attr("content"),
            title: $("title").text(),
        });
        document.addEventListener('DOMContentLoaded', function () {
            const toggleButtons = document.querySelectorAll('.toggle-replies');

            toggleButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const collapseDiv = document.querySelector(button.getAttribute('data-bs-target'));
                    const replyCount = button.getAttribute('data-replies-count');
                    const replyTextSpan = document.querySelector(`#replyToggletext${button.id.replace('toggle-replies', '')}`);

                    if (collapseDiv.classList.contains('show')) {
                        replyTextSpan.textContent = `Hide Replies (${replyCount})`;
                    } else {
                        replyTextSpan.textContent = `Show Replies (${replyCount})`;
                    }
                });
            });
        });
        function toggleReplyBox(commentId) {
            const replyBox = document.getElementById('replyBox' + commentId);
            replyBox.classList.toggle('d-none');

            if (!replyBox.classList.contains('d-none')) {
                replyBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }
    </script>
@endpush
