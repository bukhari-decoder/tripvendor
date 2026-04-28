@extends(template().'layouts.app')
@section('title',trans('Checkout || Traveller Info'))

@section('content')
    <section class="checkout-page travelInf">
        <div class="container">
            <div class="checkout-form row">
                <div class="col-lg-8">
                    <div class="row checkout-row">
                        <div class="contactDetails">
                            <div class="travelerDetails d-flex justify-content-between">
                                <h4><span class="numberStyleTwo">@lang('1')</span>@lang('Contact Details')</h4>
                                <a class="cmn-btn2" href="{{ route('user.checkout.form', [$package->slug, $instant->uid]) }}">@lang('Edit')</a>
                            </div>
                            <div class="contact-part">
                                <h5 class="userName">{{ $instant->fname .' '. $instant->lname }}</h5>
                                <p class="userInformation"><span>@lang('Email: ')</span>{{ $instant->email }}</p>
                                <p class="userInformation"><span>@lang('Phone: ')</span>{{ $instant->phone }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <form class="row" id="checkoutForm" action="" method="post">
                                @csrf

                                <div class="travelersInformationArea">
                                    <div class="card-header d-flex">
                                        <h4 class="title pb-2 text-center" ><span class="numberStyleOne text-center">@lang('2')</span>@lang('Travelers Information')</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-4">
                                            @php
                                                $adultInfo = $instant->adult_info;
                                                $childInfo = $instant->child_info;
                                                $infantInfo = $instant->infant_info;
                                            @endphp
                                            @if($instant->total_adult != 0)
                                                @for($i = 0; $i < $instant->total_adult; $i++)
                                                    {!! renderTravellerFields($adultInfo, 'adult', $i, $errors) !!}
                                                @endfor
                                            @endif

                                            @if($instant->total_children != 0)
                                                @for($i = 0; $i < $instant->total_children; $i++)
                                                    {!! renderTravellerFields($childInfo, 'child', $i, $errors) !!}
                                                @endfor
                                            @endif

                                            @if($instant->total_infant != 0)
                                                @for($i = 0; $i < $instant->total_infant; $i++)
                                                    {!! renderTravellerFields($infantInfo, 'infant', $i, $errors) !!}
                                                @endfor
                                            @endif
                                            <input name="booking" value="{{ $instant->id }}" type="hidden"/>
                                            <input name="seo" value="{{ $pageSeo['id'] }}" type="hidden"/>
                                            <input name="package" value="{{ $package->slug }}" type="hidden"/>

                                            <div class="col-md-12">
                                                <button class="cmn-btn d-inline-flex align-items-center gap-1" id="nextButton" type="submit">
                                                    <span class="spinner-border spinner-border-sm d-none" id="nextSpinner" role="status" aria-hidden="true"></span>
                                                    <span id="nextButtonText">@lang('Next')</span>
                                                    <i class="far fa-arrow-right ps-1" id="arrowRight"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="paymentDetails d-flex justify-content-between align-items-center">
                                    <p><span class="numberStyleTwo">@lang('3')</span>@lang('Payment Details')</p>
                                    <i class="far fa-chevron-right nextPlayIcon"></i>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="booking-submission-section">
                        <div class="sidebar-widget-area">
                            <div class="widget-title">
                                <h4 class="mb-2">@lang('Your booking')</h4>
                            </div>
                            <div class="section-header">
                                <div class="image-area">
                                    <img src="{{ getFile($package->thumb_driver, $package->thumb) }}" alt="{{ $package->title }}">
                                </div>
                                <div class="content-area">
                                    <h5 class="title">{{ $package->title }}</h5>
                                    <span class="location"><i class="far fa-location-dot pe-2"></i>{{ optional($package->cityTake)->name.', '. optional($package->stateTake)->name.', '.optional($package->countryTake)->name }}</span>
                                </div>
                            </div>
                            <ul class="cmn-list">
                                <li class="item">
                                    <h6>@lang('Tour type')</h6>
                                    <h6>{{ optional($package->category)->name }}</h6>
                                </li>
                                <li class="item">
                                    <h6>@lang('Departure date')</h6>
                                    <h6><span class="updated-date">{{ dateTime($instant->date) }}</span> <a href="#" class="highlight edit-btn"><i class="far fa-edit text-warning"></i></a></h6>
                                </li>
                                <div class="mb-15 schedule d-none">
                                    <h6 class="title">@lang('Date')</h6>
                                    <div class="schedule-form">
                                        <input name="date" type="text" id="myID" class="form-control" value="{{ $instant->date }}"/>
                                    </div>
                                </div>
                                <li class="item">
                                    <h6>@lang('Duration')</h6>
                                    <h6>{{ $package->duration }}</h6>
                                </li>
                                <li class="item">
                                    <h6>@lang('Number of Adult')</h6>
                                    <h6>{{ $instant->total_adult }}</h6>
                                </li>
                                <li class="item">
                                    <h6>@lang('Number of Children')</h6>
                                    <h6>{{ $instant->total_children }}</h6>
                                </li>
                                <li class="item">
                                    <h6>@lang('Number of Infant')</h6>
                                    <h6>{{ $instant->total_infant }}</h6>
                                </li>
                            </ul>
                            <div class="checkout-summary">
                                <div class="widget-title">
                                    <h4 class="title">@lang('Discount Summary')</h4>
                                </div>
                                <div class="cart-total">
                                    <ul>
                                        <li class="d-flex justify-content-between">
                                            <span>@lang('Total Amount')</span>
                                            <span>{{ currencyPosition($instant->total_price) }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset(template(true) . 'css/flatpickr.min.css') }}">
    <style>
        .error{
            height: 30px !important;
            justify-content: left !important;
        }
    </style>
