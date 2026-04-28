@extends(template().'layouts.user')
@section('page_title','Reviews')
@section('content')
    <div class="content container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <x-page-header menu="Reviews" :statBtn="true"/>

                <div class="row d-none" id="statsSection">
                    <div class="col-sm-6 col-lg-3 mb-3 mb-lg-4">
                        <div class="card card-hover-shadow h-100 stats-card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <h6 class="card-subtitle mb-3">@lang("Today's Created")</h6>
                                        <h3 class="card-title js-counter" data-value="{{ $stats['today_reviews'] }}">{{ $stats['today_reviews'] }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $stats['total_reviews'] }}</span>
                                            <span class="badge bg-soft-success text-success ms-2">
                                                <i class="bi-graph-up"></i> {{ number_format($todayPercentage, 2) }}%
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
                                        <h6 class="card-subtitle mb-3">@lang("This Week's created")</h6>
                                        <h3 class="card-title js-counter" data-value="{{ $stats['week_reviews'] }}">{{ $stats['week_reviews'] }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $stats['total_reviews'] }}</span>
                                            <span class="badge bg-soft-primary text-primary ms-2">
                                                <i class="bi-graph-up"></i> {{ number_format($weekPercentage  , 2) }}%
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
                                        <h6 class="card-subtitle mb-3">@lang("This Month's Created")</h6>
                                        <h3 class="card-title js-counter" data-value="{{ $stats['month_reviews '] }}">{{ $stats['month_reviews '] }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $stats['total_reviews'] }}</span>
                                            <span class="badge bg-soft-info text-info ms-2">
                                                <i class="bi-graph-up"></i> {{ number_format($monthPercentage , 2) }}%
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
                                        <h6 class="card-subtitle mb-3">@lang("This year's Created")</h6>
                                        <h3 class="card-title js-counter" data-value="{{ $stats['year_reviews'] }}">{{ $stats['year_reviews'] }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $stats['total_reviews'] }}</span>
                                            <span class="badge bg-soft-secondary text-secondary ms-2">
                                                <i class="bi-graph-up"></i> {{ number_format($yearPercentage , 2) }}%
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
                                               placeholder="@lang('Search Review')"
                                               aria-label="@lang('Search Review')"
                                               autocomplete="off">
                                        <a class="input-group-append input-group-text" href="javascript:void(0)">
                                            <i id="clearSearchResultsIcon" class="bi-x d-none"></i>
                                        </a>
                                    </div>
                                </div>

                                <div class="d-grid d-sm-flex justify-content-md-end align-items-sm-center gap-2">
                                    <div id="datatableCounterInfo">
                                        <div class="d-flex align-items-center">
                                            <span class="fs-5 me-3">
                                              <span id="datatableCounter">0</span>
                                              @lang('Selected')
                                            </span>
                                            <a class="btn btn-outline-primary btn-sm me-2" href="javascript:void(0)"
                                               data-bs-toggle="modal"
                                               data-bs-target="#MultipleStatusChange">
                                                <i class="fas fa-undo-alt"></i> @lang('Status Change')
                                            </a>
                                            <a class="btn btn-outline-danger btn-sm me-2" href="javascript:void(0)"
                                               data-bs-toggle="modal"
                                               data-bs-target="#MultipleDelete">
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
                                                        <div class="mb-4">
                                                            <span class="text-cap text-body">@lang('Package')</span>
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <input type="text" class="form-control" id="name_filter_input" autocomplete="off">
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
                                                                        <option value="all" data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-secondary"></span>All Status</span>'></option>
                                                                        <option value="1" data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-success"></span>Publish</span>'></option>
                                                                        <option value="0" data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-danger"></span>Hold</span>'></option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm mb-4">
                                                                <small class="text-cap text-body">@lang('Review')</small>
                                                                <div class="tom-select-custom">
                                                                    <select
                                                                        class="js-select form-select form-select-sm"
                                                                        id="filter_review"
                                                                        aria-label="Review filter">
                                                                        <option value="all">@lang('Any rating')</option>
                                                                        <option value="1">⭐ 1 @lang('star')</option>
                                                                        <option value="2">⭐⭐ 2 @lang('stars')</option>
                                                                        <option value="3">⭐⭐⭐ 3 @lang('stars')</option>
                                                                        <option value="4">⭐⭐⭐⭐ 4 @lang('stars')</option>
                                                                        <option value="5">⭐⭐⭐⭐⭐ 5 @lang('stars')</option>
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
                                                                    <button type="button" id="clear_filter" class="btn btn-white">@lang('Clear Filters')</button>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <div class="d-grid">
                                                                    <button type="button" class="btn btn-primary" id="filter_button"><i class="bi-search"></i> @lang('Apply')</button>
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
                            <div class=" table-responsive datatable-custom">
                                <table id="datatable"
                                       class="js-datatable table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                                       data-hs-datatables-options='{
                                       "columnDefs": [{
                                          "targets": [0, 5],
                                          "orderable": false
                                        }],
                                        "ordering": false,
                                       "order": [],
                                       "info": {
                                         "totalQty": "#datatableWithPaginationInfoTotalQty"
                                       },
                                       "search": "#datatableSearch",
                                       "entries": "#datatableEntries",
                                       "pageLength": 20,
                                       "isResponsive": false,
                                       "isShowPaging": false,
                                       "pagination": "datatablePagination"
                                     }'>
                                    <thead class="thead-light">
                                    <tr>
                                        <th scope="col">@lang('Package')</th>
                                        <th scope="col">@lang('Reviewer')</th>
                                        <th scope="col">@lang('Review')</th>
                                        <th scope="col">@lang('Replies')</th>
                                        <th scope="col">@lang('Status')</th>
                                        <th scope="col">@lang('Date')</th>
                                    </tr>
                                    </thead>

                                </table>
                            </div>

                            <div class="card-footer">
                                <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
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
                                                    <option value="10">10</option>
                                                    <option value="15">15</option>
                                                    <option value="20" selected>20</option>
                                                    <option value="30">30</option>
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
    <div class="modal fade" id="delete" data-bs-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalHeader">@lang('Delete Confirmation!')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="deleteModalBody">@lang('Are you certain you want to proceed with the deletion?')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                    <form action="" method="post" class="deleteModalRoute">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-soft-danger">@lang('Yes')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('style')
    <script src="{{ asset("assets/admin/js/hs-file-attach.min.js") }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/flatpickr.min.css') }}">
