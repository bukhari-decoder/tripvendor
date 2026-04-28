@extends(template().'layouts.app')
@section('title',trans('Checkout || Contact Info'))

@section('content')
    <section class="checkout-page">
        <div class="container">
            <form class="checkout-form row" id="checkoutForm" action="" method="post">
                @csrf

                <div class="col-lg-8 pe-0">
                    <div class="row">
                        <div>
                            <div class="col-12">
                                <div class="contactArea">
                                    <div class="card-header d-flex">
                                        <h4 class="title pb-2 text-center" ><span class="numberStyleOne text-center">@lang('1')</span>@lang('Contact Info')</h4>
                                    </div>
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label for="First-Name" class="form-label">@lang('First Name *')</label>
                                            <input type="text" class="form-control" name="fname" value="{{ old('fname', $instant->fname) }}" id="First-Name"
                                                   placeholder="First Name">
                                            @error('fname')
                                            <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="last-Name" class="form-label">@lang('Last Name *')</label>
                                            <input type="text" class="form-control" name="lname" value="{{ old('lname', $instant->lname) }}" id="last-Name"
                                                   placeholder="Last Name">
                                            @error('lname')
                                            <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="email" class="form-label">@lang('Email *')</label>
                                            <input type="email" class="form-control" name="email" value="{{ old('email', $instant->email)  }}" id="email"
                                                   placeholder="user@email.com">
                                            @error('email')
                                            <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="phone" class="form-label">@lang('Phone *')</label>
                                            <input type="text" class="form-control" name="phone" id="phone"value="{{ old('phone', $instant->phone) }}"
                                                   placeholder="Your Phone"
                                                   onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')">
                                            @error('phone')
                                            <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="Address-Line1" class="form-label">@lang('Address Line 1 *') </label>
                                            <input type="text" class="form-control" name="address_one" id="Address-Line1"
                                                   placeholder="Your Address Line 1" value="{{ old('address_one', $instant->address_one) }}">
                                            @error('address_one')
                                            <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="Address-Line2" class="form-label">@lang('Address Line 2')<sub>(Optional)</sub></label>
                                            <input type="text" class="form-control" name="address_two" id="Address-Line2"
                                                   placeholder="Your Address Line 2" value="{{ old('address_two', $instant->address_two) }}">
                                            @error('address_two')
                                            <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="City" class="form-label">@lang('City')</label>
                                            <input type="text" class="form-control" name="city" id="City"
                                                   placeholder="Your City" value="{{ old('city', $instant->city) }}">
                                            @error('city')
                                            <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="State/Province/Region"
                                                   class="form-label">@lang('State/Province/Region')</label>
                                            <input type="text" class="form-control" name="state" id="State/Province/Region"
                                                   placeholder="State/Province/Region" value="{{ old('state', $instant->state) }}">
                                            @error('state')
                                            <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="ZIP-code/Postal-code" class="form-label">@lang('ZIP code/Postal code')<sub>(Optional)</sub></label>
                                            <input type="text" class="form-control" name="postalCode"
                                                   id="ZIP-code/Postal-code"
                                                   placeholder="ZIP code/Postal code" value="{{ old('postalCode', $instant->postal_code) }}">
                                            @error('postalCode')
                                            <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="Country" class="form-label">@lang('Country')</label>
                                            <input type="text" class="form-control" id="Country" name="country"
                                                   placeholder="Country" value="{{ old('country', $instant->country) }}">
                                            @error('country')
                                            <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            @enderror
                                        </div>
                                        <div class="col-md-12">
                                            <label for="message" class="form-label">@lang('Message')</label>
                                            <textarea class="form-control" id="message" name="message" rows="5">{{ old('message', $instant->message) }}</textarea>
                                            @error('message')
                                            <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                            @enderror
                                        </div>
                                        <input name="booking" value="{{ $instant->id }}"  type="hidden"/>
                                        <input name="package" value="{{ $package->slug }}"  type="hidden"/>
                                        <div class="col-md-12">
                                            <button type="submit" href="" id="nextButton" class="cmn-btn pt-2">@lang('Next')<i class="far fa-arrow-right ps-1"></i></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="step-links mt-4">
                                    <div class="step-item travelerDetails d-flex justify-content-between align-items-center">
                                        <p class="mb-0"><span class="numberStyleTwo">@lang('2')</span>@lang('Travelers Details')</p>
                                        <i class="far fa-chevron-right nextPlayIcon"></i>
                                    </div>
                                    <div class="step-item paymentDetails d-flex justify-content-between align-items-center">
                                        <p class="mb-0"><span class="numberStyleTwo">@lang('3')</span>@lang('Payment Details')</p>
                                        <i class="far fa-chevron-right nextPlayIcon"></i>
                                    </div>
                                </div>
                            </div>
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
                                    <span class="location"><i class="far fa-location-dot pe-1"></i>{{ optional($package->cityTake)->name.', '. optional($package->stateTake)->name.', '.optional($package->countryTake)->name }}</span>
                                </div>
                            </div>
                            <ul class="cmn-list">
                                <li class="item">
                                    <h6>@lang('Tour type')</h6>
                                    <h6>{{ optional($package->category)->name }}</h6>
                                </li>
                                <li class="item">
                                    <h6>@lang('Departure date')</h6>
                                    <h6><span class="updated-date">{{ dateTime($instant->date) }}</span> <a href="#" class="highlight edit-btn text-warning"><i class="far fa-edit"></i></a></h6>
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
                                    <h4 class="title">@lang('Payment Summary')</h4>
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
            </form>
        </div>
    </section>
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset(template(true) . 'css/flatpickr.min.css') }}">
@endpush
@push('script')
    <script src="{{ asset(template(true).'js/flatpickr.min.js')}}"></script>
    <script>
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
                            console.error('Error:', xhr.responseText);
                        }
                    });
                }
            });
        });
        document.getElementById('nextButton').addEventListener('click', function (e) {
            e.preventDefault();

            let csrfToken = '{{ csrf_token() }}';
            let nextButton = this;
            nextButton.disabled = true;
            nextButton.innerHTML = 'Processing...';

            $.ajax({
                url: '{{ route("user.checkout.form.travelers.details") }}',
                type: 'POST',
                data: $('#checkoutForm').serialize(),
                headers: {'X-CSRF-TOKEN': csrfToken},
                success: function (res) {
                    if (res.success === true) {
                        let redirectUrl = '{{ route("user.checkout.get.travel", ['uid' => ':instantUid']) }}';
                        redirectUrl = redirectUrl.replace(':instantUid', res.instant.uid);
                        window.location.href = redirectUrl;
                    }
                },
                error: function (xhr) {
                    let errors = xhr.responseJSON?.errors;

                    $('.invalid-feedback').remove();
                    $('.form-control').removeClass('is-invalid');

                    if (errors) {
                        for (let field in errors) {
                            let input = $('[name="' + field + '"]');
                            input.addClass('is-invalid');
                            input.after('<span class="invalid-feedback d-block" role="alert"><span>' + errors[field][0] + '</span></span>');

                            input.on('input', function () {
                                $(this).removeClass('is-invalid');
                                $(this).next('.invalid-feedback').remove();
                            });
                        }
                    } else {
                        alert('An unexpected error occurred.');
                    }

                    nextButton.disabled = false;
                    nextButton.innerHTML = '@lang("Next")<i class="far fa-arrow-right ps-1"></i>';
                }
            });
        });

    </script>
@endpush
