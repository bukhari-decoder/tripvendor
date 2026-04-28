<div class="modal fade" id="refundModal" tabindex="-1" role="dialog" aria-labelledby="refundModallLabel"
     data-bs-backdrop="static"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="refundModalLabel"><i
                        class="bi bi-check2-square"></i> @lang("Confirmation")</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="get" class="refundRoute">

                <div class="modal-body">
                    <p>@lang("Do you want to refund this Package Booking Data?")</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary btn-sm">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="Confirm" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form role="form" method="POST" class="actionRoute" action="" enctype="multipart/form-data">
                @csrf
                @method('post')

                <div class="modal-body">
                    <div class="text-center mb-5">
                        <h3 class="mb-1">@lang('Booking Information')</h3>
                    </div>

                    <div class="row mb-6">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <small class="text-cap text-secondary mb-0">@lang('Transaction Id:')</small>
                            <span class="text-dark transaction_id"></span>
                        </div>

                        <div class="col-md-4 mb-3 mb-md-0">
                            <small class="text-cap text-secondary mb-0">@lang('Amount paid:')</small>
                            <h5 class="text-dark amount"></h5>
                        </div>

                        <div class="col-md-4 mb-3 mb-md-0">
                            <small class="text-cap text-secondary mb-0">@lang('Date paid:')</small>
                            <span class="text-dark date"></span>
                        </div>
                    </div>
                    <div class="modal-footer-text mt-2">
                        <div class="d-flex justify-content-end gap-3 status-buttons">
                            <button type="button" class="btn btn-white btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                            <input type="hidden" class="action_id" name="id">
                            <button type="submit" class="btn btn-success btn-sm" name="status" value="2">@lang('Completed')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="CompletedMultipleModal" tabindex="-1" role="dialog" aria-labelledby="CompletedMultipleModalLabel" data-bs-backdrop="static"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="CompletedMultipleModalLabel"><i
                        class="fa-light fa-square-check"></i> @lang('Confirmation')</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post">
                @csrf
                <div class="modal-body">
                    @lang('Do you want to make completed all selected data?')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary complete-multiple btn-sm">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="refundMultipleModal" tabindex="-1" role="dialog" aria-labelledby="refundMultipleModalLabel" data-bs-backdrop="static"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="refundMultipleModalLabel"><i
                        class="fa-light fa-square-check"></i> @lang('Confirmation')</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" class="setInactiveRoute" method="post">
                @csrf
                <div class="modal-body">
                    @lang('Do you want to make refunded all selected data?')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary refund-multiple btn-sm">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <form role="form" method="GET" class="acceptedBookingRoute" action="" enctype="multipart/form-data">

                <div class="modal-body">
                    <div class="text-center mb-5">
                        <h3 class="mb-1">@lang('Booking Information')</h3>
                    </div>

                    <div class="row mb-6">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <small class="text-cap text-secondary mb-0">@lang('Transaction Id:')</small>
                            <span class="text-dark booking_transaction_id"></span>
                        </div>

                        <div class="col-md-4 mb-3 mb-md-0">
                            <small class="text-cap text-secondary mb-0">@lang('Amount paid:')</small>
                            <h5 class="text-dark booking_amount"></h5>
                        </div>

                        <div class="col-md-4 mb-3 mb-md-0">
                            <small class="text-cap text-secondary mb-0">@lang('Date paid:')</small>
                            <span class="text-dark booking_date"></span>
                        </div>
                    </div>
                    <div class="modal-footer-text mt-2">
                        <div class="d-flex justify-content-end gap-3 status-buttons">
                            <button type="button" class="btn btn-white btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                            <input type="hidden" class="booking_action_id" name="id">
                            <button type="submit" class="btn btn-success btn-sm" name="status" value="2">@lang('Approve')</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
    <script>

        $(document).on('click', '.refundBtn', function () {
            let route = $(this).data('route');
            $('.refundRoute').attr('action', route);
        })

        $(document).on("click", '.actionBtn', function (e) {
            let id = $(this).data('id');
            let amount = $(this).data('amount');
            let date = $(this).data('paid_date');
            let transactionID = $(this).data('trx_id');
            let route = $(this).data('action');

            $('.transaction_id').html(transactionID);
            $('.amount').html(amount);
            $('.date').html(date);
            $(".action_id").val(id);
            $(".actionRoute").attr('action', route);
        });
        $(document).on("click", '.approveButton', function (e) {
            let id = $(this).data('id');
            let amount = $(this).data('amount');
            let date = $(this).data('paid_date');
            let transactionID = $(this).data('trx_id');
            let route = $(this).data('route');

            $('.booking_transaction_id').html(transactionID);
            $('.booking_amount').html(amount);
            $('.booking_date').html(date);
            $(".booking_action_id").val(id);
            $(".acceptedBookingRoute").attr('action', route);
        });

    </script>
@endpush
