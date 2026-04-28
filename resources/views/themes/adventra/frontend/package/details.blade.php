@extends(template() . 'layouts.app')
@section('title',trans('Packages Details'))
@section('content')
    <section class="tour-details-section section-padding">
        <div class="container">
            <div class="tour-details-wrapper">
                <div class="row g-5">
                    <div class="col-xl-8 col-lg-7">
                        <div class="tour-details-items">
                            <div id="packageCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                                <div class="carousel-indicators">
                                    @foreach($package->media as $key => $media)
                                        <button type="button" data-bs-target="#packageCarousel" data-bs-slide-to="{{ $key }}" class="{{ $key == 0 ? 'active' : '' }}" aria-current="{{ $key == 0 ? 'true' : 'false' }}" aria-label="Slide {{ $key + 1 }}"></button>
                                    @endforeach
                                </div>

                                <div class="carousel-inner">
                                    @foreach($package->media as $key => $media)
                                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                                            <img src="{{ getFile($media->driver, $media->image) }}" class="d-block w-100 open-modal" data-image="{{ getFile($media->driver, $media->image) }}" alt="{{ ($package->title .' '. ($key + 1)) ?? '' }}">
                                        </div>
                                    @endforeach
                                </div>

                                <button class="carousel-control-prev" type="button" data-bs-target="#packageCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">@lang('Previous')</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#packageCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">@lang('Next')</span>
                                </button>
                            </div>


                            <div class="details-content">
                                <span class="location-icon">
                                    <i class="far fa-map-marker-alt"></i>
                                    {{ collect([
                                        $package->address,
                                        optional($package->cityTake)->name,
                                        optional($package->stateTake)->name,
                                        optional($package->countryTake)->name
                                    ])->filter()->implode(', ') }}
                                </span>
                                <h2>
                                    {{ $package->title ?? '' }}
                                </h2>

                                {!! $package->description ?? '' !!}

                                <div class="destination-list-item mb-5">
                                    <h4>
                                        @lang('Experience the Difference')
                                    </h4>
                                    <div class="destination-list">
                                        <ul class="list">
                                            @foreach($package->facility ?? [] as $item)
                                                <li>
                                                    <i class="fas fa-check-circle"></i>
                                                    {{ $item }}
                                                </li>
                                            @endforeach
                                        </ul>
                                        <ul class="list">
                                            @foreach($package->excluded ?? [] as $item)
                                                <li>
                                                    <i class="fas fa-times-circle text-danger"></i>
                                                    <span class="excluded-text">{{$item }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                                @if(isset($guides) && $guides->isNotEmpty())
                                    <div class="tour-details-icon tour-guide faq-items position-relative">
                                        <h4>@lang('Guide Details')</h4>

                                        <div class="swiper guide-slider">
                                            <div class="swiper-wrapper">
                                                @foreach($guides ?? [] as $guide)
                                                    <div class="swiper-slide">
                                                        <div class="row g-5 justity-content-end">
                                                            <div class="col-lg-4 col-md-6">
                                                                <div class="guide-image">
                                                                    <img src="{{ getFile($guide->driver, $guide->image) }}" alt="{{ $guide->code }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-8">
                                                                <div class="guide-content">
                                                                    <h4 class="guid-name">{{ $guide->name ?? '' }}</h4>
                                                                    <h6 class="guid-designation">{{ $guide->designation ?? '' }}</h6>
                                                                    <a href="tel:{{ $guide->phone }}">{{ $guide->phone ?? '' }}</a>
                                                                    <a href="mailto:{{ $guide->email }}">{{ $guide->email ?? '' }}</a>
                                                                    <p>{!! $guide->description !!}</p>

                                                                    @if(isset($guide->rating))
                                                                        {!! displayStarRating($guide->rating) !!}
                                                                    @endif
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
                                @endif

                                @if($package->allAmenity->count() > 0)
                                    <div class="tour-details-icon faq-items">
                                        <h4 >@lang('Amenities')</h4>
                                        <div class="row g-5 justity-content-end">
                                            @foreach($package->allAmenity ?? [] as $amenity)
                                                <div class="col-lg-4 col-md-6">
                                                    <div class="icon">
                                                        <i class="{{ $amenity->icon ?? '' }}"></i>
                                                        <h5>{{ $amenity->title ?? '' }}</h5>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if(collect($package->expected)->isNotEmpty())
                                    <div class="faq-items">
                                        <h4>@lang('Tour Plan')</h4>
                                        <div class="faq-accordion">
                                            <div class="accordion" id="accordion2">
                                                @foreach($package->expected ?? [] as $index => $plan)
                                                    @php
                                                        $collapseId = 'faq' . $index;
                                                    @endphp
                                                    <div class="accordion-item mb-3">
                                                        <h5 class="accordion-header" id="heading{{ $index }}">
                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}" aria-expanded="false" aria-controls="{{ $collapseId }}">
                                                                <span>{{ $plan->expect ?? '' }}</span>
                                                            </button>
                                                        </h5>
                                                        <div id="{{ $collapseId }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $index }}" data-bs-parent="#accordion2">
                                                            <div class="accordion-body">
                                                                {{ $plan->expect_detail ?? '' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="map-area">
                                    <h3>
                                        @lang('View in Map')
                                    </h3>
                                    <div class="google-map">
                                        <div id="map" style="width: 100%; height: 400px;"></div>
                                    </div>
                                </div>
                                <div class="review-area">
                                    <h3>
                                        @lang('Customer Reviews')
                                    </h3>
                                    <div class="courses-reviews-box-items">
                                        <div class="courses-reviews-box">
                                            <div class="reviews-box">
                                                <h2><span class="odometer" data-count="{{ $package->avg_rating ?? 0.0 }}">00</span></h2>
                                                {!! displayStarRating($package->avg_rating ?? 0.0) !!}
                                                <p>{{ $package->reviews_count }}+ @lang('Reviews')</p>
                                            </div>
                                            <div class="reviews-ratting-right">
                                                <div class="reviews-ratting-item">
                                                    {!! displayStarRating($average_ratings['services'] ?? 0.0) !!}
                                                    <div class="progress">
                                                        <div class="progress-value style-two" style="width: {{ ($average_ratings['services'] ?? 0.0 / 5) * 100 }}% !important;"></div>
                                                    </div>
                                                    <span>@lang('Services')</span>
                                                </div>
                                                <div class="reviews-ratting-item">
                                                    {!! displayStarRating($average_ratings['safety'] ?? 0.0) !!}
                                                    <div class="progress">
                                                        <div class="progress-value style-three" style="width: {{ ($average_ratings['safety'] ?? 0.0 / 5) * 100 }}% !important;"></div>
                                                    </div>
                                                    <span>@lang('Safety')</span>
                                                </div>
                                                <div class="reviews-ratting-item">
                                                    {!! displayStarRating($average_ratings['guides'] ?? 0.0) !!}
                                                    <div class="progress">
                                                        <div class="progress-value style-three" style="width: {{ ($average_ratings['guides'] ?? 0.0 / 5) * 100 }}% !important;"></div>
                                                    </div>
                                                    <span>@lang('Guides')</span>
                                                </div>
                                                <div class="reviews-ratting-item">
                                                    {!! displayStarRating($average_ratings['foods'] ?? 0.0) !!}
                                                    <div class="progress">
                                                        <div class="progress-value style-four" style="width: {{ ($average_ratings['foods'] ?? 0.0 / 5) * 100 }}% !important;"></div>
                                                    </div>
                                                    <span>@lang('Foods')</span>
                                                </div>
                                                <div class="reviews-ratting-item">
                                                    {!! displayStarRating($average_ratings['hotel'] ?? 0.0) !!}
                                                    <div class="progress">
                                                        <div class="progress-value style-five" style="width: {{ ($average_ratings['hotel'] ?? 0.0 / 5) * 100 }}% !important;"></div>
                                                    </div>
                                                    <span>@lang('Hotels')</span>
                                                </div>
                                                <div class="reviews-ratting-item">
                                                    {!! displayStarRating($average_ratings['places'] ?? 0.0) !!}
                                                    <div class="progress">
                                                        <div class="progress-value style-five" style="width: {{ ($average_ratings['places'] ?? 0.0 / 5) * 100 }}% !important;"></div>
                                                    </div>
                                                    <span>@lang('Places')</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="client-review-items">
                                    <h3>@lang('Clients Reviews')</h3>
                                    @foreach($package->reviews ?? [] as $review)
                                        <div class="clinet-box-items mb-4">
                                            <div class="clinet-box">
                                                {!! displayStarRating($review->avg_rating) !!}
                                                <h5>
                                                    {{ $review->comment ?? '' }}
                                                </h5>
                                                <div class="review-wrap-area d-flex gap-4 align-items-center">
                                                    <div class="review-thumb">
                                                        <img src="{{ getFile($review->user?->image_driver, $review->user?->image) }}" alt="{{ $review->user->firstname.' '. $review->user->lastname }}">
                                                    </div>
                                                    <div class="review-content">
                                                        <div class="head-area d-flex flex-wrap gap-2 align-items-center justify-content-between">
                                                            <div class="cont">
                                                                <h5>{{ $review->user->firstname.' '. $review->user->lastname }}</h5>
                                                                <span>@lang('from') {{ $review->user->address_one }}</span>
                                                            </div>
                                                            <h6>
                                                                <a href="#" class="reply-toggle" data-target="reply-box-{{ $review->id }}">@lang('Reply')</a>
                                                            </h6>
                                                        </div>
                                                        <div class="reply-box mt-2 d-none" id="reply-box-{{ $review->id }}">
                                                            @php
                                                                $hasError = old('parent_review_id') == $review->id && $errors->has('message');
                                                            @endphp
                                                            <form action="{{ route('user.review.reply.store') }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="parent_review_id" value="{{ $review->id }}" />
                                                                <input type="hidden" name="package_id" value="{{ $package->id }}" />
                                                                <textarea class="form-control mb-2 @error('message') is-invalid @enderror" name="message" rows="3" placeholder="Write your reply..." required>{{ old('parent_review_id') == $review->id ? old('message') : '' }}</textarea>

                                                                @if ($hasError)
                                                                    <div class="invalid-feedback d-block">
                                                                        {{ $errors->first('message') }}
                                                                    </div>
                                                                @endif
                                                                <button type="submit" class="theme-btn replyTheme">@lang('Submit')</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if($review->reply->count() > 0)
                                                <button class="toggle-replies-btn btn btn-link p-0" data-target="replies-{{ $review->id }}">@lang('Show Replies')</button>
                                                <div class="replies-container mt-3 d-none" id="replies-{{ $review->id }}">
                                                    @foreach($review->reply ?? [] as $reply)
                                                        <div class="clinet-box style-2">
                                                            <h5>{{ $reply->comment }}</h5>
                                                            <div class="review-wrap-area d-flex gap-4 align-items-center">
                                                                <div class="review-thumb">
                                                                    <img src="{{ getFile($reply->user?->image_driver, $reply->user?->image) }}" alt="{{ $reply->user?->firstname.' '.$reply->user?->firstname }}">
                                                                </div>
                                                                <div class="review-content">
                                                                    <div class="head-area d-flex flex-wrap gap-2 align-items-center justify-content-between">
                                                                        <div class="cont">
                                                                            <h5>{{ $reply->user?->firstname.' '.$reply->user?->firstname }}</h5>
                                                                            <span>@lang('from ') {{ $reply->user?->address_one }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                <form action="{{ route('user.review.store') }}" id="contact-form" method="POST">
                                    @csrf

                                    <div class="review-comment-items">
                                        <h3>@lang('Add Your Reviews')</h3>
                                        <ul>
                                            <li>
                                                @lang('Services')
                                                <div class="star rating" data-category="services">
                                                    <i class="far fa-star" data-value="1"></i>
                                                    <i class="far fa-star" data-value="2"></i>
                                                    <i class="far fa-star" data-value="3"></i>
                                                    <i class="far fa-star" data-value="4"></i>
                                                    <i class="far fa-star" data-value="5"></i>
                                                </div>
                                                <input type="hidden" name="rating_services" value="0">
                                            </li>
                                            <li>
                                                @lang('Hotel')
                                                <div class="star rating" data-category="hotel">
                                                    <i class="far fa-star" data-value="1"></i>
                                                    <i class="far fa-star" data-value="2"></i>
                                                    <i class="far fa-star" data-value="3"></i>
                                                    <i class="far fa-star" data-value="4"></i>
                                                    <i class="far fa-star" data-value="5"></i>
                                                </div>
                                                <input type="hidden" name="rating_hotel" value="0">
                                            </li>

                                            <li>
                                                @lang('Places')
                                                <div class="star rating" data-category="places">
                                                    <i class="far fa-star" data-value="1"></i>
                                                    <i class="far fa-star" data-value="2"></i>
                                                    <i class="far fa-star" data-value="3"></i>
                                                    <i class="far fa-star" data-value="4"></i>
                                                    <i class="far fa-star" data-value="5"></i>
                                                </div>
                                                <input type="hidden" name="rating_places" value="0">
                                            </li>
                                        </ul>
                                        <ul class="mb-0">
                                            <li>
                                                @lang('Safety')
                                                <div class="star rating" data-category="safety">
                                                    <i class="far fa-star" data-value="1"></i>
                                                    <i class="far fa-star" data-value="2"></i>
                                                    <i class="far fa-star" data-value="3"></i>
                                                    <i class="far fa-star" data-value="4"></i>
                                                    <i class="far fa-star" data-value="5"></i>
                                                </div>
                                                <input type="hidden" name="rating_safety" value="0">
                                            </li>

                                            <li>
                                                @lang('Foods')
                                                <div class="star rating" data-category="foods">
                                                    <i class="far fa-star" data-value="1"></i>
                                                    <i class="far fa-star" data-value="2"></i>
                                                    <i class="far fa-star" data-value="3"></i>
                                                    <i class="far fa-star" data-value="4"></i>
                                                    <i class="far fa-star" data-value="5"></i>
                                                </div>
                                                <input type="hidden" name="rating_foods" value="0">
                                            </li>

                                            <li>
                                                @lang('Guides')
                                                <div class="star rating" data-category="guides">
                                                    <i class="far fa-star" data-value="1"></i>
                                                    <i class="far fa-star" data-value="2"></i>
                                                    <i class="far fa-star" data-value="3"></i>
                                                    <i class="far fa-star" data-value="4"></i>
                                                    <i class="far fa-star" data-value="5"></i>
                                                </div>
                                                <input type="hidden" name="rating_guides" value="0">
                                            </li>
                                        </ul>
                                        <h4>@lang('Leave Feedback')</h4>
                                        <div class="row g-4">
                                            <div class="col-lg-6">
                                                <div class="form-clt flex-column">
                                                    <select class="single-select w-100 @error('guide') is-invalid @enderror" name="guide">
                                                        <option value="" selected disabled>@lang('Select Guide')</option>
                                                        @foreach($guides ?? [] as $rGuide)
                                                            <option value="{{ $rGuide->code }}" {{ old('guide') == $rGuide->code ? 'selected' : '' }}>
                                                                {{ $rGuide->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('guide')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>

                                            <input type="hidden" name="package_id" id="package_id" value="{{ $package->id }}">

                                            <div class="col-lg-12">
                                                <div class="form-clt">
                                                    <textarea name="message" id="message" placeholder="@lang('Your comments...')" class="@error('message') is-invalid @enderror">{{ old('message') }}</textarea>
                                                    @error('message')
                                                    <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <button type="submit" class="theme-btn text-center">
                                                    <span>@lang('Submit Reviews')</span> <i class="far fa-long-arrow-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-5">
                        <div class="author-sidebar">
                            <a href="{{ route('package.author',$package->owner->slug) }}" class="author-image">
                                <img src="{{ getFile($package->owner->image_driver, $package->owner->image) }}" alt="">
                            </a>
                            <div class="author-info">
                                <a href="{{ route('package.author',$package->owner->slug) }}" class="athour-name">{{ $package->owner?->firstname.' '.$package->owner?->lastname }}</a>
                                <p class="mb-1">@lang('Member since') {{ \Carbon\Carbon::parse($package->owner?->as_a_vendor_from)->format('M, Y') }}</p>
                                <a href="maito:{{ $package->owner?->email }}" class="author-email"><span>@lang('Email '): </span> {{ $package->owner?->email }}</a>
                                <a href="tel:{{ $package->owner?->phone_code.$package->owner?->phone }}" class="author-email"><span>Phone:</span> {{ $package->owner?->phone_code.$package->owner?->phone }}</a>
                            </div>
                        </div>
                        <div class="tour-details-sidebar sticky-style">
                            <form id="bookingInformationForm" class="form" action="{{ route('user.checkout.form', $package->slug) }}" method="post">
                                @csrf
                                <div class="tour-sidebar-items">
                                    <h3>@lang('Tour Booking')</h3>
                                    <ul class="form-list">
                                        <li>
                                            <label for="fromDate" >@lang('From Date:') <span class="text-danger ps-1">*</span></label>
                                            <div class="form-clt">
                                                <div id="datepicker" class="input-group date" data-date-format="dd-mm-yyyy">
                                                    <input class="form-control" type="text" id="fromDate" name="date" placeholder="e.g. 2025-04-15" value="{{ request()->date ?? '' }}" required>
                                                    <span class="input-group-addon"><i class="far fa-calendar"></i></span>
                                                </div>
                                            </div>
                                        </li>
                                        @if(isset($package->timeSlot))
                                            <li>
                                                <h6>@lang('Time:')</h6>
                                                <div class="form-clt d-flex flex-column gap-3">
                                                    @foreach($package->timeSlot ?? [] as $ts)
                                                        <label class="checkbox-single">
                                                            <span class="d-flex gap-xl-3 gap-2 align-items-center">
                                                                <span class="checkbox-area d-center">
                                                                    <input type="radio" name="time_slot" value="{{ $ts }}" {{ (request()->slot == $ts) ? 'checked' : '' }}>
                                                                    <span class="checkmark d-center"></span>
                                                                </span>
                                                                <span class="text-color">
                                                                    {{ $ts }}
                                                                </span>
                                                            </span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </li>
                                        @endif
                                    </ul>
                                    <div class="tickets-list">
                                        <p>@lang('Tickets') <span class="text-danger ps-1">*</span></p>
                                        <ul>
                                            <div class="p-2 d-none">
                                                <span class="bookingError text-danger"></span>
                                            </div>
                                            <li>
                                                @lang('Adults'):
                                                <b class="totalAdultTravelAmount">
                                                    {{ currencyPosition(($package->adult_price ?? 0) * 1) }}
                                                </b>
                                                <div class="form-clt">
                                                    <div class="quantity-selector">
                                                        <button type="button" class="decrement">-</button>
                                                        <input type="text" class="totalAdultTraveller" value="1" readonly />
                                                        <button type="button" class="increment">+</button>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                @lang('Children'):
                                                <b class="totalChildTravelAmount">
                                                    0
                                                </b>
                                                <div class="form-clt">
                                                    <div class="quantity-selector">
                                                        <button type="button" class="decrement">-</button>
                                                        <input type="text" class="totalChildTraveller" value="0" readonly />
                                                        <button type="button" class="increment">+</button>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                @lang('Infant'):
                                                <b class="totalInfantTravelAmount">
                                                    0
                                                </b>
                                                <div class="form-clt">
                                                    <div class="quantity-selector">
                                                        <button type="button" class="decrement">-</button>
                                                        <input type="text" class="totalInfantTraveller" value="0" readonly />
                                                        <button type="button" class="increment">+</button>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>

                                    <input type="hidden" name="totalAdult" id="totalAdult" class="totalAdult" value="">
                                    <input type="hidden" name="totalChildren" id="totalChildren" class="totalChildren" value="">
                                    <input type="hidden" name="totalInfant" id="totalInfant" class="totalInfant" value="">


                                    <ul class="total-list">
                                        <li>
                                            @lang('Total'):
                                        </li>
                                        <li class="totalAmount">

                                        </li>
                                    </ul>
                                    <button type="submit" class="theme-btn">
                                        <span>@lang('Book Now')</span> <i class="far fa-long-arrow-right"></i>
                                    </button>
                                    @if(auth()->id() != $package->owner_id)
                                        <p class="text chatBox">@lang('Need any help?')</p>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <img src="" id="modalImage" class="img-fluid w-100" alt="Expanded Image">
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content message-container">
                <div class="modal-header">
                    <h5 class="modal-title" id="chatModalLabel">@lang('Chat with ')<span ><a class="text-primary" href="{{ route('package.author',$package->owner->slug) }}">{{ $package->owner->firstname.' '.$package->owner->lastname }}</a></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="chat-box-inner">
                        @if(isset($chatData) && !empty($chatData))
                            @php
                                $bubbleClass = $chatData->sender_id == auth()->user()->id ? 'message-bubble-right' : 'message-bubble-left';
                                $hasMessage = !empty($chatData->message);
                                $isAudio = is_audio($chatData->attachment);
                            @endphp

                            <div class="message-bubble {{ $bubbleClass }}">
                                <div class="tfg">
                                    @if($chatData->attachment)
                                        <div class="attachment-wrapper">
                                            <div class="row attachment-row">
                                                @php
                                                    $driver = $chatData->driver;
                                                    $images = null;
                                                    $audio = null;

                                                    if ($isAudio) {
                                                        $audio = $chatData->attachment;
                                                    } else {
                                                        $images = json_decode($chatData->attachment);
                                                    }
                                                @endphp
                                                @if($images && is_array($images))
                                                    <div class="col-md-6 attachment-col d-flex align-items-center gap-2">
                                                        @if(!$hasMessage)
                                                            <div class="message-thumbs image-thumb">
                                                                <img src="{{ getFile($chatData->sender->image_driver, $chatData->sender->image) }}" alt="Sender Image">
                                                            </div>
                                                        @endif
                                                        <div class="row attachment-row newImage g-2">
                                                            @foreach($images as $file)
                                                                <a class="attachment">
                                                                <img class="supportTicketImage" src="{{ getFile($driver, $file) }}" />
                                                            </a>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @elseif($audio)
                                                    <div class="col-md-6 attachment-col d-flex align-items-center gap-2">
                                                        @if(!$hasMessage)
                                                            <div class="message-thumbs audio-thumb-left">
                                                                <img src="{{ getFile($chatData->sender->image_driver, $chatData->sender->image) }}" alt="Sender Image">
                                                            </div>
                                                        @endif
                                                        <div class="audio-wrapper">
                                                            <audio controls>
                                                                <source src="{{ getAudioFile($driver, $audio) }}" type="audio/mpeg">
                                                            </audio>
                                                        </div>

                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    @if($hasMessage)
                                        <div class="d-flex gap-2"><div class="message-text">{{ $chatData->message }}</div>
                                            <div class="message-thumbs">
                                                <img src="{{ getFile($chatData->sender->image_driver, $chatData->sender->image) }}" alt="Sender Image">
                                            </div>

                                        </div>
                                    @endif
                                </div>
                            </div>

                            @foreach($chatData->reply as $item)
                                @php
                                    $bubbleClass = $item->sender_id == auth()->user()->id ? 'message-bubble-right' : 'message-bubble-left';
                                    $isAudio = is_audio($item->attachment);
                                    $hasMessage = !empty($item->message);
                                @endphp

                                <div class="message-bubble {{ $bubbleClass }}">
                                    <div class="fgfxvcd">
                                        @if($hasMessage)
                                            <div class="d-flex justify-content-end leftItem gap-2">
                                                <div class="message-text">{{ $item->message }}</div>
                                                <div class="message-thumbs">
                                                    <img src="{{ getFile($item->sender->image_driver, $item->sender->image) }}" alt="Sender Image">
                                                </div>
                                            </div>
                                        @endif
                                        @if($item->attachment)
                                            <div class="attachment-wrapper">
                                                <div class="row attachment-row align-items-center">
                                                    @php
                                                        if ($isAudio) {
                                                            $audio = $item->attachment;
                                                            $driver = $item->driver;
                                                            $images = null;
                                                        } else {
                                                            $images = json_decode($item->attachment);
                                                            $driver = $item->driver;
                                                            $audio = null;
                                                        }
                                                    @endphp

                                                    @if($images && is_array($images))
                                                        <div class="d-flex align-items-end gap-3">
                                                            @if(!$hasMessage)
                                                                <div class="message-thumbs d-flex align-items-center imageThumbSingle">
                                                                    <img src="{{ getFile($item->sender->image_driver, $item->sender->image) }}" alt="Sender Image">
                                                                </div>
                                                            @endif
                                                            <div class="row attachment-row newImage g-2">
                                                                <div class="col-6 attachment-col {{ $bubbleClass === 'message-bubble-right' ? 'col-right' : '' }}">
                                                                    @foreach($images as $file)
                                                                        <a class="attachment" data-bs-toggle="modal" data-bs-target="#imagePreviewModal" data-image="{{ getFile($driver, $file) }}">
                                                                            <img class="supportTicketImage" src="{{ getFile($driver, $file) }}" />
                                                                        </a>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @elseif($audio)
                                                        <div class="col-md-6 attachment-col {{ $bubbleClass === 'message-bubble-right' ? 'col-right' : '' }} d-flex align-items-center gap-2">
                                                            @if(!$hasMessage || $isAudio)
                                                                <div class="message-thumbs audio-thumb">
                                                                    <img src="{{ getFile($item->sender->image_driver, $item->sender->image) }}" alt="Sender Image">
                                                                </div>
                                                            @endif
                                                            <div class="audio-wrapper">
                                                                <audio controls>
                                                                    <source src="{{ getAudioFile($driver, $audio) }}" type="audio/mpeg">
                                                                </audio>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="messageText">
                                <h5 class="message-text">@lang('How Can I Help You?')</h5>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <form class="pt-3" action="{{ route('user.chat.reply') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="chat" value="{{ $chatData->id ?? null }}" id="chatId">
                        <input type="hidden" name="product_id" value="{{ $package->id }}" id="productId">

                        <div class="chat-box-bottom">
                            <div class="chat-box-bottom-inner">
                                <div class="row d-none" id="imagePreviewRow">
                                    <div id="imagePreview" class="message-image-preview"></div>
                                </div>

                                <div class="chat-message-box">
                                    <div class="cmn-btn-group2 d-flex justify-content-sm-end align-items-center">
                                        <input type="file" name="attachments[]" accept="image/*" id="attachment" style="display: none;" multiple onchange="previewTicketImage(event)">
                                        <button type="button" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Image File" class="single-btn2" id="exportImageButton" onclick="document.getElementById('attachment').click();">
                                            <i class="far fa-image"></i>
                                        </button>

                                        <button type="button" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Send Emoji" class="single-btn2" id="emojiButton">
                                            <i class="far fa-face-smile"></i>
                                        </button>
                                    </div>

                                    <textarea class="form-control" id="messageBox" name="message" placeholder="Type a message..."></textarea>
                                    <button type="submit" name="replayChat" value="1" class="message-send-btn">
                                        <i class="far fa-paper-plane"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <emoji-picker id="emojiPicker" class="d-none"></emoji-picker>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <img id="previewImage" src="" class="img-fluid w-100" alt="Preview">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <link href="{{ asset(template(true) . 'css/flatpickr.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />


    <style>
        .tour-details-wrapper .tour-details-items .details-content .destination-list-item .destination-list{
            align-items: normal !important;
        }
        .excluded-text {
            text-decoration-line: line-through;
            text-decoration-style: double;
        }
        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .quantity-selector button {
            width: 30px;
            height: 30px;
            font-size: 20px;
            border: 1px solid #ccc8c8;
            border-radius: 6px;
            background-color: #eee;
            cursor: pointer;
        }
        .quantity-selector input {
            width: 35px;
            height: 30px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 4px;
            color: #000;
        }
        .transition-count {
            transition: all 0.3s ease-in-out;
            background-color: #f0f0f0;
        }
        .star.rating i{
            cursor: pointer;
        }
        .nice-select .list{
            display: block !important;
            width: 100%;
        }
        .replyTheme{
            padding: 9px 15px 9px 10px !important;
        }
        .tour-details-wrapper .tour-details-items .details-content .review-area .courses-reviews-box-items .reviews-ratting-right .reviews-ratting-item{
            gap: 7px !important;
        }
        .message-bubble {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 10px;
        }
        .message-bubble-left {
            background-color: #f1eaea;
            align-self: flex-start;
        }
        .message-bubble-left .leftItem  {
            flex-direction: row-reverse;
            margin-bottom: 10px;
        }
        .message-bubble-right .fgfxvcd .newImage{
            justify-content: end;
        }
        .attachment-col{
            display: flex;
            gap: 10px;
        }
        .attachment-col.col-right{
            display: flex;
            gap: 10px;
        }
        .message-bubble-right {
            background-color: #d4e8f1;
            align-self: flex-end;
            flex-direction: row-reverse;
        }
        .message-thumbs img {
            border-radius: 50%;
            width: 30px;
            height: 30px;
        }
        .attachment-wrapper {
            margin-top: 5px;
        }
        .attachment-col {
            margin-bottom: 5px;
        }
        .col-right .attachment {
            justify-content: flex-end;
        }
        .audio-wrapper audio {
            max-width: 250px;
            border-radius: 10px;
            display: block;
        }
        .audio-thumb{
            margin-right: 10px !important;
        }
        .audio-thumb-left{
            margin-left: 10px !important;
        }

        .newImage .attachment-col img.supportTicketImage {
            width: 100%;
            border-radius: 8px;
            object-fit: cover;
        }
        .imageThumbSingle{
            position: relative;
            top: -108px;
            margin-left: 0 !important;
        }
        #emojiPicker {
            position: absolute;
            bottom: 50px;
            left: 0;
            z-index: 1000;
        }
        .nice-select .list{
            height: 200px !important;
            overflow: auto;
        }
        .discounted-price {
            font-weight: bold;
        }

        .original-price {
            font-size: 16px;
            text-decoration: line-through double;
            margin-left: 1px;
            color: #666;
        }

        .discount-text {
            font-size: 14px;
            color: #d9534f;
            margin-left: -2px;
        }
        .tour-details-wrapper .tour-details-sidebar .tour-sidebar-items .total-list li {
            font-size: 21px;
        }
    </style>
@endpush

@push('script')
    <script src="{{ asset(template(true) . 'js/flatpickr.min.js') }}"></script>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('imagePreviewModal');
            const previewImage = document.getElementById('previewImage');

            document.querySelectorAll('.attachment').forEach(function (el) {
                el.addEventListener('click', function () {
                    const imageUrl = this.getAttribute('data-image');
                    previewImage.src = imageUrl;
                });
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.toggle-replies-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const targetId = this.getAttribute('data-target');
                    const repliesDiv = document.getElementById(targetId);

                    if (repliesDiv.classList.contains('d-none')) {
                        repliesDiv.classList.remove('d-none');
                        this.textContent = 'Hide Replies';
                    } else {
                        repliesDiv.classList.add('d-none');
                        this.textContent = 'Show Replies';
                    }
                });
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const emojiButton = document.getElementById('emojiButton');
            const emojiPicker = document.getElementById('emojiPicker');
            const messageBox = document.getElementById('messageBox');

            emojiButton.addEventListener('click', function() {
                emojiPicker.classList.toggle('d-none');
            });

            document.addEventListener('click', function(event) {
                if (!emojiPicker.contains(event.target) && event.target !== emojiButton) {
                    emojiPicker.classList.add('d-none');
                }
            });
            emojiPicker.addEventListener('emoji-click', function(event) {
                messageBox.value += event.detail.unicode;

                emojiPicker.classList.add('d-none');
            });
        });

        const chatTrigger = document.querySelector('.chatBox');
        if (chatTrigger) {
            chatTrigger.addEventListener('click', function () {
                const chatModal = new bootstrap.Modal(document.getElementById('chatModal'));
                chatModal.show();
            });
        }

        function previewTicketImage(event) {
            let imagePreviewRow = document.getElementById('imagePreviewRow');
            let imagePreview = document.getElementById('imagePreview');
            imagePreview.innerHTML = '';

            let files = event.target.files;
            if (files.length > 0) {
                imagePreviewRow.classList.remove('d-none');
            } else {
                imagePreviewRow.classList.add('d-none');
            }

            for (let i = 0; i < files.length; i++) {
                let file = files[i];
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size exceeds 2 MB');
                    continue;
                }
                if (!file.type.startsWith('image/')) {
                    alert('Only image files are allowed');
                    continue;
                }
                let reader = new FileReader();

                reader.onload = function (e) {
                    let imgWrapper = document.createElement('div');
                    imgWrapper.classList.add('img-wrapper');

                    let img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.maxWidth = '100px';
                    img.style.maxHeight = '100px';
                    img.style.borderRadius = '10px';
                    img.classList.add('preview-image');

                    let removeButton = document.createElement('span');
                    removeButton.innerHTML = '&times;';
                    removeButton.classList.add('remove-preview');
                    removeButton.onclick = function () {
                        imagePreview.removeChild(imgWrapper);
                        if (imagePreview.children.length === 0) {
                            imagePreviewRow.classList.add('d-none');
                        }
                    };

                    imgWrapper.appendChild(img);
                    imgWrapper.appendChild(removeButton);
                    imagePreview.appendChild(imgWrapper);
                };

                reader.readAsDataURL(file);
            }
        }


        document.addEventListener('DOMContentLoaded', function () {
            const images = document.querySelectorAll('.open-modal');
            const modalImage = document.getElementById('modalImage');

            images.forEach(image => {
                image.addEventListener('click', function () {
                    const src = this.getAttribute('data-image');
                    modalImage.setAttribute('src', src);
                    var myModal = new bootstrap.Modal(document.getElementById('imageModal'));
                    myModal.show();
                });
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.reply-toggle').forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('data-target');
                    const replyBox = document.getElementById(targetId);

                    replyBox.classList.toggle('d-none');
                });
            });
        });

        document.querySelectorAll('.rating').forEach(starGroup => {
            const stars = starGroup.querySelectorAll('i');
            const category = starGroup.getAttribute('data-category');
            const hiddenInput = document.querySelector(`input[name=rating_${category}]`);

            stars.forEach(star => {
                star.addEventListener('click', () => {
                    const rating = parseInt(star.getAttribute('data-value'));

                    stars.forEach(s => {
                        s.classList.remove('fas', 'far');
                        s.classList.add(parseInt(s.getAttribute('data-value')) <= rating ? 'fas' : 'far');
                    });

                    hiddenInput.value = rating;
                });
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            let lat = {{ $package->lat }};
            let lng = {{ $package->long }};

            let map = L.map("map").setView([lat, lng], 12);
            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                attribution: "© OpenStreetMap contributors"
            }).addTo(map);

            L.marker([lat, lng]).addTo(map);
        });

        flatpickr('#datepicker input', {
            enableTime: false,
            dateFormat: "Y-m-d",
            minDate: 'today',
            disableMobile: true
        });

        document.addEventListener("DOMContentLoaded", function () {
            const selectors = document.querySelectorAll(".quantity-selector");

            const pricePerAdult = parseFloat("{{ $package->adult_price ?? 0 }}");
            const pricePerChild = parseFloat("{{ $package->children_Price ?? 0 }}");
            const pricePerInfant = parseFloat("{{ $package->infant_price ?? 0 }}");

            const maximumTravelers = parseInt("{{ $package->maximumTravelers ?? 0 }}");

            const currencySymbol = '{{ basicControl()->currency_symbol ?? "$" }}';
            const currencyPosition = '{{ basicControl()->is_currency_position ?? "left" }}';
            const currencySuffix = "{{ basicControl()->is_currency_position == 'right' ? basicControl()->currency_symbol : '' }}";

            const discountEnabled = {{ $package->discount == 1 ? 'true' : 'false' }};
            const discountType = Number({{ $package->discount_type ?? 1 }});
            const discountAmount = parseFloat("{{ $package->discount_amount ?? 0 }}");

            const errorWrapper = document.querySelector(".bookingError")?.closest("div.p-2");
            const errorSpan = document.querySelector(".bookingError");

            const totalAmountDisplay = document.querySelector(".totalAmount");

            const totalAdultCount = document.getElementById("totalAdult");
            const totalChildrenCount = document.getElementById("totalChildren");
            const totalInfantCount = document.getElementById("totalInfant");

            const getCounts = () => ({
                adult: parseInt(document.querySelector(".totalAdultTraveller")?.value || 0),
                child: parseInt(document.querySelector(".totalChildTraveller")?.value || 0),
                infant: parseInt(document.querySelector(".totalInfantTraveller")?.value || 0),
            });

            const getTotalTravelers = () => {
                const { adult, child, infant } = getCounts();
                return adult + child + infant;
            };

            const showError = (message) => {
                if (errorWrapper && errorSpan) {
                    errorWrapper.classList.remove("d-none");
                    errorSpan.textContent = message;
                }
            };

            const hideError = () => {
                if (errorWrapper && errorSpan) {
                    errorWrapper.classList.add("d-none");
                    errorSpan.textContent = "";
                }
            };

            const calculateTotal = () => {
                const { adult, child, infant } = getCounts();

                totalAdultCount.value = adult;
                totalChildrenCount.value = child;
                totalInfantCount.value = infant;

                let baseTotal = (adult * pricePerAdult) + (child * pricePerChild) + (infant * pricePerInfant);
                const originalTotal = baseTotal;

                if (discountEnabled) {
                    if (discountType === 1) {
                        baseTotal -= discountAmount;
                    } else if (discountType === 0) {
                        baseTotal -= (baseTotal * discountAmount / 100);
                    }
                }

                if (baseTotal < 0) baseTotal = 0;

                if (totalAmountDisplay) {
                    if (discountEnabled && baseTotal < originalTotal) {
                        let discountText = "";
                        if (discountType === 0) {
                            discountText = `(${discountAmount.toFixed(0)}% off)`;
                        } else if (discountType === 1) {
                            discountText = `(- ${currencySymbol}${discountAmount.toFixed(2)})`;
                        }

                        if (currencyPosition === 'left') {
                            totalAmountDisplay.innerHTML = `
                                <span class="discounted-price">${currencySymbol}${baseTotal.toFixed(2)}</span>
                                <span class="original-price">${currencySymbol}${originalTotal.toFixed(2)}</span>
                                <span class="discount-text">${discountText}</span>
                            `;
                        } else {
                            totalAmountDisplay.innerHTML = `
                                <span class="discounted-price">${baseTotal.toFixed(2)}${currencySuffix}</span>
                                <span class="original-price">${originalTotal.toFixed(2)}${currencySuffix}</span>
                                <span class="discount-text">${discountText}</span>
                            `;
                        }
                    } else {
                        totalAmountDisplay.textContent = currencyPosition === 'left'
                            ? `${currencySymbol}${baseTotal.toFixed(2)}${currencySuffix}`
                            : `${baseTotal.toFixed(2)}${currencySuffix}`;
                    }
                }
            };

            selectors.forEach(selector => {
                const decrementBtn = selector.querySelector(".decrement");
                const incrementBtn = selector.querySelector(".increment");
                const input = selector.querySelector("input");
                let priceDisplay, pricePerPerson, minCount;

                if (input.classList.contains("totalAdultTraveller")) {
                    pricePerPerson = pricePerAdult;
                    priceDisplay = selector.closest("li")?.querySelector(".totalAdultTravelAmount");
                    minCount = 1;
                } else if (input.classList.contains("totalChildTraveller")) {
                    pricePerPerson = pricePerChild;
                    priceDisplay = selector.closest("li")?.querySelector(".totalChildTravelAmount");
                    minCount = 0;
                } else if (input.classList.contains("totalInfantTraveller")) {
                    pricePerPerson = pricePerInfant;
                    priceDisplay = selector.closest("li")?.querySelector(".totalInfantTravelAmount");
                    minCount = 0;
                }

                const updatePrice = (count) => {
                    input.value = count;
                    if (priceDisplay) {
                        priceDisplay.textContent = `${currencySymbol}${(count * pricePerPerson).toFixed(2)}`;
                    }
                    calculateTotal();
                };

                incrementBtn.addEventListener("click", () => {
                    let count = parseInt(input.value) || 0;
                    const currentTotal = getTotalTravelers();

                    if (currentTotal < maximumTravelers) {
                        count++;
                        updatePrice(count);
                        hideError();
                    } else {
                        showError(`Maximum ${maximumTravelers} travelers allowed.`);
                    }
                });

                decrementBtn.addEventListener("click", () => {
                    let count = parseInt(input.value) || 0;
                    if (count > minCount) {
                        count--;
                        updatePrice(count);
                        hideError();
                    }
                });
            });

            calculateTotal();
        });



    </script>
@endpush
