@extends(template().'layouts.user')
@section('page_title',trans('Tour History'))
@section('content')

    <div class="content container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <x-page-header menu="Tour History" :statBtn="true"/>
                <div class="row d-none" id="statsSection">
                    <div class="col-sm-6 col-lg-3 mb-3 mb-lg-4">
                        <div class="card card-hover-shadow h-100 stats-card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <h6 class="card-subtitle mb-3">@lang('Completed')</h6>
                                        <h3 class="card-title js-counter" data-value="{{ $count->completed_count }}">{{ $count->completed_count }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $count->total_count }}</span>
                                            <span class="badge bg-soft-success text-success ms-2">
                                                <i class="bi-graph-up"></i> {{ number_format($completedPercentage, 2)  }}%
                                            </span>
                                        </div>
                                    </div>
                                    <span class="icon icon-soft-success icon-lg icon-circle ms-3">
                                        <i class="bi-wallet2 fs-1"></i>
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
                                        <h6 class="card-subtitle mb-3">@lang('Refunded')</h6>
                                        <h3 class="card-title js-counter" data-value="{{ $count->refunded_count }}">{{ $count->refunded_count }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $count->total_count }}</span>
                                            <span class="badge bg-soft-primary text-primary ms-2">
                                                <i class="bi-graph-up"></i> {{ number_format($refundedPercentage, 2)  }}%
                                            </span>
                                        </div>
                                    </div>
                                    <span class="icon icon-soft-primary icon-lg icon-circle ms-3">
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
                                        <h6 class="card-subtitle mb-3">@lang('Pending')</h6>
                                        <h3 class="card-title js-counter" data-value="{{ $count->pending_count}}">{{ $count->pending_count }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $count->total_count }}</span>
                                            <span class="badge bg-soft-warning text-warning ms-2">
                                        <i class="bi-graph-up"></i> {{ number_format($pendingPercentage, 2) }}%
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
                                        <h6 class="card-subtitle mb-3">@lang('Inactive')</h6>
                                        <h3 class="card-title js-counter" data-value="{{ $count->expired_count }}">{{ $count->expired_count }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $count->total_count }}</span>
                                            <span class="badge bg-soft-danger text-danger ms-2">
                                        <i class="bi-graph-up"></i> {{ number_format($expiredPercentage, 2) }}%
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
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header card-header-content-md-between">
                                <div class="mb-2 mb-md-0">
                                    <div class="input-group input-group-merge navbar-input-group">
                                        <div class="input-group-prepend input-group-text">
                                            <i class="bi-search"></i>
                                        </div>
                                        <input type="search" id="datatableSearch"
                                               class="search form-control form-control-sm"
                                               placeholder="@lang('Search history')"
                                               aria-label="@lang('Search history')"
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
                                                <h5 id="offcanvasRightLabel"><i class="bi-search me-1"></i> @lang('Filter')</h5>
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
                                                    <div class="mb-4">
                                                        <span class="text-cap text-body">@lang('Package Title')</span>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <input type="text" class="form-control"
                                                                       id="package_title_filter_input"
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
                                        <th>@lang('Transaction ID')</th>
                                        <th>@lang('Package')</th>
                                        <th>@lang('Paid Amount')</th>
                                        <th>@lang('Total Person')</th>
                                        <th>@lang('Tour Date')</th>
                                        <th>@lang('Status')</th>
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
        </div>
    </div>
    <div class="modal fade" id="viewInformation" tabindex="-1" aria-labelledby="viewInformationLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-top-cover bg-secondary text-center">
                    <figure class="position-absolute end-0 bottom-0 start-0">
                        <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 1920 100.1">
                            <path fill="#fff" d="M0,0c0,0,934.4,93.4,1920,0v100.1H0L0,0z"></path>
                        </svg>
                    </figure>
                    <div class="modal-header">
                        <h5 class="modal-title text-white" id="viewInformationLabel">@lang('Booking Information')</h5>
                    </div>
                    <div class="modal-close d-flex align-items-center justify-content-between">
                        <button type="button" class="btn-close btn-close-light" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <th scope="row">@lang('Package Title: ')</th>
                            <td><a href="#" target="_blank" id="modal-package-title"></a></td>
                        </tr>
                        <tr>
                            <th scope="row">@lang('Date:')</th>
                            <td id="modal-date"></td>
                        </tr>
                        <tr>
                            <th scope="row">@lang('Start Price:')</th>
                            <td id="modal-start-price"></td>
                        </tr>
                        <tr>
                            <th scope="row">@lang('Total Adults:')</th>
                            <td id="modal-total-adult"></td>
                        </tr>
                        <tr>
                            <th scope="row">@lang('Total Children:')</th>
                            <td id="modal-total-children"></td>
                        </tr>
                        <tr>
                            <th scope="row">@lang('Total Infants:')</th>
                            <td id="modal-total-infant"></td>
                        </tr>
                        <tr>
                            <th scope="row">@lang('Total:')</th>
                            <td id="modal-total-person"></td>
                        </tr>
                        <tr>
                            <th scope="row">@lang('Total Price:')</th>
                            <td id="modal-total-price"></td>
                        </tr>
                        <tr>
                            <th scope="row">@lang('Transaction ID:')</th>
                            <td id="modal-trx-id"></td>
                        </tr>
                        <tr>
                            <th scope="row">@lang('Status:')</th>
                            <td id="modal-status"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/flatpickr.min.css') }}">
    <style>
        .modal-content {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            color: white;
            padding: 15px;
        }

        .table-striped > tbody > tr:nth-of-type(odd) > * {
            --bs-table-accent-bg: none !important;
        }

        .modal-header .btn-close {
            color: white;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            padding: 15px;
            border-top: 1px solid #e5e5e5;
        }

        .modal-footer .btn {
            border-radius: 5px;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: bold;
        }

        table tbody tr:last-child {
            border-bottom: 0px solid white;
        }

        #viewInformation {
            backdrop-filter: blur(10px);
        }

        .modal-body p {
            font-size: 1rem;
            margin: 10px 0;
        }

        .packageButton {
            padding: 5px 8px;
        }

        .pointer {
            cursor: pointer;
        }
    </style>
