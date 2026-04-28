
@extends('admin.layouts.app')
@section('page_title', __('Coupon'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="javascript:void(0)">
                                    @lang('Dashboard')
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <a class="breadcrumb-link" href="{{ route('admin.all.coupon') }}">
                                    @lang('Coupon')
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Edit')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Coupon Edit Form')</h1>
                </div>
            </div>
        </div>
        <form action="{{ route('admin.coupon.update', $coupon->id) }}" method="POST">
            @csrf
            <div class="row d-flex justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <a type="button" href="{{ route('admin.all.coupon') }}" class="btn btn-white btn-sm float-end"><i class="bi bi-arrow-left pe-1"></i>@lang('Back')</a>
                            <h5 class="mb-0 h5 text-secondary">@lang('Add Coupon Information')</h5>
                        </div>
                        <div class="card-body">
                            <div id="coupon_form">
                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label"
                                           for="code">@lang('Coupon code')</label>
                                    <div class="col-lg-9">
                                        <div class="d-flex">
                                            <input type="text" placeholder="Coupon code" id="code" name="coupon_code"
                                                   class="form-control coupon_code w-75 me-3" value="{{ old('coupon_code', $coupon->coupon_code) }}" autocomplete="off">
                                            <button class="generateBtn btn btn-sm btn-soft-success" type="button"><i class="bi-lightning-charge pe-1"></i>@lang('Generate code')</button>
                                        </div>
                                        <div class="invalid-feedback d-inline-block">
                                            @error('coupon_code') @lang($message) @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label"
                                           for="code">@lang('Start Date')</label>
                                    <div class="col-lg-9">
                                        <input type="text" placeholder="start date"
                                               name="start_date" id="start_date" value="{{ old('start_date', $coupon->start_date) }}"
                                               class="form-control">
                                        <div class="invalid-feedback d-inline-block">
                                            @error('start_date') @lang($message) @enderror
                                        </div>
                                    </div>

                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label" for="code">@lang('End Date')</label>
                                    <div class="col-lg-9">
                                        <input type="text" placeholder="date"
                                               name="end_date" id="end_date" value="{{ old('end_date', $coupon->end_date) }}"
                                               class="form-control">
                                        <div class="invalid-feedback d-inline-block">
                                            @error('end_date') @lang($message) @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-from-label" for="code">@lang('Discount')</label>
                                    <div class="col-lg-9">
                                        <div class="input-group">
                                            <input type="number" placeholder="Discount" name="discount" value="{{ old('discount', $coupon->discount) }}" class="form-control" autocomplete="off">
                                            <div class="input-group-append">
                                                <select
                                                    class="js-select form-select custom-width @error('discount_type') is-invalid @enderror"
                                                    name="discount_type" id="discount_type">
                                                    <option value="1" {{ $coupon->discount_type == 1 ? 'selected' : '' }}>%</option>
                                                    <option value="0" {{ $coupon->discount_type == 0 ? 'selected' : '' }}>{{ basicControl()->base_currency }}</option>
                                                </select>
                                            </div>
                                            <div class="invalid-feedback d-inline-block">
                                                @error('discount') @lang($message) @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3 mt-3">
                                <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-arrow-clockwise pe-1"></i>@lang('Update')</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection


@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <style>
        .custom-width {
            min-width: 85px;
        }
    </style>
@endpush

@push('script')
    <script src="{{ asset('assets/admin/js/hs-file-attach.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/hs-sticky-block.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/hs-scrollspy.min.js') }}"></script>

    <script>
        flatpickr('#start_date', {
            enableTime: false,
            dateFormat: "Y-m-d",
            minDate: 'today'
        });
        flatpickr('#end_date', {
            enableTime: false,
            dateFormat: "Y-m-d",
            minDate: 'today'
        });
        $(document).ready(function () {
            HSCore.components.HSTomSelect.init('.js-select', {
                maxOptions: 250
            })
            new HSFileAttach('.js-file-attach')
            new HSStickyBlock('.js-sticky-block', {
                targetSelector: document.getElementById('header').classList.contains('navbar-fixed') ? '#header' : null
            })
            new bootstrap.ScrollSpy(document.body, {
                target: '#navbarSettings',
                offset: 100
            })
            new HSScrollspy('#navbarVerticalNavMenu', {
                breakpoint: 'lg',
                scrollOffset: -20
            })
        })

        $('.generateBtn').click(function() {
            let characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let length = 8;
            let randomString = '';
            for (let i = 0; i < length; i++) {
                randomString += characters.charAt(Math.floor(Math.random() * characters.length));
            }
            let randomNumber = Math.floor(Math.random() * 100) + 1;
            let code = randomString + randomNumber;
            $('.coupon_code').val(code);
        });

    </script>
@endpush



