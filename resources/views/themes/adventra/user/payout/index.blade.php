@extends(template().'layouts.user')
@section('page_title',__('Withdraw History'))
@section('content')
    <div class="content container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <x-page-header menu="Withdraw History" :statBtn="true"/>

                <div class="row d-none" id="statsSection">
                    <div class="col-sm-6 col-lg-3 mb-3 mb-lg-4">
                        <div class="card card-hover-shadow h-100 stats-card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <h6 class="card-subtitle mb-3">@lang("Today's Payout")</h6>
                                        <h3 class="card-title js-counter" data-value="{{ $result['today']['count'] }}">{{ $result['today']['count'] }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $result['total'] }}</span>
                                            <span class="badge bg-soft-success text-success ms-2">
                                        <i class="bi-graph-up"></i> {{ $result['today']['percentage'] }}%
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
                                        <h6 class="card-subtitle mb-3">@lang("This Week's Payout")</h6>
                                        <h3 class="card-title js-counter" data-value="{{ $result['this_week']['count'] }}">{{ $result['this_week']['count'] }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $result['total'] }}</span>
                                            <span class="badge bg-soft-info text-info ms-2">
                                                <i class="bi-graph-up"></i> {{ $result['this_week']['count'] }}%
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
                                        <h6 class="card-subtitle mb-3">@lang("This Month's Payout")</h6>
                                        <h3 class="card-title js-counter" data-value="{{ $result['this_month']['count'] }}">{{ $result['this_month']['count'] }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $result['total'] }}</span>
                                            <span class="badge bg-soft-primary text-primary ms-2">
                                        <i class="bi-graph-up"></i> {{ $result['this_month']['count'] }}%
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
                                        <h6 class="card-subtitle mb-3">@lang("This Year's Payout")</h6>
                                        <h3 class="card-title js-counter" data-value="{{ $result['this_year']['count'] }}">{{ $result['this_year']['count'] }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $result['total'] }}</span>
                                            <span class="badge bg-soft-info text-info ms-2">
                                                <i class="bi-graph-up"></i> {{ $result['this_year']['count'] }}%
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
                <div class="card" id="Test">
                    <div class="card-header card-header-content-md-between">
                        <div class="mb-2 mb-md-0">
                            <form>
                                <div class="input-group input-group-merge navbar-input-group">
                                    <div class="input-group-prepend input-group-text">
                                        <i class="bi-search"></i>
                                    </div>
                                    <input type="search" id="datatableSearch"
                                           class="search form-control form-control-sm"
                                           placeholder="@lang('Search here')"
                                           aria-label="@lang('Search here')"
                                           autocomplete="off">
                                </div>
                            </form>
                        </div>

                        <div class="d-grid d-sm-flex justify-content-md-end align-items-sm-center gap-2">

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
                                            <h5 class="card-header-title">@lang('Filter')</h5>
                                            <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm ms-2"
                                                    id="filter_close_btn">
                                                <i class="bi-x-lg"></i>
                                            </button>
                                        </div>

                                        <div class="card-body">
                                            <form id="filter_form">
                                                <div class="row">
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
                                                                <option value="all"
                                                                        data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-secondary"></span>All Status</span>'>
                                                                    @lang('All Status')
                                                                </option>
                                                                <option value="1"
                                                                        data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-warning"></span>Pending</span>'>
                                                                    @lang('Pending')
                                                                </option>
                                                                <option value="2"
                                                                        data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-success"></span>Success</span>'>
                                                                    @lang('Success')
                                                                </option>
                                                                <option value="3"
                                                                        data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-danger"></span>Cancel</span>'>
                                                                    @lang('Cancel')
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-12 mb-4">
                                                        <span class="text-cap text-body">@lang('Method')</span>
                                                        <div class="tom-select-custom">
                                                            <select class="js-select form-select" id="filter_method">
                                                                <option value="all"
                                                                        data-option-template='<span class="d-flex align-items-center"><img class="avatar avatar-xss avatar-circle me-2" src="{{ asset("assets/upload/payoutMethod/withdraw.png") }}" alt="" /><span class="text-truncate">All Withdraw Method</span></span>'>
                                                                @forelse($methods as $method)
                                                                    <option value="@lang($method->id)"
                                                                            data-option-template='<span class="d-flex align-items-center"><img class="avatar avatar-xss avatar-circle me-2" src="{{ getFile($method->driver, $method->logo) }}" alt="" /><span class="text-truncate">{{ $method->name }}</span></span>'>
                                                                        @lang($method->name)
                                                                    </option>
                                                                @empty
                                                                @endforelse
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
                                                            <button type="button" id="clear_filter"
                                                                    class="btn btn-white">@lang('Clear Filters')</button>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="d-grid">
                                                            <button type="button" class="btn btn-primary" id="filter_button"><i
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
                                <th>@lang('Trx Number')</th>
                                <th>@lang('Method')</th>
                                <th>@lang('Amount')</th>
                                <th>@lang('Charge')</th>
                                <th>@lang('Payout Amount')</th>
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
    <div class="modal fade" id="accountUserInvoiceReceiptModal" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form role="form" method="POST" class="actionRoute" action="" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="modal-body">
                        <div class="text-center mb-5">
                            <h3 class="mb-1">@lang('Withdraw Information')</h3>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <small class="text-cap text-secondary mb-0">@lang('Sender Name:')</small>
                                <h5 class="text-dark sender_name"></h5>
                                <input type="hidden" name="user_id" class="user-id">
                            </div>

                            <div class="col-md-4 mb-3 mb-md-0">
                                <small class="text-cap text-secondary mb-0">@lang('Transaction Id:')</small>
                                <span class="text-dark transaction_id"></span>
                            </div>

                            <div class="col-md-4">
                                <small class="text-cap text-secondary mb-0">@lang('Payment method:')</small>
                                <div class="d-flex align-items-center">
                                    <img class="avatar avatar-xss me-2 gateway_modal_image" src="" alt="Image Description">
                                    <span class="text-dark method"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-6">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <small class="text-cap text-secondary mb-0">@lang('Amount paid:')</small>
                                <h5 class="text-dark amount"></h5>
                            </div>

                            <div class="col-md-4 mb-3 mb-md-0">
                                <small class="text-cap text-secondary mb-0">@lang('Date paid:')</small>
                                <span class="text-dark date"></span>
                            </div>

                            <div class="col-md-4">
                                <small class="text-cap text-secondary mb-0">@lang('Status:')</small>
                                <div class="d-flex align-items-center">
                                    <span id="status" class="status"></span>
                                </div>
                            </div>


                        </div>

                        <small class="text-cap mb-2">@lang('Summary')</small>
                        <ul class="list-group mb-4 payment_information">
                        </ul>
                        <div class="get-feedback">

                        </div>
                        <div class="modal-footer-text mt-2">
                            <div class="d-flex justify-content-end gap-3 status-buttons">
                                <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                            </div>
                        </div>
                    </div>
                </form>
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
    <script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/select.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/flatpickr.min.js') }}"></script>

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
                    url: "{{ route("user.payout.search") }}",
                },

                columns: [
                    {data: 'trx', name: 'trx'},
                    {data: 'method', name: 'method'},
                    {data: 'amount', name: 'amount'},
                    {data: 'charge', name: 'charge'},
                    {data: 'net amount', name: 'net amount'},
                    {data: 'status', name: 'status'},
                    {data: 'date', name: 'date'},
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

            });

            document.getElementById("filter_button").addEventListener("click", function () {
                let filterTransactionId = $('#transaction_id_filter_input').val();
                let filterStatus = $('#filter_status').val();
                let filterMethod = $('#filter_method').val();
                let filterDate = $('#filter_date_range').val();

                const datatable = HSCore.components.HSDatatables.getItem(0);
                datatable.ajax.url("{{ route('user.payout.search') }}" + "?filterTransactionID=" + filterTransactionId + "&filterStatus=" + filterStatus + "&filterMethod=" + filterMethod +
                    "&filterDate=" + filterDate).load();
            });

            $.fn.dataTable.ext.errMode = 'throw';

        });

        $(document).on("click", '.user_edit_btn', function (e) {
            let id = $(this).data('id');
            let amount = $(this).data('amount');
            let status = $(this).data('status');
            let method = $(this).data('method');
            let date = $(this).data('datepaid');
            let senderName = $(this).data('sendername');
            let transactionID = $(this).data('transactionid');
            let userId = $(this).data('userid');
            let status_color = $(this).data('status_color');
            let status_text = $(this).data('status_text');


            $('.user-id').val(userId);
            $('.sender_name').html(senderName);
            $('.transaction_id').html(transactionID);
            $('.amount').html(amount);
            $('.method').html(method);
            $('.date').html(date);

            $("#status").attr('class', status_color);
            $("#status").text(status_text);


            if (status == 2) {
                $(".status-buttons button[name='status']").hide();
            }
            else if (status == 3) {
                $(".status-buttons button[name='status']").hide();
            }

            let feedback = $(this).data('feedback');
            let gatewayImage = $(this).data('gatewayimage');
            $('.gateway_modal_image').attr('src', gatewayImage)


            $(".action_id").val(id);
            $(".actionRoute").attr('action', $(this).data('action'));

            let details = Object.entries($(this).data('info'));


            let list = details.map(([key, value]) => {

                let field_name = value.field_name;
                let field_value = value.field_value;
                let field_name_text = field_name.replace(/_/g, ' ');

                if (value.type == 'file') {
                    return `<li class="list-group-item text-dark">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-capitalize">${field_name_text}</span>
                                        <a href="${field_value}" target="_blank"><img src="${field_value}" alt="Image Description" class="rounded-1" width="100"></a>
                                    </div>
                                </li>`;
                } else {
                    return `<li class="list-group-item text-dark">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-capitalize">${field_name_text}</span>
                                        <span>${field_value}</span>
                                    </div>
                                </li>`;
                }
            })

            let feedbackField = "";
            if (feedback == '') {
                feedbackField = `<div class="mb-3">
                                        <small class="text-cap mb-2">@lang('Send You Feedback')</small>
                                        <textarea name="feedback" class="form-control" placeholder="Feedback" rows="3">{{old('feedback')}}</textarea>
                                     </div>`;

            } else {
                feedbackField = `<div class="mb-3">
                                        <small class="text-cap mb-2">@lang('Feedback')</small>
                                        <p>${feedback}</p>
                                     </div>`;

            }

            $('.get-feedback').html(feedbackField)

            $('.payment_information').html(list);
            $('.image').html(list);

        });

    </script>

@endpush



