@extends('admin.layouts.app')
@section('page_title',__('Package Category'))
@section('content')
    <div class="content container-fluid">
        <x-page-header menu="Categories" :statBtn="true"/>

        <div class="row d-none" id="statsSection">
            <div class="col-sm-6 col-lg-3 mb-3 mb-lg-4">
                <div class="card card-hover-shadow h-100 stats-card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <h6 class="card-subtitle mb-3">@lang('Active')</h6>
                                <h3 class="card-title js-counter" data-value="{{ $totalActivePackageCategory }}">{{ $totalActivePackageCategory }}</h3>
                                <div class="d-flex align-items-center">
                                    <span class="d-block fs-6">@lang('from') {{ $totalPackageCategory }}</span>
                                    <span class="badge bg-soft-success text-success ms-2">
                                        <i class="bi-graph-up"></i> {{ number_format($totalActivePercentageCategory, 2) }}%
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
                                <h3 class="card-title js-counter" data-value="{{ $totalInactivePackageCategory }}">{{ $totalInactivePackageCategory }}</h3>
                                <div class="d-flex align-items-center">
                                    <span class="d-block fs-6">@lang('from') {{ $totalPackageCategory }}</span>
                                    <span class="badge bg-soft-danger text-danger ms-2">
                                        <i class="bi-graph-up"></i> {{ number_format($totalInactivePercentageCategory, 2) }}%
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
                                <h6 class="card-subtitle mb-3">@lang("Today's Created")</h6>
                                <h3 class="card-title js-counter" data-value="{{ $totalCreatedToday }}">{{ $totalCreatedToday }}</h3>
                                <div class="d-flex align-items-center">
                                    <span class="d-block fs-6">@lang('from') {{ $totalPackageCategory }}</span>
                                    <span class="badge bg-soft-primary text-primary ms-2">
                                        <i class="bi-graph-up"></i> {{ number_format($totalTotalCreatedTodayPercentageCategory, 2) }}%
                                    </span>
                                </div>
                            </div>
                            <span class="icon icon-soft-primary icon-lg icon-circle ms-3">
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
                                <h6 class="card-subtitle mb-3">@lang("This Month's Created")</h6>
                                <h3 class="card-title js-counter" data-value="{{ $totalCreatedThisMonth }}">{{ $totalCreatedThisMonth }}</h3>
                                <div class="d-flex align-items-center">
                                    <span class="d-block fs-6">@lang('from') {{ $totalPackageCategory }}</span>
                                    <span class="badge bg-soft-info text-info ms-2">
                                        <i class="bi-graph-up"></i> {{ number_format($totalTotalCreatedThisMonthPercentageCategory, 2) }}%
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
        </div>

        <!-- Card -->
        <div class="card">
            <div class="card-header card-header-content-md-between">
                <div class="mb-2 mb-md-0">
                    <div class="input-group input-group-merge input-group-flush">
                        <div class="input-group-prepend input-group-text">
                            <i class="bi-search"></i>
                        </div>
                        <input id="datatableSearch" type="search" class="form-control" placeholder="@lang('Search Categories')"
                               aria-label="@lang('Search Categories')" autocomplete="off">
                    </div>
                </div>

                <div class="d-grid d-sm-flex justify-content-md-end align-items-sm-center gap-2">
                    <div id="datatableCounterInfo">
                        <div class="d-flex align-items-center">
                            <span class="fs-5 me-3">
                              <span id="datatableCounter">0</span>
                              @lang('Selected')
                            </span>
                            <a class="btn btn-outline-danger btn-sm" href="javascript:void(0)" data-bs-toggle="modal"
                               data-bs-target="#DeleteMultipleModal">
                                <i class="bi-trash"></i> @lang('Delete')
                            </a>
                        </div>
                    </div>
                    <div id="datatableCounterInfo2" class="d-none">
                        <div class="d-flex align-items-center">
                            <a class="btn btn-outline-primary btn-sm inactiveButton" id="inactiveButton" href="javascript:void(0)" data-bs-toggle="modal"
                               data-bs-target="#inactiveMultipleModal">
                                <i class="fal fa-gauge me-2"></i>@lang('Status Change')
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
                                            <span class="text-cap text-body">@lang("Category Name")</span>
                                            <input type="text" class="form-control" id="username_filter_input"
                                                   autocomplete="off">
                                        </div>
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
                                                    <option value="0"
                                                            data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-warning"></span>Inactive</span>'>
                                                        @lang('Inactive')
                                                    </option>
                                                    <option value="1"
                                                            data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-success"></span>Active</span>'>
                                                        @lang('Active')
                                                    </option>
                                                </select>
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

                    <a class="btn btn-primary btn-sm" href="{{ route('admin.package.category.add') }}">
                        <i class="bi-plus-circle me-1"></i> @lang('Add New')
                    </a>
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
                        <th class="table-column-pe-0">
                            <div class="form-check">
                                <input class="form-check-input check-all tic-check" type="checkbox" name="check-all"
                                       id="datatableCheckAll">
                                <label class="form-check-label" for="datatableCheckAll"></label>
                            </div>
                        </th>
                        <th>@lang('Categories')</th>
                        <th>@lang('Packages')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Create At')</th>
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
                        <button type="button" class="btn btn-white btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-primary btn-sm">@lang('Confirm')</button>
                    </div>
                </form>
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
                        <p>@lang("Do you want to delete this Package Type ?")</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-primary btn-sm">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="DeleteMultipleModal" tabindex="-1" role="dialog" aria-labelledby="DeleteMultipleModalLabel" data-bs-backdrop="static"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="DeleteMultipleModalLabel"><i
                                class="fa-light fa-square-check"></i> @lang('Confirmation')</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post">
                    @csrf
                    <div class="modal-body">
                        @lang('Do you want to delete all selected data?')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-primary btn-sm delete-multiple">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="inactiveMultipleModal" tabindex="-1" role="dialog" aria-labelledby="inactiveMultipleModalLabel" data-bs-backdrop="static"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="inactiveMultipleModalLabel"><i
                                class="fa-light fa-square-check"></i> @lang('Confirmation')</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" class="setInactiveRoute" method="post">
                    @csrf
                    <div class="modal-body">
                        @lang('Do you want to inactive all selected data?')
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-primary btn-sm inactive-multiple">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/flatpickr.min.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/js/hs-file-attach.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/select.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/appear.min.js') }}"></script>
    <script src="{{ asset("assets/admin/js/hs-counter.min.js") }}"></script>
    <script src="{{ asset('assets/global/js/flatpickr.js') }}"></script>
