@extends('admin.layouts.app')
@section('page_title', __('Package Category'))
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
                            <li class="breadcrumb-item active" aria-current="page">@lang('Package Category Setting')</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Package Category Form')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Package Category Form')</h1>
                </div>
            </div>
        </div>

        <div class="row d-flex justify-content-center">
            <div class="col-lg-12">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card pb-3">
                        <div class="card-header d-flex justify-content-between">
                            <h4 class="card-title m-0">@lang('Add Package Category')</h4>
                            <a type="button" href="{{ route('admin.all.package.category') }}" class="btn btn-white btn-sm float-end"><i class="bi bi-arrow-left pe-1"></i>@lang('Back')</a>
                        </div>
                        <div class="card-body mt-2">
                            <form action="{{ route('admin.package.category.store') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf

                                <div class="row mb-4 d-flex align-items-center">
                                    <div class="col-md-12">
                                        <label for="nameLabel" class="form-label">@lang('Category Name')</label>
                                        <input type="text" class="form-control  @error('name') is-invalid @enderror"
                                               name="name" id="nameLabel" placeholder="Name" aria-label="Name"
                                               autocomplete="off"
                                               value="{{ old('name') }}">
                                        @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="card mb-3 mb-lg-5">
                                    <div class="card-body">
                                        <label class="form-label" for="packageThumbnail">@lang('Package Thumbnail')</label>
                                        <label class="form-check form-check-dashed" for="logoUploader" id="content_img">
                                            <img id="previewImage"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ asset("assets/admin/img/oc-browse-file.svg") }}"
                                                 alt="Image Preview" data-hs-theme-appearance="default">
                                            <span class="d-block">@lang("Browse your file here")</span>
                                            <input type="file" class="js-file-attach form-check-input" name="thumb"
                                                   id="logoUploader" data-hs-file-attach-options='{
                                                                      "textTarget": "#previewImage",
                                                                      "mode": "image",
                                                                      "targetAttr": "src",
                                                                      "allowTypes": [".png", ".jpeg", ".jpg"]
                                                                   }'>
                                        </label>
                                        <p class="pt-2">@lang('For better resolution, please use an image with a size of') {{ config('filelocation.package_category.size') }} @lang(' pixels.')</p>
                                        @error('thumb')
                                        <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="d-flex justify-content-start mt-4">
                                    <button type="submit" class="btn btn-primary btn-sm submit_btn">@lang('Add New')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="{{ asset("assets/admin/js/hs-file-attach.min.js") }}"></script>
    <script>
        document.getElementById('logoUploader').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('previewImage');

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush







