@extends('admin.layouts.app')
@section('page_title', __('Gemini Settings'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="{{ route('admin.dashboard')  }}">@lang('Dashboard')</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Settings')</li>
                            <li class="breadcrumb-item active"
                                aria-current="page">@lang('Gemini Settings')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Gemini Settings')</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3">
                @include('admin.control_panel.components.sidebar', ['settings' => config('generalsettings.settings'), 'suffix' => 'Settings'])
            </div>
            <div class="col-lg-6 seo-setting">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title h4">@lang('Gemini Settings')</h2>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.gemini.update') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-4 mt-5">
                                    <label for="currency_layer_access_key"
                                           class="col-sm-4 col-form-label form-label">@lang("Gemini Api Key")</label>
                                    <div class="col-sm-8">
                                        <input type="text"
                                               class="form-control  @error('gemini_key') is-invalid @enderror"
                                               name="gemini_key" id="gemini_key"
                                               autocomplete="off"
                                               placeholder="gemini_key"
                                               aria-label="gemini_key"
                                               value="{{ old('gemini_key',  $basicControl->gemini_key) }}">
                                        @error('gemini_key')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="currency_layer_auto_update_at"
                                           class="col-sm-4 col-form-label form-label">@lang("Gemini Model") <i
                                            class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            aria-label="@lang("Gemini Model")"
                                            data-bs-original-title="@lang("You can also import your model by writing search box")"></i></label>
                                    <div class="col-sm-8">
                                        <div class="tom-select-custom">
                                            <select class="form-control ai-model js-select" name="gemini_model"
                                                    required>
                                                <option
                                                    value="aqa" {{$basicControl->gemini_model == 'aqa'?'selected':''}}>
                                                    AQA
                                                </option>
                                                <option
                                                    value="text-embedding-004" {{$basicControl->gemini_model == 'text-embedding-004'?'selected':''}}>
                                                    Text Embedding
                                                </option>
                                                <option
                                                    value="gemini-pro" {{$basicControl->gemini_model == 'gemini-pro'?'selected':''}}>
                                                    gemini-pro
                                                </option>
                                                <option
                                                    value="gemini-1.5-pro" {{$basicControl->gemini_model == 'gemini-1.5-pro'?'selected':''}}>
                                                    Gemini 1.5 Pro
                                                </option>
                                                <option
                                                    value="gemini-1.5-flash" {{$basicControl->gemini_model == 'gemini-1.5-flash'?'selected':''}}>
                                                    Gemini 1.5 Flash
                                                </option>
                                                <option
                                                    value="gemini-2.0-flash" {{$basicControl->gemini_model == 'gemini-2.0-flash'?'selected':''}}>
                                                    Gemini 2.0 Flash
                                                </option>
                                                <option
                                                    value="gemini-2.0-flash-preview-image-generation" {{$basicControl->gemini_model == 'gemini-2.0-flash-preview-image-generation'?'selected':''}}>
                                                    Gemini 2.0 flash preview image generation (Only for image)
                                                </option>
                                                @if(!in_array($basicControl->gemini_model,['aqa','text-embedding-004','gemini-pro','gemini-1.5-pro',
                                                  'gemini-1.5-flash','gemini-2.0-flash','gemini-2.0-flash-preview-image-generation']))
                                                    <option
                                                        value="{{$basicControl->gemini_model}}" selected>
                                                        {{$basicControl->gemini_model}}
                                                    </option>
                                                @endif
                                            </select>
                                        </div>
                                        @error('gemini_model')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4 mt-5">
                                    <label for="currency_layer_access_key"
                                           class="col-sm-4 col-form-label form-label">@lang("Max Token") <i
                                            class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            aria-label="@lang("Max Token")"
                                            data-bs-original-title="@lang("Gemini max tokens parameter sets an upper limit on the number of tokens generated by its language models, allowing users to control the length of the output produced in natural language processing tasks.")"></i></label>
                                    <div class="col-sm-8">
                                        <input type="number"
                                               class="form-control  @error('gemini_max_token') is-invalid @enderror"
                                               name="gemini_max_token" id="gemini_max_token"
                                               autocomplete="off"
                                               placeholder="gemini_max_token"
                                               aria-label="gemini_max_token"
                                               value="{{ old('gemini_max_token',  $basicControl->gemini_max_token) }}">
                                        @error('gemini_max_token')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <label class="row form-check form-switch mb-4" for="open_ai_status">
                                        <span class="col-8 col-sm-9 ms-0">
                                          <span class="d-block text-dark">@lang("Gemini AI Status")</span>
                                          <span
                                              class="d-block fs-5">@lang("If you leave it on, your AI-generated content will be tailored to your user preferences and subject matter. (All other provider will be disable)")</span>
                                        </span>
                                    <span class="col-4 col-sm-3 text-end">
                                           <input type='hidden' value='0' name='gemini_status'>

                                               <input
                                                   class="form-check-input @error('gemini_status') is-invalid @enderror"
                                                   type="checkbox"
                                                   name="gemini_status"
                                                   id="gemini_status"
                                                   value="1" {{ $basicControl->gemini_status == 1 ? 'checked' : '' }}>
                                        </span>
                                    @error('gemini_status')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </label>

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-primary">@lang('Save changes')</button>
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
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush
@push('js-lib')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        (function () {
            HSCore.components.HSTomSelect.init('.js-select', {
                maxOptions: 500,
                placeholder: 'Select an option',
                create: true
            })
        })();

    </script>
@endpush


