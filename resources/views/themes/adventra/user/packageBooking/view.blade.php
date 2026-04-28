@extends(template().'layouts.user')
@section('page_title',__('Edit Booking'))
@section('content')
    <div class="content container-fluid">
        <div class="row justify-content-lg-center">
            <div class="col-lg-10">
                <div class="page-header">
                    <div class="row justify-content-between">
                        <div class="col-md-6 col-sm w-auto">
                            <h1 class="page-header-title">@lang("Package Booking")</h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-no-gutter">
                                    <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                                   href="{{ route('user.dashboard') }}">@lang('Dashboard')</a></li>
                                    <li class="breadcrumb-item active"
                                        aria-current="page">@lang("Package Booking")</li>
                                </ol>
                            </nav>
                            <p class="mb-0">{{ 'Booking Date '.dateTime($booking->date) .'.'}}</p>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end align-items-center gap-2 mt-3">

                                @if($booking->status == 5 && $booking->date > now())
                                    <a class="approveButton text-light btn btn-primary btn-sm" href="javascript:void(0)"
                                       data-route="{{ route('user.accept.booking', $booking->uid) }}"
                                       data-amount="{{ currencyPosition($booking->total_price) }}"
                                       data-id="{{ $booking->id }}"
                                       data-trx_id = " {{ $booking->trx_id }} "
                                       data-paid_date = " {{ dateTime($booking->created_at) }} "
                                       data-bs-toggle="modal"
                                       data-bs-target="#approveModal"
                                    >
                                        <i class="bi bi-check-circle"></i>
                                        @lang('Approve Tour')
                                    </a>
                                @endif
                                @if($booking->status == 1)
                                    <a class="refundBtn text-light btn btn-info btn-sm" href="javascript:void(0)"
                                       data-route="{{ route('user.refund.booking', $booking->uid) }}"
                                       data-bs-toggle="modal" data-bs-target="#refundModal">
                                        <i class="bi bi-arrow-up-circle"></i>
                                        @lang('Make it Refund')
                                    </a>
                                    <a class="actionBtn text-light btn btn-success btn-sm" href="javascript:void(0)"
                                       data-action="{{ route('user.complete.booking', $booking->uid) }}"
                                       data-bs-toggle="modal"
                                       data-bs-target="#Confirm"
                                       data-amount="{{ currencyPosition($booking->total_price) }}"
                                       data-id="{{ $booking->id }}"
                                       data-trx_id = "{{ $booking->trx_id }}"
                                       data-paid_date = " {{ dateTime($booking->created_at) }}"
                                    >
                                        <i class="bi bi-check-square"></i>
                                        @lang('Make it Completed')
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center w-100">
                    <div class="col-lg-3">
                        <div class="card mb-3 mb-lg-5">
                            <div class="card-header card-header-content-between">
                                <div class="userHeader d-flex justify-content-start align-items-center gap-2">
                                    <img class="bookingUserImage" src="{{ getFile($booking->user->image_driver, $booking->user->image) }}">
                                    <h4 class="card-header-title">{{ optional($booking->user)->firstname.' '.optional($booking->user)->lastname }}</h4>
                                </div>
                            </div>

                            <div class="card-body">
                                <ul class="list-unstyled list-py-2 text-dark mb-0">
                                    <li class="pb-0"><span class="card-subtitle">@lang("About")</span></li>
                                    <li>
                                        <i class="bi-person dropdown-item-icon"></i>  {{ optional($booking->user)->firstname.' '.optional($booking->user)->lastname }}
                                    </li>
                                    <li>
                                        <i class="bi-briefcase dropdown-item-icon"></i> {{ optional($booking->user)->email }}
                                    </li>

                                    <li class="pt-4 pb-0"><span class="card-subtitle">@lang("Contacts")</span></li>
                                    <li><i class="bi-phone dropdown-item-icon"></i>{{optional($booking->user)->phone_code . optional($booking->user)->phone }} </li>

                                    @if(optional($booking->user)->country)
                                        <li class="pt-4 pb-0"><span class="card-subtitle">@lang("Address")</span></li>
                                        <li class="fs-6 text-body">
                                            <i class="bi bi-geo-alt dropdown-item-icon"></i> @lang(optional($booking->user)->country)
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>

                        <div class="card mb-3 mb-lg-5">
                            <div class="card-header card-header-content-between">
                                <div class="userHeader d-flex justify-content-start align-items-center gap-2">
                                    <h4 class="card-header-title">@lang('Payment Information')</h4>
                                </div>
                            </div>

                            <div class="card-body">
                                <ul class="list-unstyled list-py-2 text-dark mb-0">
                                    <li class="pb-0"><span class="card-subtitle">@lang("Transaction Details")</span></li>
                                    <li class="d-flex justify-content-between align-items-center">
                                        <span>@lang('Booking ID : ')</span>  <span>{{ $booking->trx_id }}</span>
                                    </li>
                                    <li class="d-flex justify-content-between align-items-center text-danger">
                                        <span>@lang('Charge: ')</span>  <span>{{ currencyPosition(getAmount($booking->depositable?->base_currency_charge))}} </span>
                                    </li>
                                    <li class="d-flex justify-content-between align-items-center">
                                        <span>@lang('Total Price : ')</span>  <span>{{ currencyPosition(getAmount($booking->total_price)) }}</span>
                                    </li>

                                    <li class="pt-4 pb-0"><span class="card-subtitle">@lang("Coupon Information")</span></li>

                                    <li class="d-flex justify-content-between align-items-center"><span>@lang('Amount : ')</span><span>{{currencyPosition($booking->total_price + $booking->discount_amount)}}</span></li>
                                    <li class="d-flex justify-content-between align-items-center"><span>@lang('Coupon Apply : ')</span><span>@lang($booking->coupon == 1 ? 'Yes' : 'No')</span></li>
                                    <li class="d-flex justify-content-between align-items-center"><span>@lang('Coupon : ')</span><span>{{ $booking->cupon_number ?? 'N/A' }}</span></li>
                                    <li class="d-flex justify-content-between align-items-center text-danger"><span>@lang('Discount Amount : ')</span><span>{{ currencyPosition($booking->discount_amount) ?? 0 }}</span></li>
                                    <li class="d-flex justify-content-between align-items-center"><h5>@lang('Final Amount : ')</h5><h5>{{ currencyPosition($booking->total_price) ?? 0 }}</h5></li>

                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9 mb-3 mb-lg-0">
                        <div class="card mb-3 mb-lg-5">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div class="d-flex justify-content-start align-items-center gap-2">
                                    <h4 class="card-header-title">@lang('Booking Information')</h4>

                                    @if($booking->status == 5 && $booking->date > now())
                                        <span class="badge bg-soft-warning text-warning">
                                            <span class="legend-indicator bg-warning"></span>
                                            @lang('Pending')
                                        </span>
                                    @elseif($booking->status == 0 && $booking->date > now())
                                        <span class="badge bg-soft-warning text-warning">
                                            <span class="legend-indicator bg-warning"></span>
                                            @lang('Payment Pending')
                                        </span>
                                    @elseif($booking->status == 2)
                                        <span class="badge bg-soft-success text-success">
                                            <span class="legend-indicator bg-success"></span>
                                            @lang('Completed')
                                        </span>
                                    @elseif($booking->status == 4 && $booking->date > now())
                                        <span class="badge bg-soft-secondary text-secondary">
                                            <span class="legend-indicator bg-secondary"></span>
                                            @lang('Refunded')
                                        </span>
                                    @elseif($booking->status == 1 && $booking->date > now())
                                        <span class="badge bg-soft-info text-info">
                                            <span class="legend-indicator bg-info"></span>
                                            @lang('Tour Pending')
                                        </span>
                                    @elseif($booking->status == 3 && $booking->date > now())
                                        <span class="badge bg-soft-danger text-danger">
                                            <span class="legend-indicator bg-danger"></span>
                                            @lang('Rejected')
                                        </span>
                                    @elseif ($booking->date < now())
                                        <span class="badge bg-soft-danger text-danger">
                                            <span class="legend-indicator bg-danger"></span>
                                            @lang('Expired')
                                        </span>
                                    @endif
                                </div>
                                <div class="d-flex justify-content-start align-items-center gap-2">
                                    <a type="button" href="{{ route('user.vendor.booking.list') }}" class="btn btn-white btn-sm float-end">
                                        <i class="bi bi-arrow-left pe-1"></i>@lang('Back')
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="card">
                                    <div class="card-body">
                                        <form action="{{ route('admin.booking.update') }}" method="post" enctype="multipart/form-data">
                                            @csrf

                                            <input type="hidden" name="booking" value="{{ $booking->id }}" />
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="mb-4">
                                                        <label for="nameLabel" class="form-label">@lang('Name') <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Tour title"></i></label>
                                                        <input type="text" class="form-control" name="name" id="nameLabel" placeholder="e.g dhaka" aria-label="name" value="{{ old('name', $booking->package_title) }}" {{ ($booking->status == 1 && $booking->date > now()) ? '' : 'disabled' }}>
                                                        @error('name')
                                                        <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-4">
                                                        <label class="form-label" for="TotalPriceLabel">@lang('Total Price')
                                                            <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Total Paid Amount"></i></label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control wid1" name="total_price" id="TotalPriceLabel" value="{{ old('total_price', $booking->total_price)  }}" placeholder="e.g 500" aria-label="price" {{ ($booking->status == 1 && $booking->date > now()) ? '' : 'disabled' }}>
                                                            <h5 class="form-control wid2 mb-0">{{ basicControl()->base_currency }}</h5>
                                                        </div>
                                                        @error('adult_price')
                                                        <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="mb-4">
                                                        <label class="form-label" for="statrtPoint">@lang('Booking Date')</label>
                                                        <input type="text" name="bookingDate" id="bookingDate" class="form-control date-picker2" placeholder="@lang('Booking Date')" value="{{ $booking->date }}" {{ ($booking->status == 1 && $booking->date > now()) ? '' : 'disabled' }}>
                                                        @error('bookingDate')
                                                        <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="mb-4">
                                                        <label class="form-label" for="statrtPoint">@lang('Booking Created Date')</label>
                                                        <input type="text" class="form-control date-picker2" placeholder="@lang('Created At')" value="{{ dateTime($booking->created_at) }}" disabled>
                                                        @error('startPoint')
                                                        <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="mb-4">
                                                        <label class="form-label" for="totalAdult">@lang('Total Adult')</label>
                                                        <input type="text" name="total_adult" class="form-control" placeholder="e.g. 5" id="totalAdult" value="{{ old('total_adult', $booking->total_adult) }}" {{ ($booking->status == 1 && $booking->date > now()) ? '' : 'disabled' }}>
                                                        @error('total_adult')
                                                        <span class="invalid-feedback d-block" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="mb-4">
                                                        <label class="form-label" for="totalChild">@lang('Total Child')</label>
                                                        <input type="text" name="total_children" class="form-control" placeholder="e.g. 5" id="totalChild" value="{{ old('total_children', $booking->total_children) }}" {{ ($booking->status == 1 && $booking->date > now()) ? '' : 'disabled' }}>
                                                        @error('total_child')
                                                        <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="mb-4">
                                                        <label class="form-label" for="totalInfant">@lang('Total Infant')</label>
                                                        <input type="text" name="total_infant" class="form-control" placeholder="e.g. 5" id="totalInfant" value="{{ old('total_infant', $booking->total_infant) }}" {{ ($booking->status == 1 && $booking->date > now()) ? '' : 'disabled' }}>
                                                        @error('total_infant')
                                                        <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            @if($booking->status == 1 && $booking->date > now())
                                                <button type="submit" class="btn btn-primary">@lang("Save Changes")</button>
                                            @endif
                                        </form>
                                    </div>
                                </div>
                                <div class="card mt-2">
                                    <div class="card-header">
                                        <h3>@lang('Traveller Information')</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="accordion" id="travellerAccordion">
                                            @foreach(['adult' => $booking->adult_info, 'child' => $booking->child_info, 'infant' => $booking->infant_info] as $type => $info)
                                                @if (isset($info) && !empty($info))
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="heading{{ ucfirst($type) }}">
                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ ucfirst($type) }}" aria-expanded="false" aria-controls="collapse{{ ucfirst($type) }}">
                                                                @lang("Traveler's Information for " . ucfirst($type) . "s")
                                                            </button>
                                                        </h2>
                                                        <div id="collapse{{ ucfirst($type) }}" class="accordion-collapse collapse" aria-labelledby="heading{{ ucfirst($type) }}" data-bs-parent="#travellerAccordion">
                                                            <div class="accordion-body">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <div class="row g-4">
                                                                            @foreach($info as $key => $person)
                                                                                <div class="col-lg-4 col-md-6 col-sm-12 ">
                                                                                    <div class="travellerDetailsColumn">
                                                                                        <p class="travellerInfoTitle">
                                                                                            @lang(ucfirst($type) . ' ') {{ $key + 1 }} @lang(' Information')
                                                                                        </p>

                                                                                        <form action="{{ route('admin.traveller.update') }}" method="post" enctype="multipart/form-data">
                                                                                            @csrf
                                                                                            <input type="hidden" name="booking" value="{{ $booking->id }}" />

                                                                                            <div class="form-group">
                                                                                                <label class="form-label" for="{{ $type }}_info[{{ $key }}][first_name]">@lang('First Name: ')</label>
                                                                                                <input type="text" name="{{ $type }}_info[{{ $key }}][first_name]" id="{{ $type }}_info[{{ $key }}][first_name]" value="{{ $person['first_name'] }}" class="form-control" {{ ($booking->status == 1 && $booking->date > now()) ? '' : 'disabled' }}>

                                                                                                @error("{{ $type }}_info[{{ $key }}][first_name]")
                                                                                                <span class="invalid-feedback d-block" role="alert">
                                                                                                        <strong>{{ $message }}</strong>
                                                                                                    </span>
                                                                                                @enderror

                                                                                                <label class="form-label" for="{{ $type }}_info[{{ $key }}][last_name]">@lang('Last Name: ')</label>
                                                                                                <input type="text" name="{{ $type }}_info[{{ $key }}][last_name]" value="{{ $person['last_name'] }}" id="{{ $type }}_info[{{ $key }}][last_name]" class="form-control" {{ ($booking->status == 1 && $booking->date > now()) ? '' : 'disabled' }}>

                                                                                                @error("{{ $type }}_info[{{ $key }}][last_name]")
                                                                                                <span class="invalid-feedback d-block" role="alert">
                                                                                                        <strong>{{ $message }}</strong>
                                                                                                    </span>
                                                                                                @enderror

                                                                                                <label class="form-label" for="{{ $type }}_info[{{ $key }}][birth_date]">@lang('Birth Date: ')</label>
                                                                                                <input type="text" name="{{ $type }}_info[{{ $key }}][birth_date]" id="{{ $type }}_info[{{ $key }}][birth_date]" value="{{ $person['birth_date'] }}" class="form-control date-picker" {{ ($booking->status == 1 && $booking->date > now()) ? '' : 'disabled' }}>

                                                                                                @error("{{ $type }}_info[{{ $key }}][birth_date]")
                                                                                                <span class="invalid-feedback d-block" role="alert">
                                                                                                        <strong>{{ $message }}</strong>
                                                                                                    </span>
                                                                                                @enderror

                                                                                                @if($booking->status == 1 && $booking->date > now())
                                                                                                    <div class="submit-btn pt-2">
                                                                                                        <button class="btn btn-primary" type="submit">@lang('Save Changes')</button>
                                                                                                    </div>
                                                                                                @endif
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
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
    @include(template().'user.packageBooking.partials.modal')

@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/global/css/flatpickr.min.css') }}">
@endpush

@push('script')
    <script src="{{ asset('assets/global/js/flatpickr.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let today = new Date();
            today.setDate(today.getDate() - 1);
            let maxDate = today.toISOString().split('T')[0];

            flatpickr('.date-picker', {
                enableTime: false,
                dateFormat: "Y-m-d",
                maxDate: maxDate
            });
            flatpickr('.date-picker2', {
                enableTime: false,
                dateFormat: "Y-m-d"
            });
        });
    </script>
@endpush
