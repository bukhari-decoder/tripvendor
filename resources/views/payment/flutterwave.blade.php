@extends(template().'layouts.user')
@section('page_title')
    {{ __('Pay with ').__(optional($deposit->gatewayable)->name) }}
@endsection
@section('content')

    <div class="container paymentContainer">
        <div class="payment-process-container">
            <div class="payment-process-container-inner">
                <div class="payment-process-image">
                    <img
                        src="{{getFile(optional($deposit->gatewayable)->driver,optional($deposit->gatewayable)->image) }}"
                        class="card-img-top gateway-img">
                </div>
                <div class="payment-process-content">
                    <h5 class="my-3">@lang('Please Pay') {{getAmount($deposit->payable_amount)}} {{$deposit->payment_method_currency}}</h5>
                    <button type="button" class="btn btn-primary" id="btn-confirm"
                            onClick="payWithRave()">@lang('Pay Now')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
    <script>
        'use strict';
        let btn = document.querySelector("#btn-confirm");
        btn.setAttribute("type", "button");
        const API_publicKey = "{{$data->API_publicKey }}";

        function payWithRave() {
            let x = getpaidSetup({
                PBFPubKey: API_publicKey,
                customer_email: "{{ $data->customer_email }}",
                amount: "{{ $data->amount }}",
                customer_phone: "{{ $data->customer_phone }}",
                currency: "{{ $data->currency }}",
                txref: "{{ $data->txref }}",
                onclose: function () {
                },
                callback: function (response) {
                    let txref = response.tx.txRef;
                    let status = response.tx.status;
                    window.location = '{{ url('payment/flutterwave') }}/' + txref + '/' + status;
                }
            });
        }
    </script>
@endpush
