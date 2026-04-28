@extends(template().'layouts.user')
@section('page_title',trans('Payment History'))
@section('content')
    <div class="content container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <x-page-header menu="Payment History" :statBtn="true"/>

                <div class="row d-none" id="statsSection">
                    <div class="col-sm-6 col-lg-3 mb-3 mb-lg-4">
                        <div class="card card-hover-shadow h-100 stats-card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <h6 class="card-subtitle mb-3">@lang('Today Created')</h6>
                                        <h3 class="card-title js-counter" data-value="{{ $stats->today  }}">{{ $stats->today }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $stats->total }}</span>
                                            <span class="badge bg-soft-success text-success ms-2">
                                                <i class="bi-graph-up"></i> {{ $percentages['today'] }}%
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
                                        <h6 class="card-subtitle mb-3">@lang('This Week Created')</h6>
                                        <h3 class="card-title js-counter" data-value="{{ $stats->this_week  }}">{{ $stats->this_week }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $stats->total }}</span>
                                            <span class="badge bg-soft-info text-info ms-2">
                                                <i class="bi-graph-up"></i> {{ $percentages['this_week'] }}%
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
                                        <h6 class="card-subtitle mb-3">@lang('This Month Created')</h6>
                                        <h3 class="card-title js-counter" data-value="{{ $stats->this_month  }}">{{ $stats->this_month }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $stats->total }}</span>
                                            <span class="badge bg-soft-secondary text-secondary ms-2">
                                                <i class="bi-graph-up"></i> {{ $percentages['this_month'] }}%
                                            </span>
                                        </div>
                                    </div>
                                    <span class="icon icon-soft-secondary icon-lg icon-circle ms-3">
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
                                        <h6 class="card-subtitle mb-3">@lang('This Year Created')</h6>
                                        <h3 class="card-title js-counter" data-value="{{ $stats->this_year  }}">{{ $stats->this_year }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $stats->this_year }}</span>
                                            <span class="badge bg-soft-primary text-primary ms-2">
                                                <i class="bi-graph-up"></i> {{ $percentages['this_year'] }}%
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
                                       placeholder="@lang('Search logs')"
                                       aria-label="@lang('Search logs')"
                                       autocomplete="off">
                            </div>
                        </div>

                        <div class="d-grid d-sm-flex justify-content-md-end align-items-sm-center gap-2">
                            <div class="dropdown">
                                <button class="btn btn-white btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                                    <i class="bi-filter me-1"></i> @lang('Filter')
                                </button>

                                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                                    <div class="offcanvas-header">
                                        <h5 id="offcanvasRightLabel">@lang('Filter')</h5>
                                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                    </div>
                                    <div class="offcanvas-body">
                                        <form id="filter_form">
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
                                                        <button type="button" class="btn btn-primary"
                                                                id="filter_button"><i
                                                                class="bi-search"></i> @lang('Apply')
                                                        </button>
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
                                          "targets": [0, 6],
                                          "orderable": false
                                        }],
                                       "order": [],
                                       "info": {
                                         "totalQty": "#datatableWithPaginationInfoTotalQty"
                                       },
                                       "search": "#datatableSearch",
                                       "entries": "#datatableEntries",
                                       "pageLength": 10,
                                       "isResponsive": false,
                                       "isShowPaging": false,
                                       "pagination": "datatablePagination"
                                     }'>
                            <thead class="thead-light">
                            <tr>
                                <th>@lang('Serial')</th>
                                <th>@lang('Transaction ID')</th>
                                <th>@lang('Method')</th>
                                <th>@lang('Amount')</th>
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
                        <div
                            class="row justify-content-center justify-content-sm-between align-items-sm-center">
                            <div class="col-sm mb-2 mb-sm-0">
                                <div
                                    class="d-flex justify-content-center justify-content-sm-start align-items-center">
                                    <span class="me-2">@lang('Showing:')</span>
                                    <div class="tom-select-custom">
                                        <select id="datatableEntries"
                                                class="js-select form-select form-select-borderless w-auto"
                                                autocomplete="off"
                                                data-hs-tom-select-options='{
                                                        "searchInDropdown": false,
                                                        "hideSearch": true
                                                      }'>
                                            <option value="5">5</option>
                                            <option value="10" selected>10</option>
                                            <option value="15">15</option>
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


    <div class="modal fade" id="detailModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="staticBackdropLabel">@lang("Payment Information")</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <ul class="list-group mb-4 payment_information">
                            </ul>
                            <label>@lang('Admin Feedback')</label>
                            <textarea class="form-control" id="feedBack"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
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
    <script src="{{ asset('assets/admin/js/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/select.min.js') }}"></script>
    <script>
        $(document).on("click", '.bookingView', function (e) {
            $('.payment_information').html('');
            $('#feedBack').text('');

            let details = $(this).data('details_info');
            let feedback = $(this).data('feedback');

            if (details) {
                let list = Object.entries(details).map(([key, value]) => {
                    let field_name = value.field_name;
                    let field_value = value.field_value;
                    let field_name_text = field_name.replace(/_/g, ' ');

                    if (value.type === 'file') {
                        return `<li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-capitalize">${field_name_text}</span>
                                <a href="${field_value}" target="_blank">
                                    <img src="${field_value}" alt="${field_name_text}" class="rounded-1" width="100">
                                </a>
                            </div>
                        </li>`;
                    } else {
                        return `<li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-capitalize">${field_name_text}</span>
                                <span>${field_value}</span>
                            </div>
                        </li>`;
                    }
                });

                $('.payment_information').html(list.join(''));
            }

            $('#feedBack').text(feedback);
        });

        $(document).ready(function() {
            $('[data-bs-toggle="tooltip"]').tooltip();

            HSCore.components.HSFlatpickr.init('.js-flatpickr')
            HSCore.components.HSTomSelect.init('.js-select', {
                maxOptions: 250,
            })

            HSCore.components.HSDatatables.init($('#datatable'), {
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: "{{ route("user.fund.index.search") }}",
                },
                columns: [
                    {data: 'serial', name: 'serial'},
                    {data: 'trx_id', name: 'trx_id'},
                    {data: 'method', name: 'method'},
                    {data: 'amount', name: 'amount'},
                    {data: 'status', name: 'status'},
                    {data: 'date', name: 'date'},
                    {data: 'action', name: 'action'},
                ],
                language: {
                    zeroRecords: `<div class="text-center p-4">
                    <img class="dataTables-image mb-3 no_image_size" src="{{ asset('assets/admin/img/oc-error.svg') }}" alt="Image Description" data-hs-theme-appearance="default">
                    <img class="dataTables-image mb-3 no_image_size" src="{{ asset('assets/admin/img/oc-error-light.svg') }}" alt="Image Description" data-hs-theme-appearance="dark">
                    <p class="mb-0">No data to show</p>
                    </div>`,
                    processing: `<div><div></div><div></div><div></div><div></div></div>`
                },
            });

            document.getElementById("filter_button").addEventListener("click", function () {
                let filterTransactionId = $('#transaction_id_filter_input').val();
                let filterDate = $('#filter_date_range').val();

                const datatable = HSCore.components.HSDatatables.getItem(0);
                datatable.ajax.url("{{ route('user.fund.index.search') }}" + "?filterTransactionID=" + filterTransactionId + "&filterDate=" + filterDate).load();
            });

            document.getElementById("clear_filter").addEventListener("click", function () {
                document.getElementById("filter_form").reset();
            });


            $.fn.dataTable.ext.errMode = 'throw';
        });


    </script>
@endpush

