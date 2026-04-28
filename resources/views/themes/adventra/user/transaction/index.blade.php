@extends(template().'layouts.user')
@section('page_title',__('Transaction'))
@section('content')
    <div class="content container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <x-page-header menu="Transactions" :statBtn="true"/>

                <div class="row d-none" id="statsSection">
                    <div class="col-sm-6 col-lg-3 mb-3 mb-lg-4">
                        <div class="card card-hover-shadow h-100 stats-card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <h6 class="card-subtitle mb-3">@lang("Today's")</h6>
                                        <h3 class="card-title js-counter" data-value="{{ $totals->today }}">{{ $totals->today }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $totals->total }}</span>
                                            <span class="badge bg-soft-success text-success ms-2">
                                                <i class="bi-graph-up"></i> {{ number_format($percentages['today'], 2) }}%
                                            </span>
                                        </div>
                                    </div>
                                    <span class="icon icon-soft-success icon-lg icon-circle ms-3">
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
                                        <h6 class="card-subtitle mb-3">@lang("This Week's")</h6>
                                        <h3 class="card-title js-counter" data-value="{{ $totals->this_week }}">{{ $totals->this_week }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $totals->total }}</span>
                                            <span class="badge bg-soft-info text-info ms-2">
                                                <i class="bi-graph-up"></i> {{ number_format($percentages['this_week'], 2) }}%
                                            </span>
                                        </div>
                                    </div>
                                    <span class="icon icon-soft-info icon-lg icon-circle ms-3">
                                        <i class="bi-calendar-week fs-1"></i>
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
                                        <h6 class="card-subtitle mb-3">@lang("This Month's")</h6>
                                        <h3 class="card-title js-counter" data-value="{{ $totals->this_month }}">{{ $totals->this_month }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $totals->total }}</span>
                                            <span class="badge bg-soft-primary text-primary ms-2">
                                                <i class="bi-graph-up"></i> {{ number_format($percentages['this_month'], 2) }}%
                                            </span>
                                        </div>
                                    </div>
                                    <span class="icon icon-soft-primary icon-lg icon-circle ms-3">
                                        <i class="bi-calendar-month fs-1"></i>
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
                                        <h6 class="card-subtitle mb-3">@lang("This Year's")</h6>
                                        <h3 class="card-title js-counter" data-value="{{ $totals->this_year }}">{{ $totals->this_year }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $totals->total }}</span>
                                            <span class="badge bg-soft-secondary text-secondary ms-2">
                                                <i class="bi-graph-up"></i> {{ number_format($percentages['this_year'], 2) }}%
                                            </span>
                                        </div>
                                    </div>
                                    <span class="icon icon-soft-secondary icon-lg icon-circle ms-3">
                                        <i class="bi-calendar3 fs-1"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header card-header-content-md-between">
                        <div class="mb-2 mb-md-0">

                            <div class="input-group input-group-merge navbar-input-group">
                                <div class="input-group-prepend input-group-text">
                                    <i class="bi-search"></i>
                                </div>
                                <input type="search" id="datatableSearch"
                                       class="search form-control form-control-sm"
                                       placeholder="@lang('Search transaction')"
                                       aria-label="@lang('Search transaction')"
                                       autocomplete="off">
                                <a class="input-group-append input-group-text display-none" href="javascript:void(0)">
                                    <i id="clearSearchResultsIcon" class="bi-x"></i>
                                </a>
                            </div>

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
                                            <div class="mb-4">
                                                                    <span
                                                                        class="text-cap text-body">@lang('Transaction ID')</span>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <input type="text" class="form-control"
                                                               id="transaction_id_filter_input"
                                                               autocomplete="off">
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
                                                        <button type="button" id="clear_filter" class="btn btn-white">@lang('Clear Filters')</button>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="d-grid">
                                                        <button type="button" class="btn btn-primary" id="filter_button">     <i class="bi-search"></i> @lang('Apply')</button>
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
                                  "targets": [0, 4],
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
                                <th>@lang('Transaction Id')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Charge')</th>
                                <th>@lang('Remarks')</th>
                                <th>@lang('Date-time')</th>
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
                                    <!-- Select -->
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
                                    <span class="text-secondary me-2">@lang('of')</span>
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
        </div>
    </div>
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/flatpickr.min.css') }}">
@endpush

@push('script')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/flatpickr.min.js') }}"></script>

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
                    url: "{{ route("user.transaction.search") }}",
                },

                columns: [
                    {data: 'trx', name: 'trx'},
                    {data: 'amount', name: 'amount'},
                    {data: 'charge', name: 'charge'},
                    {data: 'remarks', name: 'remarks'},
                    {data: 'date-time', name: 'date-time'},
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
                let filterDate = $('#filter_date_range').val();
                const datatable = HSCore.components.HSDatatables.getItem(0);
                datatable.ajax.url("{{ route('user.transaction.search') }}" + "?filterTransactionID=" + filterTransactionId +
                    "&filterDate=" + filterDate).load();
            });
            $.fn.dataTable.ext.errMode = 'throw';
        });

    </script>
@endpush




