@extends(template() . 'layouts.app')
@section('title',trans('Destination Details'))
@section('content')
    <section class="destination-details-section section-padding">
        <div class="container">
            <div class="destination-details-wrapper">
                <div class="row g-5">
                    <div class="col-xl-8 col-12">
                        <div class="details-post">
                            <div class="details-image">
                                <img src="{{ getFile($destination->thumb_driver, $destination->thumb) }}" alt="{{ $destination->title ?? '' }}">
                            </div>
                            <div class="details-content">
                                <h2>
                                    {{ $destination->title.', '.$destination->countryTake?->name }}
                                </h2>
                                {!! $destination->details !!}
                                @if(isset($destination->place) && count($destination->place) > 0)
                                    <div class="destination-list-item">
                                        <h4>
                                            @lang('Experience the Difference')
                                        </h4>
                                        <div class="destination-list">
                                            <div class="list-container d-flex align-items-center justify-content-between w-100">
                                                <ul class="list">
                                                    @php
                                                        $half = ceil(count($destination->place) / 2);
                                                    @endphp
                                                    @foreach(array_slice($destination->place, 0, $half) as $placeItem)
                                                        <li>
                                                            <i class="fas fa-check-circle"></i>
                                                            @lang($placeItem)
                                                        </li>
                                                    @endforeach
                                                </ul>
                                                <ul class="list">
                                                    @foreach(array_slice($destination->place, $half) as $placeItem)
                                                        <li>
                                                            <i class="fas fa-check-circle"></i>
                                                            @lang($placeItem)
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="map-area">
                                    <h2>
                                        @lang('View in Map')
                                    </h2>
                                    <div class="google-map">
                                        <div id="map" style="width: 100%; height: 400px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-12">
                        <div class="main-side-bar sticky-style">
                            <div class="destination-single-sideber-widget">
                                <div class="widget-title">
                                    @lang('Destination Info')
                                </div>
                                <ul class="destination-card">
                                    <li>@lang('Country'):
                                        <span>{{ $destination->countryTake?->name ?? '' }}</span>
                                    </li>
                                    <li>@lang('Visa Requirements'):
                                        <span>{{ $destination->countryTake?->name ?? '' }}</span>
                                    </li>
                                    <li>@lang('Languages Spoken'):
                                        <span>{{ $destination->main_language ?? 'English' }}</span>
                                    </li>
                                    <li>@lang('Currency Used'):
                                        <span>{{ $destination->currency ?? 'USD' }}</span>
                                    </li>
                                    <li>@lang('Area(km2)'):
                                        <span>{{ number_format($destination->area ?? 0, 2) }} @lang('km2')</span>
                                    </li>
                                    <li>@lang('Location'):
                                        <span>{{ count($destination->place) ?? 0 }}</span>
                                    </li>
                                </ul>
                                <a href="{{ route('package', ['destination' => $destination->slug]) }}" class="theme-btn w-100">
                                    <span>@lang('Book Now')</span> <i class="far fa-long-arrow-right"></i>
                                </a>
                            </div>
                            @if(isset($destination->offer_image))
                                <div class="offer-card bg-cover" style="background-image: url({{ asset(template(true).'img/destinations/offter-card.jpg') }});">
                                    <h3>@lang('Book Now and Enjoy Amazing Savings!')</h3>
                                    <img src="{{ getFile($destination->offer_image_driver, $destination->offer_image) }}" alt="@lang('discount Image')">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('style')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
@endpush

@push('script')
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let lat = {{ $destination->lat }};
            let lng = {{ $destination->long }};

            let map = L.map("map").setView([lat, lng], 12);
            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                attribution: "© OpenStreetMap contributors"
            }).addTo(map);

            L.marker([lat, lng]).addTo(map);
        });
    </script>
@endpush

