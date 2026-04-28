@extends('admin.layouts.app')
@section('page_title',__('User Management'))
@section('content')
    <div class="content container-fluid">
        <x-page-header menu="Users" :statBtn="true"/>

        <div class="row d-none" id="statsSection">
            <div class="col-sm-6 col-lg-3 mb-3 mb-lg-4">
                <div class="card card-hover-shadow h-100 stats-card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <h6 class="card-subtitle mb-3">@lang("Active")</h6>
                                <h3 class="card-title js-counter" data-value="{{ $userRecord[0]['activeUser'] }}">{{ $userRecord[0]['activeUser'] }}</h3>
                                <div class="d-flex align-items-center">
                                    <span class="d-block fs-6">@lang('from') {{ $userRecord[0]['totalUser'] }}</span>
                                    <span class="badge bg-soft-success text-success ms-2">
                                        <i class="bi-graph-up"></i> {{ number_format($userRecord[0]['activeUserPercentage'], 2) }}%
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
                                <h6 class="card-subtitle mb-3">@lang("Inactive")</h6>
                                <h3 class="card-title js-counter" data-value="{{ $userRecord[0]['inactiveUser'] }}">{{ $userRecord[0]['inactiveUser'] }}</h3>
                                <div class="d-flex align-items-center">
                                    <span class="d-block fs-6">@lang('from') {{ $userRecord[0]['totalUser'] }}</span>
                                    <span class="badge bg-soft-danger text-danger ms-2">
                                        <i class="bi-graph-up"></i> {{ number_format($userRecord[0]['inactiveUserPercentage'], 2) }}%
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
                                <h6 class="card-subtitle mb-3">@lang("Today's Joined")</h6>
                                <h3 class="card-title js-counter" data-value="{{ $userRecord[0]['todayJoined'] }}">{{ $userRecord[0]['todayJoined'] }}</h3>
                                <div class="d-flex align-items-center">
                                    <span class="d-block fs-6">@lang('from') {{ $userRecord[0]['totalUser'] }}</span>
                                    <span class="badge bg-soft-primary text-primary ms-2">
                                        <i class="bi-graph-up"></i> {{ number_format($userRecord[0]['todayJoinPercentage'], 2) }}%
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
                                <h6 class="card-subtitle mb-3">@lang("This Month's Joined")</h6>
                                <h3 class="card-title js-counter" data-value="{{ $userRecord[0]['thisMonthJoined'] }}">{{ $userRecord[0]['thisMonthJoined'] }}</h3>
                                <div class="d-flex align-items-center">
                                    <span class="d-block fs-6">@lang('from') {{ $userRecord[0]['totalUser'] }}</span>
                                    <span class="badge bg-soft-primary text-primary ms-2">
                                        <i class="bi-graph-up"></i> {{ number_format($userRecord[0]['thisMonthJoinPercentage'], 2) }}%
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
        </div>

        <!-- Card -->
        <div class="card">
            <div class="card-header card-header-content-md-between">
                <div class="mb-2 mb-md-0">
                    <div class="input-group input-group-merge input-group-flush">
                        <div class="input-group-prepend input-group-text">
                            <i class="bi-search"></i>
                        </div>
                        <input id="datatableSearch" type="search" class="form-control" placeholder="Search users"
                               aria-label="Search users" autocomplete="off">
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
                               data-bs-target="#userDeleteMultipleModal">
                                <i class="bi-trash"></i> @lang('Delete')
                            </a>
                        </div>
                    </div>

                    <a class="btn btn-primary btn-sm" href="{{ route('admin.users.add') }}">
                        <i class="bi-person-plus-fill me-1"></i> @lang('Add user')
                    </a>

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
                                        <div class="row">
                                            <div class="col-sm-12 mb-4">
                                                <small class="text-cap text-body">@lang("User Status")</small>
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
                                                        <option value="1"
                                                                data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-success"></span>Active</span>'>
                                                            @lang("Active")
                                                        </option>
                                                        <option value="0"
                                                                data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-danger"></span>Inactive</span>'>
                                                            @lang("Inactive")
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <small class="col-sm-7 text-cap text-body">@lang('Email Verification')</small>
                                            <div class="col-sm-5 mb-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                           id="emailFilterCheckAll">
                                                    <label class="form-check-label" for="emailFilterCheckAll">
                                                        @lang('Verified')
                                                    </label>
                                                </div>
                                            </div>

                                            <small class="col-sm-7 text-cap text-body">@lang('SMS Verification')</small>
                                            <div class="col-sm-5 mb-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                           id="SMSFilterCheckAll">
                                                    <label class="form-check-label" for="SMSFilterCheckAll">
                                                        @lang('Verified')
                                                    </label>
                                                </div>
                                            </div>

                                            <small class="col-sm-7 text-cap text-body">@lang('2FA Security')</small>
                                            <div class="col-sm-5 mb-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                           id="TwoFaFilterCheckAll">
                                                    <label class="form-check-label" for="TwoFaFilterCheckAll">
                                                        @lang('Verified')
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-12 mb-4">
                                                <span class="text-cap text-body">@lang("Name")</span>
                                                <input type="text" class="form-control" id="username_filter_input"
                                                       autocomplete="off">
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
                        <th class="table-column-ps-0">@lang('Full Name')</th>
                        <th>@lang('Email-Phone')</th>
                        <th>@lang('Balance')</th>
                        <th>@lang('Country')</th>
                        <th>@lang('Sms Verification')</th>
                        <th>@lang('Email Verification')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Last Login')</th>
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

    @include('admin.user_management.components.multiple_user_delete_modal')
    @include('admin.user_management.components.login_as_user')
    @include('admin.user_management.components.update_balance_modal')

