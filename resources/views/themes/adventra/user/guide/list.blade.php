@extends(template().'layouts.user')
@section('page_title',trans('Tour Guides'))
@section('content')
    <div class="content container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <x-page-header menu="Guides" :statBtn="true"/>

                <div class="row d-none" id="statsSection">
                    <div class="col-sm-6 col-lg-3 mb-3 mb-lg-4">
                        <div class="card card-hover-shadow h-100 stats-card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <h6 class="card-subtitle mb-3">@lang('Active')</h6>
                                        <h3 class="card-title js-counter" data-value="{{ $count['active_count'] }}">{{ $count['active_count'] }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $count['total'] }}</span>
                                            <span class="badge bg-soft-success text-success ms-2">
                                        <i class="bi-graph-up"></i> {{ $count['active_percent'] }}%
                                    </span>
                                        </div>
                                    </div>
                                    <span class="icon icon-soft-success icon-lg icon-circle ms-3">
                                <i class="bi-check2-circle fs-1"></i>
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
                                        <h3 class="card-title js-counter" data-value="{{ $count['inactive_count'] }}">{{ $count['inactive_count'] }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $count['total'] }}</span>
                                            <span class="badge bg-soft-danger text-danger ms-2">
                                        <i class="bi-graph-up"></i> {{ $count['inactive_percent'] }}%
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
                    <div class="col-sm-6 col-lg-3 mb-3 mb-lg-4">
                        <div class="card card-hover-shadow h-100 stats-card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <h6 class="card-subtitle mb-3">@lang('This Month')</h6>
                                        <h3 class="card-title js-counter" data-value="{{ $count['this_month_count'] }}">{{ $count['this_month_count'] }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $count['total'] }}</span>
                                            <span class="badge bg-soft-info text-info ms-2">
                                        <i class="bi-graph-up"></i> {{ $count['this_month_percent'] }}%
                                    </span>
                                        </div>
                                    </div>
                                    <span class="icon icon-soft-info icon-lg icon-circle ms-3">
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
                                        <h6 class="card-subtitle mb-3">@lang('This Year')</h6>
                                        <h3 class="card-title js-counter" data-value="{{ $count['this_year_count'] }}">{{ $count['this_year_count'] }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $count['total'] }}</span>
                                            <span class="badge bg-soft-primary text-primary ms-2">
                                        <i class="bi-graph-up"></i> {{ $count['this_year_percent'] }}%
                                    </span>
                                        </div>
                                    </div>
                                    <span class="icon icon-soft-primary icon-lg icon-circle ms-3">
                                      <i class="bi-calendar3 fs-1"></i>
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
                                               placeholder="@lang('Search guides')"
                                               aria-label="@lang('Search guides')"
                                               autocomplete="off">
                                    </div>
                                </div>

                                <div class="d-grid d-sm-flex justify-content-md-end align-items-sm-center gap-2">
                                    <a class="btn btn-primary btn-sm" href="{{ route('user.guide.add') }}"><i class="bi bi-plus-circle pe-1"></i>@lang('Add New')</a>

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
                                                        <span class="text-cap text-body">@lang('Name')</span>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <input type="text" class="form-control"
                                                                       id="name_filter_input"
                                                                       autocomplete="off">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <small class="text-cap text-body">@lang("Status")</small>
                                                    <div class="tom-select-custom mb-4">
                                                        <select
                                                            class="js-select js-datatable-filter form-select form-select-sm"
                                                            id="select_status"
                                                            data-target-column-index="4" data-hs-tom-select-options='{
                                                                  "placeholder": "Any status",
                                                                  "searchInDropdown": false,
                                                                  "hideSearch": true,
                                                                  "dropdownWidth": "10rem"
                                                                }'>
                                                            <option value="all"
                                                                    data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-secondary"></span>@lang("All Status")</span>'>
                                                                @lang("All Status")
                                                            </option>
                                                            <option value="0"
                                                                    data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-danger"></span>Inactive</span>'>
                                                                @lang("Inactive")
                                                            </option>
                                                            <option value="1"
                                                                    data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-success"></span>Active</span>'>
                                                                @lang("Active")
                                                            </option>
                                                        </select>
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
                                          "targets": [0, 9],
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
                                        <th>@lang('Name')</th>
                                        <th>@lang('Code')</th>
                                        <th>@lang('Email')</th>
                                        <th>@lang('Phone')</th>
                                        <th>@lang('Experience')</th>
                                        <th>@lang('Tour Completed')</th>
                                        <th>@lang('rating')</th>
                                        <th>@lang('Status')</th>
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
        </div>
    </div>
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
         data-bs-backdrop="static"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="deleteModalLabel"><i
                            class="bi bi-check2-square"></i> @lang("Confirmation")</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post" class="setRoute">
                    @csrf
                    @method("delete")
                    <div class="modal-body">
                        <p>@lang("Do you want to delete this Guide")</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-primary">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel"
         data-bs-backdrop="static"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="statusModalLabel"><i
                            class="bi bi-check2-square"></i> @lang('Confirmation')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="get" class="setStatusRoute">
                    @method('get')
                    <div class="modal-body">
                        <p>@lang('Are you sure you want to change the status of this item? This action cannot be undone and will affect the current status of the item.')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-primary">@lang('Confirm')</button>
                    </div>
                </form>
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

            $(document).on('click', '.statusBtn', function () {
                let route = $(this).data('route');
                $('.setStatusRoute').attr('action', route);
            })
            $(document).on('click', '.deleteBtn', function () {
                let route = $(this).data('route');
                $('.setRoute').attr('action', route);
            })

            HSCore.components.HSFlatpickr.init('.js-flatpickr')
            HSCore.components.HSTomSelect.init('.js-select', {
                maxOptions: 250,
            })

            HSCore.components.HSDatatables.init($('#datatable'), {
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: "{{ route("user.all.guides.search") }}",
                },
                columns: [
                    {data: 'serial', name: 'serial'},
                    {data: 'name', name: 'name'},
                    {data: 'code', name: 'code'},
                    {data: 'email', name: 'email'},
                    {data: 'phone', name: 'phone'},
                    {data: 'experience', name: 'experience'},
                    {data: 'tour_completed', name: 'tour_completed'},
                    {data: 'rating', name: 'rating'},
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
                let filtername = $('#name_filter_input').val();
                let filterStatus = $('#select_status').val();

                const datatable = HSCore.components.HSDatatables.getItem(0);
                datatable.ajax.url("{{ route('user.all.guides.search') }}" + "?filtername=" + filtername + "&filterStatus=" + filterStatus).load();
            });

            document.getElementById("clear_filter").addEventListener("click", function () {
                document.getElementById("filter_form").reset();
            });


            $.fn.dataTable.ext.errMode = 'throw';
        });


    </script>
@endpush
