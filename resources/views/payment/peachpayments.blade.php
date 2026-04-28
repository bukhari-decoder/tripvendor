@extends(template().'layouts.user')
@section('page_title')
	{{ __('Pay with ').__(optional($deposit->gatewayable)->name) }}
@endsection
@section('content')

	<script src="https://js.stripe.com/v3/"></script>
	<style>
		.StripeElement {
			box-sizing: border-box;
			height: 40px;
			padding: 10px 12px;
			border: 1px solid transparent;
			border-radius: 4px;
			background-color: white;
			box-shadow: 0 1px 3px 0 #e6ebf1;
			-webkit-transition: box-shadow 150ms ease;
			transition: box-shadow 150ms ease;
		}

		.StripeElement--focus {
			box-shadow: 0 1px 3px 0 #cfd7df;
		}

		.StripeElement--invalid {
			border-color: #fa755a;
		}

		.StripeElement--webkit-autofill {
			background-color: #fefde5 !important;
		}
	</style>
    <div class="container paymentContainer">
        <div class="payment-process-container">
            <div class="payment-process-container-inner">
                <div class="payment-process-image">
                    <img
                        src="{{getFile(optional($deposit->gatewayable)->driver, optional($deposit->gatewayable)->image)}}"
                        class="card-img-top gateway-img">
                </div>
                <div class="payment-process-content">
                    <h5 class="my-3">@lang('Please Pay') {{getAmount($deposit->payable_amount)}} {{$deposit->payment_method_currency}}</h5>
                    <form action="{{$data->url}}" class="paymentWidgets"
                          data-brands="VISA MASTER AMEX"></form>
                </div>
            </div>
        </div>
    </div>


	@if($data->environment == 'test' || $deposit->mode == 1)
		<script src="https://test.oppwa.com/v1/paymentWidgets.js?checkoutId={{$data->checkoutId}}"></script>
	@else
		<script src="https://oppwa.com/v1/paymentWidgets.js?checkoutId={{$data->checkoutId}}"></script>
	@endif
@endsection
