@if(isset($tour_places) && !empty($tour_places['single']))
    <section class="tour-places-section-4 fix section-padding section-bg">
        <div class="container">
            <div class="section-title text-center">
                <span class="wow fadeInUp">{{ $tour_places['single']['title'] ?? '' }}</span>
                <h2 class="wow fadeInUp" data-wow-delay=".3s">
                    {{ $tour_places['single']['sub_title'] ?? '' }}
                </h2>
                <p class="mt-3 wow fadeInUp" data-wow-delay=".5s">
                    {{ $tour_places['single']['description_one'] ?? '' }}  <br> {{ $tour_places['single']['description_two'] ?? '' }}
                </p>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    @foreach($tour_places['places'] ?? [] as $item)

                        <div class="tour-places-wrapper-4 wow fadeInUp" data-wow-delay=".3s">
                            <div class="content">
                                <span>{{ $item->countryTake?->name }}</span>
                                <h3><a href="{{ route('package.details', $item->slug) }}">{{ $item->title }}</a></h3>
                                <p>{{ Str::limit(strip_tags($item->description), 80) }}</p>
                            </div>
                            <ul class="list">
                                <li class="d-flex align-items-center gap-1">
                                    {!! displayStarRating($item->avg_rating) !!}
                                    <span>({{ $item->reviews_count ?? 0 }}) @lang('Reviews')</span>
                                </li>
                                @if(isset($item->address))
                                    <li>
                                        <i class="fas fa-map-marker-alt"></i>
                                        {{ $item->address }}
                                    </li>
                                @endif
                                @if(isset($item->duration))
                                    <li>
                                        <i class="far fa-calendar-minus"></i>
                                        {{ $item->duration }}
                                    </li>
                                @endif
                            </ul>
                            <div class="thumb">
                                <img src="{{ getFile($item->thumb_driver, $item->thumb) }}" alt="{{ $item->title }}">
                                <span class="price-list">
                                    <span class="price">{{ currencyPosition($item->adult_price) }}</span>
                                    <span class="person">/ @lang('person')</span>
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <style>
        .tour-places-wrapper-4 .list li i{
            margin-right: 0 !important;
        }
    </style>
@endif

