@extends(template() . 'layouts.app')
@section('title',trans('Packages'))
@section('content')
    <section class="tour-section fix section-padding">
        <div class="container">
            <div class="filter-form">
                <div class="booking-list-area outside-bar d-flex align-items-center gap-2 justify-content-between mb-4">
                    <div class="booking-select d-flex align-items-center gap-2 ">

                        <div class="booking-list">
                            <div class="content ">
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
                        <button class="theme-btn" data-bs-target="#advamceModal" data-bs-toggle="modal">  <i class="far fa-list"></i> @lang('Advanced')</button>

                    </div>
                    <div class="booking-list">
                        <div class="content">
                            <div class="form-clt">
                                <div class="form">
                                    <select class="single-select w-100" name="sort_by">
                                        <option value="desc" {{ (request()->short_by == 'desc') || is_null(request()->sort_by) ? 'selected' : '' }}>@lang('Date ')<sub>(@lang(' Desc '))</sub></option>
                                        <option value="asc" {{ (request()->short_by == 'asc') ? 'selected' : '' }}>@lang('Date ')<sub>(@lang(' Asc '))</sub></option>
                                        <option value="lth" {{ request()->sort_by == 'lth' ? 'selected' : '' }}>@lang('Price ')<sub>(@lang(' Low to High '))</sub></option>
                                        <option value="htl" {{ (request()->short_by == 'htl') ? 'selected' : '' }}>@lang('Price ')<sub>(@lang(' High to Low '))</sub></option>
                                        <option value="mpv" {{ (request()->short_by == 'msv') ? 'selected' : '' }}>@lang('Popular ')<sub>(@lang(' by view '))</sub></option>
                                        <option value="mps" {{ (request()->short_by == 'mps') ? 'selected' : '' }}>@lang('Popular ')<sub>(@lang(' by sell '))</sub></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-4 showItems">
                @forelse($packages ?? [] as $package)
                    <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".2s">
                        <div class="tour-box-items mt-0">
                            <div class="thumb">
                                <img src="{{ getFile($package->thumb_driver, $package->thumb) }}" alt="{{ $package->title ?? '' }}">
                            </div>
                            <div class="content">
                                <div class="list-one-item">
                                    <span>{{ $package->countryTake?->name ?? '' }}</span>
                                    <h4>
                                        <a href="{{ route('package.details', $package->slug) }}">
                                            {{ str_replace('&amp;', '&', $package->title ?? '') }}
                                        </a>
                                    </h4>
                                    <h6>@lang('From')
                                        <span>{{ discountPrice($package) }}</span>
                                        @if($package->discount == 1)
                                            <del>{{ currencyPosition($package->adult_price ?? '0') }}</del>
                                        @endif
                                    </h6>
                                </div>
                                <ul class="list">
                                    <li>
                                        <i class="far fa-calendar"></i>
                                        {{ $package->duration ?? '' }}
                                    </li>
                                    <li>
                                        <i class="far fa-flag"></i>
                                        {{ $package->place_count .' places' }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @empty
                    @include('empty')
                @endforelse

            </div>

            {{ $packages->appends(request()->query())->links(template().'partials.pagination') }}

            <div class="d-flex justify-content-center mt-5 loadMoreAreaOutside d-none">
                <button class="theme-btn load-more-btn d-flex align-items-center justify-content-center" data-segment="1">
                    <span class="btn-text">@lang('Load More')</span>
                    <span class="spinner-border spinner-border-sm text-light ms-2 d-none" role="status" aria-hidden="true"></span>
                </button>
            </div>
        </div>
    </section>
    <div class="modal fade" id="advamceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>@lang('Advance Search')</h4>
                    <button type="button" class="modal-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="far fa-xmark"></i>
                    </button>
                </div>
                <form id="advanceSearchForm" action="{{ route('package') }}" method="GET">

                    <div class="modal-body p-sm-5">
                        <div class="tour-sidebar-area">
                            <div class="tour-destination-sidebar">
                                <div class="booking-list-area">
                                    <div class="booking-search">
                                        <div class="content">
                                            <div class="form-clt">
                                                <div class="form">
                                                    <input type="search" class="form-control" name="search" placeholder="Search Hear...">
                                                </div>
                                                <i class="far fa-search"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="booking-list">
                                        <div class="icon">
                                            <img src="{{ asset(template(true).'img/location.png') }}" alt="img">
                                        </div>
                                        <div class="content">
                                            <h5>@lang('Destination')</h5>
                                            <div class="form-clt DropDown">
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
                                            <div class="form-clt DropDown">
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
                                    <div class="booking-list style-2">
                                        <div class="icon">
                                            <img src="{{ asset(template(true).'img/country.png') }}" alt="img">
                                        </div>
                                        <div class="content">
                                            <h5>@lang('Country')</h5>
                                            <div class="form-clt DropDown">
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
                                                <input type="range" id="min-slider" name="min_price" class="slider"
                                                       min="{{ $rangeMin }}"
                                                       max="{{ $rangeMax }}"
                                                       value="{{ $min }}">
                                                <input type="range" id="max-slider" name="max_price" class="slider"
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
                                                            <input type="checkbox" name="amenities[]" value="{{ $amenity->id }}"
                                                                   {{ in_array($amenity->id, request()->amenities ?? []) ? 'checked' : '' }}>
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
                                    <div class="search-widget">
                                        <button type="submit" class="theme-btn search-btn"><span>@lang('Search')</span><i class="fal fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .DropDown .nice-select ul.list{
            max-height: 300px !important;
            height: auto !important;
            overflow: auto !important;
        }
        .DropDown .nice-select.open .list{
            max-height: 320px;
            overflow: auto;
        }
    </style>
@endpush
@push('script')
    <script>
        $(document).ready(function () {
            let currentSegment = 1;

            function runAjaxSearch() {
                currentSegment = 1;

                let destination = $('select[name="destination"]').val();
                let sort_by = $('select[name="sort_by"]').val();

                $.ajax({
                    url: '{{ route('package.search') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        destination: destination,
                        sort_by: sort_by,
                        segment: currentSegment
                    },
                    success: function (response) {
                        $('.showItems').html('');
                        $('#pagination').addClass('d-none');
                        $('.loadMoreAreaOutside').removeClass('d-none');

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
                        </section>`);
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
                            $('.loadMoreAreaOutside').addClass('d-none');
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
                packages.forEach(function (item) {
                    let html = `
                        <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".2s">
                            <div class="tour-box-items mt-0">
                                <div class="thumb">
                                    <img src="${item.imageUrl}" alt="${item.title}">
                                </div>
                                <div class="content">
                                    <span>${item.countryName}</span>
                                    <h4><a href="${item.detailsUrl}">${item.title}</a></h4>
                                    <h6>@lang('From')
                                        <span>${item.formatedDiscountPrice}</span>
                                        ${item.discount ? `<del>${item.formatedPrice}</del>` : ''}
                                    </h6>
                                    <ul class="list">
                                        <li><i class="far fa-calendar"></i> ${item.duration}</li>
                                        <li><i class="far fa-flag"></i> ${item.place_count} places</li>
                                    </ul>
                                </div>
                            </div>
                        </div>`;
                    $('.showItems').append(html);
                });
            }

            $('.filter-form').on('change', 'select[name="destination"], select[name="sort_by"]', runAjaxSearch);

            $('.load-more-btn').on('click', loadMore);
        });

    </script>
@endpush
