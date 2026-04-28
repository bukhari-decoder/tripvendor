<div class="col-md-5 col-lg-5 mb-3 mb-lg-5">
    <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title m-0 me-2">@lang('Latest Transactions')</h5>
            <div class="dropdown">
                <span class="p-0" type="button" id="transactionID" data-bs-toggle="dropdown">
                    <i class="bi-three-dots-vertical"></i>
                </span>
                <div class="dropdown-menu dropdown-menu-end" >
                    <a class="dropdown-item" href="{{ route('user.fund.index') }}">@lang('View all transactions')</a>
                </div>
            </div>
        </div>
        <div class="card-body pt-4">
            <ul class="p-0 m-0">
                @forelse($transactions ?? [] as $item)
                    <li class="d-flex align-items-center mb-6">
                        <div class="icon icon-md  flex-shrink-0 me-3
                        {{ ($item->trx_type == '+') ? 'icon-soft-success' : 'icon-soft-danger' }} ">
                            <i class="{{ ($item->trx_type == '+') ? 'bi-box-arrow-in-down' : 'bi-arrow-left-right' }}"></i>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                                <div class="d-block">{{ $item->type }}</div>
                                <h6 class="fw-normal mb-0">{{ Str::limit($item->remarks, 25) }}</h6>
                            </div>
                            <div class="user-progress d-flex align-items-center gap-2">
                                <h4 class="fw-normal mb-0">{{ $item->trx_type }}{{ $item->amount }}</h4>
                                <span class="text-body-secondary">{{ basicControl()->currency_symbol }}</span>
                            </div>
                        </div>
                    </li>
                @empty
                    <div class="no-data-content">
                        <div class="no-data-image mb-4">
                            <img src="{{ asset('assets/global/img/oc-error-light.svg') }}" alt="No Data" class="img-fluid noDataImage">
                        </div>
                        <h2 class="no-data-title mb-3">@lang('No Data Found')</h2>
                        <p class="no-data-text mb-4">@lang("We couldn't find what you're looking for. Please try again later or modify your search.")</p>
                    </div>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@push('style')
    <style>
        .no-data-content{
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .no-data-content img{
            height: 100px;
        }
    </style>
@endpush
