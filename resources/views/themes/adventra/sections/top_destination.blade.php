@if(isset($top_destination) && !empty($top_destination['single']))
    <section class="top-destination-section-4 section-padding pb-0">
        <div class="container">
            <div class="section-title-area">
                <div class="section-title">
                    <span class="wow fadeInUp">{{ $top_destination['single']['title'] ?? '' }}</span>
                    <h2 class="wow fadeInUp" data-wow-delay=".3s">{{ $top_destination['single']['sub_title'] ?? '' }}</h2>
                    <p class="mt-3 mt-mb-0 wow fadeInUp" data-wow-delay=".3s">
                        {{ $top_destination['single']['description_one'] ?? '' }}<br> {{ $top_destination['single']['description_two'] ?? '' }}
                    </p>
                </div>
                <a href="{{ route('package') }}" class="theme-btn theme-btn-2 wow fadeInUp" data-wow-delay=".5s">
                    <span>{{ $top_destination['single']['button'] ?? '' }}</span> <i class="far fa-long-arrow-right"></i>
                </a>
            </div>
            <div class="row">
                @foreach($top_destination['categories'] ?? [] as $item)
                    <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".2s">
                        <div class="destination-feature-box">
                            <div class="icon">
                                <img class="topCatImg" src="{{ getFile($item->thumb_driver, $item->thumb) }}" alt="{{ $item->name }}">
                            </div>
                            <div class="content">
                                <h6>{{ $item->name ?? '' }}</h6>
                                <span><b>{{ $item->packages_count ?? 0 }}</b>
                                    @lang(' Tours ')@if(isset($item->min_adult_price))- @lang('From')
                                        <b>{{ currencyPosition($item->min_adult_price) }}</b>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="cta-wrapper-4 section-padding bg-cover" style="background-image: url({{ getFile($top_destination['single']['media']->image->driver, $top_destination['single']['media']->image->path) }});">
                <div class="section-title text-center mb-0">
                    <span class="wow fadeInUp">{{ $top_destination['single']['special_offer_title'] ?? '' }}</span>
                    <h2 class="text-white wow fadeInUp" data-wow-delay=".3s">{{ $top_destination['single']['special_offer_sub_title_one'] }} <br> {{ $top_destination['single']['special_offer_sub_title_two'] }}</h2>
                </div>
                <a href="{{ route('package') }}" class="theme-btn wow fadeInUp" data-wow-delay=".5s">
                    <span>{{ $top_destination['single']['special_offer_button'] ?? '' }}</span> <i class="far fa-long-arrow-right"></i>
                </a>
                <div class="discount-shape float-bob-y">
                    <img src="{{ getFile($top_destination['single']['media']->image_two->driver, $top_destination['single']['media']->image_two->path) }}" alt="img">
                </div>
                <div class="bag-shape float-bob-x">
                    <img src="{{ getFile($top_destination['single']['media']->image_three->driver, $top_destination['single']['media']->image_three->path) }}" alt="img">
                </div>
                <div class="plane-shape">
                    <img src="{{ asset(template(true).'img/cta/olane-shape.png') }}" alt="img">
                </div>
            </div>
        </div>
    </section>
@endif

