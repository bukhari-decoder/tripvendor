@extends('admin.layouts.app')
@section('page_title', trans('Edit Plan'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0);">@lang('Dashboard')</a></li>
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0);">@lang('Edit Plan')</a></li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Edit Plan')</h1>
                </div>
            </div>
        </div>
        <div class="row ">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="media pl-2 justify-content-start">
                            <h4 class="text-dark mb-0">@lang('Edit Plan')</h4>
                        </div>
                        <div class="media justify-content-end">
                            <a href="{{ route('admin.plan.list') }}" class="btn btn-sm  btn-white mr-2">
                                <span><i class="fas fa-arrow-left"></i> @lang('Back')</span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('admin.plan.update', $plans->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('put')

                            <div class="row">
                                <div class="form-group col-md-6 col-6">
                                    <label class="form-label">{{ trans('Name') }}</label>
                                    <input type="text" class="form-control" name="name"
                                        value="{{ old('name', $plans->name) }}">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6 col-6">
                                    <label class="form-label">{{ trans('Price') }}</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="price"
                                            value="{{ old('price', $plans->price) }}">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                {{ basicControl()->base_currency ?? 'USD' }}
                                            </div>
                                        </div>
                                    </div>
                                    @error('price')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6 col-6 pt-2">
                                    <label class="form-label " for="listing">{{ trans('Maximum Package Create') }}</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="listing" id="listing" value="{{ old('listing', $plans->listing_allowed) }}">
                                    </div>
                                    @error('listing')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6 col-6 pt-2">
                                    <label class="form-label " for="featured">{{ trans('Featured Package') }}</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="featured" id="featured" value="{{ old('featured', $plans->featured_listing) }}">
                                    </div>
                                    @error('featured')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6 pt-2">
                                    <label class="form-label">@lang('Validity')</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="validity"
                                               value="{{ old('validity', $plans->validity) }}" placeholder="@lang('e.g. 5')">
                                        <div class="input-group-append">
                                            <select class="form-control select" id="select-validity-type" name="validity_type">
                                                <option value="daily" {{ old('validity_type', $plans->validity_type) == 'daily' ? 'selected' : '' }}>@lang('Daily')</option>
                                                <option value="weekly" {{ old('validity_type', $plans->validity_type) == 'weekly' ? 'selected' : '' }}>@lang('Weekly')</option>
                                                <option value="monthly" {{ old('validity_type', $plans->validity_type) == 'monthly' ? 'selected' : '' }}>@lang('Monthly')</option>
                                                <option value="yearly" {{ old('validity_type', $plans->validity_type) == 'yearly' ? 'selected' : '' }}>@lang('Yearly')</option>
                                            </select>
                                        </div>
                                    </div>
                                    @error('validity')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-4 mt-3">
                                        <label for="features" class="form-label">@lang('Add Custom Feature')</label>
                                        <div class=" justify-content-between">
                                            <div class="form-group">
                                                <a href="javascript:void(0)" class="btn btn-soft-info btn-sm float-left mt-2 generate">
                                                    <i class="fa fa-plus-circle"></i> @lang('Add Feature')</a>
                                            </div>
                                            <div class="row addedField mt-3 col-12">
                                                @if (isset($plans->features))
                                                    @foreach($plans->features ?? [] as $key => $value)
                                                        <div class="col-md-6 pb-2">
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <input name="features[]" class="form-control" id="features" type="text"
                                                                           value="{{ old('features', isset($key) ? $value : '') }}"
                                                                       required placeholder="{{ trans('Enter a Feature') }}">
                                                                <span class="input-group-btn">
                                                                <button class="btn btn-white delete_desc" type="button">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mt-2">
                                    <div class="form-group">
                                        <div class="row g-4">
                                            <label  class="form-label">@lang('Image')</label>
                                            <label class="form-check form-check-dashed form-label mt-0 ms-2" for="image">
                                                <img id="serviceImageLight"
                                                     class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                     src="{{ getFile($plans->driver, $plans->image) }}"
                                                     alt="@lang("Image")"
                                                     data-hs-theme-appearance="default">

                                                <img id="serviceImageDark"
                                                     class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                     src="{{ getFile($plans->driver, $plans->image) }}"
                                                     alt="@lang("Image")"
                                                     data-hs-theme-appearance="dark">
                                                <span class="d-block">@lang("Browse your file here")</span>
                                                <input type="file" class="js-file-attach form-check-input"
                                                       name="image" id="image"
                                                       data-hs-file-attach-options='{
                                                          "textTarget": "#serviceImage",
                                                          "mode": "image",
                                                          "targetAttr": "src",
                                                          "allowTypes": [".png", ".jpeg", ".jpg"]
                                                   }'>
                                            </label>
                                            @error("image")
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row  flex-column gap-4 mt-4">
                                        <div class="col-md-12">
                                            <div class="form-group mt-2 mx-4">
                                                <div class="row align-items-center">
                                                    <div class="col-sm mb-2 mb-sm-0">
                                                        <h5 class="mb-0">@lang('AI Feature')</h5>
                                                        <p class="fs-5 text-body mb-0">@lang("AI now helps you create tour packages faster and even generates custom images based on vendor requirements.")</p>
                                                    </div>
                                                    <div class="col-sm-auto d-flex align-items-center">
                                                        <div class="form-check form-switch form-switch-google">
                                                            <input type="hidden" name="ai_feature" value="0">
                                                            <input class="form-check-input" name="ai_feature"
                                                                   type="checkbox" id="ai_feature" value="1" {{ old('ai_feature', $plans->ai_feature) == 1 ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                   for="ai_feature"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group mt-2 mx-4">
                                                <div class="row align-items-center">
                                                    <div class="col-sm mb-2 mb-sm-0">
                                                        <h5 class="mb-0">@lang('Status')</h5>
                                                        <p class="fs-5 text-body mb-0">@lang("You can enable or disable the plan as needed. This option lets you control the plan's status.")</p>
                                                    </div>
                                                    <div class="col-sm-auto d-flex align-items-center">
                                                        <div class="form-check form-switch form-switch-google">
                                                            <input type="hidden" name="status" value="0">
                                                            <input class="form-check-input" name="status" type="checkbox" id="status" value="1" {{ old('status', $plans->status) == 1 ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="status"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="row subscriptionArea">
                                                @if($gateways)
                                                    @foreach($gateways as $gateway)
                                                        <div class="col-lg-3 col-md-3 col-sm-12 col-12">
                                                            <label class="form-label" for="SubscriptionLabel">{{$gateway->name}} @lang('Subscription Plan Id')</label>
                                                            <input type="text" class="form-control" name="gateway_plan_id[{{$gateway->code}}][]"
                                                                   value="{{ isset($plans->gateway_plan_id[$gateway->code]) ? $plans->gateway_plan_id[$gateway->code] : null }}"
                                                                   id="plan_name" aria-label="@lang('plan id')" autocomplete="off">
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn  btn-primary btn-sm btn-block mt-3">@lang('Save Changes')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/summernote-bs5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap-select.min.css') }}">
    <style>
        .ts-dropdown.single.plugin-change_listener.plugin-hs_smart_position.plugin-dropdown_input{
            width: 125px !important;
        }
        .subscriptionArea{
            padding-left: 25px;
        }
    </style>
@endpush
@push('script')
    <script src="{{ asset('assets/admin/js/summernote-bs5.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/bootstrap-select.min.js') }}"></script>
@endpush

@push('script')
    <script>
        "use strict";
        HSCore.components.HSTomSelect.init('#select-validity-type', {
            maxOptions: 250,
            placeholder: 'Select Type'
        });

        $(document).ready(function () {
            $(".generate").on('click', function () {
                let form = `<div class="col-md-6 pb-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input name="features[]" class="form-control " type="text" value="" required placeholder="{{trans('Enter a feature')}}">

                                        <span class="input-group-btn">
                                            <button class="btn btn-white delete_desc" type="button">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div> `;
                $(this).parents('.form-group').siblings('.addedField').append(form)
            });
            $(document).on('click', '.delete_desc', function () {
                $(this).closest('.col-md-6').remove();
            });

            $('#image').on("change", function () {
                if (this.files && this.files[0]) {
                    let reader = new FileReader();
                    reader.onload = function (e) {
                        $('#serviceImageLight').attr('src', e.target.result);
                        $('#serviceImageDark').attr('src', e.target.result);
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });
    </script>
@endpush
