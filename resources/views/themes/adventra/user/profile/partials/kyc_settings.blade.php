@extends(template().'layouts.user')
@section('page_title',trans('KYC Settings'))
@section('content')

    <div class="content container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <x-page-header menu="KYC Settings" :statBtn="true"/>

                <div class="row d-none" id="statsSection">
                    <div class="col-sm-6 col-lg-3 mb-3 mb-lg-4">
                        <div class="card card-hover-shadow h-100 stats-card">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <h6 class="card-subtitle mb-3">@lang('Verified')</h6>
                                        <h3 class="card-title js-counter" data-value="{{ $count['verified_count'] }}">{{ $count['verified_count'] }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $count['total'] }}</span>
                                            <span class="badge bg-soft-success text-success ms-2">
                                        <i class="bi-graph-up"></i> {{ $count['verified_percent'] }}%
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
                                        <h3 class="card-title js-counter" data-value="{{ $count['pending_count'] }}">{{ $count['pending_count'] }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $count['total'] }}</span>
                                            <span class="badge bg-soft-warning text-warning ms-2">
                                        <i class="bi-graph-up"></i> {{ $count['pending_percent'] }}%
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
                                        <h6 class="card-subtitle mb-3">@lang('Rejected')</h6>
                                        <h3 class="card-title js-counter" data-value="{{ $count['rejected_count'] }}">{{ $count['rejected_count'] }}</h3>
                                        <div class="d-flex align-items-center">
                                            <span class="d-block fs-6">@lang('from') {{ $count['total'] }}</span>
                                            <span class="badge bg-soft-danger text-danger ms-2">
                                        <i class="bi-graph-up"></i> {{ $count['rejected_percent'] }}%
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
                <div class="row" id="add_kyc_form_table">
                    <div class="col-lg-12">
                        <div class="d-grid gap-3 gap-lg-5">
                            <form action="{{ route('user.kyc.verification.submit') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf

                                <div class="card pb-3">
                                    <div class="card-header d-flex align-items-center justify-content-between">
                                        <h4 class="card-title m-0">@lang('KYC Verification')</h4>
                                        @php
                                            foreach ($userKyc as $entry) {
                                                $entry['created_at_formatted'] = dateTime($entry['created_at']);
                                                $entry['approved_at_formatted'] = $entry['approved_at'] ? dateTime($entry['approved_at']) : null;
                                            }
                                        @endphp
                                        <a class="btn btn-white kycHistoryView btn-sm" data-bs-target="#kycHistory" data-bs-toggle="modal" data-history="{{ $userKyc }}">@lang('History')</a>
                                    </div>
                                    <div class="card-body">
                                        <label class="form-label mb-2">@lang('Select Kyc Type')</label>

                                        <div class="tom-select-custom">
                                            <select class="js-select form-select" name="kycType" id="kycTypeSelect">
                                                <option value="">@lang('Select Kyc Type')</option>
                                                @foreach($kyc as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach
                                            </select>
                                            <div id="oldKyc"></div>
                                        </div>

                                        <div id="kycForm" class="mt-0"></div>

                                        <div class="btn-area mt-0">
                                            <button type="submit" class="btn btn-primary btn-sm d-none" id="submitButton">
                                                <i class="bi bi-plus-circle pe-1"></i>@lang('Submit')
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
    <div class="modal fade" id="kycHistory" tabindex="-1" aria-labelledby="kycHistoryLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="kycHistoryLabel">@lang('Kyc History')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="kycHistoryBody">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset(template(true) . 'css/flatpickr.min.css') }}">
    <style>
        .form-control{
            height: 38px;
        }
        #oldKyc{
            padding-top: 5px;
        }
        .account-settings-navbar{
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
    </style>
@endpush
@push('script')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset(template(true).'js/flatpickr.js')}}"></script>
    <script>
        $(document).ready(function () {
            HSCore.components.HSTomSelect.init('.js-select', {
                placeholder: 'Select One'
            });

            $('#kycTypeSelect').on('change', function () {
                if ($(this).val()) {
                    $('#submitButton').removeClass('d-none');
                } else {
                    $('#submitButton').addClass('d-none');
                }
            });
        });
        $(document).on('click', '.kycHistoryView', function () {
            let $this = $(this);
            let historyData = $this.data('history');
            let html = '';

            if (Array.isArray(historyData) && historyData.length > 0) {
                html += `
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>@lang('KYC Type')</th>
                                    <th>@lang('Fields')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('reason')</th>
                                    <th>@lang('Submitted')</th>
                                    <th>@lang('Approved')</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                historyData.forEach((entry, index) => {
                    let fieldsHtml = '<table class="table table-sm mb-0">';
                    for (let key in entry.kyc_info) {
                        if (entry.kyc_info.hasOwnProperty(key)) {
                            let field = entry.kyc_info[key];
                            fieldsHtml += `
                        <tr>
                            <td><strong>${field.field_label}</strong></td>
                            <td>${field.field_value}</td>
                        </tr>
                    `;
                        }
                    }
                    fieldsHtml += '</table>';

                    html += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${entry.kyc_type}</td>
                    <td>${fieldsHtml}</td>
                    <td>${getStatusText(entry.status)}</td>
                    <td>${entry.reason ? entry.reason : '-'}</td>
                    <td>${entry.created_at_formatted}</td>
                    <td>${entry.approved_at_formatted && entry.approved_at_formatted !== 'null' ? entry.approved_at_formatted : '-'}</td>
                </tr>
            `;
                });

                html += `
                    </tbody>
                </table>
            </div>
        `;
            } else {
                html = '<p class="text-muted">No history available.</p>';
            }

            $('#kycHistoryBody').html(html);
        });

        function getStatusText(status) {
            switch (status) {
                case 0: return 'Pending';
                case 1: return 'Approved';
                case 2: return 'Rejected';
                default: return 'Unknown';
            }
        }
        function previewFile(event) {
            const input = event.target;
            const previewId = input.id.replace('_input', '_preview');
            const preview = document.getElementById(previewId);
            const file = input.files[0];
            const reader = new FileReader();

            reader.onloadend = function () {
                preview.src = reader.result;
                preview.style.display = 'block';
                preview.style.height = '100px';
                preview.style.width = '100px';
                preview.style.borderRadius = '10px';
                preview.style.margin = '10px';
            }

            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.src = "{{ asset('assets/themes/light/img/no_image.png')}}";
                preview.style.display = 'none';
            }
        }
        $(document).ready(function() {
            $('select[name="kycType"]').change(function() {
                $('#kycForm').empty();
                let selectedKyc = $(this).val();
                if (selectedKyc) {
                    let ajaxUrl = "{{ route('user.kycFrom.details') }}";
                    let csrfToken = $('[name="_token"]').val();

                    $.ajax({
                        url: ajaxUrl,
                        type: 'GET',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        data: {
                            kycTypeID: selectedKyc
                        },
                        success: function(response) {
                            let statusMessage = '';

                            let kycName = response.kyc.name;

                            if (response.status === 0) {
                                statusMessage = `<div class="alert alert-warning mt-1" role="alert">
                                    <i class="fa-sharp fa-light fa-triangle-exclamation pe-2 alertIcon"></i>
                                    ${kycName} submitted and Pending Now.
                                 </div>`;
                            } else if (response.status === 1) {
                                statusMessage = `<div class="alert alert-success mt-1" role="alert">
                                    <i class="fa-sharp fa-light fa-triangle-exclamation pe-2 alertIcon"></i>
                                    ${kycName} Already Submitted and Also Verified.
                                 </div>`;
                            } else if (response.status === 2) {
                                let rejectReason = response.reason;
                                statusMessage = `<div class="alert alert-danger mt-1" role="alert">
                                    <i class="fa-sharp fa-light fa-triangle-exclamation pe-2 alertIcon"></i>
                                    Your previous ${kycName} submission was rejected due to ${rejectReason}.
                                    Please resubmit your ${kycName} with accurate and complete information.
                                 </div>`;
                            }

                            $('#oldKyc').html(statusMessage);

                            if (response.status !== 0 && response.status !== 1) {
                                let inputFormHtml = '';
                                $.each(response.kyc.input_form, function(key, value) {
                                    if (value.type === "text" || value.type === "number") {
                                        inputFormHtml += `
                                    <div class="input-box col-md-12 pb-3">
                                        <label for="" class="form-label">${value.field_label}</label>
                                        <input type="${value.type}" class="form-control"
                                            name="${value.field_name}"
                                            placeholder="${value.field_label}"
                                            autocomplete="off"/>
                                        @if($errors->has('${value.field_name}'))
                                        <div class="error text-danger">@lang($errors->first('${value.field_name}'))</div>
                                        @endif
                                        </div>
`;
                                    } else if (value.type === "date") {
                                        inputFormHtml += `
                                    <div class="input-box col-md-12 pb-3">
                                        <label for="${value.field_name}" class="form-label">${value.field_label}</label>
                                        <input type="text" id="${value.field_name}" class="form-control flatpickr"
                                            name="${value.field_name}"
                                            placeholder="${value.field_label}"
                                            autocomplete="off"/>
                                        <div class="error text-danger" id="${value.field_name}_error">@lang($errors->first('${value.field_name}'))</div>
                                    </div>
                                `;
                                    } else if (value.type === "textarea") {
                                        inputFormHtml += `
                                    <div class="input-box col-md-12 pb-3">
                                        <label for="" class="form-label">${value.field_label}</label>
                                        <textarea class="form-control" id="" cols="5" rows="2"
                                            name="${value.field_name}"></textarea>
                                        @if($errors->has('${value.field_name}'))
                                        <div class="error text-danger">@lang($errors->first('${value.field_name}'))</div>
                                        @endif
                                        </div>
`;
                                    } else if (value.type === "file") {
                                        inputFormHtml += `
                                    <div class="input-box col-12 pb-3">
                                        <label for="" class="form-label">${value.field_label}</label>
                                        <div class="attach-file">
                                            <img id="${value.field_name}_preview" src="" alt="${value.field_label}" style="display:none; max-width: 100%; margin-top: 10px;"/>
                                            <input class="form-control" accept="image/*" name="${value.field_name}" type="file" id="${value.field_name}_input" onchange="previewFile(event)"/>
                                        </div>
                                    </div>
                                `;
                                    }
                                });
                                $('#kycForm').html(inputFormHtml);
                                flatpickr('.flatpickr', {
                                    enableTime: false,
                                    dateFormat: "Y-m-d",
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            });
        });

    </script>
@endpush