@endsection


@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush


@push('js-lib')
    <script src="{{ asset('assets/admin/js/hs-file-attach.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/select.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/appear.min.js') }}"></script>
    <script src="{{ asset("assets/admin/js/hs-counter.min.js") }}"></script>
@endpush


@push('script')
    <script>
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
                    url: "{{ route("admin.users.search", ['country' => $country, 'state' => $state, 'city' => $city, 'reqVal' => $reqVal]) }}",

                },
                columns: [
                    {data: 'checkbox', name: 'checkbox'},
                    {data: 'name', name: 'name'},
                    {data: 'email-phone', name: 'email-phone'},
                    {data: 'balance', name: 'balance'},
                    {data: 'country', name: 'country'},
                    {data: 'sms_v', name: 'sms_v'},
                    {data: 'email_v', name: 'email_v'},
                    {data: 'status', name: 'status'},
                    {data: 'last-login', name: 'last-login'},
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
                let filterSelectedStatus = $('#select_status').val();
                let filterName = $('#username_filter_input').val();
                let filterSelectedLocation = $('#location_filter_select option:selected').val();

                let emailVerifiedFilter = $('#emailFilterCheckAll').is(':checked');
                let SMSVerifiedFilter = $('#SMSFilterCheckAll').is(':checked');
                let TwoFaFilter = $('#TwoFaFilterCheckAll').is(':checked');

                emailVerifiedFilter = emailVerifiedFilter ? 1 : '';
                SMSVerifiedFilter = SMSVerifiedFilter ? 1 : '';
                TwoFaFilter = TwoFaFilter ? 1 : '';


                const datatable = HSCore.components.HSDatatables.getItem(0);

                datatable.ajax.url("{{ route('admin.users.search') }}" + "?filterStatus=" + filterSelectedStatus +
                    "&filterName=" + filterName + "&filterLocation=" + filterSelectedLocation + "&filterEmailVerification=" +
                    emailVerifiedFilter + "&filterSMSVerification=" + SMSVerifiedFilter + "&filterTwoFaVerification=" + TwoFaFilter).load();
            });

            $.fn.dataTable.ext.errMode = 'throw';

            $(document).on('click', '.loginAccount', function () {
                let route = $(this).data('route');
                $('.loginAccountAction').attr('action', route)
            });

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
                    url: "{{ route('admin.user.delete.multiple') }}",
                    data: {strIds: strIds},
                    datatType: 'json',
                    type: "post",
                    success: function (data) {
                        location.reload();
                    },
                });
            });

            $(document).on('click', '.addBalance', function () {
                $('.setBalanceRoute').attr('action', $(this).data('route'));
                $('.user-balance').text($(this).data('balance'));
            })


        });

    </script>

@endpush




