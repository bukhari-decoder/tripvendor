@extends(template() . 'layouts.app')
@section('title',trans('Packages Author'))
@section('content')
    <div class="author-section">
        <div class="container">
            <div class="author-box3">
                <div class="img-box">
                    <img src="{{ getFile($author->image_driver, $author->image) }}" alt="">
                </div>
                <div class="text-box">
                    <div class="d-flex flex-column gap-3 flex-lg-row align-items-lg-center justify-content-between">
                        <div class="left-side">
                            <h4>{{ $author->firstname.' '. $author->lastname }}</h4>
                            <p class="mb-1">@lang('Member since') {{ \Carbon\Carbon::parse($author->as_a_vendor_from)->format('M, Y') }}</p>
                            <p class="mb-1">{{ $author->packages_count }} @lang('Packages')</p>
                            @if ($author->city || $author->state || $author->country)
                                <div class="item">
                                    <i class="far fa-location-dot"></i>
                                    {{ collect([$author->city, $author->state, $author->country])->filter()->implode(', ') }}
                                </div>
                            @endif

                        </div>
                        <div class="right-side">
                            <div class="social-area mt-50">
                                <ul class="d-flex justify-content-center justify-content-md-start">
                                    <li><a href="{{ $author->vendorInfo->facebook_link ?? '' }}"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="{{ $author->vendorInfo->twitter_link ?? '' }}"><i class="fab fa-twitter"></i></a></li>
                                    <li><a href="{{ $author->vendorInfo->linkedin_link ?? '' }}"><i class="fab fa-linkedin"></i></a></li>
                                    <li><a href="{{ $author->vendorInfo->instagram_link ?? '' }}"><i class="fab fa-instagram"></i></a></li>
                                </ul>
                            </div>
                            <div class="mt-4 d-flex justify-content-center justify-content-md-start gap-3 flex-wrap">
                                <button id="copyProfileBtn" class="cmn-btn3">@lang('Copy Profile') <i class="far fa-copy"></i></button>
                                <button class="cmn-btn4 share">
                                    <i class="far fa-share-from-square"></i>
                                    <div id="shareBlock"></div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="author-container">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">@lang('Details')</button>
                        <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">@lang('Packages')</button>
                        <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact" aria-selected="false">@lang('Reviews')</button>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <div class="package-container">
                            <div class="author-title">
                                <h4>{{ $author->firstname.' '.$author->lastname }}</h4>
                                <p class="mb-1">{{ $author->about_me }}</p>
                            </div>
                            <div class="author-content">
                                <ul>
                                    <li><a href=""><span>@lang('Email '): </span>{{ $author->email ?? '' }}</a></li>
                                    <li><a href=""><span>@lang('Phone '): </span> {{ $author->phone_code.$author->phone }}</a></li>
                                    <li><span>@lang('Address '): </span> {{ $author->address_one ?? '' }}</li>
                                    <li><span>@lang('City '): </span> {{ $author->city ?? 'Unknown' }}</li>
                                    <li><span>@lang('State '): </span> {{ $author->state ?? 'Unknown' }}</li>
                                    <li><span>@lang('Country '): </span> {{ $author->country ?? 'Unknown' }}</li>
                                    <li><span>@lang('Avg Rating '): </span> {{ $author->vendorInfo->avg_rating ?? 'Unknown'  }} / 5</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <div class="package-container">
                            <div class="container">
                                <div class="amazing-tour-wrapper">
                                    <div class="row g-4">
                                        @php
                                            $packages = $author->packages()->paginate(basicControl()->user_paginate);
                                        @endphp
                                        @foreach($packages ?? [] as $package)
                                            <div class="col-xl-3 col-lg-4 col-md-6" >
                                                <div class="amazing-tour-items mt-0">
                                                    <div class="thumb">
                                                        <div class="post-box">
                                                            <h4>{{ currencyPosition($package->adult_price) }}</h4>
                                                            <span>/ @lang('person')</span>
                                                        </div>
                                                        <img src="{{ getFile($package->thumb_driver, $package->thumb) }}" alt="img">
                                                        <div class="list-items">
                                                            @if($package->is_featured == 1)
                                                                <h6>@lang('FEATURED')</h6>
                                                            @endif
                                                            <ul class="popup-icon">
                                                                @if(!empty($package->video))
                                                                    <li>
                                                                        <a href="{{ $package->video }}" class="video-buttton video-popup">
                                                                            <i class="far fa-play"></i>
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                                <li>
                                                                    @php
                                                                        $images = [];
                                                                        foreach ($package->media as $packImage) {
                                                                            $images[] = getFile($packImage->driver, $packImage->image);
                                                                        }
                                                                    @endphp
                                                                    <a href="#" class="package_img_popup" data-images='@json($images)'>
                                                                        <i class="far fa-images"></i>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="content">
                                                        <h4>
                                                            <a href="{{ route('package.details', ['slug' => $package->slug]) }}">{{ $package->title }}</a>
                                                        </h4>
                                                        <span class="location-icon">
                                                            <i class="far fa-map-marker-alt"></i>
                                                            {{ $package->cityTake?->name.', '.$package->stateTake?->name.', '.$package->countryTake?->name }}
                                                        </span>
                                                        <a href="{{ route('package.details', ['slug' => $package->slug]) }}" class="theme-btn">
                                                            <span>@lang('Book Now')</span> <i class="far fa-long-arrow-right"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    {{ $packages->appends(request()->query())->links(template().'partials.pagination') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                        <div class="package-container">
                            <div class="tour-details-wrapper">
                                <div class="row g-5">
                                    <div class="col-lg-6">
                                        <div class="tour-details-items">
                                            <div class="details-content">
                                                <div class="review-area">
                                                    <h3>
                                                        @lang('Review Overview')
                                                    </h3>
                                                    <div class="courses-reviews-box-items">
                                                        <div class="courses-reviews-box">
                                                            <div class="reviews-box">
                                                                <h2><span class="odometer" data-count="{{ $author->vendorInfo->avg_rating ?? 0.0 }}">00</span></h2>
                                                                {!! displayStarRating($author->vendorInfo->avg_rating ?? 0.0) !!}
                                                                <p>{{ $count }}+ @lang('Reviews')</p>
                                                            </div>
                                                            <div class="reviews-ratting-right">
                                                                <div class="reviews-ratting-item">
                                                                    {!! displayStarRating($average_ratings['services'] ?? 0.0) !!}
                                                                    <div class="progress">
                                                                        <div class="progress-value style-two" style="width: {{ ($average_ratings['services'] / 5) * 100 }}% !important;"></div>
                                                                    </div>
                                                                    <span>@lang('Services')</span>
                                                                </div>
                                                                <div class="reviews-ratting-item">
                                                                    {!! displayStarRating($average_ratings['safety'] ?? 0.0) !!}
                                                                    <div class="progress">
                                                                        <div class="progress-value style-three" style="width: {{ ($average_ratings['safety']  / 5) * 100 }}% !important;"></div>
                                                                    </div>
                                                                    <span>@lang('Safety')</span>
                                                                </div>
                                                                <div class="reviews-ratting-item">
                                                                    {!! displayStarRating($average_ratings['guides'] ?? 0.0) !!}
                                                                    <div class="progress">
                                                                        <div class="progress-value style-three" style="width: {{ ($average_ratings['guides'] / 5) * 100 }}% !important;"></div>
                                                                    </div>
                                                                    <span>@lang('Guides')</span>
                                                                </div>
                                                                <div class="reviews-ratting-item">
                                                                    {!! displayStarRating($average_ratings['foods'] ?? 0.0) !!}
                                                                    <div class="progress">
                                                                        <div class="progress-value style-four" style="width: {{ ($average_ratings['foods'] / 5) * 100 }}% !important;"></div>
                                                                    </div>
                                                                    <span>@lang('Foods')</span>
                                                                </div>
                                                                <div class="reviews-ratting-item">
                                                                    {!! displayStarRating($average_ratings['hotel'] ?? 0.0) !!}
                                                                    <div class="progress">
                                                                        <div class="progress-value style-five" style="width: {{ ($average_ratings['hotel'] / 5) * 100 }}% !important;"></div>
                                                                    </div>
                                                                    <span>@lang('Hotels')</span>
                                                                </div>
                                                                <div class="reviews-ratting-item">
                                                                    {!! displayStarRating($average_ratings['places'] ?? 0.0) !!}
                                                                    <div class="progress">
                                                                        <div class="progress-value style-five" style="width: {{ ($average_ratings['places'] / 5) * 100 }}% !important;"></div>
                                                                    </div>
                                                                    <span>@lang('Places')</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
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
                                                            <input type="hidden" name="vendor_id" id="vendor_id" value="{{ $author->id }}">

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
                                    @if(isset($reviews) && $count > 0)
                                        <div class="col-lg-6">
                                            <div class="tour-details-items">
                                                <div class="details-content">
                                                    <div class="client-review-items">
                                                        <h3>@lang('Clients Reviews')</h3>
                                                        @foreach($reviews ?? [] as $review)
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
                                                                                    <input type="hidden" name="vendor_id" value="{{ $author->id }}" />
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
                                                                    @foreach($review->reply ?? [] as $reply)
                                                                        <div class="clinet-box style-2">
                                                                            <h5>
                                                                                {{ $reply->comment }}
                                                                            </h5>
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
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('style')
    <style>
        .tour-details-wrapper .tour-details-items .details-content .review-area .courses-reviews-box-items .reviews-ratting-right .reviews-ratting-item {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .tour-details-wrapper .tour-details-items .details-content .review-area .courses-reviews-box-items .courses-reviews-box .reviews-box {
            padding: 44px 34px;
            text-align: center;
            min-width: 216px;
            border-radius: 28px;
            background: rgba(255, 255, 255, 0.1);
        }
        .fa-star{
            cursor: pointer;
        }
        .tour-details-wrapper .tour-details-items .details-content .client-review-items .clinet-box-items .clinet-box .review-wrap-area {
            border-bottom: 1px solid rgba(21, 20, 21, 0.14);
            padding-bottom: 40px;
            margin-top: 11px;
        }
        .tour-details-wrapper .tour-details-items .details-content .client-review-items .clinet-box-items .clinet-box.style-2 .review-wrap-area {
            border-bottom: none;
            margin-top: 10px;
            padding-bottom: 0;
        }
    </style>
@endpush
@push('script')
    <script src="{{ asset(template(true) . 'js/socialSharing.js') }}"></script>
    <script>
        if ($("#shareBlock").length) {
            $("#shareBlock").socialSharingPlugin({
                urlShare: window.location.href,
                description: $("meta[name=description]").attr("content"),
                title: $("title").text(),
            });
        }
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
        $(document).ready(function () {
            $('.package_img_popup').on('click', function (e) {
                e.preventDefault();
                var images = $(this).data('images');

                if (!images || !images.length) return;

                var items = images.map(function (url) {
                    return {src: url};
                });

                $.magnificPopup.open({
                    items: items,
                    type: 'image',
                    gallery: {
                        enabled: true
                    }
                });
            });
        });
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('copyProfileBtn').addEventListener('click', () => {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    Notiflix.Notify.success('Profile Copied!');

                    const btn = document.getElementById('copyProfileBtn');
                    const originalHTML = btn.innerHTML;
                    btn.innerHTML = "@lang('Profile Copied') <i class='far fa-check'></i>";

                    setTimeout(() => {
                        btn.innerHTML = originalHTML;
                    }, 5000);
                });
            });
        });
    </script>
@endpush
