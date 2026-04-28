@if(isset($tour_three) && !empty($tour_three['single']))
    <section class="tour-section section-padding fix bg-cover section-bg" style="background-image: url({{ getFile($tour_three['single']['media']->background_image->driver, $tour_three['single']['media']->background_image->path) }});">
        <div class="container">
            <div class="section-title text-center">
                <span class="wow fadeInUp">{{ $tour_three['single']['title'] ?? '' }}</span>
                <h2 class="wow fadeInUp" data-wow-delay=".3s">{{ $tour_three['single']['sub_title'] ?? '' }}</h2>
            </div>
            <div class="row">
                @foreach($tour_three['packages'] ?? [] as $item)
                    <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".3s">
                        <div class="tour-box-items-2">
                            <div class="tour-image">
                                <img src="{{ getFile($item->thumb_driver, $item->thumb) }}" alt="{{ $item->title }}">
                                <div class="post-bar">
                                    @if($item->is_featured == 1)
                                        <div class="post">
                                            @lang('FEATURED')
                                        </div>
                                    @endif
                                    @if($item->discount == 1)
                                        <div class="post bg-color">
                                            @if($item->discount_type == 0)
                                                {{ $item->discount_amount.'% OFF' }}
                                            @elseif($item->discount_type == 1)
                                                {{ currencyPosition($item->discount_amount).' OFF' }}
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="tour-content">
                                <h3>
                                    <a href="{{ route('package.details', $item->slug) }}">
                                        {{ $item->title }}
                                    </a>
                                </h3>
                                @if(isset($item->address))
                                    <ul class="meta">
                                        <li>
                                            <i class="fal fa-map-marker-alt"></i>
                                            {{ $item->address }}
                                        </li>
                                    </ul>
                                @endif

                                <div class="rating-bar">
                                    <ul class="rating">
                                        <li>
                                            <i class="fas fa-star"></i>
                                            {{ $item->avg_rating ?? 0 }} @lang('BY') {{ $item->reviews_count ?? 0 }} @lang('REVIEWS')
                                        </li>
                                    </ul>
                                    <ul class="icon">
                                        @if(!empty($item->video))
                                            <li>
                                                <a href="{{ $item->video }}" class="tour-three-popup-video">
                                                    <i class="fal fa-video"></i>
                                                </a>
                                            </li>
                                        @endif
                                        @if(isset($item->media) && $item->media->count() > 0)
                                            @php
                                                $images = [];

                                                foreach ($item->media as $media){
                                                    $images[] = getFile($media->driver, $media->image);
                                                }
                                            @endphp
                                            <ul>
                                                <li>
                                                    <a href="#" data-images='@json($images)' class="tour-three-popup-image">
                                                        <i class="fal fa-camera-alt"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        @endif
                                    </ul>
                                </div>
                                <div class="tour-btn">
                                    <a href="{{ route('package.details', $item->slug) }}" class="theme-btn">
                                        <span>@lang('Book Now')</span> <i class="far fa-long-arrow-right"></i>
                                    </a>
                                    <h2>{{ discountPrice($item) }}/<span>/@lang('per person')</span></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

