@extends('admin.layouts.app')
@section('page_title', __('Home Variations'))
@section('content')
    <div class="content container-fluid" id="homeStyles">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0)">@lang('Dashboard')</a></li>
                            <li class="breadcrumb-item active"
                                aria-current="page">@lang("Home Variations")</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang("Home Variations")</h1>
                </div>
            </div>
        </div>
        <div class="row d-flex justify-content-center">
            <div class="col-12">
                <div class="row d-flex justify-content-center">

                    @foreach(config('themes')[$basicControl->theme]['home_version'] as $key => $homeVersion)

                        <div class="col-sm-12 col-lg-4 mb-3 mb-lg-5">
                            <div class="select-theme">
                                <label class="form-control" for="formControlRadioReverseEg{{$key}}">
                                  <span class="form-check">
                                    <input type="radio" class="form-check-input" name="homeStyle" value="{{ $key }}" data-name="{{$homeVersion['name']}}" id="formControlRadioReverseEg{{$key}}" @checked($key == $basicControl->home_style)>
                                     <img class="img-fluid w-100 homeStyleImage" src="{{ asset($homeVersion['preview_link']) }}"
                                          alt="Image Description">
                                  </span>
                                </label>
                            </div>
                            <div class="text-center">
                                <h5 class="mb-0 bg-warning p-3">@lang($homeVersion['name'])</h5>
                            </div>
                        </div>
                    @endforeach


                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).ready(function() {
            $('.form-check-input').on('change', function() {
                if ($(this).prop('checked')) {
                    let radioValue = $(this).val();
                    let title = $(this).data('name');
                    $.ajax({
                        url: '{{ route('admin.select.home.style') }}',
                        type: 'GET',
                        data: {
                            val: radioValue,
                            title: title
                        },
                        success: function (response) {
                            Notiflix.Notify.success(response.message);
                        },
                        error: function (xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            });



        });
    </script>
@endpush
