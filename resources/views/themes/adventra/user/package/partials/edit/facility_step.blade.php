<div id="stepFacilityDetails" class="step-content-section d-none">
    <div class="row">
        <div class="col-12">
            <div class="mb-4">
                <div class=" justify-content-between">
                    <div class="form-group">
                        <a href="javascript:void(0)" class="btn btn-soft-info btn-sm float-left mt-3 generate">
                            <i class="fa fa-plus-circle"></i> @lang('Included Facility')</a>
                    </div>
                    <div class="row addedField mt-3 col-md-10">
                        @if (isset($package->facility))
                            @foreach($package->facility as $key => $value)
                                <div class="col-md-6 pb-2">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input name="facility[]" class="form-control" type="text"
                                                   value="{{ old('facility.'.$key, $value ?? '') }}"
                                                   required placeholder="{{ trans('Enter a included facility') }}">
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
                @error('facility')
                <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                @enderror
            </div>
        </div>
        <div class="col-12">
            <div class="mb-4">
                <div class=" justify-content-between">
                    <div class="form-group">
                        <a href="javascript:void(0)" class="btn btn-soft-success btn-sm float-left mt-3 generateExcluded">
                            <i class="fa fa-plus-circle"></i> @lang('Excluded Facility')</a>
                    </div>
                    <div class="row addedExcludedField mt-3 col-md-10">
                        @if (isset($package->excluded))
                            @foreach($package->excluded as $key => $value)
                                <div class="col-md-6 pb-2">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input name="excluded[]" class="form-control" type="text"
                                                   value="{{ old('excluded.'.$key, $value ?? '') }}"
                                                   required placeholder="{{ trans('Enter a excluded facility') }}">
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
                </div>@error('excluded')
                <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                @enderror

            </div>
        </div>
        <div class="col-12">
            <div class="mb-4">
                <div class=" justify-content-between">
                    <div class="form-group">
                        <a href="javascript:void(0)" class="btn btn-soft-secondary btn-sm float-left mt-3 generateExpect">
                            <i class="fa fa-plus-circle"></i> @lang('What We Expect')</a>
                    </div>
                    <div class="row addedExpectField mt-3">
                        @if (isset($package->expected))
                            @foreach($package->expected as $key => $value)
                                <div class="col-md-6 pb-2 expectationArea">
                                    <div class="form-group">
                                        <div class="inputArea">
                                            <input name="expect[]" class="form-control expect" type="text"
                                                   value="{{ old('expect.'.$key, $value->expect ?? '') }}"
                                                   required placeholder="{{ trans('Enter a expect title') }}">

                                            <textarea name="expect_details[]" class="form-control mt-2"
                                                      rows="4"
                                                      placeholder="Expectation details">{{ $value->expect_detail }}</textarea>
                                        </div>
                                        <div class="deleteExpectArea ms-1">
                                            <div class="input-group-btn">
                                                <button class="btn btn-white delete-btn" type="button" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                @error('expect')
                <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                @enderror
                @error('expect_details')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-md-12">
            <div class="d-flex align-items-center justify-content-between pb-2">
                <label class="pt-2 form-label" for="details">@lang('Package Details')<span class="text-danger ps-1">*</span></label>
                @if(isAiAccess())
                    <button class="btn btn-soft-primary btn-sm detailsGenerateBtn" type="button" data-bs-toggle="offcanvas" data-bs-target="#detailsGenerateOffcanvas" aria-controls="detailsGenerateOffcanvas" id="generateDetailsBtn"><i class="bi bi-lightning pe-1"></i>@lang('Use AI')</button>
                    <div class="offcanvas offcanvas-end" tabindex="-1" id="detailsGenerateOffcanvas" aria-labelledby="detailsGenerateOffcanvasLabel">
                    <div class="offcanvas-header">
                        <h5 id="offcanvasRightLabel"><i class="bi-gear me-1"></i>@lang('Write with '. basicControl()->site_title.' AI assistant')</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <span class="text-dark font-weight-bold">@lang('Enhance your tour packages with AI! 🎯. Let us auto-generate rich, detailed descriptions to attract more bookings. Want to try it now?')</span>
                        <div class="col-12 mb-4 mt-4">
                            <span class="text-cap text-body">@lang("Type your package title")</span>
                            <textarea class="form-control" id="title" rows="4" placeholder="@lang('e.g. Athens : A Historical Start')" autocomplete="off">{{ $package->title }}</textarea>
                        </div>
                        <div class="col-12 mb-4 mt-2">
                            <span class="text-cap text-body">@lang("Max Result Length")</span>
                            <input type="number" class="form-control" id="length" placeholder="e.g. 30" autocomplete="off">
                        </div>
                        <div class="row gx-2">
                            <div class="col">
                                <div class="d-grid">
                                    <button type="button" class="btn btn-primary btn-sm" id="generateBtn"><i class="bi bi-lightning"></i> @lang('Generate')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

            </div>
            <textarea
                name="details"
                class="form-control summernote"
                cols="30"
                rows="5"
                id="packdetails"
                placeholder="Package details"
            >{{ $package->description }}</textarea>
            @error('details')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
    <div class="bottom-area">
        <button type="button" class="btn btn-secondary btn-sm"
                data-hs-step-form-prev-options='{ "targetSelector": "#stepGeneralInfo" }'>
            <i class="bi-arrow-bar-left pe-1"></i>@lang('Previous')
        </button>
        <button type="button" class="btn btn-primary btn-sm"
                data-hs-step-form-next-options='{ "targetSelector": "#stepImages" }'>
            @lang('Next')<i class="bi-arrow-bar-right ps-1"></i>
        </button>
    </div>
