@extends(template().'layouts.app')
@section('title',trans('Payment'))

@section('content')
    <!-- payment -->
    <section class="payment">
        <div class="container">
            <form id="paymentForm" action="{{ route('user.plan.make.payment') }}" method="post" enctype="multipart/form-data">
                @csrf

                <h4 class="payment-title">@lang('Select Payment')</h4>
                <div class="row">
                    <div class="col-7">
                        <div class="payment-box">
                            <ul class="payment-list">
                                @forelse($gateways as $index => $method)
                                    <li class="item">
                                        <input class="form-check-input select-payment-method"
                                               value="{{ $method->id }}"
                                               data-name="{{ strtolower($method->name) }}"
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

                    <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
                    <input type="hidden" name="base_currency" value="{{ basicControl()->base_currency }}">

                    <div class="col-5 side-bar"></div>

                    <div class="paymentModal">
                        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
                             tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="staticBackdropLabel">@lang('Payment')</h4>
                                        <button type="button" class="cmn-btn-close text-white"
                                                data-bs-dismiss="modal" aria-label="Close">
                                            <i class="fas fa-times text-light"></i>
                                        </button>
                                    </div>
                                    <div class="modal-body" id="paymentModalBody"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

    {{-- ── EcoCash waiting overlay (hidden until AJAX fires) ── --}}
    <div id="ecocashOverlay">
        <div class="eco-overlay-card">
            <div id="overlayIcon">📲</div>
            <h5 id="overlayTitle">Waiting for your approval</h5>
            <p id="overlayMessage">
                A prompt has been sent to your EcoCash number.<br>
                Please approve it on your phone to continue.
            </p>
            <div id="overlaySpinner" class="spinner-border text-success" role="status">
                <span class="visually-hidden">Loading…</span>
            </div>
            <div id="overlayResult"></div>
            <button id="overlayDismiss" class="btn btn-outline-secondary btn-sm mt-3"
                    onclick="dismissEcoOverlay()">Close</button>
        </div>
    </div>
@endsection

