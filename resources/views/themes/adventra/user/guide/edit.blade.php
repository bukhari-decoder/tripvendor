@extends(template().'layouts.user')
@section('page_title',trans('Edit Guide'))
@section('content')
    <div class="content container-fluid">
        <div class="row justify-content-lg-center">
            <div class="col-lg-10">
                <div class="page-header custom-header">
                    <div class="row align-items-end">
                        <div class="col-sm mb-2 mb-sm-0">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-no-gutter">
                                    <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                                   href="javascript:void(0);">@lang('Dashboard')</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">@lang('Guides')</li>
                                </ol>
                            </nav>
                            <h1 class="page-header-title">@lang('Edit Guides')</h1>
                        </div>
                    </div>
                </div>
                <form action="{{ route('user.guide.update') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="guide_slug" id="guide_slug" value="{{ $guide->slug }}" />

                    <div class="card mb-3 mb-lg-5">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h4 class="card-header-title">@lang('Edit Guide')</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="mb-4">
                                        <label for="productNameLabel" class="form-label">@lang('Name') <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Type your guide name here..."></i></label>
                                        <input type="text" class="form-control" name="name" id="nameLabel" placeholder="e.g. Tom Curran" aria-label="name" value="{{ old('name', $guide->name) }}">

                                        @error('name')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6">
                                    <div class="mb-4">
                                        <label for="slugLabel" class="form-label">@lang('Slug') <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Slug will be auto-generated based on the name."></i></label>
                                        <input type="text" class="form-control" name="slug" id="slugLabel" placeholder="e.g. tom-curran" aria-label="slug" value="{{ old('slug', $guide->slug) }}">

                                        @error('slug')
                                        <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="mb-4">
                                        <label for="guideCodeLabel" class="form-label">@lang('Guide Code') <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Type your guide code here..."></i></label>
                                        <input type="text" class="form-control" name="code" id="guideCodeLabel" placeholder="e.g. tom745 " aria-label="Code" value="{{ old('code', $guide->code) }}">

                                        @error('code')
                                        <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="mb-4">
                                        <label for="emailLabel" class="form-label">@lang('Email') <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Type guide email here..."></i></label>
                                        <input type="email" class="form-control" name="email" id="emailLabel" placeholder="e.g. tom745@gmail.com " aria-label="Email" value="{{ old('email', $guide->email) }}">

                                        @error('email')
                                        <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="mb-4">
                                        <label for="phoneLabel" class="form-label">@lang('Phone') <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Type guide phone here..."></i></label>
                                        <input type="text" class="form-control" name="phone" id="phoneLabel" placeholder="e.g. +1 4855 854 255 " aria-label="Phone" value="{{ old('phone', $guide->phone) }}">

                                        @error('phone')
                                        <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="mb-4">
                                        <label for="years_of_experienceLabel" class="form-label">@lang('Years of experience') <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Type years of experience here..."></i></label>
                                        <input type="text" class="form-control" name="years_of_experience" id="years_of_experienceLabel" placeholder="e.g. 5 " aria-label="Years of experience" value="{{ old('years_of_experience', $guide->years_of_experience) }}">

                                        @error('years_of_experience')
                                        <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="mb-4">
                                        <label for="tour_completed" class="form-label">@lang('Tour Completed') <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Type total number of completed tour as a guide here..."></i></label>
                                        <input type="text" class="form-control" name="tour_completed" id="tour_completedLabel" placeholder="e.g. 5 " aria-label="Total Completed tour" value="{{ old('tour_completed', $guide->tour_completed) }}">

                                        @error('tour_completed')
                                        <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="mb-4">
                                        <label for="designation" class="form-label">@lang('Destignation') <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Type here tour guide designation..."></i></label>
                                        <input type="text" class="form-control" name="designation" id="designation" placeholder="e.g. Tour Guide " aria-label="Designation" value="{{ old('designation', $guide->designation) }}">

                                        @error('designation')
                                        <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3 mb-lg-5">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <label class="form-label" for="details">@lang('Description')</label>
                                    <textarea
                                        name="description"
                                        class="form-control summernote"
                                        cols="30"
                                        rows="5"
                                        id="details"
                                        placeholder="Guide Description"
                                    >{{ old('description', $guide->description) }}</textarea>
                                    @error('description')
                                    <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3 mb-lg-5">
                        <div class="card-body">
                            <label class="form-label" for="packageThumbnail">@lang('Thumbnail')</label>
                            <label class="form-check form-check-dashed" for="logoUploader" id="content_img">
                                <img id="previewImage"
                                     class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                     src="{{ getFile($guide->driver, $guide->image) }}"
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
                            <p class="pt-2">@lang('For better resolution, please use an image with a size of') {{ config('filelocation.guide.size') }} @lang(' pixels.')</p>
                            @error('thumb')
                            <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">@lang("Save")</button>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset(template(true).'css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset(template(true).'css/summernote-lite.min.css') }}">
@endpush
@push('script')
    <script src="{{ asset(template(true).'js/summernote-lite.min.js') }}"></script>
    <script>
        "use strict";
        document.addEventListener('DOMContentLoaded', function () {
            const nameInput = document.getElementById('nameLabel');
            const slugInput = document.getElementById('slugLabel');
            const codeInput = document.getElementById('guideCodeLabel');

            nameInput.addEventListener('input', function () {
                const nameValue = nameInput.value;
                slugInput.value = generateSlug(nameValue);
                codeInput.value = generateGuideCode(nameValue);
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

            function generateGuideCode(text) {
                const cleaned = text
                    .replace(/\s+/g, '')
                    .replace(/[^a-zA-Z]/g, '');

                const randomNum = Math.floor(100 + Math.random() * 900);
                return cleaned + randomNum;
            }
        });
        document.getElementById('logoUploader').addEventListener('change', function() {
            let file = this.files[0];
            let reader = new FileReader();

            reader.onload = function(e) {
                document.getElementById('previewImage').src = e.target.result;
            }

            reader.readAsDataURL(file);
        });
        $(document).ready(function(){
            $('.summernote').summernote({
                height: 200,
                disableDragAndDrop: true,
                callbacks: {
                    onBlurCodeview: function () {
                        let codeviewHtml = $(this).siblings('div.note-editor').find('.note-codable').val();
                        $(this).val(codeviewHtml);
                    }
                }
            });
        });
    </script>
@endpush