</div>
@push('style')
    <link rel="stylesheet" href="{{ asset(template(true).'css/summernote-lite.min.css') }}">
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
    </style>
@endpush
@push('script')
    <script src="{{ asset(template(true).'js/summernote-lite.min.js') }}"></script>

    <script>
        "use strict";

        $(document).ready(function(){
            $(".generate").on('click', function () {
                let lang = $(this).data();
                let form = `<div class="col-md-6 pb-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input name="facility[]" class="form-control " type="text" value="" required placeholder="{{trans('Enter a facility')}}">

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
            $(".generateExcluded").on('click', function () {
                let lang = $(this).data();
                let form = `<div class="col-md-6 pb-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input name="excluded[]" class="form-control " type="text" value="" required placeholder="{{trans('Enter a excluded facility')}}">

                                        <span class="input-group-btn">
                                            <button class="btn btn-white delete_desc" type="button">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div> `;
                $(this).parents('.form-group').siblings('.addedExcludedField').append(form)
            });
            $(".generateExpect").on('click', function () {
                let form = `<div class="col-md-6 pb-2 expectationArea">
                                <div class="form-group">
                                    <div class="inputArea">
                                        <input name="expect[]" class="form-control expect" type="text" value="" required placeholder="{{trans('Enter a expect title')}}">
                                        <textarea
                                            name="expect_details[]"
                                            class="form-control summernote"
                                            cols="30"
                                            rows="5"
                                            id="details"
                                            placeholder="Expectation details"
                                        ></textarea>
                                    </div>
                                    <div class="deleteExpectArea ms-1">
                                        <span class="input-group-btn">
                                            <button class="btn btn-white delete_desc" type="button">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div> `;
                $(this).parents('.form-group').siblings('.addedExpectField').append(form)
            });

            $(document).on('click', '.delete_desc', function () {
                $(this).closest('.col-md-6').remove();
            });

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
        $('#generateBtn').on('click', function () {
            var title = $('#title').val().trim();
            var length = $('#length').val().trim();

            if (!title || !length) {
                Notiflix.Notify.failure('Please fill in both fields.');
                return;
            }

            $.ajax({
                url: '{{ route('user.ai.generate') }}',
                method: 'POST',
                data: {
                    title: title,
                    length: length,
                    _token: '{{ csrf_token() }}'
                },
                beforeSend: function () {
                    $('#generateBtn').prop('disabled', true).html(`
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        @lang('Generating...')
                    `);
                },
                success: function (response) {
                    $('#packdetails').summernote('code', response.ai_response);
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    Notiflix.Notify.failure('An error occurred while generating the description.');
                },
                complete: function () {
                    $('#generateBtn').prop('disabled', false).html('<i class="bi bi-lightning"></i> @lang('Generate')');
                }
            });
        });
    </script>
@endpush
