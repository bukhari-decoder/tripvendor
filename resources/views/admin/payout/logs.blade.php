@extends('admin.layouts.app')
@section('page_title',__('Withdraw Log'))
@section('content')
    <div class="content container-fluid">
        <x-page-header menu="Withdraw Log" :statBtn="true"/>

        <div class="row d-none" id="statsSection">
            <div class="col-sm-6 col-lg-3 mb-3 mb-lg-4">
                <div class="card card-hover-shadow h-100 stats-card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <h6 class="card-subtitle mb-3">@lang("Today's")</h6>
                                <h3 class="card-title js-counter" data-value="{{ $payoutRecord[0]['todayWithdraw'] }}">{{ $payoutRecord[0]['todayWithdraw'] }}</h3>
                                <div class="d-flex align-items-center">
                                    <span class="d-block fs-6">@lang('from') {{ $payoutRecord[0]['totalWithdrawLog'] }}</span>
                                    <span class="badge bg-soft-info text-info ms-2">
                                        <i class="bi-graph-up"></i> {{ number_format($payoutRecord[0]['todayWithdrawPercentage'], 2) }}%
                                    </span>
                                </div>
                            </div>
                            <span class="icon icon-soft-info icon-lg icon-circle ms-3">
                                <i class="bi-calendar-day fs-1"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3 mb-3 mb-lg-4">
                <div class="card card-hover-shadow h-100 stats-card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <h6 class="card-subtitle mb-3">@lang("Success Withdraw")</h6>
                                <h3 class="card-title js-counter" data-value="{{ $payoutRecord[0]['successWithdraw'] }}">{{ $payoutRecord[0]['successWithdraw'] }}</h3>
                                <div class="d-flex align-items-center">
                                    <span class="d-block fs-6">@lang('from') {{ $payoutRecord[0]['totalWithdrawLog'] }}</span>
                                    <span class="badge bg-soft-success text-success ms-2">
                                        <i class="bi-graph-up"></i> {{ number_format($payoutRecord[0]['successWithdrawPercentage'], 2) }}%
                                    </span>
                                </div>
                            </div>
                            <span class="icon icon-soft-success icon-lg icon-circle ms-3">
                                <i class="bi-check-circle fs-1"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3 mb-3 mb-lg-4">
                <div class="card card-hover-shadow h-100 stats-card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <h6 class="card-subtitle mb-3">@lang("Pending Withdraw")</h6>
                                <h3 class="card-title js-counter" data-value="{{ $payoutRecord[0]['pendingWithdraw'] }}">{{ $payoutRecord[0]['pendingWithdraw'] }}</h3>
                                <div class="d-flex align-items-center">
                                    <span class="d-block fs-6">@lang('from') {{ $payoutRecord[0]['totalWithdrawLog'] }}</span>
                                    <span class="badge bg-soft-warning text-warning ms-2">
                                        <i class="bi-graph-up"></i> {{ number_format($payoutRecord[0]['pendingWithdrawPercentage'], 2) }}%
                                    </span>
                                </div>
                            </div>
                            <span class="icon icon-soft-warning icon-lg icon-circle ms-3">
                                <i class="bi-hourglass-split fs-1"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3 mb-3 mb-lg-4">
                <div class="card card-hover-shadow h-100 stats-card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <h6 class="card-subtitle mb-3">@lang("Cancel Withdraw")</h6>
                                <h3 class="card-title js-counter" data-value="{{ $payoutRecord[0]['cancelWithdraw'] }}">{{ $payoutRecord[0]['cancelWithdraw'] }}</h3>
                                <div class="d-flex align-items-center">
                                    <span class="d-block fs-6">@lang('from') {{ $payoutRecord[0]['totalWithdrawLog'] }}</span>
                                    <span class="badge bg-soft-danger text-danger ms-2">
                                        <i class="bi-graph-up"></i> {{ number_format($payoutRecord[0]['cancelWithdrawPercentage'], 2) }}%
                                    </span>
                                </div>
                            </div>
                            <span class="icon icon-soft-danger icon-lg icon-circle ms-3">
                                <i class="bi-x-circle fs-1"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" id="Test">
            <div class="card-header card-header-content-md-between">
                <div class="mb-2 mb-md-0">
                    <form>
                        <div class="input-group input-group-merge navbar-input-group">
                            <div class="input-group-prepend input-group-text">
                                <i class="bi-search"></i>
                            </div>
                            <input type="search" id="datatableSearch"
                                   class="search form-control form-control-sm"
                                   placeholder="@lang('Search withdraws')"
                                   aria-label="@lang('Search withdraws')"
                                   autocomplete="off">
                        </div>
                    </form>
                </div>

                <div class="d-grid d-sm-flex justify-content-md-end align-items-sm-center gap-2">
                    <div class="dropdown">
                        <button class="btn btn-white btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                            <i class="bi-filter me-1"></i> @lang('Filter')
                        </button>

                        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                            <div class="offcanvas-header">
                                <h5 id="offcanvasRightLabel"><i class="bi-search me-1"></i>@lang('Filter')</h5>
                                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body">
                                <form id="filter_form">
                                    <div class="row">
                                        <div class="mb-4">
                                            <span class="text-cap text-body">@lang('Transaction ID')</span>
                                            <div class="row">
                                                <div class="col-12">
                                                    <input type="text" class="form-control"
                                                           id="transaction_id_filter_input"
                                                           autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm mb-4">
                                            <small class="text-cap text-body">@lang('Status')</small>
                                            <div class="tom-select-custom">
                                                <select
                                                    class="js-select js-datatable-filter form-select form-select-sm"
                                                    id="filter_status"
                                                    data-target-column-index="4" data-hs-tom-select-options='{
                                                                  "placeholder": "Any status",
                                                                  "searchInDropdown": false,
                                                                  "hideSearch": true,
                                                                  "dropdownWidth": "10rem"
                                                                }'>
                                                    <option value="all"
                                                            data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-secondary"></span>All Status</span>'>
                                                        @lang('All Status')
                                                    </option>
                                                    <option value="1"
                                                            data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-warning"></span>Pending</span>'>
                                                        @lang('Pending')
                                                    </option>
                                                    <option value="2"
                                                            data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-success"></span>Success</span>'>
                                                        @lang('Success')
                                                    </option>
                                                    <option value="3"
                                                            data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-danger"></span>Cancel</span>'>
                                                        @lang('Cancel')
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12 mb-4">
                                            <span class="text-cap text-body">@lang('Method')</span>
                                            <div class="tom-select-custom">
                                                <select class="js-select form-select" id="filter_method">
                                                    <option value="all"
                                                            data-option-template='<span class="d-flex align-items-center"><img class="avatar avatar-xss avatar-circle me-2" src="{{ asset("assets/upload/payoutMethod/withdraw.png") }}" alt="" /><span class="text-truncate">All Withdraw Method</span></span>'>
                                                    @forelse($methods as $method)
                                                        <option value="@lang($method->id)"
                                                                data-option-template='<span class="d-flex align-items-center"><img class="avatar avatar-xss avatar-circle me-2" src="{{ getFile($method->driver, $method->logo) }}" alt="" /><span class="text-truncate">{{ $method->name }}</span></span>'>
                                                            @lang($method->name)
                                                        </option>
                                                    @empty
                                                    @endforelse
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12 mb-4">
                                            <span class="text-cap text-body">@lang('Date Range')</span>
                                            <div class="input-group mb-3 custom">
                                                <input type="text" id="filter_date_range"
                                                       class="js-flatpickr form-control"
                                                       placeholder="Select dates"
                                                       data-hs-flatpickr-options='{
                                                                 "dateFormat": "d/m/Y",
                                                                 "mode": "range"
                                                               }' aria-describedby="flatpickr_filter_date_range">
                                                <span class="input-group-text" id="flatpickr_filter_date_range">
                                                        <i class="bi bi-arrow-counterclockwise"></i>
                                                    </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row gx-2">
                                        <div class="col">
                                            <div class="d-grid">
                                                <button type="button" id="clear_filter"
                                                        class="btn btn-white">@lang('Clear Filters')</button>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="d-grid">
                                                <button type="button" class="btn btn-primary" id="filter_button"><i
                                                        class="bi-search"></i> @lang('Apply')</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class=" table-responsive datatable-custom  ">
                <table id="datatable"
                       class="js-datatable table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                       data-hs-datatables-options='{
                       "columnDefs": [{
                          "targets": [0, 8],
                          "orderable": false
                        }],
                       "order": [],
                       "info": {
                         "totalQty": "#datatableWithPaginationInfoTotalQty"
                       },
                       "search": "#datatableSearch",
                       "entries": "#datatableEntries",
                       "pageLength": 15,
                       "isResponsive": false,
                       "isShowPaging": false,
                       "pagination": "datatablePagination"
                     }'>
                    <thead class="thead-light">
                    <tr>
                        <th>@lang('No.')</th>
                        <th>@lang('Trx Number')</th>
                        <th>@lang('User')</th>
                        <th>@lang('Method')</th>
                        <th>@lang('Amount')</th>
                        <th>@lang('Charge')</th>
                        <th>@lang('Payout Amount')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Date')</th>
                        <th>@lang('Action')</th>
                    </tr>
                    </thead>

                    <tbody>

                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
                    <div class="col-sm mb-2 mb-sm-0">
                        <div class="d-flex justify-content-center justify-content-sm-start align-items-center">
                            <span class="me-2">@lang('Showing:')</span>
                            <div class="tom-select-custom">
                                <select id="datatableEntries"
                                        class="js-select form-select form-select-borderless w-auto" autocomplete="off"
                                        data-hs-tom-select-options='{
                                            "searchInDropdown": false,
                                            "hideSearch": true
                                          }'>
                                    <option value="10">10</option>
                                    <option value="15" selected>15</option>
                                    <option value="20">20</option>
                                </select>
                            </div>
                            <span class="text-secondary me-2">of</span>
                            <span id="datatableWithPaginationInfoTotalQty"></span>
                        </div>
                    </div>
                    <div class="col-sm-auto">
                        <div class="d-flex  justify-content-center justify-content-sm-end">
                            <nav id="datatablePagination" aria-label="Activity pagination"></nav>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    @include('admin.user_management.components.payout_information_modal')
