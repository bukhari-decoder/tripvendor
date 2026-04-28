@extends('admin.layouts.app')
@section('page_title', __('Payment Collection'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="javascript:void(0)">@lang('Dashboard')</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Settings')</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Payment Collection')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Payment Collection')</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="alert alert-soft-dark " role="alert">
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
                            <h3 class=" mb-1">@lang("Attention!")</h3>
                            <div class="d-flex align-items-center">
                                <p class="mb-0 text-body"> @lang(" If you get 500(server error) for some reason, please turn on `Debug Log` and try again. Then you can see what was missing in your system. ")</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                @include('admin.control_panel.components.sidebar', ['settings' => config('generalsettings.settings'), 'suffix' => 'Settings'])
            </div>
            <div class="col-lg-9" id="basic_control">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title h4">@lang('Payment Flow Control')</h2>
                        </div>
                        <div class="card-body">
                            <div class="page-header inHead">
                                <div class="row align-items-center">
                                    <div class="col-sm">
                                        <h2 class="page-header-title">@lang('Payment Flow')</h2>
                                        <p class="page-header-text">@lang("When a client books a package, the payment is received either by the vendor or the admin. If the vendor receives the payment directly, there's no need to request a payout from the admin. However, if the admin receives the payment, the vendor will need to submit a payout request to the admin. You can customize the payment flow for each user based on your needs.")<a href="{{ route('admin.users') }}"><i class="bi bi-arrow-90deg-right ps-1"></i></a></p>
                                    </div>
                                </div>
                            </div>
                            <div class="input-group input-group-sm-vertical">
                                <label class="form-control" for="payment_collection1">
                                    <span class="form-check">
                                      <input type="radio" class="form-check-input" name="payment_collection" id="payment_collection1" value="0" {{ basicControl()->payment_collection = 0 ? 'checked' : '' }}>
                                      <span class="form-check-label">@lang('Direct Received Vendor')</span>
                                    </span>
                                </label>
                                <label class="form-control" for="payment_collection">
                                    <span class="form-check">
                                      <input type="radio" class="form-check-input" name="payment_collection" id="payment_collection" value="1" {{ basicControl()->payment_collection = 1 ? 'checked' : '' }}>
                                      <span class="form-check-label">@lang('Received Through Admin')</span>
                                    </span>
                                </label>
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
    <style>
        .inHead{
            border-bottom: none !important;
            padding-bottom: 0 !important;
        }
    </style>
@endpush
@push('js-lib')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        $( document ).ready(function() {

            $('input[name="payment_collection"]').on('change', function() {
                var selectedValue = $(this).val();


                $.ajax({
                    url: '{{ route('admin.payment.collection.update') }}',
                    method: 'POST',
                    data: {
                        payment_collection: selectedValue,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Notiflix.Notify.success(response.message);
                    },
                    error: function(xhr) {
                        Notiflix.Notify.failure('Failed to update payment collection. Please try again.');
                    }
                });
            });
        })
    </script>
@endpush