@endpush


@push('script')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/select.min.js') }}"></script>

    <script>
        'use strict';

        $(document).on('ready', function () {
            HSCore.components.HSFlatpickr.init('.js-flatpickr')
            HSCore.components.HSTomSelect.init('.js-select', {
                maxOptions: 250,
            })

            HSCore.components.HSDatatables.init($('#datatable'), {
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route("user.review.search", ['slug' => $slug]) }}",
                },

                columns: [
                    {data: 'package', name: 'package'},
                    {data: 'reviewer', name: 'reviewer'},
                    {data: 'review', name: 'review'},
                    {data: 'replies', name: 'replies'},
                    {data: 'status', name: 'status'},
                    {data: 'date', name: 'date'},
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

            document.getElementById("filter_button").addEventListener("click", function () {
                let name = $('#name_filter_input').val();
                let filterStatus = $('#filter_status').val();
                let filterDate = $('#filter_date_range').val();
                let filterReview = $('#filter_review').val();

                const datatable = HSCore.components.HSDatatables.getItem(0);
                datatable.ajax.url("{{ route("user.review.search", ['slug' => $slug]) }}" + "?name=" + name +
                    "&filterDate=" + filterDate + "&filterStatus=" + filterStatus+ "&filterReview=" + filterReview).load();
            });

            $.fn.dataTable.ext.errMode = 'throw';
        });
    </script>

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

@endpush
