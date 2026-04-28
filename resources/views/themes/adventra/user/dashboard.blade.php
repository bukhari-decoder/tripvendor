@extends(template().'layouts.user')
@section('page_title',trans('Dashboard'))
@section('content')
    <div class="content container-fluid">
        <div class="row justify-content-lg-center">
            <div class="col-lg-10">
                <div class="page-header custom-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h1 class="page-header-title">@lang('Dashboard')</h1>
                        </div>
                        @if(auth()->user()->role != 1 && auth()->user()->restrict_for_vendor_request == 0)
                            <div class="col-auto">
                                <a class="btn btn-success openKycModal btn-sm" href="#" data-bs-toggle="modal" data-bs-target="#becomeHostModal" data-kycdetails='@json($kycForm->input_form)'>
                                    <i class="fa-light fa-paper-plane me-2"></i>@lang('Become a Vendor')
                                </a>
                                <a class="btn btn-primary btn-sm statBtn" href="javascript:;" id="toggleStats">
                                    <i class="bi-receipt"></i> @lang('See Stats')
                                </a>
                            </div>
                        @endif
                        @if(auth()->user()->role == 1)
                            <div class="col-auto">
                                <span>@lang('Balance: ')<span>{{ currencyPosition(auth()->user()->balance ?? 0) }}</span></span>
                            </div>
                        @endif
                    </div>
                </div>
                <div id="firebase-app">
                    <div class="shadow p-3 mb-5 alert alert-soft-dark mb-4 mb-lg-7" role="alert"
                         v-if="notificationPermission == 'default' && !is_notification_skipped" v-cloak>
                        <div class="alert-box d-flex flex-wrap align-items-center">
                            <div class="flex-shrink-0">
                                <img class="avatar avatar-xl"
                                     src="{{ asset('assets/admin/img/oc-megaphone.svg') }}"
                                     alt="Image Description" data-hs-theme-appearance="default">
                                <img class="avatar avatar-xl"
                                     src="{{ asset('assets/admin/img/oc-megaphone-light.svg') }}"
                                     alt="Image Description" data-hs-theme-appearance="dark">
                            </div>

                            <div class="flex-grow-1 ms-3">
                                <h3 class="mb-1">@lang("Attention!")</h3>
                                <div class="d-flex align-items-center alertContent">
                                    <p class="mb-0 text-body"> @lang('Please allow your browser to get instant push notification. Allow it from notification setting.')</p>
                                    <button id="allow-notification" class="btn btn-sm btn-primary mx-2"><i class="fa fa-check-circle"></i> @lang('Allow me')</button>
                                </div>
                            </div>
                            <button type="button" class="btn-white btn-close alertButton"
                                    @click.prevent="skipNotification" data-bs-dismiss="alert"
                                    aria-label="Close">
                                <i class="fas fa-xmark"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row" id="statsSection">
                    @foreach($charts as $chart)
                        @if($chart['permission'] == auth()->user()->role || $chart['permission'] == 'all')
                            <div class="col-sm-6 col-lg-3 mb-3 mb-lg-4">
                                <div class="card card-hover-shadow h-100 stats-card">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="flex-grow-1">
                                                <h6 class="card-subtitle mb-3">{{ $chart['title'] }}</h6>
                                                <h3 class="card-title js-counter" data-value="{{ $chart['total'] }}">{{ $chart['total'] }}</h3>
                                                <div class="d-flex align-items-center">
                                                    <span class="d-block fs-6">@lang('from') {{ $chart['from_total'] }}</span>
                                                    <span class="badge {{ $chart['graph_class'] }} ms-2">
                                                        <i class="bi-graph-up"></i> {{ $chart['percentage'] ?? 0 }}%
                                                    </span>
                                                </div>
                                            </div>
                                            <span class="icon {{ $chart['icon_class'] }} icon-lg icon-circle ms-3">
                                                <i class="{{ $chart['icon'] }} fs-1"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach

                </div>
                <!-- End Stats -->
                @if(auth()->user()->role == 1)

                    <div class="row">
                        @include(template().'user.partials.dashboard.bookings_chart')
                        @include(template().'user.partials.dashboard.booking_calender')
                    </div>

                @endif

                @if(auth()->user()->role == 1)
                    <div class="row">

                        @include(template().'user.partials.dashboard.popular_packages')
                        @include(template().'user.partials.dashboard.chart')
                    </div>
                @endif

                @if(auth()->user()->role !=1)
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header d-flex align-items-center justify-content-between">
                                    <label>@lang('Tour History')</label>
                                    <div class="d-flex align-items-center justify-content-end gap-2">
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
                                                        <h5 id="offcanvasRightLabel">@lang('Filter')</h5>
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

                                </div>

                                <div class=" table-responsive datatable-custom">
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
                @endif



            </div>
        </div>
    </div>

    <div class="modal fade" id="becomeHostModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-close">
                    <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi-x-lg"></i>
                    </button>
                </div>
                <form action="{{ route('user.become.vendor') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-sm-5">
                        <div class="text-center">
                            <div class="w-75 w-sm-50 mx-auto mb-4">
                                <img class="img-fluid" src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}" alt="{{ basicControl()->site_title }}">
                            </div>
                            <div id="kycFormContainer" class="row"></div>
                        </div>
                    </div>
                    <div class="modal-footer d-block text-center py-sm-5">
                        <small class="text-cap text-muted">@lang('Confirm your interest and join our trusted vendor network. We value your partnership!')</small>
                        <div class="modal-footer-button">
                            <button type="button" class="btn btn-white" data-bs-dismiss="modal" aria-label="Close">@lang('Cancel')</button>
                            <button type="submit" class="btn btn-success" name="confirm" value="1">@lang('Confirm')</button>
                        </div>
                    </div>
                </form>
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
                            <th scope="row">@lang('Total Persons:')</th>
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

