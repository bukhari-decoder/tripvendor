@if(isset($news_three) && !empty($news_three['single']))
    <section class="news-section section-padding fix bg-cover" style="background-image: url({{ getFile($news_three['single']['media']->background_image->driver, $news_three['single']['media']->background_image->path) }});">
        <div class="container">
            <div class="section-title text-center">
                <span class="wow fadeInUp">@lang($news_three['single']['title'] ?? '')</span>
                <h2 class="wow fadeInUp" data-wow-delay=".3s">{{ $news_three['single']['sub_title_one'] ?? '' }}<br> {{ $news_three['single']['sub_title_two'] ?? '' }}</h2>
            </div>
            <div class="row">
                @foreach($news_three['blogs'] ?? [] as $blog)
                    <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".3s">
                        <div class="news-card-items">
                            <div class="news-image">
                                <img src="{{ getFile($blog->blog_image_driver, $blog->blog_image) }}" alt="{{ $blog->details?->title }}">
                                <ul class="post">
                                    <li>
                                        <i class="far fa-calendar"></i>
                                        {{ dateTime($blog->created_at) }}
                                    </li>
                                    <li class="style-2">{{ $blog->category?->title ?? '' }}</li>
                                </ul>
                            </div>
                            <div class="news-content">
                                <h3>
                                    <a href="{{ route('news.details', $blog->slug) }}">{{ $blog->details?->title }}</a>
                                </h3>
                                <p>
                                    {!! Str::limit($blog->details?->description, 120) !!}
                                </p>
                                <a href="{{ route('news.details', $blog->slug) }}" class="theme-btn">
                                    <span>@lang('Read More')</span> <i class="far fa-long-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="brand-section fix section-padding pb-0">
            <div class="container">
                <div class="swiper brand-slider">
                    <div class="swiper-wrapper">
                        @foreach($news_three['brands'] ?? [] as $brands)
                            <div class="swiper-slide">
                                <div class="brand-img text-center">
                                    <img src="{{ getFile($brands['media']->image->driver, $brands['media']->image->path) }}" alt="img">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <style>
        .news-card-items .news-image img {
            height: 300px !important;
        }
        @media (max-width: 991px){
            .news-card-items .news-image img {
                height: 275px;
            }
        }
        @media (max-width: 768px) {
            .news-card-items .news-image img {
                height: 250px;
            }
        }
    </style>
@endif

