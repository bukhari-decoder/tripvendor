@extends(template().'layouts.user')
@section('page_title', __('Package Edit'))
@section('content')
    <div class="content container-fluid">
        <div class="row justify-content-lg-center">
            <div class="col-lg-10">
                <div class="page-header custom-header">
                    <div class="row align-items-end">
                        <div class="col-sm mb-2 mb-sm-0">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-no-gutter">
                                    <li class="breadcrumb-item">
                                        <a class="breadcrumb-link" href="javascript:void(0)">
                                            @lang('Dashboard')
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">@lang('Packages')</li>
                                </ol>
                            </nav>
                            <h1 class="page-header-title">@lang('Package Edit')</h1>
                        </div>

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
                    </div>
                </div>

                <div class="card mb-3 mb-lg-5">
                    <div class="card-body card-inner">
                        <form action="{{ route('user.package.update', $package->id) }}" method="post" id="packageUpdate" enctype="multipart/form-data">
                            @csrf

                            <div class="row justify-content-lg-center">
                                <div class="col-lg-10">
                                    <ul id="editPackageStepFormProgress" class="js-step-progress step step-sm step-icon-sm step step-inline step-item-between mb-3 mb-md-5">

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

                            @include(template().'user.package.partials.edit.general_step')
                            @include(template().'user.package.partials.edit.facility_step')
                            @include(template().'user.package.partials.edit.image_step')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/global/css/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/image-uploader.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/summernote-bs5.min.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <style>
        .expectationArea{
            width: 100%;
        }
        .expectationArea .form-group{
            display: flex;
            border: 1px solid #f3ecec;
            padding: 10px;
            border-radius: 10px;
            width: 100%;
            box-shadow: 0 .375rem .75rem rgba(140, 152, 164, .075);
            margin-bottom: 15px;
        }
        .expectationArea .form-group .inputArea {
            max-width: 550px;
            width: 100%;
        }
        .expectationArea .form-group .inputArea .expect{
            margin-bottom: 5px;
        }

        .time-slot {
            position: relative;
        }

        .time-slot .badge {
            padding: 8px 25px 8px 12px;
            font-size: 0.9rem;
            font-weight: normal;
        }

        .btn-remove-slot {
            position: absolute;
            right: 3px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: white;
            opacity: 0.7;
            padding: 0px 3px 2px;
            line-height: 1;
            background: red;
            border-radius: 4px;
        }

        .btn-remove-slot:hover {
            opacity: 1;
            color: #ffcc00;
        }

        #timeSlotContainer {
            min-height: 50px;
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 10px;
        }
        #timeSlotError {
            position: absolute;
            background: #f8d7da;
            color: #721c24;
            padding: 5px 10px;
            border-radius: 4px;
            z-index: 10;
            width: max-content;
            max-width: 200px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-top: 5px;
        }

        #timeSlotError:before {
            content: "";
            position: absolute;
            bottom: 100%;
            left: 10px;
            border-width: 5px;
            border-style: solid;
            border-color: transparent transparent #f8d7da transparent;
        }

    </style>
@endpush
@push('script')
    <script src="{{ asset('assets/admin/js/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/summernote-bs5.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/image-uploader.min.js') }}"></script>
    <script src="{{ asset("assets/admin/js/hs-file-attach.min.js") }}"></script>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

    <script>
        "use strict";
        document.addEventListener('DOMContentLoaded', function () {
            const nameInput = document.getElementById('nameLabel');
            const slugInput = document.getElementById('slugLabel');

            nameInput.addEventListener('input', function () {
                slugInput.value = generateSlug(nameInput.value);
            });

            function generateSlug(text) {
                return text
                    .toString()
                    .toLowerCase()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .replace(/[^a-z0-9 -]/g, '')
                    .trim()
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
            }
        });
        $(document).ready(() => new HSFileAttach('.js-file-attach'));
        $(document).ready(function(){

            ['#amenities', '#country', '#state', '#city', '#places','#destination','#category','#guides'].forEach(id => {
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

                document.querySelectorAll('#editPackageStepFormProgress .step-item').forEach(step => {
                    step.classList.remove('active');
                });

                const stepLink = document.querySelector(`[data-hs-step-form-next-options*="${targetSelector}"], [data-hs-step-form-prev-options*="${targetSelector}"]`);
                if (stepLink) {
                    const stepItem = stepLink.closest('.step-item');
                    if (stepItem) stepItem.classList.add('active');
                }

                history.replaceState(null, '', targetSelector);
            }

            const initialStep = window.location.hash || '#stepGeneralInfo';
            goToStep(initialStep);
        });
        document.querySelector('.packageUpdateSubmit').addEventListener('click', function() {
            document.getElementById('packageUpdate').submit();
        });
    </script>
@endpush

