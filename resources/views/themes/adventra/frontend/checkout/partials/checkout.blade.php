<section class="checkout-page paymentPage">
    <div class="container">
        <div class="checkout-form row g-4">
            <div class="col-lg-8">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="contactDetails">
                            <div class="travelerDetails d-flex justify-content-between">
                                <h4><span class="numberStyleTwo">@lang('1')</span>@lang('Contact Details')</h4>
                                <a class="cmn-btn2" href="{{ route('user.checkout.form', [$package->slug,$instant->uid]) }}">@lang('Edit')</a>
                            </div>
                            <div class="contact-part">
                                <h5 class="userName">{{ $instant->fname .' '. $instant->lname }}</h5>
                                <p class="userInformation"><span>@lang('Email: ')</span>{{ $instant->email }}</p>
                                <p class="userInformation"><span>@lang('Phone: ')</span>{{ $instant->phone }}</p>
                            </div>
                        </div>
                        <div class="card checkout-form-card travelersInfo">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="title pb-2">
                                    <span class="numberStyleTwo">@lang('2')</span>
                                    <a class="btn-link" data-bs-toggle="collapse" href="#travelerInfoCollapse" role="button" aria-expanded="false" aria-controls="travelerInfoCollapse">
                                        @lang('Travelers Information')
                                        <i class="fa fa-chevron-down ms-2"></i>
                                    </a>
                                </h4>
                                <a class="cmn-btn2 editButton" href="{{ route('user.checkout.get.travel', [$instant->uid]) }}">@lang('Edit')</a>
                            </div>
                            <div id="travelerInfoCollapse" class="collapse">
                                <div class="card-body">
                                    <div class="row g-4">
                                        @php
                                            $adultInfo = $instant->adult_info;
                                            $childInfo = $instant->child_info;
                                            $infantInfo = $instant->infant_info;
                                        @endphp
                                        @if($instant->total_adult != 0)
                                            @for($i = 0; $i < $instant->total_adult; $i++)
                                                {!! renderDisabledTravellerFields($adultInfo, 'adult', $i) !!}
                                            @endfor
                                        @endif

                                        @if($instant->total_children != 0)
                                            @for($i = 0; $i < $instant->total_children; $i++)
                                                {!! renderDisabledTravellerFields($childInfo, 'child', $i) !!}
                                            @endfor
                                        @endif

                                        @if($instant->total_infant != 0)
                                            @for($i = 0; $i < $instant->total_infant; $i++)
                                                {!! renderDisabledTravellerFields($infantInfo, 'infant', $i) !!}
                                            @endfor
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <form class="row g-4" id="checkoutForm" action="{{ route('user.make.payment') }}"
                              method="post" enctype="multipart/form-data">
                            @csrf
                            <input name="booking" type="text" id="instant_save" value="{{$instant->id}}" hidden/>

                            <div class="payment-section">
                                <div class="card">
                                    <div class="card-header d-flex align-items-center">
                                        <h4 class="title pb-2 text-center p-0"><span class="numberStyleOne text-center">3</span>@lang('Payment Information')</h4>
                                    </div>
                                    <div class="card-body pt-0">
                                        <ul class="payment-container-list mt-0">
                                            @foreach($gateway as $item)
                                                <li class="item">
                                                    <input class="form-check-input select-payment-method"
                                                           value="{{ $item->id }}" name="gateway_id"
                                                           type="radio"
                                                           id="{{ $item->name }}"
                                                        {{ $loop->first ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="{{ $item->name }}">
                                                        <div class="image-area">
                                                            <img src="{{ getFile($item->driver, $item->image) }}"
                                                                 alt="{{ $item->name }}">
                                                        </div>
                                                        <div class="content-area">
                                                            <h5>{{ $item->name }}</h5>
                                                        </div>
                                                    </label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="side-bar">

                                    </div>

                                    <div class="paymentModal">
                                        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                             aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title" id="staticBackdropLabel">@lang('Payment')</h4>
                                                        <button type="button" class="cmn-btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
                                                            <i class="fas fa-times text-light"></i>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body" id="paymentModalBody">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 order-2 order-lg-2">
                <div class="booking-submission-section">
                    <div class="sidebar-widget-area">
                        <div class="widget-title">
                            <h4 class="mb-3">@lang('Your booking')</h4>
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
                                <h6>{{optional($package->category)->name }}</h6>
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
                        <div class="coupon-code-area">
                            <div class="widget-title mb-2">
                                <h4 class="title">@lang('Coupon code')</h4>
                            </div>
                            <div class="input-group">
                                <input type="text" class="form-control" id="coupon-input" placeholder="Code here"
                                       aria-label="Recipient's username" name="coupon"
                                       aria-describedby="apply-coupon-btn">
                                <a href="#" class="cmn-btn2" id="apply-coupon-btn">@lang('Apply')</a>
                            </div>
                            <span class="discountMessage mx-2"></span>
                        </div>
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
                                    <hr>
                                    <li class="d-flex justify-content-between">
                                        <span class="text-danger">@lang('Discount')</span>
                                        <span class="text-danger" id="totalDiscount">{{currencyPosition($instant->discount_amount ?? 0)}}</span>
                                    </li>
                                    <hr>
                                    <li class="d-flex justify-content-between">
                                        <span>@lang('Gross Total')</span>
                                        <span class="grossAmountShow" id="grossAmount">{{ currencyPosition($instant->total_price) }}</span>
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
@push('style')
    <style>
        .btn-link{
            color: black;
            text-decoration: none;
        }
        .bookingPayment {
            background: #fdf8f8;
        }
        .payment-section{
            margin-top: 0 !important;
        }
        .form-check-label .content-area{
            display: flex;
            align-items: center;
        }
        .bookingPayment .nice-select.open .list{
            height: 150px;
            overflow: auto;
        }
    </style>
@endpush
@push('script')
    <script>
        const packageId = '{{ $package->id }}';

        document.addEventListener('DOMContentLoaded', function() {
            let collapseElement = document.getElementById('travelerInfoCollapse');
            let chevronIcon = document.querySelector('a[href="#travelerInfoCollapse"] i');

            collapseElement.addEventListener('shown.bs.collapse', function () {
                chevronIcon.classList.remove('fa-chevron-down');
                chevronIcon.classList.add('fa-chevron-up');
            });

            collapseElement.addEventListener('hidden.bs.collapse', function () {
                chevronIcon.classList.remove('fa-chevron-up');
                chevronIcon.classList.add('fa-chevron-down');
            });
        });
        $(document).ready(function () {
            let amountField = $('#amount');
            let amountStatus = false;
            let selectedGateway = "";
            let baseCurrency = "{{basicControl()->currency_symbol}}";

            function clearMessage(fieldId) {
                $(fieldId).removeClass('is-valid')
                $(fieldId).removeClass('is-invalid')
                $(fieldId).closest('div').find(".invalid-feedback").html('');
                $(fieldId).closest('div').find(".is-valid").html('');
            }

            calculateAmount();

            $(document).on('click', '.select-payment-method', function () {
                calculateAmount();
            });

            function calculateAmount() {
                $('.showCharge').html(`${baseCurrency}0.00`);
                selectedGateway = $('.select-payment-method:checked').val();
                let updatedWidth = window.innerWidth;
                window.addEventListener('resize', () => {
                    updatedWidth = window.innerWidth;
                });

                let html = `
                    <div class="card bookingPayment">
                        <div class="card-body">
                            <div class="row g-2 my-3">
                                <div class="col-md-12">
                                    <input type="number" class="form-control" name="amount"
                                           id="amount"
                                           placeholder="0.00" step="0.0000000001" value="{{ $instant->total_price }}" autocomplete="off" hidden=""/>
                                </div>

                                <div class="col-md-12 fiat-currency">
                                    <label class="form-label">@lang("Supported Currency")</label>
                                    <select class="nice-select" name="supported_currency" id="supported_currency">
                                        <option value="" disabled selected>@lang("Select Currency")</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-12 crypto-currency"></div>
                            </div>
                            <div class="transfer-details-section">
                                <ul class="transfer-list show-deposit-summery"></ul>
                                <div class="form-check checkBox">
                                    <input class="form-check-input agree-checked" type="checkbox" value=""
                                           id="Yes, i have confirmed the order!" required>
                                    <label class="form-check-label" for="Yes, i have confirmed the order!">
                                        @lang("I agree to the") <a href="{{ route('page','terms-of-use') }}" class="link">@lang("terms and conditions.")</a>
                                    </label>
                                </div>
                                <div class="payment-btn-group pt-2">
                                    <button type="submit" class="theme-btn rounded-1 confirmBtn">@lang("confirm and continue")</button>
                                </div>
                            </div>
                        </div>
                    </div>`;

                if (updatedWidth <= 991) {
                    $('.side-bar').html('');
                    $('#paymentModalBody').html(html);
                    let paymentModal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
                    paymentModal.show();
                } else {
                    $('.side-bar').html(html);
                }

                $('#supported_currency').niceSelect('destroy');
                $('#supported_currency').niceSelect();

                supportCurrency(selectedGateway);
            }


            function supportCurrency(selectedGateway) {
                if (!selectedGateway) {
                    console.error('Selected Gateway is undefined or null.');
                    return;
                }

                $('#supported_currency').empty();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('supported.currency') }}",
                    data: {
                        gateway: selectedGateway,
                        type : 'booking',
                        'package_id': packageId
                    },
                    type: "GET",
                    success: function (response) {
                        $('#supported_currency').empty();

                        if (response.data === "") {
                            $('#supported_currency').append(`<option value="USD">USD</option>`);
                        } else {
                            let markup = '<option value="" disabled selected>@lang("Select Currency")</option>';
                            $('#supported_currency').append(markup);

                            if (response.currencyType == 1) {
                                $('.fiat-currency').show();
                                $('.crypto-currency').hide();

                                $(response.data).each(function (index, value) {
                                    let selected = index === 0 ? ' selected' : '';
                                    $('#supported_currency').append(`<option value="${value}"${selected}>${value}</option>`);
                                });

                                $('#supported_currency').niceSelect('destroy').niceSelect();

                                let amount = $('#amount').val();
                                let selectedCurrency = $('#supported_currency').val();

                                checkAmount(amount, selectedCurrency, selectedGateway);
                            }

                            if (response.currencyType === 0) {
                                $('.fiat-currency').hide();
                                $('.crypto-currency').show();

                                let markupCrypto = `
                                    <label class="form-label">@lang("Select Crypto Currency")</label>
                                    <select class="form-control nice-select" name="supported_crypto_currency" id="supported_crypto_currency">
                                        <option value="">@lang("Selected Crypto Currency")</option>
                                    </select>`;
                                $('.crypto-currency').html(markupCrypto);

                                $(response.data).each(function (index, value) {
                                    let selected = index === 0 ? ' selected' : '';
                                    $('#supported_crypto_currency').append(`<option value="${value}"${selected}>${value}</option>`);
                                });

                                $('#supported_crypto_currency').niceSelect('destroy').niceSelect();

                                let amount = $('#amount').val();
                                let selectedCurrency = $('#supported_crypto_currency').val();
                                checkAmount(amount, selectedCurrency, selectedGateway, selectedCurrency);
                            }
                        }
                    },
                    error: function (error) {
                        console.error('AJAX Error:', error);
                    }
                });
            }

            $(document).on('change input', '#amount, #supported_currency, .select-payment-method, #supported_crypto_currency', function (e) {

                var amount = $('#amount').val();
                let selectedCurrency = $('#supported_currency').val() ?? 'USD';
                let selectedCryptoCurrency = $('#supported_crypto_currency').val();
                let selectedGateway = $('.select-payment-method:checked').val();
                let currency_type = 1;

                if (!isNaN(amount) && amount > 0) {
                    let fraction = amount.split('.')[1];
                    let limit = currency_type == 0 ? 8 : 2;

                    if (fraction && fraction.length > limit) {
                        amount = (Math.floor(amount * Math.pow(10, limit)) / Math.pow(10, limit)).toFixed(limit);
                        $('#amount').val(amount);
                    }

                    checkAmount(amount, selectedCurrency, selectedGateway, selectedCryptoCurrency);
                } else {
                    clearMessage(amountField);
                }
            });


            function checkAmount(amount, selectedCurrency, selectGateway, selectedCryptoCurrency = null) {

                $.ajax({
                    method: "GET",
                    url: "{{ route('deposit.checkAmount') }}",
                    dataType: "json",
                    data: {
                        'amount': amount,
                        'selected_currency': selectedCurrency,
                        'select_gateway': selectGateway,
                        'selectedCryptoCurrency': selectedCryptoCurrency,
                        'amountType': 'yes',
                        'gatewayType':'booking',
                        'package_id': packageId
                    }
                }).done(function (response) {
                    let amountField = $('#amount');
                    if (response.status) {

                        clearMessage(amountField);
                        $(amountField).addClass('is-valid');
                        $(amountField).closest('div').find(".valid-feedback").html(response.message);
                        $('.confirmBtn').removeClass('d-none').addClass('d-block');
                        $('.form-check').removeClass('d-none').addClass('d-block');
                        amountStatus = true;
                        let base_currency = "{{ basicControl()->base_currency }}"
                        showSummery(response, base_currency);
                    } else {
                        amountStatus = false;
                        clearMessage(amountField);
                        $(amountField).addClass('is-invalid');
                        $(amountField).closest('div').find(".invalid-feedback").html(response.message);
                    }


                });
            }


            function showSummery(response, currency) {
                let formattedAmount = response.amount;
                let formattedChargeAmount = response.charge;
                let formattedPayableAmount = response.payable_amount;
                let payableAmountInBase = response.payable_amount_baseCurrency;
                let baseCurrencySymbol = "{{ basicControl()->currency_symbol }}";

                let paymentSummery = `
                    <h5 class="title">@lang("Payment Summery")</h5>
                    <li class="item">
                        <span class="item-name">Amount</span>
                        <span class="item-value">${formattedAmount} ${response.currency}</span>
                    </li>
                    <li class="item text-danger">
                        <span class="item-name">Charge</span>
                        <span class="item-value">${formattedChargeAmount} ${response.currency}</span>
                    </li>
                    <li class="item">
                        <span class="item-name"><a href="javascript:void(0)">Payable Amount</a></span>

                        <span class="item-value">${formattedPayableAmount} ${response.currency}</span>
                   </li>
                   <li class="item">
                        <span class="item-name"><a href="javascript:void(0)">Payable Amount <sub>(in base currency)</sub></a></span>

                        <span class="item-value">${payableAmountInBase} ${baseCurrencySymbol}</span>
                   </li>`;
                $('.show-deposit-summery').html(paymentSummery)
            }
        });

        isAgree();

        function isAgree() {
            const isAgreeChecked = $(".agree-checked").is(":checked");
            const isPaymentMethodSelected = $(".select-payment-method").is(":checked");

            if (isAgreeChecked && isPaymentMethodSelected) {
                $('.payment-btn-group .cmn-btn').attr('disabled', false);
            } else {
                $('.payment-btn-group .cmn-btn').attr('disabled', true);
            }
        }

        $(document).on('click', '.select-payment-method, .agree-checked', function () {
            isAgree();
        });
    </script>
@endpush

