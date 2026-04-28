@extends(template().'layouts.user')
@section('page_title')
	{{ __('Pay with ').__(optional($deposit->gatewayable)->name) }}
@endsection
@section('content')
    <div class="container paymentContainer">
        <div class="payment-process-container">
            <div class="payment-process-container-inner">
                <div class="payment-process-image">
                    <img src="{{getFile(optional($deposit->gatewayable)->driver,optional($deposit->gatewayable)->image)}}"
                        class="card-img-top gateway-img">
                </div>
                <div class="payment-process-content">
                    <h5 class="my-3">@lang('Please Pay') {{getAmount($deposit->payable_amount)}} {{$deposit->payment_method_currency}}</h5>
                    <button type="button" class="btn btn-primary"
                            id="btn-confirm">@lang('Pay Now')</button>
                    <form
                        action="{{ route('ipn', [optional($deposit->gatewayable)->code, $deposit->utr]) }}"
                        method="POST">
                        @csrf
                        <script src="//js.paystack.co/v1/inline.js"
                                data-key="{{ $data->key }}"
                                data-email="{{ $data->email }}"
                                data-amount="{{$data->amount}}"
                                data-currency="{{$data->currency}}"
                                data-ref="{{ $data->ref }}"
                                data-custom-button="btn-confirm">
                        </script>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection

