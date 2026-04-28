@if(isset($news_two) && !empty($news_two['single']))
    <section class="news-section section-padding section-bg">
        <div class="container">
            <div class="section-title text-center">
                <span class="wow fadeInUp" data-wow-delay=".3s">@lang($news_two['single']['title'] ?? '')</span>
                <h2 class="wow fadeInUp" data-wow-delay=".5s">{{ $news_two['single']['sub_title_one'] ?? '' }} <br>{{ $news_two['single']['sub_title_two'] ?? '' }}</h2>
            </div>
            <div class="row">
                @foreach($news_two['blogs'] ?? [] as $blog)
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="news-box-items-4">
                            <div class="news-img">
                                <img src="{{ getFile($blog->blog_image_driver, $blog->blog_image) }}" alt="{{ $blog->details?->title }}">
                                <ul class="post-date">
                                    <li>
                                        {{ \Carbon\Carbon::parse($blog->created_at)->format('j') }}
                                    </li>
                                    <li>
                                        {{ \Carbon\Carbon::parse($blog->created_at)->format('M') }}
                                    </li>
                                </ul>
                            </div>
                            <div class="news-content">
                                <ul>
                                    <li> <b>@lang('By')</b>@lang(' Admin')</li>
                                    <li><b>{{ $blog->comments_count }}</b>@lang(' Comments')</li>
                                </ul>
                                <h3><a href="{{ route('news.details', $blog->slug) }}">{{ $blog->details?->title }}</a></h3>
                                <p>{!! Str::limit($blog->details?->description, 100) !!}</p>
                                <a href="{{ route('news.details', $blog->slug) }}" class="link-btn">@lang('Continue Reading') <i class="far fa-long-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