@endpush
@push('script')
    <script src="{{ asset(template(true).'js/flatpickr.min.js')}}"></script>
    <script>
        const today = new Date().toISOString().split('T')[0];
        flatpickr('.flatpickr', {
            enableTime: false,
            dateFormat: "Y-m-d",
            maxDate: today,
            disableMobile: "true"

        });
        document.addEventListener("DOMContentLoaded", function() {
            let csrfToken = '{{ csrf_token() }}';
            let instantId = "{{ $instant->id }}";

            document.querySelector('.edit-btn').addEventListener('click', function (event) {
                event.preventDefault();
                document.querySelector('.schedule').classList.toggle('d-none');
            });
            flatpickr('#myID', {
                enableTime: false,
                dateFormat: "Y-m-d",
                minDate: 'today',
                disableMobile: "true"
            });
            flatpickr('input[name="date"]', {
                enableTime: false,
                dateFormat: 'Y-m-d',
                minDate: 'today',
                disableMobile: "true",
                onClose: function (selectedDates, dateStr) {
                    $.ajax({
                        url: '{{ route("user.date.update") }}',
                        type: 'POST',
                        headers: {'X-CSRF-TOKEN': csrfToken},
                        data: {id: instantId, date: dateStr},
                        success: function () {
                            document.querySelector('.updated-date').textContent = dateStr;
                            const url = new URL(window.location.href);
                            url.searchParams.set('date', dateStr);
                            history.pushState({}, '', `${url.pathname}?${url.searchParams.toString()}`);
                        },
                        error: function (xhr) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        });
        document.getElementById('checkoutForm').addEventListener('submit', function (e) {
            e.preventDefault();
            let csrfToken = '{{ csrf_token() }}';
            const nextButton = document.getElementById('nextButton');
            const spinner = document.getElementById('nextSpinner');
            const arrowItem = document.getElementById('arrowRight');
            const nextButtonText = document.getElementById('nextButtonText');

            nextButton.disabled = true;
            spinner.classList.remove('d-none');
            arrowItem.classList.add('d-none');
            nextButtonText.textContent = 'Processing...';

            $.ajax({
                url: '{{ route("user.checkout.form.travelers.payment") }}',
                type: 'POST',
                data: $(this).serialize(),
                headers: {'X-CSRF-TOKEN': csrfToken},
                success: function (res) {
                    if (res.success === true) {
                        let redirectUrl = '{{ route("user.checkout.payment.form", ["uid" => ":instantUid"]) }}';
                        redirectUrl = redirectUrl.replace(':instantUid', res.instant.uid);

                        window.location.href = redirectUrl;
                    }
                },
                error: function (xhr) {
                    let errors = xhr.responseJSON.errors;
                    function displayErrors(errors) {
                        for (const key in errors) {
                            if (errors.hasOwnProperty(key)) {
                                const errorMessages = errors[key];

                                const errorSpanId = `.error_${key.replace('.', '_')}`;
                                const errorSpan = document.querySelector(errorSpanId);

                                if (errorSpan) {
                                    errorSpan.textContent = errorMessages.join(', ');
                                }

                                const errorKey = key.replace(/\.(\d+)/, '[$1]');
                                const inputField = document.querySelector(`[name="${errorKey}"]`);
                                if (inputField) {
                                    inputField.addEventListener('input', function() {
                                        if (inputField.value.trim() !== '') {
                                            errorSpan.textContent = '';
                                        }
                                    });
                                }
                            }
                        }
                    }
                    displayErrors(errors);
                }
            });
        });
    </script>
@endpush
