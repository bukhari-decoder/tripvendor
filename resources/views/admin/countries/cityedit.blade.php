@extends('admin.layouts.app')
@section('page_title', __('City Edit'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm">
                    <h1 class="page-header-title">@lang("City Edit")</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="{{ route('admin.dashboard') }}">@lang('Dashboard')</a></li>
                            <li class="breadcrumb-item active"
                                aria-current="page">@lang("City Edit")</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>


        <div class="row d-flex justify-content-center">
            <div class="col-lg-10">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h3 class="card-title mt-2">@lang("City Edit")</h3>
                            <a href="{{ route('admin.country.state.all.city',[$city->country_id, $city->state_id]) }}" class="btn btn-info btn-sm"><i class="bi bi-arrow-left pe-1"></i>@lang("Back")</a>
                        </div>
                        <div class="card-body mt-2">
                            <form action="{{ route('admin.country.state.city.update', [$city->country_id,$city->state_id,$city->id]) }}" method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="NameLabel" class="form-label  ">@lang("City Name")</label>
                                            <div class="input-group input-group-sm-vertical">
                                                <input type="text" class="form-control change_name_input" name="name"
                                                       id="NameLabel" value="{{ old('name', $city->name) }}"
                                                       placeholder="@lang("City Name")" autocomplete="off">
                                            </div>
                                            @error("name")
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row align-items-center mt-4">
                                            <div class="col-sm mb-2 mb-sm-0">
                                                <h5 class="mb-0">@lang('Status')</h5>
                                                <p class="fs-5 text-body mb-0">@lang('City status enable or Disable for hide or unhide city. ')</p>
                                            </div>
                                            <div class="col-sm-auto d-flex align-items-center">
                                                <div class="form-check form-switch form-switch-google">
                                                    <input type="hidden" name="status" value="0">
                                                    <input class="form-check-input" name="status"
                                                           type="checkbox" id="status" value="1" {{ $city->status == 1 ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                           for="status"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-start mt-4">
                                        <button type="submit"
                                                class="btn btn-primary submit_btn btn-sm">@lang("Save")</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/summernote-bs5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/js/summernote-bs5.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset("assets/admin/js/hs-file-attach.min.js") }}"></script>
@endpush

@push('script')
    <script>
        $(document).ready(function () {
            HSCore.components.HSTomSelect.init('.js-select');
            new HSFileAttach('.js-file-attach')
            $(document).on('input', ".change_name_input", function (e) {
                let inputValue = $(this).val();
                let final_value = inputValue.toLowerCase().replace(/\s+/g, '-');
                $('.set-slug').val(final_value);
            });
            $('.dropdown-toggle').dropdown();

            $('#image').change(function(e){
                var reader = new FileReader();
                reader.onload = function(e){
                    $('#showImage').attr('src',e.target.result);
                }
                reader.readAsDataURL(e.target.files['0']);
            });
        });
    </script>

    <script src="{{ asset("assets/admin/js/hs-file.min.js") }}"></script>
@endpush








