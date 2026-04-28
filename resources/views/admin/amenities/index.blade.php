@extends('admin.layouts.app')
@section('page_title', __('Amenities'))
@section('content')
    <div class="content container-fluid">

        <x-page-header menu="Amenities" :statBtn="true"/>

        <div class="row d-none" id="statsSection">
            <div class="col-sm-6 col-lg-3 mb-3 mb-lg-4">
                <div class="card card-hover-shadow h-100 stats-card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <h6 class="card-subtitle mb-3">@lang('Active')</h6>
                                <h3 class="card-title js-counter" data-value="{{ $amenityStats->active }}">{{ $amenityStats->active }}</h3>
                                <div class="d-flex align-items-center">
                                    <span class="d-block fs-6">@lang('from') {{ $amenityStats->total }}</span>
                                    <span class="badge bg-soft-success text-success ms-2">
                                        <i class="bi-graph-up"></i> {{ $percentages['active'] }}%
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
                                <h3 class="card-title js-counter" data-value="{{ $amenityStats->inactive }}">{{ $amenityStats->inactive }}</h3>
                                <div class="d-flex align-items-center">
                                    <span class="d-block fs-6">@lang('from') {{ $amenityStats->total }}</span>
                                    <span class="badge bg-soft-danger text-danger ms-2">
                                        <i class="bi-graph-up"></i> {{ $percentages['inactive'] }}%
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
                                <h6 class="card-subtitle mb-3">@lang("This Month Created")</h6>
                                <h3 class="card-title js-counter" data-value="{{ $amenityStats->this_month }}">{{ $amenityStats->this_month }}</h3>
                                <div class="d-flex align-items-center">
                                    <span class="d-block fs-6">@lang('from') {{ $amenityStats->total }}</span>
                                    <span class="badge bg-soft-primary text-primary ms-2">
                                        <i class="bi-graph-up"></i> {{ $percentages['this_month'] }}%
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
                                <h6 class="card-subtitle mb-3">@lang("This Month's Created")</h6>
                                <h3 class="card-title js-counter" data-value="{{ $amenityStats->this_year }}">{{ $amenityStats->this_year }}</h3>
                                <div class="d-flex align-items-center">
                                    <span class="d-block fs-6">@lang('from') {{ $amenityStats->total }}</span>
                                    <span class="badge bg-soft-info text-info ms-2">
                                        <i class="bi-graph-up"></i> {{ $percentages['this_year'] }}%
                                    </span>
                                </div>
                            </div>
                            <span class="icon icon-soft-info icon-lg icon-circle ms-3">
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
                    <div class="input-group input-group-merge input-group-flush">
                        <div class="input-group-prepend input-group-text">
                            <i class="bi-search"></i>
                        </div>
                        <input id="datatableSearch" type="search" class="form-control" placeholder="amenities title"
                               aria-label="Search amenities" autocomplete="off">
                    </div>
                </div>

                <div class="d-grid d-sm-flex justify-content-md-end align-items-sm-center gap-2">
                    <div id="datatableCounterInfo2" class="d-none">
                        <div class="d-flex align-items-center">
                                    <span class="fs-5 me-3">
                                      <span id="datatableCounter">0</span>
                                      @lang('Selected')
                                    </span>
                            <a class="btn btn-outline-danger btn-sm deleteBtn" href="javascript:void(0)"
                               data-bs-toggle="modal"
                               data-bs-target="#deleteMultiple">
                                <i class="bi-trash"></i> @lang('Delete')
                            </a>
                        </div>
                    </div>
                    <div id="datatableCounterInfo">
                        <div class="d-flex align-items-center">
                            <a class="btn btn-outline-info btn-sm" href="javascript:void(0)" data-bs-toggle="modal"
                               data-bs-target="#multipleStatusChangeModal">
                                <i class="bi-arrow-bar-right"></i> @lang('Change Status')
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
                                        <div class="col-12 mb-4">
                                            <span class="text-cap text-body">@lang("Amenities Title")</span>
                                            <input type="text" class="form-control" id="filter_input" placeholder="Name" autocomplete="off">
                                        </div>

                                        <small class="text-cap text-body">@lang("Amenity Type")</small>
                                        <div class="tom-select-custom mb-4">
                                            <select
                                                class="js-select js-datatable-filter form-select form-select-sm"
                                                id="select_type"
                                                data-target-column-index="4" data-hs-tom-select-options='{
                                                          "placeholder": "Any Type",
                                                          "searchInDropdown": false,
                                                          "hideSearch": true,
                                                          "dropdownWidth": "10rem"
                                                        }'>
                                                <option value="all"
                                                        data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-secondary"></span>@lang("All Type")</span>'>
                                                    @lang("All Type")
                                                </option>
                                                <option value="amenity"
                                                        data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-warning"></span>Amenity</span>'>
                                                    @lang("Amenity")
                                                </option>
                                                <option value="favourites"
                                                        data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-info"></span>Favourites</span>'>
                                                    @lang("Favourites")
                                                </option>
                                                <option value="safety_item"
                                                        data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-primary"></span>Safety Item</span>'>
                                                    @lang("Safety Item")
                                                </option>
                                            </select>
                                        </div>

                                        <small class="text-cap text-body">@lang("Amenity Status")</small>
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
                                                        data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-warning"></span>Inactive</span>'>
                                                    @lang("Inactive")
                                                </option>
                                                <option value="1"
                                                        data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-info"></span>Active</span>'>
                                                    @lang("Active")
                                                </option>
                                            </select>
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

                    <div class="dropdown">
                        <a href="{{ route('admin.amenity.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle pe-1"></i>@lang("Add New")</a>
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
                           "pageLength": 15,
                           "isResponsive": false,
                           "isShowPaging": false,
                           "pagination": "datatablePagination"
                         }'>
                    <thead class="thead-light">
                    <tr>
                        <th class="table-column-pe-0">
                            <div class="form-check">
                                <input class="form-check-input check-all tic-check" type="checkbox" name="check-all" id="datatableCheckAll">
                                <label class="form-check-label" for="datatableCheckAll"></label>
                            </div>
                        </th>
                        <th scope="col">@lang('Title')</th>
                        <th scope="col">@lang('Type')</th>
                        <th scope="col">@lang('Total Property')</th>
                        <th scope="col">@lang('Status')</th>
                        <th scope="col">@lang('Created At')</th>
                        <th scope="col">@lang('Action')</th>
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
                                    <option value="15">15</option>
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
    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel"
         data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="statusModalLabel">
                        <i class="bi bi-check2-square"></i> {{ __('Confirmation') }}
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" class="setStatusRoute">
                    @csrf
                    @method('POST')
                    <div class="modal-body">
                        <p>{{ __('Are you sure you want to change the status of this item? This action cannot be undone and will affect the current status of the item.') }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white btn-sm" data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary btn-sm">{{ __('Confirm') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="multipleStatusChangeModal" tabindex="-1" role="dialog" aria-labelledby="multipleStatusChangeModalLabel"
         data-bs-backdrop="static"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="multipleStatusChangeModalLabel"><i
                            class="bi bi-check2-square"></i> @lang('Confirmation')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="get" class="setMultipleStatusRoute">
                    @method('get')
                    <div class="modal-body">
                        <p>@lang('Are you sure you want to change the status of all of selected item? This action cannot be undone and will affect the current status of all selected item.')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-primary btn-sm statusMultiple">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
         data-bs-backdrop="static"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="deleteModalLabel"><i class="bi bi-check2-square"></i> @lang("Confirmation")</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post" class="setRoute">
                    @csrf
                    @method("delete")
                    <div class="modal-body">
                        <p>@lang("Do you want to delete this Amenity?")</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-primary btn-sm">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Delete Modal -->

    <div class="modal fade" id="deleteMultiple" tabindex="-1" role="dialog" aria-labelledby="deleteMultipleLabel" data-bs-backdrop="static"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="deleteMultipleLabel"><i class="fa-light fa-square-check"></i> @lang('Confirmation')</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post">
                    @csrf
                    <div class="modal-body">
                        @lang('Do you want to delete all selected amenity data?')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-primary btn-sm delete-multiple">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/flatpickr.min.css') }}">
    <link href="{{ asset(template(true) . 'css/font-awesome.css') }}" rel="stylesheet">
@endpush


@push('js-lib')
    <script src="{{ asset('assets/admin/js/hs-file-attach.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/select.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/appear.min.js') }}"></script>
    <script src="{{ asset("assets/admin/js/hs-counter.min.js") }}"></script>
    <script src="{{ asset('assets/admin/js/flatpickr.min.js') }}"></script>
@endpush

@push('script')
    @if ($errors->any())
        @php
            $collection = collect($errors->all());
            $errors = $collection->unique();
        @endphp
        <script>
            "use strict";
            @foreach ($errors as $error)
            Notiflix.Notify.failure("{{ trans($error) }}");
            @endforeach
        </script>
    @endif
    <script>
        $(document).on('ready', function () {

            $(document).on('click', '.deleteSingleBtn', function () {
                let route = $(this).data('route');
                $('.setRoute').attr('action', route);
            });

            $(document).on('click', '.statusBtn', function () {
                let route = $(this).data('route');
                $('.setStatusRoute').attr('action', route);
            });

            HSCore.components.HSFlatpickr.init('.js-flatpickr')
            new HSCounter('.js-counter')
            new HSFileAttach('.js-file-attach')
            HSCore.components.HSTomSelect.init('.js-select', {
                maxOptions: 250,
            })

            HSCore.components.HSDatatables.init($('#datatable'), {
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: "{{ route("admin.all.amenity.search") }}",
                },
                columns: [
                    {data: 'checkbox', name: 'checkbox'},
                    {data: 'title', name: 'title'},
                    {data: 'type', name: 'type'},
                    {data: 'property', name: 'property'},
                    {data: 'status', name: 'status'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action'},
                ],
                select: {
                    style: 'multi',
                    selector: 'td:first-child input[type="checkbox"]',
                    classMap: {
                        checkAll: '#datatableCheckAll',
                        counter: '#datatableCounter',
                        counterInfo: '#datatableCounterInfo'
                    }
                },
                language: {
                    zeroRecords: `<div class="text-center p-4">
                    <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error.svg') }}" alt="Image Description" data-hs-theme-appearance="default">
                    <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error-light.svg') }}" alt="Image Description" data-hs-theme-appearance="dark">
                    <p class="mb-0">No data to show</p>
                    </div>`,
                    processing: `<div><div></div><div></div><div></div><div></div></div>`
                },
            })


            $(document).on('click', '#filter_button', function () {
                let filterStatus = $('#select_status').val();
                let filterType = $('#select_type').val();
                let filterSearch = $('#filter_input').val();
                let filterDate = $('#filter_date_range').val();

                const datatable = HSCore.components.HSDatatables.getItem(0);
                datatable.ajax.url("{{ route('admin.all.amenity.search') }}" + "?filterStatus=" + filterStatus +
                    "&filterSearch=" + filterSearch + "&filterStartDate=" + "&filterDate=" + filterDate+ "&filterType=" + filterType).load();
            });

            $.fn.dataTable.ext.errMode = 'throw';

            $('#datatable').on('select.dt deselect.dt', function () {
                var selectedRows = $('#datatable').DataTable().rows({ selected: true }).count();

                if (selectedRows > 0) {
                    $('#datatableCounterInfo').removeClass('d-none');
                    $('#datatableCounterInfo2').removeClass('d-none');
                } else {
                    $('#datatableCounterInfo').addClass('d-none');
                    $('#datatableCounterInfo2').addClass('d-none');
                }
            });
            $(document).on('change', ".row-tic", function () {
                let length = $(".row-tic").length;
                let checkedLength = $(".row-tic:checked").length;
                if (length == checkedLength) {
                    $('#check-all').prop('checked', true);
                } else {
                    $('#check-all').prop('checked', false);
                }
            });

            $(document).on('click', '.statusMultiple', function (e) {
                e.preventDefault();
                let all_value = [];
                $(".row-tic:checked").each(function () {
                    all_value.push($(this).attr('data-id'));
                });
                let strIds = all_value;
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('admin.amenity.statusMultiple') }}",
                    data: {strIds: strIds},
                    datatType: 'json',
                    type: "post",
                    success: function (data) {
                        if (data.success) {
                            location.reload();
                        }
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        Notiflix.Notify.failure('An error occurred: ' + xhr.responseText);
                    }
                });
            });

            $(document).on('click', '.delete-multiple', function (e) {
                e.preventDefault();
                let all_value = [];
                $(".row-tic:checked").each(function () {
                    all_value.push($(this).attr('data-id'));
                });
                let strIds = all_value;
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('admin.amenity.deleteMultiple') }}",
                    data: {strIds: strIds},
                    datatType: 'json',
                    type: "post",
                    success: function (data) {
                        if (data.success) {
                            location.reload();
                        }
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        Notiflix.Notify.failure('An error occurred: ' + xhr.responseText);
                    }
                });
            });

        });

    </script>
@endpush



