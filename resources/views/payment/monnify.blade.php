@extends(template().'layouts.user')
@section('page_title')
    {{ __('Pay with ').__(optional($deposit->gatewayable)->name) }}
@endsection

@section('content')

    <div class="container paymentContainer">
        <div class="payment-process-container">
            <div class="payment-process-container-inner">
                <div class="payment-process-image">
                    <img src="{{getFile(optional($deposit->gatewayable)->driver,optional($deposit->gatewayable)->image)}}" class="card-img-top gateway-img">
                </div>
                <div class="payment-process-content">
                    <h5 class="my-3">@lang('Please Pay') {{getAmount($deposit->payable_amount)}} {{$deposit->payment_method_currency}}</h5>
                    <button class="btn btn-primary" onclick="payWithMonnify()">@lang('Pay Now')</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script')
	<script type="text/javascript" src="//sdk.monnify.com/plugin/monnify.js"></script>
	<script type="text/javascript">
		'use strict';
            function payWithMonnify() {
                MonnifySDK.initialize({
                    amount: {{ $data->amount }},
                    currency: "{{ $data->currency }}",
                    reference: "{{ $data->ref }}",
                    customerName: "{{$data->customer_name }}",
                    customerEmail: "{{$data->customer_email }}",
                    customerMobileNumber: "{{ $data->customer_phone }}",
                    apiKey: "{{ $data->api_key }}",
                    contractCode: "{{ $data->contract_code }}",
                    paymentDescription: "{{ $data->description }}",
                    isTestMode: true,
                    onComplete: function (response) {
                        if (response.paymentReference) {
                            window.location.href = '{{ route('ipn', ['monnify', $data->ref]) }}';
                        } else {
                            window.location.href = '{{ route('failed') }}';
                        }
                    },
                    onClose: function (data) {
                    }
                });
            }
	</script>
@endpush