@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/flatpickr.min.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/select.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/flatpickr.min.js') }}"></script>
@endpush


@push('script')
    <script>

        $(document).on('ready', function () {

            HSCore.components.HSFlatpickr.init('.js-flatpickr')
            HSCore.components.HSTomSelect.init('.js-select', {
                maxOptions: 250,
            })

            HSCore.components.HSDatatables.init($('#datatable'), {
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: "{{ route("admin.payout.search") }}",
                },

                columns: [
                    {data: 'no', name: 'no'},
                    {data: 'trx', name: 'trx'},
                    {data: 'name', name: 'name'},
                    {data: 'method', name: 'method'},
                    {data: 'amount', name: 'amount'},
                    {data: 'charge', name: 'charge'},
                    {data: 'net amount', name: 'net amount'},
                    {data: 'status', name: 'status'},
                    {data: 'date', name: 'date'},
                    {data: 'action', name: 'action'},
                ],

                language: {
                    zeroRecords: `<div class="text-center p-4">
                    <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error.svg') }}" alt="Image Description" data-hs-theme-appearance="default">
                    <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error-light.svg') }}" alt="Image Description" data-hs-theme-appearance="dark">
                    <p class="mb-0">No data to show</p>
                    </div>`,
                    processing: `<div><div></div><div></div><div></div><div></div></div>`
                },

            });

            document.getElementById("filter_button").addEventListener("click", function () {
                let filterTransactionId = $('#transaction_id_filter_input').val();
                let filterStatus = $('#filter_status').val();
                let filterMethod = $('#filter_method').val();
                let filterDate = $('#filter_date_range').val();

                const datatable = HSCore.components.HSDatatables.getItem(0);
                datatable.ajax.url("{{ route('admin.payout.search') }}" + "?filterTransactionID=" + filterTransactionId + "&filterStatus=" + filterStatus + "&filterMethod=" + filterMethod +
                    "&filterDate=" + filterDate).load();
            });

            $.fn.dataTable.ext.errMode = 'throw';

        });



    </script>

@endpush



