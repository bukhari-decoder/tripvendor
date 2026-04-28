@if(isset($tour_discover) && !empty($tour_discover['single']))
    <section class="tour-descover-section section-padding fix bg-cover" style="background-image: url({{ getFile($tour_discover['single']['media']->image->driver, $tour_discover['single']['media']->image->path)  }});">
        <div class="container">
            <div class="tour-discover-wrapper">
                <div class="row g-4">
                    <div class="col-xl-5">
                        <div class="tour-content lg-center">
                            <div class="section-title">
                                <span class="wow fadeInUp">{{ $tour_discover['single']['title'] ?? '' }}</span>
                                <h2 class="wow fadeInUp" data-wow-delay=".3s">{{ $tour_discover['single']['sub_title_one']?? '' }} <br>{{ $tour_discover['single']['sub_title_two'] ?? '' }}</h2>
                                <p class="mt-3 mt-mb-0 wow fadeInUp" data-wow-delay=".5s">
                                    {{ $tour_discover['single']['description_one'] ?? '' }} <br> {{ $tour_discover['single']['description_two'] ?? '' }} <br>
                                    {{ $tour_discover['single']['description_three'] ?? '' }}
                                </p>
                            </div>
                            <div class="tour-button mt-3">
                                <a href="{{ route('package') }}" class="theme-btn wow fadeInUp" data-wow-delay=".5s">
                                    <span>{{ $tour_discover['single']['button'] ?? 'Explore More' }}</span> <i class="far fa-long-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-7">
                        <div class="swiper tour-slider">
                            <div class="swiper-wrapper">
                                @foreach($tour_discover['packages'] ?? [] as $package)
                                    <div class="swiper-slide">
                                        <div class="tour-card-item">
                                            <div class="tour-image" >
                                                <img src="{{ getFile($package->thumb_driver, $package->thumb) }}" alt="{{ $package->title ?? '' }}">
                                            </div>
                                            <div class="tour-content">
                                                <h6>@lang('From') <span>{{ discountPrice($package) }}</span>
                                                    @if($package->discount == 1)
                                                        <del>{{ currencyPosition($package->adult_price) }}</del>
                                                    @endif
                                                </h6>
                                                <h4>
                                                    <a href="{{ route('package.details', $package->slug) }}">
                                                        {{ str_replace('&amp;', '&', $package->title ?? '') }}
                                                    </a>
                                                </h4>
                                                <ul>
                                                    <li>
                                                        <i class="far fa-map-marker-alt"></i>
                                                        {{ $package->address ?? '' }}
                                                    </li>
                                                </ul>
                                                <div class="list">
                                                    <ul>
                                                        <li>
                                                            <i class="far fa-calendar"></i>
                                                            {{ $package->duration }}
                                                        </li>
                                                    </ul>
                                                    <div class="d-flex align-items-center gap-1">
                                                        {!! displayStarRating($package->avg_rating) !!}
                                                        <span>({{ $package->reviews_count ?? 0 }})</span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="swiper-dot4 mt-5">
                            <div class="dot"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