@push('script')
    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
            Notiflix.Notify.failure('{{ $error }}');
            @endforeach
        </script>
    @endif

    <script>
        $('.js-chart').each(function() {
            const chartOptions = JSON.parse($(this).attr('data-hs-chartjs-options'));
            new Chart(this, chartOptions);
        });
    </script>
@endpush

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
        #kycFormContainer .input-box{
            text-align: left !important;
        }
        #kycFormContainer .img-preview {
            width: 100px;
            height: 100px;
            object-fit: cover;
            display: block;
            margin-top: 10px;
        }
        .alertButton .fas.fa-xmark{
            padding: 5px;
        }
        .alertButton .fas.fa-xmark::before {
            content: none !important;
        }
        .alertButton{
            padding: 10px !important;
        }
    </style>
@endpush
@push('script')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/select.min.js') }}"></script>
    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                Notiflix.Notify.failure("{{ $error }}");
            @endforeach
        </script>
    @endif
    <script>
        $(document).on('click', '.openKycModal', function (e) {
            e.preventDefault();

            let kycData = $(this).data('kycdetails');
            if (!kycData) return;

            let inputFormHtml = '';

            $.each(kycData, function (key, value) {
                switch (value.type) {
                    case "text":
                    case "number":
                        inputFormHtml += `
                        <div class="input-box col-md-12 pb-3">
                            <label for="${value.field_name}" class="form-label">${value.field_label}</label>
                            <input type="${value.type}" class="form-control" name="${value.field_name}" placeholder="${value.field_label}" autocomplete="off"/>
                            <div class="error text-danger" id="${value.field_name}_error"></div>
                        </div>`;
                        break;

                    case "date":
                        inputFormHtml += `
                        <div class="input-box col-md-12 pb-3">
                            <label for="${value.field_name}" class="form-label">${value.field_label}</label>
                            <input type="text" id="${value.field_name}" class="form-control flatpickr" name="${value.field_name}" placeholder="${value.field_label}" autocomplete="off"/>
                            <div class="error text-danger" id="${value.field_name}_error"></div>
                        </div>`;
                        break;

                    case "textarea":
                        inputFormHtml += `
                        <div class="input-box col-md-12 pb-3">
                            <label for="${value.field_name}" class="form-label">${value.field_label}</label>
                            <textarea class="form-control" name="${value.field_name}" rows="3" placeholder="${value.field_label}"></textarea>
                            <div class="error text-danger" id="${value.field_name}_error"></div>
                        </div>`;
                        break;

                    case "file":
                        inputFormHtml += `
                            <div class="card-box pt-3">
                                <div class="row g-2">
                                    <label class="form-label text-start">${value.field_label}</label>
                                    <label class="form-check form-check-dashed form-label" for="${value.field_name}">
                                        <img id="${value.field_name}_preview_light"
                                             class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                             src="{{ asset('assets/admin/img/oc-browse-file.svg') }}"
                                             alt="${value.field_label}"
                                             data-hs-theme-appearance="default">

                                        <img id="${value.field_name}_preview_dark"
                                             class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                             src="{{ asset('assets/admin/img/oc-browse-file-light.svg') }}"
                                             alt="${value.field_label}"
                                             data-hs-theme-appearance="dark">

                                        <span class="d-block">Browse your file here</span>
                                        <input type="file" class="js-file-attach form-check-input"
                                           name="${value.field_name}" id="${value.field_name}"
                                           onchange="previewFile(event, '${value.field_name}')"
                                           data-hs-file-attach-options='{
                                               "textTarget": "#${value.field_name}_preview_light, #${value.field_name}_preview_dark",
                                               "mode": "image",
                                               "targetAttr": "src",
                                               "allowTypes": [".png", ".jpeg", ".jpg"]
                                           }'>
                                    </label>
                                    <div class="error text-danger" id="${value.field_name}_error"></div>
                                </div>
                            </div>`;
                        break;
                }
            });

            $('#kycFormContainer').html(inputFormHtml);

            if (typeof flatpickr !== 'undefined') {
                $(".flatpickr").flatpickr();
            }
        });

        function previewFile(event, previewIdBase) {
            const input = event.target;
            const file = input.files[0];

            if (file && file.type.startsWith("image/")) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const lightPreview = document.getElementById(previewIdBase + "_preview_light");
                    const darkPreview = document.getElementById(previewIdBase + "_preview_dark");
                    if (lightPreview) lightPreview.src = e.target.result;
                    if (darkPreview) darkPreview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

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

            const datatable = HSCore.components.HSDatatables.init($('#datatable'), {
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: "{{ route('user.booking.list.search') }}",
                    data: function (d) {
                        d.filterTransactionID = $('#transaction_id_filter_input').val();
                        d.filterPackageTitle = $('#package_title_filter_input').val();
                        d.filterDate = $('#filter_date_range').val();
                    }
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

        document.addEventListener('DOMContentLoaded', function () {
            let button = document.getElementById('toggleStats');
            let statsSection = document.getElementById('statsSection');

            if (localStorage.getItem('statsVisible') === 'true') {
                statsSection.classList.remove('d-none');
                button.innerHTML = `<i class="bi-receipt"></i> {{ __('Hide Stats') }}`;
            }

            button.addEventListener('click', function () {
                statsSection.classList.toggle('d-none');
                let isVisible = !statsSection.classList.contains('d-none');
                localStorage.setItem('statsVisible', isVisible);
                button.innerHTML = isVisible
                    ? `<i class="bi-receipt"></i> {{ __('Hide Stats') }}`
                    : `<i class="bi-receipt"></i> {{ __('See Stats') }}`;
            });
        });

        function formatPersonLabel(count) {
            return `${count} ${count == 1 ? 'person' : 'persons'}`;
        }
    </script>
@endpush
@if($firebaseNotify)
    @push('script')
        <script type="module">

            import {initializeApp} from "https://www.gstatic.com/firebasejs/9.17.1/firebase-app.js";
            import {
                getMessaging,
                getToken,
                onMessage
            } from "https://www.gstatic.com/firebasejs/9.17.1/firebase-messaging.js";

            const firebaseConfig = {
                apiKey: "{{$firebaseNotify['apiKey']}}",
                authDomain: "{{$firebaseNotify['authDomain']}}",
                projectId: "{{$firebaseNotify['projectId']}}",
                storageBucket: "{{$firebaseNotify['storageBucket']}}",
                messagingSenderId: "{{$firebaseNotify['messagingSenderId']}}",
                appId: "{{$firebaseNotify['appId']}}",
                measurementId: "{{$firebaseNotify['measurementId']}}"
            };

            const app = initializeApp(firebaseConfig);
            const messaging = getMessaging(app);
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('{{ getProjectDirectory() }}' + `/firebase-messaging-sw.js`, {scope: './'}).then(function (registration) {
                        requestPermissionAndGenerateToken(registration);
                    }
                ).catch(function (error) {
                });
            } else {
            }

            onMessage(messaging, (payload) => {
                if (payload.data.foreground || parseInt(payload.data.foreground) == 1) {
                    const title = payload.notification.title;
                    const options = {
                        body: payload.notification.body,
                        icon: payload.notification.icon,
                    };
                    new Notification(title, options);
                }
            });

            function requestPermissionAndGenerateToken(registration) {
                document.addEventListener("click", function (event) {
                    if (event.target.id == 'allow-notification') {
                        Notification.requestPermission().then((permission) => {
                            if (permission === 'granted') {
                                getToken(messaging, {
                                    serviceWorkerRegistration: registration,
                                    vapidKey: "{{$firebaseNotify['vapidKey']}}"
                                })
                                    .then((token) => {
                                        $.ajax({
                                            url: "{{ route('admin.save.token') }}",
                                            method: "post",
                                            data: {
                                                token: token,
                                            },
                                            success: function (res) {
                                            }
                                        });
                                        window.newApp.notificationPermission = 'granted';
                                    });
                            } else {
                                window.newApp.notificationPermission = 'denied';
                            }
                        });
                    }
                });
            }
        </script>
        <script>
            window.newApp = new Vue({
                el: "#firebase-app",
                data: {
                    admin_foreground: '',
                    admin_background: '',
                    notificationPermission: Notification.permission,
                    is_notification_skipped: sessionStorage.getItem('is_notification_skipped') == '1'
                },
                mounted() {
                    sessionStorage.clear();
                    this.admin_foreground = "{{$firebaseNotify['admin_foreground']}}";
                    this.admin_background = "{{$firebaseNotify['admin_background']}}";
                },
                methods: {
                    skipNotification() {
                        sessionStorage.setItem('is_notification_skipped', '1');
                        this.is_notification_skipped = true;
                    }
                }
            });
        </script>
    @endpush
@endif