@endpush
@push('script')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/select.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('[data-bs-toggle="tooltip"]').tooltip();

            $(document).on('click', '.bookingView', function () {
                let $this = $(this);

                $('#modal-package-title')
                    .text($this.data('title'))
                    .attr('href', $this.data('package'));

                $('#modal-date').text($this.data('date'));
                $('#modal-start-price').text($this.data('start_price'));
                $('#modal-total-adult').text(formatPersonLabel($this.data('total_adult')));
                $('#modal-total-children').text(formatPersonLabel($this.data('total_children')));
                $('#modal-total-infant').text(formatPersonLabel($this.data('total_infant')));
                $('#modal-total-person').text(formatPersonLabel($this.data('total_person')));
                $('#modal-total-price').text($this.data('total_price'));
                $('#modal-trx-id').text($this.data('trx_id'));

                let statusBadge = '';
                const status = parseInt($this.data('status'));
                const dateText = $this.data('date');
                const today = new Date();
                const packageDate = new Date(dateText);

                today.setHours(0, 0, 0, 0);
                packageDate.setHours(0, 0, 0, 0);
                if ((status !== 2 && status !== 4) && packageDate < today) {
                    statusBadge = '<span class="badge text-bg-danger">@lang("Expired")</span>';
                } else if (status === 1) {
                    statusBadge = '<span class="badge text-bg-secondary">@lang("Tour Pending")</span>';
                } else if (status === 2) {
                    statusBadge = '<span class="badge text-bg-success">@lang("Completed")</span>';
                }else if (status === 3) {
                    statusBadge = '<span class="badge text-bg-danger">@lang("Rejected")</span>';
                } else if (status === 4) {
                    statusBadge = '<span class="badge text-bg-info">@lang("Refunded")</span>';
                } else if (status === 5) {
                    statusBadge = '<span class="badge text-bg-warning text-light">@lang("Pending")</span>';
                } else {
                    statusBadge = '<span class="badge text-bg-danger">@lang("Expired")</span>';
                }

                $('#modal-status').html(statusBadge);
                $('#viewInformation').modal('show');
            });

            HSCore.components.HSFlatpickr.init('.js-flatpickr')
            HSCore.components.HSTomSelect.init('.js-select', {
                maxOptions: 250,
            })

            HSCore.components.HSDatatables.init($('#datatable'), {
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: "{{ route("user.booking.list.search") }}",
                },
                columns: [
                    {data: 'booking_id', name: 'booking_id'},
                    {data: 'package', name: 'package'},
                    {data: 'amount', name: 'amount'},
                    {data: 'person', name: 'person'},
                    {data: 'date-time', name: 'date-time'},
                    {data: 'status', name: 'status'},
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
                let filterPackageTitle = $('#package_title_filter_input').val();
                let filterDate = $('#filter_date_range').val();

                const datatable = HSCore.components.HSDatatables.getItem(0);
                datatable.ajax.url("{{ route('user.booking.list.search') }}" + "?filterTransactionID=" + filterTransactionId + "&filterDate=" + filterDate+ "&filterPackageTitle=" + filterPackageTitle).load();
            });

            document.getElementById("clear_filter").addEventListener("click", function () {
                document.getElementById("filter_form").reset();
            });


            $.fn.dataTable.ext.errMode = 'throw';
        });
        function formatPersonLabel(count) {
            return `${count} ${count == 1 ? 'person' : 'persons'}`;
        }

    </script>
@endpush
