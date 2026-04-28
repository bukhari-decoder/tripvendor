@extends(template().'layouts.user')
@section('page_title',trans('Support Ticket'))
@section('content')
    <div class="content container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <x-page-header menu="Support Ticket" :statBtn="true"/>

                <div class="row d-none" id="statsSection">
                    <div class="col-sm-6 col-lg-3 mb-3 mb-lg-4">
                        <div class="card card-hover-shadow h-100 stats-card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <h6 class="card-subtitle mb-3">@lang('Today')</h6>
                                        <h3 class="card-title js-counter" data-value="{{ $count['today_count'] }}">{{ $count['today_count'] }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $count['total'] }}</span>
                                            <span class="badge bg-soft-success text-success ms-2">
                                        <i class="bi-graph-up"></i> {{ $count['today_percent'] }}%
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
                                        <h6 class="card-subtitle mb-3">@lang('This Week')</h6>
                                        <h3 class="card-title js-counter" data-value="{{ $count['this_week_count'] }}">{{ $count['this_week_count'] }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $count['total'] }}</span>
                                            <span class="badge bg-soft-secondary text-secondary ms-2">
                                        <i class="bi-graph-up"></i> {{ $count['this_week_percent'] }}%
                                    </span>
                                        </div>
                                    </div>
                                    <span class="icon icon-soft-secondary icon-lg icon-circle ms-3">
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
                                        <h3 class="card-title js-counter" data-value="{{ $count['this_month_count'] }}">{{ $count['this_month_count'] }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $count['total'] }}</span>
                                            <span class="badge bg-soft-primary text-primary ms-2">
                                        <i class="bi-graph-up"></i> {{ $count['this_month_percent'] }}%
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
                                        <h6 class="card-subtitle mb-3">@lang('This Year')</h6>
                                        <h3 class="card-title js-counter" data-value="{{ $count['this_year_count'] }}">{{ $count['this_year_count'] }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $count['total'] }}</span>
                                            <span class="badge bg-soft-info text-info ms-2">
                                        <i class="bi-graph-up"></i> {{ $count['this_year_percent'] }}%
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
                                               placeholder="@lang('Search tickets')"
                                               aria-label="@lang('Search tickets')"
                                               autocomplete="off">
                                        <a class="input-group-append input-group-text" href="javascript:void(0)">
                                            <i id="clearSearchResultsIcon" class="bi-x d-none"></i>
                                        </a>
                                    </div>
                                </div>

                                <div class="d-grid d-sm-flex justify-content-md-end align-items-sm-center gap-2">
                                    <a class="btn btn-primary btn-sm" data-bs-target="#createTicket" data-bs-toggle="modal"><i class="bi bi-plus-circle pe-1"></i>@lang('Create ticket')</a>

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
                                                        <span class="text-cap text-body">@lang('SUBJECT')</span>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <input type="text" class="form-control" id="subject_filter_input" autocomplete="off">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm mb-4">
                                                            <small class="text-cap text-body">@lang('Status')</small>
                                                            <div class="tom-select-custom">
                                                                <select class="js-select js-datatable-filter form-select form-select-sm"
                                                                    id="filter_status"
                                                                    data-target-column-index="4" data-hs-tom-select-options='{
                                                                          "placeholder": "Any status",
                                                                          "searchInDropdown": false,
                                                                          "hideSearch": true,
                                                                          "dropdownWidth": "10rem"
                                                                        }'>
                                                                    <option value="all" data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-secondary"></span>All Status</span>'>
                                                                        @lang('All Status')
                                                                    </option>
                                                                    <option value="0" data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-warning"></span>Open</span>'>
                                                                        @lang('Open')
                                                                    </option>
                                                                    <option value="1" data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-success"></span>Answered</span>'>
                                                                        @lang('Answered')
                                                                    </option>
                                                                    <option value="2" data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-info"></span>Customer Reply</span>'>
                                                                        @lang('Customer Reply')
                                                                    </option>
                                                                    <option value="3" data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-danger"></span>Closed</span>'>
                                                                        @lang('Closed')
                                                                    </option>
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

                            <div class=" table-responsive datatable-custom  ">
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
                                        <th scope="col">@lang('Sl')</th>
                                        <th scope="col">@lang('User')</th>
                                        <th scope="col">@lang('Subject')</th>
                                        <th scope="col">@lang('Status')</th>
                                        <th scope="col">@lang('Last Reply')</th>
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

    <div class="modal fade" id="createTicket" tabindex="-1" aria-labelledby="createTicketLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createTicketLabel">@lang('Create Ticket')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-row" action="{{route('user.ticket.store')}}" method="post"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="col-md-12">
                            <div class="form-group mb-2">
                                <label>@lang('Subject')</label>
                                <input class="form-control" type="text" name="subject"
                                       value="{{old('subject')}}" placeholder="@lang('Enter Subject')">
                                @error('subject')
                                <div class="error text-danger">@lang($message) </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group mb-2">
                                <label>@lang('Message')</label>
                                <textarea class="form-control ticket-box" name="message" rows="5"
                                          id="textarea1"
                                          placeholder="@lang('Enter Message')">{{old('message')}}</textarea>
                                @error('message')
                                <div class="error text-danger">@lang($message) </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12" id="form-group-container">
                            <div class="form-group mb-2">
                                <div id="image-preview"></div>
                                <label for="file-input" id="file-label" class="form-control ticketText d-none">@lang('Choose Files')</label>
                                <input type="file" name="attachments[]"
                                       class="form-control ticketText"
                                       id="file-input"
                                       multiple
                                >
                                @error('attachments')
                                <span class="text-danger">{{trans($message)}}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group d-flex justify-content-end align-items-center gap-2">
                                <button type="button" class="btn btn-white btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                                <button type="submit" class="btn btn-success btn-sm"><span>@lang('Submit')</span></button>
                            </div>
                        </div>
                    </form>
                </div>
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

            HSCore.components.HSDatatables.init($('#datatable'), {
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: "{{ route("user.ticket.user.search") }}",
                },

                columns: [
                    {data: 'no', name: 'no'},
                    {data: 'username', name: 'username'},
                    {data: 'subject', name: 'subject'},
                    {data: 'status', name: 'status'},
                    {data: 'lastReply', name: 'lastReply'},
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
                let subject = $('#subject_filter_input').val();
                let filterStatus = $('#filter_status').val();
                let filterDate = $('#filter_date_range').val();

                const datatable = HSCore.components.HSDatatables.getItem(0);
                datatable.ajax.url("{{ route("user.ticket.user.search") }}" + "?subject=" + subject +
                    "&filterDate=" + filterDate + "&filterStatus=" + filterStatus).load();
            });

            $.fn.dataTable.ext.errMode = 'throw';
        });

        document.getElementById('file-input').addEventListener('change', function (event) {
            const preview = document.getElementById('image-preview');
            const fileInput = this;
            const fileLabel = document.getElementById('file-label');
            const formGroupContainer = document.getElementById('form-group-container');

            const allFiles = Array.from(event.target.files);
            updatePreview(allFiles);

            function updatePreview(files) {
                preview.innerHTML = '';
                fileLabel.style.display = files.length ? 'block' : 'none';
                formGroupContainer.style.display = files.length ? 'block' : 'none';
                fileLabel.textContent = `${files.length} file(s) selected`;

                const dataTransfer = new DataTransfer();

                files.forEach((file) => {
                    const container = document.createElement('div');
                    container.className = 'preview-container';
                    container.style.cssText = 'position:relative;display:inline-block;margin:10px;';
                    container.dataset.filename = file.name + file.size;

                    const closeIcon = document.createElement('span');
                    closeIcon.innerHTML = '&times;';
                    closeIcon.className = 'close-icon';
                    closeIcon.style.cssText = 'position:absolute;top:5px;right:5px;cursor:pointer;background:rgba(255,255,255,0.8);border-radius:50%;padding:2px 5px;z-index:1;font-size:15px;';
                    closeIcon.onclick = function () {
                        const remainingFiles = files.filter(f => f.name + f.size !== file.name + file.size);
                        updatePreview(remainingFiles);
                    };

                    if (file.type.startsWith('image/')) {
                        const img = document.createElement('img');
                        img.src = URL.createObjectURL(file);
                        img.style.cssText = 'height:100px;width:100px;border-radius:15px;';
                        container.appendChild(img);
                    } else {
                        const div = document.createElement('div');
                        div.textContent = file.name;
                        div.style.cssText = 'padding:20px;border:1px solid #ccc;border-radius:15px;width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;';
                        container.appendChild(div);
                    }

                    container.appendChild(closeIcon);
                    preview.appendChild(container);

                    dataTransfer.items.add(file);
                });

                fileInput.files = dataTransfer.files;
            }
        });


    </script>
@endpush



