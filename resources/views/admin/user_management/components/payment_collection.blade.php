<div id="payment_collection" class="card">
    <div class="card-header">
        <h2 class="card-title h4">@lang('Payment Collection')</h2>
    </div>
    <div class="card-body">
        <label class="row form-check form-switch mb-4" for="paymentCollectionSwitch">
                <span class="col-8 col-sm-9 ms-0">
                  <span class="d-block text-dark">@lang('Direct Payment Received')</span>
                  <span
                      class="d-block fs-5">@lang('If you disable direct payments, vendors will no longer receive payments directly; instead, all payments will be routed through the admin.')</span>
                </span>
            <span class="col-4 col-sm-3 text-end">
                 <input type="hidden" name="payment_collection" value="0">
                  <input type="checkbox" class="form-check-input" name="payment_collection" id="paymentCollectionSwitch" value="1" {{ $user->payment_collection_system == 1 ? 'checked' : '' }}>
                </span>
        </label>
    </div>
</div>

@push('script')
    <script>
        $(document).ready(function () {
            $('#paymentCollectionSwitch').on('change', function () {
                let isChecked = $(this).is(':checked') ? 1 : 0;
                let userId = '{{ $user->id }}';

                $.ajax({
                    url: '{{ route('admin.user.set.payment.collection') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        payment_collection: isChecked,
                        user_id: userId
                    },
                    success: function (response) {
                        Notiflix.Notify.success(response.message);
                    },
                    error: function (xhr) {
                        Notiflix.Notify.failure('Failed to update payment collection. Please try again.');
                    }
                });
            });
        });
    </script>
@endpush
