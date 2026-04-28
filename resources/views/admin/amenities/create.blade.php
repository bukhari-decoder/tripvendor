@extends('admin.layouts.app')
@section('page_title', __('Amenity Create'))
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
                            <li class="breadcrumb-item active" aria-current="page">@lang('Amenity')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Create Amenity')</h1>
                </div>
            </div>
        </div>

        <div class="row d-flex justify-content-center">
            <div class="col-lg-10">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card pb-3">
                        <div class="card-header d-flex justify-content-between">
                            <h4 class="card-title m-0">@lang('Amenity Create')</h4>
                        </div>
                        <div class="card-body mt-2">
                            <form action="{{ route('admin.amenity.store') }}" method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="row mb-4 d-flex align-items-center">
                                    <div class="col-md-6">
                                        <label for="nameLabel" class="form-label">@lang('Name')</label>
                                        <input type="text" class="form-control  @error('name') is-invalid @enderror"
                                               name="name" id="nameLabel" placeholder="Name" aria-label="Name"
                                               autocomplete="off"
                                               value="{{ old('name') }}">
                                        @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"
                                               for="Category">@lang('Type')</label>
                                        <div class="tom-select-custom">
                                            <select class="js-select form-select" id="dateFormatLabel" name="type">
                                                <option value="amenity" {{ (old('type') == 'amenity' ? ' selected' : '') }}>@lang('Amenity')</option>
                                                <option value="favourites" {{ (old('type') == 'favourites' ? ' selected' : '') }}>@lang('Favourites')</option>
                                                <option value="safety_item" {{ (old('type') == 'safety_item' ? ' selected' : '') }}>@lang('Safety Item')</option>
                                            </select>
                                        </div>
                                        @error('type')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label mt-2">@lang('Icon')</label>
                                        <input type="text" name="icon" id="iconInput" class="form-control" value="{{ old('icon') }}">
                                        @error("icon")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 pt-5">
                                        <h2 class="card-title h4">@lang("Status")</h2>
                                        <label class="row form-check form-switch" for="breadcrumbSwitch">
                                            <span class="col-8 col-sm-9 ms-0">
                                              <span class="text-dark">@lang("Status")
                                                  <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                                     data-bs-placement="top"
                                                     aria-label="@lang("Enable status for amenity publish")"
                                                     data-bs-original-title="@lang("Enable Amenity for user create property")"></i></span>
                                            </span>
                                            <span class="col-4 col-sm-3 text-end">
                                                <input type="hidden" name="status" value="0">
                                                <input type="checkbox" class="form-check-input" name="status"
                                                       id="breadcrumb" value="1" {{ old('status') == 1 ? 'checked' : ' ' }}>
                                            </span>
                                        </label>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-start mt-4">
                                    <button type="submit" class="btn btn-primary btn-sm submit_btn"><i class="bi-check-circle pe-1"></i>@lang('save')</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush
@push('script')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/bootstrap-iconpicker.min.js') }}"></script>

    <script>
        $(document).ready(function(e) {
            HSCore.components.HSTomSelect.init('.js-select', {
                maxOptions: 5000
            })
        });
    </script>
@endpush








