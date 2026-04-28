@extends(template().'layouts.app')
@section('title',trans('Payment'))

@section('content')
    <!-- payment -->
    <section class="payment">
        <div class="container">
            <form action="{{ route('user.plan.make.payment') }}" method="post" enctype="multipart/form-data">
                @csrf

                <h4 class="payment-title">@lang('Select Payement')</h4>
                <div class="row">
                    <div class="col-7">
                        <div class="payment-box">
                            <ul class="payment-list">
                                @forelse($gateways as $index => $method)
                                    <li class="item">
                                        <input class="form-check-input select-payment-method"
                                               value="{{ $method->id }}"
                                               type="radio"
                                               name="gateway_id"
                                               id="{{ $method->name }}"
                                            {{ $index === 0 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="{{ $method->name }}">
                                            <span class="payment-list-content">
                                                <span class="payment-list-image">
                                                    <img src="{{ getFile($method->driver, $method->image) }}" alt="image">
                                                </span>
                                                <span class="payment-list-info">
                                                    <span class="payment-list-title">@lang($method->name)</span>
                                                    <span class="payment-list-text">@lang($method->description)</span>
                                                </span>
                                            </span>
                                        </label>
                                    </li>
                                @empty
                                    <p>@lang('No payment methods available')</p>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                    <input type="hidden" name="purchase_id" value="{{$purchase->id}}">
                    <input type="hidden" name="base_currency" value="{{basicControl()->base_currency}}">
                    <div class="col-5 side-bar">

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
            </form>
        </div>
    </section>
@endsection
@push('style')
    <style>
        .theme-btn{
            padding: 19px 15px 19px 30px;
        }
        .transfer-details-section{
            padding: 20px;
        }
        .payment-side-bar .nice-select .list{
            height: 150px;
        }
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function () {
            $('#supported_currency').niceSelect('destroy');
            $('#supported_currency').niceSelect();

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
                        <div class="payment-side-bar">
                            <input type="number form-check-input" class="form-control" name="amount"
                                           id="amount"
                                           placeholder="0.00" step="0.0000000001" value="{{ $purchase->price }}" autocomplete="off" hidden=""/>

                            <div class="payment-side-box" >
                                <h4 class="d-none supported_currency_area_wallet" id="supported_currency_area_wallet">@lang('Payment Summery')</h4>
                                <div class="col-md-12 fiat-currency" id="supported_currency_area">
                                    <label class="form-label">@lang("Supported Currency")</label>
                                    <select class="nice-select" name="supported_currency" id="supported_currency">
                                        <option value="" disabled selected>@lang("Select Currency")</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-12 crypto-currency SometimesHidden"></div>
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

                $('#supported_currency_area_wallet').addClass('d-none');
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
                    data: {gateway: selectedGateway},
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
                $('#supported_currency_area_wallet').addClass('d-none');
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

        function walletShowCharge(amount) {
            $('.check').prop('disabled', false);
            let txnDetails = `<ul class="list-group">
                <li class="list-group-item d-flex justify-content-between">
                    <span>{{ __('Payable Amount') }}</span>
							<span class="text-info"> ${amount} {{basicControl()->base_currency}}</span>
						</li>
					</ul>`;
            $('.show-deposit-summery').html(txnDetails)
        }

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

