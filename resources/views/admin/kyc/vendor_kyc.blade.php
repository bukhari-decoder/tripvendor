@extends('admin.layouts.app')
@section('page_title', __('Vendor KYC List'))
@section('content')
    <div class="content container-fluid">
        <x-page-header menu="Vendor Kycs" :statBtn="true"/>

        <div class="row d-none" id="statsSection">
            <div class="col-sm-6 col-lg-3 mb-3 mb-lg-4">
                <div class="card card-hover-shadow h-100 stats-card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <h6 class="card-subtitle mb-3">@lang("Today's")</h6>
                                <h3 class="card-title js-counter" data-value="{{ $userKYCRecord[0]['todayKYC'] }}">{{ $userKYCRecord[0]['todayKYC'] }}</h3>
                                <div class="d-flex align-items-center">
                                    <span class="d-block fs-6">@lang('from') {{ $userKYCRecord[0]['totalKYC'] }}</span>
                                    <span class="badge bg-soft-info text-info ms-2">
                                        <i class="bi-graph-up"></i> {{ number_format($userKYCRecord[0]['todayKYCPercentage'], 2) }}%
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
                                <h6 class="card-subtitle mb-3">@lang("Pending KYC")</h6>
                                <h3 class="card-title js-counter" data-value="{{ $userKYCRecord[0]['pendingKYC'] }}">{{ $userKYCRecord[0]['pendingKYC'] }}</h3>
                                <div class="d-flex align-items-center">
                                    <span class="d-block fs-6">@lang('from') {{ $userKYCRecord[0]['totalKYC'] }}</span>
                                    <span class="badge bg-soft-warning text-warning ms-2">
                                        <i class="bi-graph-up"></i> {{ number_format($userKYCRecord[0]['pendingKYCPercentage'], 2) }}%
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
                                <h6 class="card-subtitle mb-3">@lang("Approved KYC")</h6>
                                <h3 class="card-title js-counter" data-value="{{ $userKYCRecord[0]['approvedKYC'] }}">{{ $userKYCRecord[0]['approvedKYC'] }}</h3>
                                <div class="d-flex align-items-center">
                                    <span class="d-block fs-6">@lang('from') {{ $userKYCRecord[0]['totalKYC'] }}</span>
                                    <span class="badge bg-soft-success text-success ms-2">
                                        <i class="bi-graph-up"></i> {{ number_format($userKYCRecord[0]['approvedKYCPercentage'], 2) }}%
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
                                <h6 class="card-subtitle mb-3">@lang("Rejected KYC")</h6>
                                <h3 class="card-title js-counter" data-value="{{ $userKYCRecord[0]['rejectedKYC'] }}">{{ $userKYCRecord[0]['rejectedKYC'] }}</h3>
                                <div class="d-flex align-items-center">
                                    <span class="d-block fs-6">@lang('from') {{ $userKYCRecord[0]['totalKYC'] }}</span>
                                    <span class="badge bg-soft-danger text-danger ms-2">
                                        <i class="bi-graph-up"></i> {{ number_format($userKYCRecord[0]['rejectedKYCPercentage'], 2) }}%
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
                                       placeholder="@lang('Search KYC')"
                                       aria-label="@lang('Search KYC')"
                                       autocomplete="off">
                                <a class="input-group-append input-group-text" href="javascript:void(0)">
                                    <i id="clearSearchResultsIcon" class="bi-x d-none"></i>
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
                                                <span class="text-cap text-body">@lang('Vendor Name')</span>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <input type="text" class="form-control"
                                                               id="name"
                                                               autocomplete="off">
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
                                                            <option value="0"
                                                                    data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-warning"></span>Pending</span>'>
                                                                @lang('Pending')
                                                            </option>
                                                            <option value="1"
                                                                    data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-success"></span>Success</span>'>
                                                                @lang('Verified')
                                                            </option>
                                                            <option value="2"
                                                                    data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-danger"></span>Cancel</span>'>
                                                                @lang('Rejected')
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-12 mb-4">
                                                                <span
                                                                    class="text-cap text-body">@lang('Date Range')</span>
                                                    <input type="text"
                                                           class="js-flatpickr form-control flatpickr-custom"
                                                           id="date_range"
                                                           placeholder="Select dates"
                                                           data-hs-flatpickr-options='{
                                                                         "dateFormat": "d/m/Y",
                                                                         "mode": "range"
                                                                     }'>
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
                                          "targets": [0, 4],
                                          "orderable": false
                                        }],
                                        "ordering": false,
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
                                <th scope="col">@lang('No.')</th>
                                <th scope="col">@lang('Name')</th>
                                <th scope="col">@lang('Verification Type')</th>
                                <th scope="col">@lang('Status')</th>
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
                                            <option value="15" selected>15</option>
                                            <option value="20">20</option>
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

@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/flatpickr.min.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/flatpickr.min.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        $(document).on('ready', function () {

            HSCore.components.HSFlatpickr.init('.js-flatpickr')
            HSCore.components.HSTomSelect.init('.js-select', {
                maxOptions: 250,
            })

            let ajaxUrl = '';

            ajaxUrl = "{{ route('admin.vendor.kyc.requests.search') }}";

            HSCore.components.HSDatatables.init($('#datatable'), {
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: ajaxUrl,
                },
                columns: [
                    {data: 'no', name: 'no'},
                    {data: 'name', name: 'name'},
                    {data: 'verification type', name: 'verification type'},
                    {data: 'status', name: 'status'},
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
            })

            document.getElementById("filter_button").addEventListener("click", function () {
                let name = $('#name').val();
                let filterStatus = $('#filter_status').val();
                let filterDate = $('#date_range').val();

                const datatable = HSCore.components.HSDatatables.getItem(0);
                datatable.ajax.url("{{ route("admin.vendor.kyc.requests.search" ) }}" + "?name=" + name +
                    "&filterDate=" + filterDate + "&filterStatus=" + filterStatus).load();
            });

            document.getElementById("clear_filter").addEventListener("click", function () {
                document.getElementById("filter_form").reset();
            });

            $.fn.dataTable.ext.errMode = 'throw';

        });

    </script>
@endpush