@endpush

@push('script')
    <script>
        HSCore.components.HSFlatpickr.init('.js-flatpickr')
        $(document).on('click', '.deleteBtn', function () {
            let route = $(this).data('route');
            $('.setRoute').attr('action', route);
        })
        $(document).on('click', '.statusBtn', function () {
            let route = $(this).data('route');
            $('.setStatusRoute').attr('action', route);
        })
        $(document).on('ready', function () {
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
                    url: "{{ route("admin.all.package.category.search") }}",
                },
                columns: [
                    {data: 'checkbox', name: 'checkbox'},
                    {data: 'name', name: 'name'},
                    {data: 'packages', name: 'packages'},
                    {data: 'status', name: 'status'},
                    {data: 'create-at', name: 'create-at'},
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
            $(document).on('click', '#filter_button', function () {
                let filterName = $('#username_filter_input').val();
                let filterDate = $('#filter_date_range').val();
                let filterStatus = $('#filter_status').val();

                const datatable = HSCore.components.HSDatatables.getItem(0);

                let url = "{{ route('admin.all.package.category.search') }}";
                url += "?filterName=" + encodeURIComponent(filterName);
                url += "&filterDate=" + encodeURIComponent(filterDate);
                url += "&filterStatus=" + encodeURIComponent(filterStatus);

                datatable.ajax.url(url).load();
            });

            $.fn.dataTable.ext.errMode = 'throw';


            $(document).on('click', '#datatableCheckAll', function () {
                $('input:checkbox').not(this).prop('checked', this.checked);
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
                    url: "{{ route('admin.package.category.delete.multiple') }}",
                    data: {strIds: strIds},
                    datatType: 'json',
                    type: "post",
                    success: function (data) {
                        if (data.success) {
                            Notiflix.Notify.success('Package Categories have been deleted successfully.');
                            location.reload();
                        }
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        Notiflix.Notify.failure('An error occurred: ' + xhr.responseText);
                    }
                });
            });
            $(document).on('click', '.inactive-multiple', function (e) {
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
                    url: "{{ route('admin.packageCategory.inactiveMultiple') }}",
                    data: {strIds: strIds},
                    datatType: 'json',
                    type: "post",
                    success: function (data) {
                        location.reload();
                    },
                });
            });
        });

    </script>

@endpush



