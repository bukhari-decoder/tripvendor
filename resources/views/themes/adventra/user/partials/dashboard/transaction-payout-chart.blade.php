<div class="row">
    <div class="col-12 col-xxl-8 order-2 order-md-3 order-xxl-2 mb-6">
        <div class="card" data-block="chartOne">
            <div class="row row-bordered g-0">
                <div class="col-lg-8">
                    <div class="card-header border-bottom-0 d-flex align-items-center justify-content-between">
                        <div class="card-title mb-0">
                            <h4 class="m-0 me-2">@lang('Transfer & Request Summary')</h4>
                        </div>
                    </div>
                    <div id="totalRevenueChart" class="px-3"></div>
                </div>
                <div class="col-lg-4">
                    <div class="card-body px-xl-9 py-12 d-flex align-items-center flex-column">

                        <div id="growthChart" class="my-6"></div>

                        <div class="d-flex gap-5 justify-content-between">
                            <div class="d-flex gap-2">
                                <span class="icon icon-md icon-soft-primary icon-square">
                                    <i class="bi-send-check fs-2"></i>
                                </span>
                                <div class="d-flex flex-column">
                                    <small>@lang('Transfer')</small>
                                    <h6 class="mb-0" id="transferAmount"></h6>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <span class="icon icon-md icon-soft-info icon-square">
                                    <i class="bi-box-arrow-in-down fs-2"></i>
                                </span>
                                <div class="d-flex flex-column">
                                    <small>@lang('Request')</small>
                                    <h6 class="mb-0" id="requestAmount"></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-8 col-lg-12 col-xxl-4 order-3 order-md-2">
        <div class="row ">
            <div class="col-md-6 col-sm-12 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between mb-5">
                            <div class="avatar avatar-circle flex-shrink-0">
                                <img src="{{ asset('assets/user/img/payment.png') }}" alt="..." class="avatar-img avatar-circle"/>
                            </div>
                        </div>
                        <p class="mb-1 fs-2 mt-3">@lang('Payments')</p>
                        <h4 class="card-title mb-1">{{ $payments ?? 0 }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12 mb-4">
                <div class="card h-100" data-block="chartWeek">
                    <div class="card-body pb-0">
                        <span class="d-block fw-medium mb-3">@lang('Daily Transaction')</span>
                        <div id="revenueChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-4">
                <div class="card" data-block="transferChart">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                            <div>
                                <h6 class="mb-1">@lang('Transfer Report')</h6>
                                <span class="badge bg-warning mb-3">@lang('YEAR') {{ date('Y') }}</span>
                                <div class="mt-3 mb-2">
                                    <span class="text-success fw-semibold d-block mb-1">@lang('Amount')</span>
                                    <h4 class="mb-0">{{ currencyPosition($transfer_this_year ?? 0) }}</h4>
                                </div>
                            </div>
                            <div id="transferReportChart" class=" w-100 w-md-auto float-end mb-0 mt-5"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
