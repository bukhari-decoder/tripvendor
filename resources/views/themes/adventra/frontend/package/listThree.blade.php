@php use Illuminate\Support\Str; @endphp
@extends(template() . 'layouts.app')
@section('title',trans('Package Searchs'))
@section('content')
    <section class="amazing-tour-section section-padding">
        <div class="container">
            <div class="amazing-tour-wrapper">
                <div class="d-flex justify-content-end mb-3 d-block d-lg-none">
                    <button class="theme-btn smallMenuButton" id="toggleSidebarBtn">
                        <i class="fas fa-list"></i> @lang('Search')
                    </button>
                </div>

                <div class="row g-4">
                    <div class="col-xl-3 col-lg-4">
                        <div class="tour-sidebar-area sticky-style d-none d-lg-block" id="tourSidebar">
                            <div class="tour-destination-sidebar">
                                <div class="booking-list-area">
                                    <form action="{{ route('package') }}" method="GET">
                                        <div class="booking-list d-flex align-items-start">
                                            <div class="icon me-1">
                                                <img src="{{ asset(template(true).'img/search.png') }}" alt="img">
                                            </div>
                                            <div class="content w-100">
                                                <h5 class="mt-2">@lang('Search')</h5>

                                                <div class="position-relative mt-2">
                                                    <input
                                                        type="text"
                                                        class="form-control pe-5"
                                                        name="search"
                                                        value="{{ old('search', request()->search) }}"
                                                        placeholder="@lang('Search here')"
                                                    >
                                                    <button
                                                        type="submit"
                                                        class="btn position-absolute end-0 top-50 translate-middle-y me-2 p-0 border-0 bg-transparent"
                                                        style="z-index: 10;"
                                                    >
                                                        <i class="far fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    <form id="filter-form">
                                        <div class="booking-list">
                                            <div class="icon">
                                                <img src="{{ asset(template(true).'img/location.png') }}" alt="img">
                                            </div>
                                            <div class="content">
                                                <h5>@lang('Destination')</h5>
                                                <div class="form-clt">
                                                    <div class="form">
                                                        <select class="single-select w-100" name="destination">
                                                            <option value="">@lang('Select Destination')</option>
                                                            @foreach($destinations ?? [] as $destination)
                                                                <option value="{{ $destination->slug }}" {{ (request()->destination == $destination->slug) ? 'selected' : '' }}>{{ $destination->title }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="booking-list">
                                            <div class="icon">
                                                <img src="{{ asset(template(true).'img/category.png') }}" alt="img">
                                            </div>
                                            <div class="content">
                                                <h5>@lang('Category')</h5>
                                                <div class="form-clt">
                                                    <div class="form">
                                                        <select class="single-select w-100" name="category">
                                                            <option value="">@lang('Select Category')</option>
                                                            @foreach($categories ?? [] as $category)
                                                                <option value="{{ slug($category->name) }}" {{ (request()->category == slug($category->name)) ? 'selected' : '' }}>{{ $category->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="booking-list">
                                            <div class="icon">
                                                <img src="{{ asset(template(true).'img/country.png') }}" alt="img">
                                            </div>
                                            <div class="content">
                                                <h5>@lang('Country')</h5>
                                                <div class="form-clt countryDropDown">
                                                    <div class="form">
                                                        <select class="single-select w-100" name="country">
                                                            <option value="">@lang('Select Country')</option>
                                                            @foreach($countries ?? [] as $country)
                                                                <option value="{{ $country->iso2 }}" {{ ($country->iso2 == request()->country) ? 'selected' : '' }}>{{ $country->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tour-sidebar-widget">
                                            <div class="wid-title">
                                                <h3>@lang('Filter by Price')</h3>
                                            </div>
                                            <div class="price-range-wrapper">
                                                <div class="slider-container">
                                                    <input type="range" id="min-slider" class="slider"
                                                           min="{{ $rangeMin }}"
                                                           max="{{ $rangeMax }}"
                                                           value="{{ $min }}">
                                                    <input type="range" id="max-slider" class="slider"
                                                           min="{{ $rangeMin }}"
                                                           max="{{ $rangeMax }}"
                                                           value="{{ $max }}">
                                                </div>
                                                <div class="price-text pt-4 d-flex gap-3">
                                                    <label for="amount">@lang('Price:')</label>
                                                    <input type="text" id="amount" readonly style="border:0;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tour-sidebar-widget">
                                            <div class="wid-title">
                                                <h3>@lang('Amenities')</h3>
                                            </div>
                                            <div class="checkbox-items">
                                                @foreach($amenities ?? [] as $amenity)
                                                    <label class="checkbox-single">
                                                        <span class="d-flex gap-xl-3 gap-2 align-items-center">
                                                            <span class="checkbox-area d-center">
                                                                <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}">
                                                                <span class="checkmark d-center"></span>
                                                            </span>
                                                            <span class="text-color">
                                                                {{ $amenity->title }}
                                                            </span>
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-8">
                        <div class="row g-4 showItems">
                            @php
                                $params = [
                                    'date' => request()->date,
                                    'slot' => request()->slot,
                                ];

                                $query = http_build_query(array_filter($params));
                            @endphp
                            @forelse($packages as $pack)
                                <div class="col-xl-4 col-lg-6 col-md-6">
                                    <div class="amazing-tour-items mt-0">
                                        <div class="thumb">
                                            <div class="post-box">
                                                <h4>{{ discountPrice($pack) }}</h4>
                                                <span>/ @lang('person')</span>
                                            </div>
                                            <img src="{{ getFile($pack->thumb_driver, $pack->thumb) }}" alt="{{ $pack->title ?? '' }}">
                                            <div class="list-items">
                                                @if($pack->is_featured == 1)
                                                    <h6>@lang('FEATURED')</h6>
                                                @endif

                                                <ul class="popup-icon">
                                                    @if(isset($pack->video))
                                                        <li>
                                                            <a href="{{ $pack->video ?? '#' }}" class="video-buttton video-popup">
                                                                <i class="far fa-video"></i>
                                                            </a>
                                                        </li>
                                                    @endif

                                                    <li>
                                                        @php
                                                            $images = [];
                                                            foreach ($pack->media as $packImage) {
                                                                $images[] = getFile($packImage->driver, $packImage->image);
                                                            }
                                                        @endphp
                                                        <a href="#" class="package_img_popup" data-images='@json($images)'>
                                                            <i class="far fa-camera"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="content">
                                            <div class="package-content-title">
                                                <h4>
                                                    <a href="{{ route('package.details', ['slug' => $pack->slug]). ($query ? '?' . $query : '') }}">{{ str_replace('&amp;', '&', $pack->title ?? '') }}</a>
                                                </h4>
                                                @php
                                                    $fullLocation = $pack->cityTake?->name . ', ' . $pack->stateTake?->name . ', ' . $pack->countryTake?->name;
                                                    $shortLocation = Str::limit($fullLocation, 40);
                                                @endphp
                                                <span class="location-icon" title="{{ $fullLocation }}">
                                                    <i class="far fa-map-marker-alt"></i>
                                                    {{ $shortLocation }}
                                                </span>
                                            </div>
                                            <a href="{{ route('package.details', ['slug' => $pack->slug]). ($query ? '?' . $query : '') }}" class="theme-btn">
                                                <span>@lang('Book Now')</span> <i class="far fa-long-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                @include('empty')
                            @endforelse
                        </div>
                        {{ $packages->appends(request()->query())->links(template().'partials.pagination') }}

                        <div class="d-flex justify-content-center mt-5 ajaxLoadMore d-none">
                            <button class="theme-btn load-more-btn d-flex align-items-center justify-content-center" data-segment="1">
                                <span class="btn-text">@lang('Load More')</span>
                                <span class="spinner-border spinner-border-sm text-light ms-2 d-none" role="status" aria-hidden="true"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('style')
    <style>
        .searchInput::placeholder{
            font-weight: 400;
        }

        .form-control {
            width: 100% !important;
            outline: none !important;
            border-radius: 4px !important;
            border: 1px solid rgba(35, 28, 37, 0.1) !important;
            background: var(--white) !important;
            color: var(--text) !important;
            padding: 10px 13px !important;
            font-weight: 500 !important;
            text-transform: capitalize !important;
        }
        .countryDropDown .nice-select .list{
            max-height: 350px !important;
            height: auto !important;
            overflow: auto !important;
        }
        .smallMenuButton i{
            width: 28px;
            height: 28px;
            line-height: 28px;
            margin: 0 10px 0 0;
            background: transparent;
            color: #fff;
        }
        .smallMenuButton{
            margin-bottom: 10px;
        }
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function () {
            $('#toggleSidebarBtn').on('click', function () {
                const $sidebar = $('#tourSidebar');

                if (window.innerWidth < 992) {
                    $sidebar.toggleClass('d-none');
                }
            });
        });
        document.addEventListener("DOMContentLoaded", function () {
            const minSlider = document.getElementById("min-slider");
            const maxSlider = document.getElementById("max-slider");
            const amount = document.getElementById("amount");

            function updateAmount() {
                let minValue = parseInt(minSlider.value, 10);
                let maxValue = parseInt(maxSlider.value, 10);

                if (minValue > maxValue) {
                    minValue = maxValue;
                    minSlider.value = minValue;
                }

                amount.value = "$" + minValue + " - $" + maxValue;

                const minPercent = ((minValue - minSlider.min) / (minSlider.max - minSlider.min)) * 100;
                const maxPercent = ((maxValue - maxSlider.min) / (maxSlider.max - maxSlider.min)) * 100;

                const bg = `linear-gradient(to right, #000 ${minPercent}%, #4D40CA ${minPercent}%, #4D40CA ${maxPercent}%, #000 ${maxPercent}%)`;

                minSlider.style.background = bg;
                maxSlider.style.background = bg;
            }

            if (amount && minSlider && maxSlider) {
                updateAmount();
                minSlider.addEventListener("input", updateAmount);
                maxSlider.addEventListener("input", updateAmount);
            }
        });

        $(document).ready(function () {
            $(document).on('click', '.package_img_popup', function (e) {
                e.preventDefault();
                var images = $(this).data('images');

                if (!images || !images.length) return;

                var items = images.map(function (url) {
                    return { src: url };
                });

                $.magnificPopup.open({
                    items: items,
                    type: 'image',
                    gallery: {
                        enabled: true
                    }
                });
            });

            $('.ajaxLoadMore').addClass('d-none');
            let currentSegment = 1;

            function runAjaxSearch() {
                currentSegment = 1;

                let destination = $('select[name="destination"]').val();
                let country = $('select[name="country"]').val();
                let min_price = $('#min-slider').val();
                let max_price = $('#max-slider').val();

                let amenities = [];
                $('input[name="amenities[]"]:checked').each(function () {
                    amenities.push($(this).val());
                });

                $.ajax({
                    url: '{{ route('package.search') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        destination: destination,
                        country: country,
                        min_price: min_price,
                        max_price: max_price,
                        amenities: amenities,
                        segment: currentSegment
                    },
                    success: function (response) {
                        $('.showItems').html('');
                        $('#pagination').addClass('d-none');
                        $('.ajaxLoadMore').removeClass('d-none');
                        if (response.packages.length === 0) {
                            $('.showItems').html(`
                                <section class="no-data-section section-padding text-center">
                                    <div class="container">
                                        <div class="row justify-content-center">
                                            <div class="col-lg-6">
                                                <div class="no-data-content">
                                                    <div class="no-data-image mb-4">
                                                        <img src="{{ asset('assets/global/img/oc-error.svg') }}" alt="No Data" class="img-fluid noDataImage">
                                                    </div>
                                                    <h2 class="no-data-title mb-3">@lang('No Data Found')</h2>
                                                    <p class="no-data-text mb-4">@lang("We couldn't find what you're looking for. Please try again later or modify your search.")</p>
                                                    <a href="{{ url()->previous() }}" class="btn btn-primary noDataBtn">@lang("Go Back")</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                `);
                            $('.load-more-btn').addClass('d-none');
                            return;
                        }
                        let $btn = $('.load-more-btn');
                        let $spinner = $btn.find('.spinner-border');
                        let $btnText = $btn.find('.btn-text');

                        $btn.prop('disabled', false);
                        $spinner.addClass('d-none');
                        $btnText.text('Load More');

                        appendPackages(response.packages);

                        if (response.has_more) {
                            $('.load-more-btn').removeClass('d-none').data('segment', response.next_segment);
                        } else {
                            $('.load-more-btn').addClass('d-none');
                        }
                    }
                });
            }

            function loadMore() {
                let $btn = $('.load-more-btn');
                let $spinner = $btn.find('.spinner-border');
                let $btnText = $btn.find('.btn-text');

                let segment = $btn.data('segment');
                let destination = $('select[name="destination"]').val();
                let sort_by = $('select[name="sort_by"]').val();

                $btn.prop('disabled', true);
                $spinner.removeClass('d-none');
                $btnText.text('Loading...');

                $.ajax({
                    url: '{{ route('package.search') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        destination: destination,
                        sort_by: sort_by,
                        segment: segment
                    },
                    success: function (response) {
                        appendPackages(response.packages);

                        if (response.has_more) {
                            $btn.data('segment', response.next_segment).prop('disabled', false);
                            $btnText.text('Load More');
                            $spinner.addClass('d-none');
                        } else {
                            $btn.addClass('d-none');
                        }
                    },
                    error: function () {
                        $btn.prop('disabled', false);
                        $btnText.text('Load More');
                        $spinner.addClass('d-none');
                    }
                });
            }



            function appendPackages(packages) {
                console.log(packages)
                packages.forEach(function (item) {
                    let html = `
                        <div class="col-xl-4 col-lg-6 col-md-6">
                            <div class="amazing-tour-items mt-0">
                                <div class="thumb">
                                    <div class="post-box">
                                        <h4>${item.formatedPrice}</h4>
                                        <span>/ person</span>
                                    </div>
                                    <img src="${item.imageUrl}" alt="img">
                                    <div class="list-items">
                                        ${item.is_featured == 1 ? `<h6>FEATURED</h6>` : ''}
                                        <ul class="popup-icon">
                                            ${item.video ? `
                                                <li>
                                                    <a href="${item.video}" class="video-buttton video-popup">
                                                        <i class="far fa-video"></i>
                                                    </a>
                                                </li>` : ''}
                                            <li>
                                                <a href="#" class="package_img_popup" data-images='${JSON.stringify(item.imagesUrl)}'>
                                                    <i class="far fa-camera"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="content">
                                    <h4>
                                        <a href="${item.detailsUrl}">${item.title}</a>
                                    </h4>
                                    <span class="location-icon" title="${item.address ?? 'Location not available'}">
                                        <i class="far fa-map-marker-alt"></i>
                                        ${ (item.address && item.address.length > 50)
                                                            ? item.address.substring(0, 50) + '...'
                                                            : (item.address ?? 'Location not available') }
                                    </span>
                                    <a href="${item.detailsUrl}" class="theme-btn">
                                        <span>@lang('Book Now')</span> <i class="far fa-long-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>`;
                    $('.showItems').append(html);
                });
            }

            $('#filter-form').on('change', 'select, input[type=checkbox], input[type=range]', runAjaxSearch);

            let debounceTimer;
            $('#filter-form').on('input', 'input[type=range]', function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(runAjaxSearch, 300);
            });

            $('.load-more-btn').on('click', loadMore);
        });
    </script>
@endpush
