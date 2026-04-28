@extends('admin.layouts.app')
@section('page_title',__('View User Booking'))
@section('content')
    <div class="content container-fluid">
        <div class="row justify-content-lg-center">
            <div class="col-lg-10">
                @include('admin.user_management.components.header_user_profile')
                <div class="card">
                    <div class="card-header card-header-content-md-between">
                        <div class="mb-2 mb-md-0">
                            <div class="input-group input-group-merge input-group-flush">
                                <div class="input-group-prepend input-group-text">
                                    <i class="bi-search"></i>
                                </div>
                                <input id="datatableSearch" type="search" class="form-control" placeholder="@lang("Search tour's")"
                                       aria-label="@lang("Search Tour's")" autocomplete="off">
                            </div>
                        </div>
                        <div class="d-grid d-sm-flex justify-content-md-end align-items-sm-center gap-2">
                            <div id="datatableCounterInfo">
                                <div class="d-flex align-items-center">
                                    <span class="fs-5 me-3">
                                      <span id="datatableCounter">0</span>
                                      @lang('Selected')
                                    </span>
                                    <a class="btn btn-outline-success btn-sm" href="javascript:void(0)" data-bs-toggle="modal"
                                       data-bs-target="#CompletedMultipleModal">
                                        <i class="bi-check-square"></i> @lang('Make it Completed')
                                    </a>
                                </div>
                            </div>
                            <div id="datatableCounterInfo2" class="d-none">
                                <div class="d-flex align-items-center">
                                    <a class="btn btn-outline-primary btn-sm" id="refund-multiple" href="javascript:void(0)" data-bs-toggle="modal"
                                       data-bs-target="#refundMultipleModal">
                                        <i class="fal fa-gauge me-2"></i>@lang('Refund')
                                    </a>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button type="button" class="btn btn-white btn-sm w-100"
                                        id="dropdownMenuClickable" data-bs-auto-close="false"
                                        id="usersFilterDropdown"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                    <i class="bi-filter me-1"></i> @lang('Filter')
                                </button>
                                <div
                                    class="dropdown-menu dropdown-menu-sm-end dropdown-card card-dropdown-filter-centered filter_dropdown"
                                    aria-labelledby="dropdownMenuClickable">
                                    <div class="card">
                                        <div class="card-header card-header-content-between">
                                            <h5 class="card-header-title">@lang("Filter Tour's")</h5>
                                            <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm ms-2"
                                                    id="filter_close_btn">
                                                <i class="bi-x-lg"></i>
                                            </button>
                                        </div>

                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 mb-4">
                                                    <span class="text-cap text-body">@lang("Booking Id")</span>
                                                    <input type="text" class="form-control" id="booking_id"
                                                           autocomplete="off">
                                                </div>
                                                <div class="col-12 mb-4">
                                                    <span class="text-cap text-body">@lang("Package")</span>
                                                    <input type="text" class="form-control" id="package_filter_input"
                                                           autocomplete="off">
                                                </div>
                                                <div class="col-12 mb-4">
                                                    <span class="text-cap text-body">@lang("User")</span>
                                                    <input type="text" class="form-control" id="username_filter_input"
                                                           autocomplete="off">
                                                </div>

                                                <div class="col-sm-12 mb-4">
                                                    <small class="text-cap text-body">@lang("Status")</small>
                                                    <div class="tom-select-custom">
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
                                                            <option value="2"
                                                                    data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-success"></span>@lang('Completed')</span>'>
                                                                @lang("Completed")
                                                            </option>
                                                            <option value="4"
                                                                    data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-secondary"></span>@lang('Refunded')</span>'>
                                                                @lang("Refunded")
                                                            </option>
                                                            <option value="1"
                                                                    data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-warning"></span>@lang('Pending')</span>'>
                                                                @lang("Pending")
                                                            </option>
                                                            <option value="5"
                                                                    data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-danger"></span>@lang('Expired')</span>'>
                                                                @lang("Expired")
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 mb-4">
                                                    <label for="startDateLabel" class="form-label">@lang('Booking Date Start')</label>
                                                    <input class="form-control" id="startDateLabel" name="startDate"  type="text" />
                                                </div>
                                                <div class="col-sm-12 mb-4">
                                                    <label for="endDateLabel" class="form-label">@lang('Booking Date End')</label>
                                                    <input class="form-control" id="endDateLabel" name="endDate"  type="text" />
                                                </div>

                                            </div>

                                            <div class="d-grid">
                                                <button type="button" id="filter_button"
                                                        class="btn btn-primary">@lang('Apply')</button>
                                            </div>
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
                          "targets": [0, 7],
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
                                <th>@lang('Booking ID')</th>
                                <th>@lang('Date')</th>
                                <th>@lang('Duration')</th>
                                <th>@lang('Price')</th>
                                <th>@lang('Package')</th>
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
    </div>

    @include('admin.booking.partials.modal')
    @include('admin.user_management.components.payment_information_modal')
    @include('admin.user_management.components.login_as_user')
    @include('admin.user_management.components.block_profile_modal')

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
                    url: "{{ route("admin.user.booking.search", $user->id) }}",

                },
                columns: [
                    {data: 'checkbox', name: 'checkbox'},
                    {data: 'booking-id', name: 'booking-id'},
                    {data: 'date', name: 'date'},
                    {data: 'duration', name: 'duration'},
                    {data: 'price', name: 'price'},
                    {data: 'package', name: 'package'},
                    {data: 'status', name: 'status'},
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
                let filterSelectedStatus = $('#select_status').val();
                let startDate = $('#startDateLabel').val();
                let endDate = $('#endDateLabel').val();
                let filterName = $('#username_filter_input').val();
                let filterPackageName = $('#package_filter_input').val();
                let filterBookingId = $('#booking_id').val();

                const datatable = HSCore.components.HSDatatables.getItem(0);

                datatable.ajax.url("{{ route('admin.user.booking.search', $user->id) }}" + "?filterStatus=" + filterSelectedStatus+ "&filterName=" + filterName+
                    "&filterStartDate=" + startDate + "&filterEndDate=" + endDate+ "&filterPackageName=" + filterPackageName+ "&filterBookingId=" + filterBookingId).load();
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
            $(document).on('click', '.complete-multiple', function (e) {
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
                    url: "{{ route('admin.booking.completed.multiple') }}",
                    data: {strIds: strIds},
                    datatType: 'json',
                    type: "post",
                    success: function (data) {
                        location.reload();
                    },
                });
            });

            $(document).on('click', '.refund-multiple', function (e) {
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
                    url: "{{ route('admin.booking.refund.multiple') }}",
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







