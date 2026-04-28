@extends(template().'layouts.user')
@section('page_title',__('Packages'))
@section('content')
    <div class="content container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <x-page-header menu="Packages" :statBtn="true"/>

                <div class="row d-none" id="statsSection">
                    <div class="col-sm-6 col-lg-3 mb-3 mb-lg-4">
                        <div class="card card-hover-shadow h-100 stats-card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <h6 class="card-subtitle mb-3">@lang('Active')</h6>
                                        <h3 class="card-title js-counter" data-value="{{ $count['active'] }}">{{ $count['active'] }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $count['total'] }}</span>
                                            <span class="badge bg-soft-success text-success ms-2">
                                        <i class="bi-graph-up"></i> {{ $percent['active'] }}%
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
                                        <h6 class="card-subtitle mb-3">@lang('Pending')</h6>
                                        <h3 class="card-title js-counter" data-value="{{ $count['inactive'] }}">{{ $count['inactive'] }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $count['total'] }}</span>
                                            <span class="badge bg-soft-danger text-danger ms-2">
                                        <i class="bi-graph-up"></i> {{ $percent['inactive'] }}%
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
                                        <h6 class="card-subtitle mb-3">@lang('This Week')</h6>
                                        <h3 class="card-title js-counter" data-value="{{ $count['this_week'] }}">{{ $count['this_week'] }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $count['total'] }}</span>
                                            <span class="badge bg-soft-primary text-primary ms-2">
                                        <i class="bi-graph-up"></i> {{ $percent['this_week'] }}%
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
                                        <h6 class="card-subtitle mb-3">@lang('This Month')</h6>
                                        <h3 class="card-title js-counter" data-value="{{ $count['this_month'] }}">{{ $count['this_month'] }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $count['total'] }}</span>
                                            <span class="badge bg-soft-info text-info ms-2">
                                        <i class="bi-graph-up"></i> {{ $percent['this_month'] }}%
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
                </div>

                <div class="card">
                    <div class="card-header card-header-content-md-between">
                        <div class="mb-2 mb-md-0">
                            <div class="input-group input-group-merge input-group-flush">
                                <div class="input-group-prepend input-group-text">
                                    <i class="bi-search"></i>
                                </div>
                                <input id="datatableSearch" type="search" class="form-control" placeholder="@lang('Search Packages')"
                                       aria-label="@lang('Search Packages')" autocomplete="off">
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
                                                    <span class="text-cap text-body">@lang("Package")</span>
                                                    <input type="text" class="form-control" id="name"
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
                                                                    data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-warning"></span>Pending</span>'>
                                                                @lang('Pending')
                                                            </option>
                                                            <option value="1"
                                                                    data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-success"></span>Accepted</span>'>
                                                                @lang('Accepted')
                                                            </option>
                                                            <option value="2"
                                                                    data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-info"></span>Resubmitted</span>'>
                                                                @lang('Resubmitted')
                                                            </option>
                                                            <option value="3"
                                                                    data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-dark"></span>Holded</span>'>
                                                                @lang('Holded')
                                                            </option>
                                                            <option value="4"
                                                                    data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-soft-danger"></span>Soft Rejected</span>'>
                                                                @lang('Soft Rejected')
                                                            </option>
                                                            <option value="5"
                                                                    data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-danger"></span>Hard Rejected</span>'>
                                                                @lang('Hard Rejected')
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
                        </div>
                    </div>

                    <div class=" table-responsive datatable-custom  ">
                        <table id="datatable"
                               class="js-datatable table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                               data-hs-datatables-options='{
                                   "columnDefs": [{
                                      "targets": [0, 5],
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
                                <th>@lang('Package')</th>
                                <th>@lang('Category')</th>
                                <th>@lang('Destination')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Created at')</th>
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
                        <p>@lang("Do you want to delete this Package")</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-primary">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="discountModal" tabindex="-1" role="dialog" aria-labelledby="discountModalLabel"
         data-bs-backdrop="static"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="discountModalLabel"><i class="bi bi-check2-square"></i> @lang('Confirmation')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="post" class="setdiscountRoute">
                    @csrf
                    @method('post')

                    <div class="modal-body">
                        <div class="row">
                            <div class="row">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="pt-1">
                                            <label class="form-label" for="discount_type">@lang('Discount Type')</label>
                                            <select class="form-control js-select" id="discount_type" name="discount_type">
                                                <option value="" disabled selected>@lang('Select Type')</option>
                                                <option value="0" {{ old('discount_type') == '0' ? 'selected' : '' }}>@lang('Percentage')</option>
                                                <option value="1" {{ old('discount_type') == '1' ? 'selected' : '' }}>@lang('Amount')</option>
                                            </select>
                                            @error('discount_type')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="pt-3">
                                            <label class="form-label">@lang('Discount Amount')</label>
                                            <input type="text" name="amount" class="form-control" value="{{ old('amount') }}" placeholder="e.g 50" />
                                            @error('amount')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-primary">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="featuredModal" tabindex="-1" role="dialog" aria-labelledby="featuredModalLabel"
         data-bs-backdrop="static"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="featuredModalLabel"><i
                            class="bi bi-check2-square"></i> @lang('Confirmation')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="get" class="setFeaturedRoute">
                    @method('get')
                    <div class="modal-body">
                        <p>@lang('Are you sure you want to set this item as a featured item. ')</p>
                    </div>

                    <input type="hidden" name="package_id" value="" id="package_id" />
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-primary" name="confirm" value="1">@lang('Confirm')</button>
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
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-primary delete-multiple">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" data-bs-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="viewModalLabel">
                        <div class="mb-2">
                            <i class="bi-geo-alt"></i>
                            <span class="packageTitle pe-2">@lang('Package Details')</span>
                            <span class="status"></span>
                        </div>
                        <span class="comment pt-2"></span>
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="activity-alert mb-3"></div>
                    <div class="package-info">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('style')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset(template(true).'css/flatpickr.min.css') }}">
    <style>
        .page-header{
            padding-bottom: 16px !important;
        }
        .package-info .package-title {
            font-size: 2rem;
            color: #007bff;
            margin-bottom: 10px;
        }

        .package-info .price {
            font-size: 1.25rem;
            font-weight: bold;
            color: #28a745;
        }

        .package-info .section-title {
            font-size: 1.25rem;
            margin-top: 20px;
            font-weight: bold;
        }

        .package-info p {
            font-size: 1rem;
            line-height: 1.5;
        }

        .info-box {
            display: flex;
            align-items: center;
            background: #f1f3f5;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .info-box i {
            font-size: 1.5rem;
            color: #007bff;
            margin-right: 10px;
        }

        .info-box p {
            font-size: 1rem;
            margin: 0;
        }

        .facilities-list {
            list-style-type: none;
            padding: 0;
        }

        .facilities-list li {
            background: #f1f3f5;
            padding: 8px;
            margin: 5px 0;
            border-radius: 5px;
            font-size: 1rem;
        }
        .expected-item {
            margin-bottom: 20px;
        }

        .expected-item .card {
            background-color: #f7f7f7;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }

        .expected-item .card-body {
            padding: 15px;
        }

        .expected-item .expect-title {
            font-size: 1.1rem;
            font-weight: bold;
            color: #333;
        }

        .expected-item .expect-detail {
            font-size: 0.9rem;
            color: #666;
            margin-top: 10px;
        }
        .expected-item .card:hover {
            background-color: #e9ecef;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .custom-warning {
            background-color: #fff3cd;
            color: #856404;
            font-size: 0.875rem;
            border-radius: 0.25rem;
            padding: 0.5rem 0.75rem;
            display: flex;
            align-items: center;
        }
    </style>
@endpush


@push('script')
    <script src="{{ asset('assets/admin/js/hs-file-attach.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/select.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/appear.min.js') }}"></script>
    <script src="{{ asset("assets/admin/js/hs-counter.min.js") }}"></script>
    <script src="{{ asset('assets/global/js/flatpickr.js') }}"></script>

    <script>

        $(document).on('click', '.deleteBtn', function () {
            let route = $(this).data('route');
            $('.setRoute').attr('action', route);
        })

        $(document).on('click', '.featuredBtn', function () {
            let route = $(this).data('route');
            let package_id = $(this).data('package_id');

            $('.setFeaturedRoute').attr('action', route);
            $('#package_id').val(package_id);
        })
        $(document).on('click', '.discountBtn', function () {
            let route = $(this).data('route');
            let type = $(this).data('discount_type');
            let amount = $(this).data('discount_amount');

            $('.setdiscountRoute').attr('action', route);
            $('#discount_type').val(type);
            $('input[name="amount"]').val(amount);
        });
        $(document).on('ready', function () {
            new HSCounter('.js-counter')
            new HSFileAttach('.js-file-attach')
            HSCore.components.HSTomSelect.init('.js-select', {
                maxOptions: 250,
                placeholder: 'Select Type'

            })
            HSCore.components.HSFlatpickr.init('.js-flatpickr')

            HSCore.components.HSDatatables.init($('#datatable'), {
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: "{{ route("user.all.package.search", ['guideCode' => $guide]) }}",
                },
                columns: [
                    {data: 'checkbox', name: 'checkbox'},
                    {data: 'package', name: 'package'},
                    {data: 'category', name: 'category'},
                    {data: 'destination', name: 'destination'},
                    {data: 'status', name: 'status'},
                    {data: 'create-at', name: 'created-at'},
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
                } else {
                    $('#datatableCounterInfo').addClass('d-none');
                }
            });

            $(document).on('click', '#filter_button', function () {

                let filterName = $('#name').val();
                let filterDate = $('#filter_date_range').val();
                let filterStatus = $('#filter_status').val();

                const datatable = HSCore.components.HSDatatables.getItem(0);

                datatable.ajax.url("{{ route('admin.all.package.search') }}" +
                    "?filterName=" + encodeURIComponent(filterName) +
                    "&filterDate=" + encodeURIComponent(filterDate) +
                    "&filterStatus=" + encodeURIComponent(filterStatus)).load();
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
                    url: "{{ route('user.package.delete.multiple') }}",
                    data: {strIds: strIds},
                    datatType: 'json',
                    type: "post",
                    success: function (data) {
                        location.reload();
                    },
                });
            });
        });
        $(document).on('click', '.viewBtn', function (e) {
            const itemData = JSON.parse(this.getAttribute('data-item'));
            const thumb = this.getAttribute('data-thumbImage');

            const amenity_data = JSON.parse(this.getAttribute('data-amenity_data'));
            const modalBody = document.querySelector('#viewModal .modal-body');
            const currency = '{{ basicControl()->currency_symbol }}'
            const activity = JSON.parse(this.getAttribute('data-activity'));

            const alertHtml = activity?.description
                ? `<div class="custom-warning mb-0">
                        <i class="bi bi-chat-left-text me-2"></i>
                        <div>${activity.description}</div>
                   </div>`
                : '';

            $('.comment').html(alertHtml);


            let amenityList = '';
            if (Array.isArray(amenity_data) && amenity_data.length > 0) {
                amenityList = amenity_data.map(amenity => `<li>${amenity.title}</li>`).join('');
            } else {
                amenityList = '<li>No amenities available</li>';
            }

            let timeSlotList = '';
            if (Array.isArray(itemData.timeSlot) && itemData.timeSlot.length > 0) {
                timeSlotList = itemData.timeSlot.map(time => `<li>${time}</li>`).join('');
            } else {
                timeSlotList = '<li>No time slots available</li>';
            }

            let placeList = '';
            if (Array.isArray(itemData.places) && itemData.places.length > 0) {
                placeList = itemData.places.map(place => `<li>${place}</li>`).join('');
            } else {
                placeList = '<li>No places available</li>';
            }

            let guidesList = '';
            if (Array.isArray(itemData.guides) && itemData.guides.length > 0) {
                guidesList = itemData.guides.map(guide => `<li>${guide}</li>`).join('');
            } else {
                guidesList = '<li>No guides available</li>';
            }

            let expectedSection = '';
            if (Array.isArray(itemData.expected) && itemData.expected.length > 0) {
                expectedSection = itemData.expected.map(item => `
                    <div class="col-md-3 expected-item">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="expect-title">${item.expect}</h6>
                                <p class="expect-detail">${item.expect_detail}</p>
                            </div>
                        </div>
                    </div>
                `).join('');
            } else {
                expectedSection = `
                    <div class="col-md-12">
                        <h5 class="section-title">Expected</h5>
                        <p>No expected items available</p>
                    </div>
                `;
            }

            let facilitiesSection = '';

            if (Array.isArray(itemData.facility) && itemData.facility.length > 0) {
                facilitiesSection = `
                    <div class="col-md-6">
                        <h5 class="section-title">@lang('Facilities')</h5>
                        <ul class="facilities-list">
                            ${itemData.facility.map(facility => `<li>${facility}</li>`).join('')}
                        </ul>
                    </div>
                `;
            } else {
                facilitiesSection = `
                    <div class="col-md-6">
                        <h5 class="section-title">@lang('Facilities')</h5>
                        <ul class="facilities-list">
                            <li>No facilities available</li>
                        </ul>
                    </div>
                `;
            }

            let excludedSection = '';
            if (Array.isArray(itemData.excluded) && itemData.excluded.length > 0) {
                excludedSection = `
                    <div class="col-md-6">
                        <h5 class="section-title">@lang('Excluded')</h5>
                        <ul class="facilities-list">
                            ${itemData.excluded.map(excluded => `<li>${excluded}</li>`).join('')}
                        </ul>
                    </div>
                `;
            } else {
                excludedSection = `
                    <div class="col-md-6">
                        <h5 class="section-title">@lang('Excluded')</h5>
                        <ul class="facilities-list">
                            <li>No exclusions available</li>
                        </ul>
                    </div>
                `;
            }

            const statusMap = {
                0: { text: 'Pending', badgeClass: 'bg-warning' },
                1: { text: 'Accepted', badgeClass: 'bg-success' },
                2: { text: 'Resubmitted', badgeClass: 'bg-info' },
                3: { text: 'Holded', badgeClass: 'bg-secondary' },
                4: { text: 'Soft Rejected', badgeClass: 'bg-danger' },
                5: { text: 'Hard Rejected', badgeClass: 'bg-dark' }
            };
            const status = statusMap[itemData.status] || { text: 'Unknown Status', badgeClass: 'bg-light' };
            const modalHeader = document.querySelector('#viewModal .modal-header .status');
            $('.packageTitle').text(itemData.title)


            modalHeader.innerHTML = `<span class="badge ${status.badgeClass}">${status.text}</span>`;
            const originalPrice = currency + itemData.adult_price;
            let priceContent = `<p class="price"><strong>@lang('Price'):</strong> ${originalPrice}</p>`;

            if (itemData.discount_amount > 0) {
                const discountedPrice = (itemData.adult_price - itemData.discount_amount).toFixed(2);
                priceContent = `
                    <p class="price">
                        <strong>@lang('Price'):</strong>
                        <span class="text-decoration-line-through pe-2">${originalPrice}</span>
                        ${currency}${discountedPrice} <span class="badge bg-success">-${currency}${itemData.discount_amount}</span>
                    </p>
                `;
            }


            const modalContent = `
                <div class="package-info">
                    <div class="row">
                        <div class="col-12 text-center pb-4">
                            ${thumb ? `<img src="${thumb}" alt="${itemData.title}" class="img-fluid">` : ''}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            ${priceContent}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <h5 class="section-title">@lang('Description')</h5>
                            <p>${itemData.description}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box">
                                <i class="bi-geo-alt"></i>
                                <p><strong>Destination:</strong> ${itemData.destination.title}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box">
                                <i class="bi-clock"></i>
                                <p><strong>Duration:</strong> ${itemData.duration}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        ${facilitiesSection}
                        ${excludedSection}
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="section-title">@lang('Amenities')</h5>
                            <ul class="amenity-list">
                                ${amenityList}
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5 class="section-title">@lang('Time Slots')</h5>
                            <ul class="time-slot-list">
                                ${timeSlotList}
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="section-title">@lang('Places')</h5>
                            <ul class="place-list">
                                ${placeList}
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5 class="section-title">@lang('Guides')</h5>
                            <ul class="guide-list">
                                ${guidesList}
                            </ul>
                        </div>
                    </div>
                    <div class="row">
                        <h5 class="section-title">@lang('Tour Plans')</h5>
                        ${expectedSection}
                    </div>
                </div>
            `;

            modalBody.innerHTML = modalContent;
        });

    </script>

@endpush