@push('style')
    <style>
        .theme-btn { padding: 19px 15px 19px 30px; }
        .transfer-details-section { padding: 20px; }
        .payment-side-bar .nice-select .list { height: 150px; }

        /* ── EcoCash field styles ── */
        .ecocash-fields { margin-top: 14px; }
        .ecocash-fields .form-label {
            font-weight: 600; font-size: 0.85rem;
            text-transform: uppercase; letter-spacing: .04em;
        }
        .ecocash-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: linear-gradient(135deg, #e8fbe8, #d0f0d0);
            border: 1px solid #4caf50; border-radius: 8px;
            padding: 6px 12px; font-size: 0.8rem;
            font-weight: 700; color: #2e7d32; margin-bottom: 12px;
        }
        .phone-input-group { display: flex; align-items: stretch; }
        .phone-prefix {
            background: #f0f0f0; border: 1px solid #ced4da;
            border-right: none; border-radius: 4px 0 0 4px;
            padding: 8px 12px; font-weight: 600; font-size: 0.9rem;
            color: #555; white-space: nowrap;
            display: flex; align-items: center;
        }
        .phone-input-group .form-control { border-radius: 0 4px 4px 0; }
        .phone-hint { font-size: 0.75rem; color: #888; margin-top: 4px; }
        .phone-hint.valid-hint   { color: #2e7d32; font-weight: 600; }
        .phone-hint.invalid-hint { color: #c62828; font-weight: 600; }
        #ecocashCurrencySelect { border: 2px solid #4caf50; }

        /* ── Overlay ── */
        #ecocashOverlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,.65);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }
        #ecocashOverlay.active { display: flex; }
        .eco-overlay-card {
            background: #fff; border-radius: 16px;
            padding: 36px 40px; text-align: center;
            max-width: 380px; width: 90%;
        }
        #overlayIcon   { font-size: 3rem; margin-bottom: 8px; }
        #overlayTitle  { font-weight: 700; color: #1a1a1a; margin-bottom: 6px; }
        #overlayMessage { color: #555; font-size: 0.9rem; margin-bottom: 20px; }
        #overlayResult { margin-top: 10px; }
        #overlayDismiss { display: none; }
    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function () {

            /* ─────────────────────────────────────────────────────────────
               Constants
            ───────────────────────────────────────────────────────────── */
            let amountStatus    = false;
            let selectedGateway = '';
            const baseCurrency  = "{{ basicControl()->currency_symbol }}";
            const ecocashRoute  = "{{ route('user.make.payment.ecocash') }}";
            const csrfToken     = $('meta[name="csrf-token"]').attr('content');

            /* ─────────────────────────────────────────────────────────────
               Utility: clear field validation classes
            ───────────────────────────────────────────────────────────── */
            function clearMessage(fieldId) {
                $(fieldId).removeClass('is-valid is-invalid');
                $(fieldId).closest('div').find('.invalid-feedback, .valid-feedback').html('');
            }

            /* ─────────────────────────────────────────────────────────────
               Validate Zimbabwe EcoCash (Econet) number
               Accepts: 077xxxxxxx / 078xxxxxxx  OR  26377xxxxxxx / 26378xxxxxxx
               Returns: { valid, e164, display }
            ───────────────────────────────────────────────────────────── */
            function validateZimNumber(raw) {
                const digits   = raw.replace(/\D/g, '');
                const localPfx = ['077', '078'];
                const intlPfx  = ['26377', '26378'];
                let local = null;

                if (digits.length === 10 && localPfx.some(p => digits.startsWith(p))) {
                    local = digits;
                } else if (digits.length === 12 && intlPfx.some(p => digits.startsWith(p))) {
                    local = '0' + digits.substring(3);
                } else {
                    return { valid: false, e164: null, display: '' };
                }

                return { valid: true, e164: '263' + local.substring(1), display: local };
            }

            /* ─────────────────────────────────────────────────────────────
               Is the currently selected gateway EcoCash?
            ───────────────────────────────────────────────────────────── */
            function isEcoCash() {
                return ($('.select-payment-method:checked').data('name') || '').includes('ecocash');
            }

            /* ─────────────────────────────────────────────────────────────
               Overlay helpers
            ───────────────────────────────────────────────────────────── */
            function showOverlay() {
                $('#overlayIcon').text('📲');
                $('#overlayTitle').text('Waiting for your approval');
                $('#overlayMessage').html(
                    'A prompt has been sent to your EcoCash number.<br>Please approve it on your phone to continue.'
                );
                $('#overlaySpinner').show();
                $('#overlayResult').hide().html('');
                $('#overlayDismiss').hide();
                $('#ecocashOverlay').addClass('active');
            }

            function overlaySuccess(msg) {
                $('#overlayIcon').text('✅');
                $('#overlayTitle').text('Payment Successful!');
                $('#overlayMessage').html('');
                $('#overlaySpinner').hide();
                $('#overlayResult').show().html(`<div class="alert alert-success mb-0">${msg}</div>`);
                // redirect handled by caller
            }

            function overlayError(msg) {
                $('#overlayIcon').text('❌');
                $('#overlayTitle').text('Payment Failed');
                $('#overlayMessage').html('');
                $('#overlaySpinner').hide();
                $('#overlayResult').show().html(`<div class="alert alert-danger mb-0">${msg}</div>`);
                $('#overlayDismiss').show();
            }

            window.dismissEcoOverlay = function () {
                $('#ecocashOverlay').removeClass('active');
            };

            /* ─────────────────────────────────────────────────────────────
               Build sidebar / card HTML
            ───────────────────────────────────────────────────────────── */
            function buildSidebarHTML() {
                const ecocashBlock = `
                <div class="ecocash-fields" id="ecocashFields">
                    <div class="ecocash-badge">
                        <i class="fas fa-mobile-alt"></i> EcoCash Mobile Payment
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">@lang('Select Currency')</label>
                        <select class="form-control" name="ecocash_currency" id="ecocashCurrencySelect">
                            <option value="USD">USD (US Dollar)</option>
                            <option value="ZWL">ZWL (Zimbabwe Dollar)</option>
                        </select>
                    </div>

                    <div class="col-md-12 mb-2">
                        <label class="form-label">@lang('EcoCash Number')</label>
                        <div class="phone-input-group">
                            <span class="phone-prefix">🇿🇼 +263</span>
                            <input type="tel" class="form-control"
                                   name="ecocash_number" id="ecocashNumber"
                                   placeholder="77 123 4567"
                                   maxlength="15" autocomplete="off">
                        </div>
                        <div class="phone-hint" id="phoneHint">
                            Enter your Econet number starting with 077 or 078
                        </div>
                        <input type="hidden" name="ecocash_e164" id="ecocashE164">
                        <div class="invalid-feedback d-block" id="phoneError"></div>
                    </div>
                </div>`;

                const standardBlock = `
                <div id="standardCurrencyBlock">
                    <div class="col-md-12 fiat-currency" id="supported_currency_area">
                        <label class="form-label">@lang("Supported Currency")</label>
                        <select class="nice-select" name="supported_currency" id="supported_currency">
                            <option value="" disabled selected>@lang("Select Currency")</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="col-md-12 crypto-currency SometimesHidden"></div>
                </div>`;

                return `
                <div class="card bookingPayment">
                    <div class="payment-side-bar">
                        <input type="hidden" name="amount" id="amount" value="{{ $purchase->price }}"/>
                        <div class="payment-side-box">
                            <h4 class="d-none" id="supported_currency_area_wallet">@lang('Payment Summary')</h4>
                            ${isEcoCash() ? ecocashBlock : standardBlock}
                        </div>
                        <div class="transfer-details-section">
                            <ul class="transfer-list show-deposit-summery"></ul>
                            <div class="form-check checkBox">
                                <input class="form-check-input agree-checked" type="checkbox"
                                       value="" id="agreeCheckbox" required>
                                <label class="form-check-label" for="agreeCheckbox">
                                    @lang("I agree to the")
                <a href="{{ route('page','terms-of-use') }}" class="link">@lang("terms and conditions.")</a>
                                </label>
                            </div>
                            <div class="payment-btn-group pt-2">
                                <button type="button" class="theme-btn rounded-1 confirmBtn"
                                        id="payNowBtn" disabled>
                                    @lang("Pay Now")
                </button>
            </div>
        </div>
    </div>
</div>`;
            }

            /* ─────────────────────────────────────────────────────────────
               Render sidebar (or modal on mobile)
            ───────────────────────────────────────────────────────────── */
            function calculateAmount() {
                selectedGateway = $('.select-payment-method:checked').val();
                const html = buildSidebarHTML();

                if (window.innerWidth <= 991) {
                    $('.side-bar').html('');
                    $('#paymentModalBody').html(html);
                    new bootstrap.Modal(document.getElementById('staticBackdrop')).show();
                } else {
                    $('.side-bar').html(html);
                }

                if (isEcoCash()) {
                    bindEcoCashEvents();
                    updatePayNowButton();
                } else {
                    $('#supported_currency').niceSelect('destroy').niceSelect();
                    supportCurrency(selectedGateway);
                }
            }

            /* ─────────────────────────────────────────────────────────────
               EcoCash: live phone validation events
            ───────────────────────────────────────────────────────────── */
            function bindEcoCashEvents() {
                // Unbind first to avoid stacking on re-render
                $(document).off('input.eco').on('input.eco', '#ecocashNumber', function () {
                    const raw    = $(this).val().trim();
                    const result = validateZimNumber(raw);
                    const hint   = $('#phoneHint');

                    if (raw === '') {
                        hint.text('Enter your Econet number starting with 077 or 078')
                            .removeClass('valid-hint invalid-hint');
                        $(this).removeClass('is-valid is-invalid');
                        $('#ecocashE164').val('');
                        $('#phoneError').text('');
                    } else if (result.valid) {
                        hint.text('✓ Valid EcoCash number – ' + result.display)
                            .removeClass('invalid-hint').addClass('valid-hint');
                        $(this).removeClass('is-invalid').addClass('is-valid');
                        $('#ecocashE164').val(result.e164);
                        $('#phoneError').text('');
                    } else {
                        hint.text('✗ Must be an Econet number: 077xxxxxxx or 078xxxxxxx')
                            .removeClass('valid-hint').addClass('invalid-hint');
                        $(this).removeClass('is-valid').addClass('is-invalid');
                        $('#ecocashE164').val('');
                        $('#phoneError').text('Invalid Zimbabwe mobile number.');
                    }

                    updatePayNowButton();
                });

                $(document).off('change.eco').on('change.eco', '#ecocashCurrencySelect, #agreeCheckbox', function () {
                    updatePayNowButton();
                });
            }

            /* ─────────────────────────────────────────────────────────────
               Enable / disable Pay Now button
            ───────────────────────────────────────────────────────────── */
            function updatePayNowButton() {
                const btn = $('#payNowBtn');
                if (isEcoCash()) {
                    const ok = $('#ecocashNumber').hasClass('is-valid')
                        && !!$('#ecocashCurrencySelect').val()
                        && $('#agreeCheckbox').is(':checked');
                    btn.prop('disabled', !ok);
                } else {
                    btn.prop('disabled', !$('#agreeCheckbox').is(':checked'));
                }
            }

            /* ─────────────────────────────────────────────────────────────
               Pay Now click handler
               – EcoCash  → AJAX POST (overlay shown while server polls EcoCash)
               – Other    → normal form submit
            ───────────────────────────────────────────────────────────── */
            $(document).on('click', '#payNowBtn', function (e) {
                e.preventDefault();

                if (!isEcoCash()) {
                    $('#paymentForm').submit();
                    return;
                }

                // Final client-side validation before firing AJAX
                const phoneRaw = $('#ecocashNumber').val().trim();
                const result   = validateZimNumber(phoneRaw);

                if (!result.valid) {
                    $('#ecocashNumber').addClass('is-invalid');
                    $('#phoneError').text('Please enter a valid EcoCash number before proceeding.');
                    return;
                }

                // Show overlay & lock button
                showOverlay();
                $(this).prop('disabled', true).text('Processing…');

                $.ajax({
                    url:     ecocashRoute,
                    type:    'POST',
                    timeout: 60000,          // 60 s – controller can sleep up to ~25 s
                    data: {
                        _token:      csrfToken,
                        phone:       result.e164,
                        currency:    $('#ecocashCurrencySelect').val(),
                        amount:      $('#amount').val(),
                        purchase_id: $('input[name="purchase_id"]').val(),
                        gateway_id:  $('.select-payment-method:checked').val(),
                    },
                    success: function (response) {
                        if (response.status === 'success') {
                            overlaySuccess(response.message || 'Payment confirmed!');
                            setTimeout(function () {
                                window.location.href = response.redirect;
                            }, 1800);
                        } else {
                            overlayError(response.message || 'Payment was not completed. Please try again.');
                            $('#payNowBtn').prop('disabled', false).text('Pay Now');
                        }
                    },
                    error: function (xhr) {
                        let msg = 'An unexpected error occurred. Please try again.';

                        // Laravel 422 validation errors
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }

                        overlayError(msg);
                        $('#payNowBtn').prop('disabled', false).text('Pay Now');
                    }
                });
            });

            /* ─────────────────────────────────────────────────────────────
               Standard gateway: currency loading
            ───────────────────────────────────────────────────────────── */
            function supportCurrency(gw) {
                if (!gw) return;
                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': csrfToken } });
                $.ajax({
                    url: "{{ route('supported.currency') }}",
                    data: { gateway: gw },
                    type: 'GET',
                    success: function (response) {
                        $('#supported_currency').empty();

                        if (response.data === '') {
                            $('#supported_currency').append('<option value="USD">USD</option>');
                            return;
                        }

                        $('#supported_currency').append('<option value="" disabled selected>@lang("Select Currency")</option>');

                        if (response.currencyType == 1) {
                            $('.fiat-currency').show();
                            $('.crypto-currency').hide();
                            $(response.data).each(function (i, v) {
                                $('#supported_currency').append(`<option value="${v}"${i===0?' selected':''}>${v}</option>`);
                            });
                            $('#supported_currency').niceSelect('destroy').niceSelect();
                            checkAmount($('#amount').val(), $('#supported_currency').val(), gw);
                        }

                        if (response.currencyType === 0) {
                            $('.fiat-currency').hide();
                            $('.crypto-currency').show();
                            $('.crypto-currency').html(`
                            <label class="form-label">@lang("Select Crypto Currency")</label>
                            <select class="form-control nice-select" name="supported_crypto_currency"
                                    id="supported_crypto_currency">
                                <option value="">@lang("Selected Crypto Currency")</option>
                            </select>`);
                            $(response.data).each(function (i, v) {
                                $('#supported_crypto_currency').append(`<option value="${v}"${i===0?' selected':''}>${v}</option>`);
                            });
                            $('#supported_crypto_currency').niceSelect('destroy').niceSelect();
                            checkAmount($('#amount').val(), $('#supported_crypto_currency').val(), gw, $('#supported_crypto_currency').val());
                        }
                    },
                    error: function (err) { console.error('AJAX Error:', err); }
                });
            }

            $(document).on('change input', '#amount, #supported_currency, .select-payment-method, #supported_crypto_currency', function () {
                if (isEcoCash()) return;
                const amount   = $('#amount').val();
                const currency = $('#supported_currency').val() ?? 'USD';
                const crypto   = $('#supported_crypto_currency').val();
                const gw       = $('.select-payment-method:checked').val();

                if (!isNaN(amount) && amount > 0) {
                    const fraction = amount.split('.')[1];
                    if (fraction && fraction.length > 2) {
                        $('#amount').val((Math.floor(amount * 100) / 100).toFixed(2));
                    }
                    checkAmount(amount, currency, gw, crypto);
                }
            });

            function checkAmount(amount, selectedCurrency, selectGateway, selectedCryptoCurrency = null) {
                $.ajax({
                    method: 'GET',
                    url: "{{ route('deposit.checkAmount') }}",
                    dataType: 'json',
                    data: {
                        amount,
                        selected_currency:       selectedCurrency,
                        select_gateway:          selectGateway,
                        selectedCryptoCurrency,
                        amountType:              'yes',
                    }
                }).done(function (response) {
                    const f = $('#amount');
                    if (response.status) {
                        clearMessage(f);
                        f.addClass('is-valid');
                        amountStatus = true;
                        showSummery(response);
                    } else {
                        amountStatus = false;
                        clearMessage(f);
                        f.addClass('is-invalid');
                        f.closest('div').find('.invalid-feedback').html(response.message);
                    }
                });
            }

            function showSummery(response) {
                const sym = "{{ basicControl()->currency_symbol }}";
                $('.show-deposit-summery').html(`
                <h5 class="title">@lang("Payment Summary")</h5>
                <li class="item">
                    <span class="item-name">Amount</span>
                    <span class="item-value">${response.amount} ${response.currency}</span>
                </li>
                <li class="item text-danger">
                    <span class="item-name">Charge</span>
                    <span class="item-value">${response.charge} ${response.currency}</span>
                </li>
                <li class="item">
                    <span class="item-name">Payable Amount</span>
                    <span class="item-value">${response.payable_amount} ${response.currency}</span>
                </li>
                <li class="item">
                    <span class="item-name">Payable Amount <sub>(base)</sub></span>
                    <span class="item-value">${response.payable_amount_baseCurrency} ${sym}</span>
                </li>`);
            }

            /* ─────────────────────────────────────────────────────────────
               Gateway switch
            ───────────────────────────────────────────────────────────── */
            $(document).on('click', '.select-payment-method', calculateAmount);

            // Boot
            calculateAmount();
        });
    </script>
@endpush
