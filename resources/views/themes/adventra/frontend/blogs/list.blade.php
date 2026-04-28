@extends(template() . 'layouts.app')
@section('title',trans('Newses'))
@section('content')
    <section class="blog-wrapper news-wrapper section-padding">
        <div class="container">
            <div class="news-area">
                <div class="row">
                    <div class="col-12 col-xl-8 col-lg-7">
                        <div class="blog-posts">
                            @forelse($blogs as $item)
                            <div class="single-blog-post">
                                <div class="post-featured-thumb bg-cover" style="background-image: url({{ getFile($item->blog_image_driver, $item->blog_image) }});">

                                </div>
                                <div class="post-content">
                                    <div class="post-meta">
                                        <span><i class="fal fa-comments"></i>{{ $item->comments_count }} @lang(' Comments')</span>
                                        <span><i class="fal fa-calendar-alt"></i>{{ dateTime($item->created_at) }}</span>
                                    </div>
                                    <h2>
                                        <a href="{{ route('news.details', $item->slug) }}">
                                            {{ $item->details->title ?? '' }}
                                        </a>
                                    </h2>
                                    <p>
                                        {!! Str::limit($item->details->description ?? '', 100) !!}
                                    </p>
                                    <a href="{{ route('news.details', $item->slug) }}" class="theme-btn mt-4 line-height">
                                        <span>@lang('Read More')</span> <i class="far fa-long-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                            @empty
                                @include('empty')
                            @endforelse
                        </div>
                        {{ $blogs->appends(request()->query())->links(template().'partials.pagination') }}
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
                                        <div class="single-post-item">
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
@endsection
