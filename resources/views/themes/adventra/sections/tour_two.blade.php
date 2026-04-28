@if(isset($tour_two) && !empty($tour_two['single']))
    <section class="tour-section-5 section-bg-2 fix">
        <div class="tour-wrapper-5 section-padding bg-cover" style="background-image: url({{ getFile($tour_two['single']['media']->background_image->driver, $tour_two['single']['media']->background_image->path) }});">
            <div class="container">
                <div class="section-title style-2 text-center">
                    <span class="wow fadeInUp">{{ $tour_two['single']['title'] ?? '' }}</span>
                    <h2 class="text-white wow fadeInUp" data-wow-delay=".3s">{{ $tour_two['single']['sub_title_one'] ?? '' }}<br>{{ $tour_two['single']['sub_title_two'] ?? '' }}</h2>
                </div>
            </div>
            <div class="swiper tour-slider-5">
                <div class="swiper-wrapper">
                    @foreach($tour_two['packages'] ?? [] as $package)
                        <div class="swiper-slide">
                            <div class="tour-box-items-6">
                                <div class="tour-image">
                                    <img src="{{ getFile($package->thumb_driver, $package->thumb) }}" alt="{{ $package->title ?? '' }}">
                                    <div class="star">
                                        {!! displayStarRating($package->avg_rating) !!}
                                        <span>({{ $package->reviews_count ?? 0 }}) @lang('Reviews')</span>
                                    </div>
                                </div>
                                <div class="tour-content">
                                    <h6>@lang('From') <span>{{ discountPrice($package) }}</span> <del>{{ currencyPosition($package->adult_price ?? '0') }}</del></h6>
                                    <h5>
                                        <a href="{{ route('package.details', $package->slug) }}">
                                            @lang($package->title)
                                        </a>
                                    </h5>
                                    <ul>
                                        @if(isset($package->address))
                                            <li>
                                                <i class="far fa-map-marker-alt"></i>
                                                {{ $package->address }}
                                            </li>
                                        @endif
                                        @if(isset($package->duration))
                                                <li>
                                                    <i class="far fa-calendar"></i>
                                                    {{ $package->duration }}
                                                </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="swiper-dot4 mt-5 text-center">
                <div class="dot1"></div>
            </div>
        </div>
    </section>
@endif

