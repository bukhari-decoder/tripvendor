@extends('admin.layouts.app')
@section('page_title', __('OpenAi Settings'))
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
                                aria-current="page">@lang('OpenAi Settings')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('OpenAi Settings')</h1>
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
                            <h2 class="card-title h4">@lang('OpenAi Settings')</h2>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.openAi.update') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-4 mt-5">
                                    <label for="currency_layer_access_key"
                                           class="col-sm-4 col-form-label form-label">@lang("OpenAi Api Key")</label>
                                    <div class="col-sm-8">
                                        <input type="text"
                                               class="form-control  @error('open_ai_key') is-invalid @enderror"
                                               name="open_ai_key" id="open_ai_key"
                                               autocomplete="off"
                                               placeholder="open_ai_key"
                                               aria-label="open_ai_key"
                                               value="{{ old('open_ai_key',  $basicControl->open_ai_key) }}">
                                        @error('open_ai_key')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <label for="currency_layer_auto_update_at"
                                           class="col-sm-4 col-form-label form-label">@lang("Open AI Model") <i
                                            class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            aria-label="@lang("Open AI Mode")"
                                            data-bs-original-title="@lang("You can also import your model by writing search box")"></i></label>
                                    <div class="col-sm-8">
                                        <div class="tom-select-custom">
                                            <select class="form-control ai-model js-select" name="open_ai_model"
                                                    required>
                                                <option
                                                    value="gpt-3.5-turbo" {{$basicControl->open_ai_model == 'gpt-3.5-turbo'?'selected':''}}>
                                                    gpt-3.5-turbo
                                                </option>
                                                <option
                                                    value="gpt-4" {{$basicControl->open_ai_model == 'gpt-4'?'selected':''}}>
                                                    gpt-4
                                                </option>
                                                <option
                                                    value="gpt-4-32k" {{$basicControl->open_ai_model == 'gpt-4-32k'?'selected':''}}>
                                                    gpt-4-32k
                                                </option>
                                                @if(!in_array($basicControl->open_ai_model,['gpt-3.5-turbo','gpt-4','gpt-4-32k']))
                                                    <option
                                                        value="{{$basicControl->open_ai_model}}" selected>
                                                        {{$basicControl->open_ai_model}}
                                                    </option>
                                                @endif
                                            </select>
                                        </div>
                                        @error('open_ai_model')
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
                                            data-bs-original-title="@lang("OpenAI's max tokens parameter sets an upper limit on the number of tokens generated by its language models, allowing users to control the length of the output produced in natural language processing tasks.")"></i></label>
                                    <div class="col-sm-8">
                                        <input type="number"
                                               class="form-control  @error('open_ai_max_token') is-invalid @enderror"
                                               name="open_ai_max_token" id="open_ai_max_token"
                                               autocomplete="off"
                                               placeholder="open_ai_max_token"
                                               aria-label="open_ai_max_token"
                                               value="{{ old('open_ai_max_token',  $basicControl->open_ai_max_token) }}">
                                        @error('open_ai_max_token')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <label class="row form-check form-switch mb-4" for="open_ai_status">
                                        <span class="col-8 col-sm-9 ms-0">
                                          <span class="d-block text-dark">@lang("Open AI Status")</span>
                                          <span
                                              class="d-block fs-5">@lang("If you leave it on, your AI-generated content will be tailored to your user preferences and subject matter. (All other provider will be disable)")</span>
                                        </span>
                                    <span class="col-4 col-sm-3 text-end">
                                           <input type='hidden' value='0' name='open_ai_status'>

                                               <input
                                                   class="form-check-input @error('open_ai_status') is-invalid @enderror"
                                                   type="checkbox"
                                                   name="open_ai_status"
                                                   id="open_ai_status"
                                                   value="1" {{ $basicControl->open_ai_status == 1 ? 'checked' : '' }}>
                                        </span>
                                    @error('open_ai_status')
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


