@extends(template().'layouts.user')
@section('page_title',trans('Create Package'))
@section('content')
    <div class="content container-fluid">
        <div class="row justify-content-lg-center">
            <div class="col-lg-10">
                <div class="page-header custom-header">
                    <div class="row align-items-end">
                        <div class="col-sm mb-2 mb-sm-0">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-no-gutter">
                                    <li class="breadcrumb-item"><a class="breadcrumb-link" href="javascript:void(0);">@lang('Dashboard')</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">@lang('Packages')</li>
                                </ol>
                            </nav>
                            <h1 class="page-header-title">@lang('Create Package')</h1>
                        </div>

                        @if($freeLimit <= $vendor->posted_listing)
                            @if(isset($vendor->active_plan))
                                @if($vendor->current_plan_expiry_date <= now())
                                    <div class="alert alert-soft-dark" role="alert">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img class="avatar avatar-xl alert_image"
                                                     src="{{ asset('assets/admin/img/oc-megaphone.svg') }}"
                                                     alt="Image Description" data-hs-theme-appearance="default">
                                                <img class="avatar avatar-xl alert_image"
                                                     src="{{ asset('assets/admin/img/oc-megaphone-light.svg') }}"
                                                     alt="Image Description" data-hs-theme-appearance="dark">
                                            </div>

                                            <div class="flex-grow-1 ms-3">
                                                <div class="d-flex align-items-center">
                                                    <p class="mb-0 text-body">@lang('Your plan has expired. Please renew your plan to continue posting.')<a class="btn btn-sm btn-soft-success" href="{{ route('page', 'plans') }}">@lang('Buy Plan')</a></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @elseif($vendor->plan->listing_allowed <= $vendor->current_plan_posted_listing)
                                    <div class="alert alert-soft-dark" role="alert">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img class="avatar avatar-xl alert_image"
                                                     src="{{ asset('assets/admin/img/oc-megaphone.svg') }}"
                                                     alt="Image Description" data-hs-theme-appearance="default">
                                                <img class="avatar avatar-xl alert_image"
                                                     src="{{ asset('assets/admin/img/oc-megaphone-light.svg') }}"
                                                     alt="Image Description" data-hs-theme-appearance="dark">
                                            </div>

                                            <div class="flex-grow-1 ms-3">
                                                <div class="d-flex align-items-center">
                                                    <p class="mb-0 text-body">@lang('You have reached the maximum listing limit of your current plan. To add more packages, please purchase a new plan.')<a class="btn btn-sm btn-soft-success" href="{{ route('page', 'plans') }}">@lang('Buy Plan')</a></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-soft-dark" role="alert">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <img class="avatar avatar-xl alert_image"
                                                 src="{{ asset('assets/admin/img/oc-megaphone.svg') }}"
                                                 alt="Image Description" data-hs-theme-appearance="default">
                                            <img class="avatar avatar-xl alert_image"
                                                 src="{{ asset('assets/admin/img/oc-megaphone-light.svg') }}"
                                                 alt="Image Description" data-hs-theme-appearance="dark">
                                        </div>

                                        <div class="flex-grow-1 ms-3">
                                            <div class="d-flex align-items-center">
                                                <p class="mb-0 text-body">@lang('To create a tour package, please purchase a plan first.')<a class="btn btn-sm btn-soft-success ms-2" href="{{ route('page', 'plans') }}">@lang('Click Here to view plan')</a></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="card mb-3 mb-lg-5">
                    <div class="card-body card-inner">
                        <form action="{{ route('user.package.store') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="row justify-content-lg-center">
                                <div class="col-lg-10">
                                    <ul id="addPackageStepFormProgress"
                                        class="js-step-progress step step-sm step-icon-sm step step-inline step-item-between mb-3 mb-md-5">

                                        <li class="step-item">
                                            <a class="step-content-wrapper" href="javascript:void(0);"
                                               data-hs-step-form-next-options='{ "targetSelector": "#stepGeneralInfo" }'>
                                                <span class="step-icon step-icon-soft-dark">1</span>
                                                <div class="step-content">
                                                    <span class="step-title">@lang('General Information')</span>
                                                </div>
                                            </a>
                                        </li>

                                        <li class="step-item">
                                            <a class="step-content-wrapper" href="javascript:void(0);"
                                               data-hs-step-form-next-options='{ "targetSelector": "#stepFacilityDetails" }'>
                                                <span class="step-icon step-icon-soft-dark">2</span>
                                                <div class="step-content">
                                                    <span class="step-title">@lang('Facility and Details')</span>
                                                </div>
                                            </a>
                                        </li>

                                        <li class="step-item">
                                            <a class="step-content-wrapper" href="javascript:void(0);"
                                               data-hs-step-form-next-options='{ "targetSelector": "#stepImages" }'>
                                                <span class="step-icon step-icon-soft-dark">3</span>
                                                <div class="step-content">
                                                    <span class="step-title">@lang('Images')</span>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            @include(template().'user.package.partials.add.general_step')
                            @include(template().'user.package.partials.add.facility_step')
                            @include(template().'user.package.partials.add.image_step')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <style>

        .inMain {
            margin: 100px 0 !important;
        }
    </style>
@endpush
@push('script')

    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script>
        "use strict";

        $(document).ready(function(){
            ['#amenities', '#country', '#state', '#city', '#places','#destination','#category','#guides','#imageType'].forEach(id => {
                HSCore.components.HSTomSelect.init(id, {
                    maxOptions: 250,
                    placeholder: `Select ${id.replace('#', '').replace('_', ' ')}`
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-hs-step-form-next-options]').forEach(button => {
                button.addEventListener('click', function () {
                    const targetSelector = JSON.parse(button.getAttribute('data-hs-step-form-next-options')).targetSelector;
                    goToStep(targetSelector);
                });
            });

            document.querySelectorAll('[data-hs-step-form-prev-options]').forEach(button => {
                button.addEventListener('click', function () {
                    const targetSelector = JSON.parse(button.getAttribute('data-hs-step-form-prev-options')).targetSelector;
                    goToStep(targetSelector);
                });
            });

            function goToStep(targetSelector) {
                document.querySelectorAll('.step-content-section').forEach(section => {
                    section.classList.add('d-none');
                });

                const targetStep = document.querySelector(targetSelector);
                if (targetStep) {
                    targetStep.classList.remove('d-none');
                }

                document.querySelectorAll('#addPackageStepFormProgress .step-item').forEach(step => {
                    step.classList.remove('active');
                });

                const stepLink = document.querySelector(`[data-hs-step-form-next-options*="${targetSelector}"]`);
                if (stepLink) {
                    stepLink.closest('.step-item').classList.add('active');
                }
            }

            goToStep('#stepGeneralInfo');
        });
    </script>
@endpush
