@extends($extends)
@section('title')
    {{ 'Pay with '.optional($deposit->gatewayable)->name ?? '' }}
@endsection

@section('content')
    <section class="manual-payment feature-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="feature-box">
                    <div class="col-md-12">
                        <h3 class="title text-center">{{trans('Please follow the instruction below')}}</h3>
                        <p class="text-center mt-2 ">{{trans('You have requested to payment')}} <b class="text--base">{{currencyPosition($deposit->payable_amount_in_base_currency)}}</b> , {{trans('Please pay')}}
                            <b class="text--base">{{getAmount($deposit->payable_amount)}} {{$deposit->payment_method_currency}}</b> {{trans('for successful payment')}}
                        </p>

                        <p class=" mt-2 ">
                            <?php echo optional($deposit->gatewayable)->note; ?>
                        </p>

                        <form action="{{route('addFund.fromSubmit',$deposit->trx_id)}}" method="post"
                              enctype="multipart/form-data"
                              class="form-row  preview-form">
                            @csrf
                            @if(optional($deposit->gatewayable)->parameters)
                                @foreach($deposit->gatewayable->parameters as $k => $v)
                                    @if($v->type == "text")
                                        <div class="col-md-12 mt-2">
                                            <div class="form-group  ">
                                                <label>{{trans($v->field_label)}} @if($v->validation == 'required')
                                                        <span class="text--danger">*</span>
                                                    @endif </label>
                                                <input type="text" name="{{$k}}"
                                                       class="form-control bg-transparent"
                                                       @if($v->validation == "required") required @endif>
                                                @if ($errors->has($k))
                                                    <span
                                                        class="text-danger">{{ trans($errors->first($k)) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        @elseif($v->type == "number")
                                            <div class="col-md-12 mt-2">
                                                <div class="form-group  ">
                                                    <label>{{trans($v->field_label)}} @if($v->validation == 'required')
                                                            <span class="text--danger">*</span>
                                                        @endif </label>
                                                    <input type="text" name="{{$k}}"
                                                           class="form-control bg-transparent"
                                                           @if($v->validation == "required") required @endif>
                                                    @if ($errors->has($k))
                                                        <span
                                                            class="text-danger">{{ trans($errors->first($k)) }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                    @elseif($v->type == "textarea")
                                        <div class="col-md-12 mt-2">
                                            <div class="form-group">
                                                <label>{{trans($v->field_label)}} @if($v->validation == 'required')
                                                        <span class="text--danger">*</span>
                                                    @endif </label>
                                                <textarea name="{{$k}}" class="form-control bg-transparent"
                                                          rows="3"
                                                          @if($v->validation == "required") required @endif></textarea>
                                                @if ($errors->has($k))
                                                    <span
                                                        class="text-danger">{{ trans($errors->first($k)) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @elseif($v->type == "file")
                                        <div class="col-md-6 form-group">
                                            <label>
                                                {{ trans($v->field_label) }}
                                                @if($v->validation == 'required')
                                                    <span class="text--danger">*</span>
                                                @endif
                                            </label>

                                            <div class="custom-image-upload">
                                                <label for="image" class="upload-icon">
                                                    <i class="fas fa-upload"></i>
                                                </label>

                                                <input type="file" name="{{$k}}" id="image" accept="image/*"
                                                       @if($v->validation == "required") required @endif>

                                                <img id="image_preview_container"
                                                     src="{{ getFile(config('filelocation.default')) }}"
                                                     alt="@lang('Upload Image')"
                                                     class="preview-image">
                                            </div>

                                            @error($k)
                                            <span class="text-danger">@lang($message)</span>
                                            @enderror
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                            <div class="col-md-12 ">
                                <div class=" form-group">
                                    <button type="submit" class="theme-btn w-100 mt-3">
                                        <span>@lang('Confirm Now')</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('style')
    <style>
        .theme-btn {
            padding: 17px 15px 17px 30px !important;
        }
    </style>
@endpush

@push('script')
    <script>
        'use strict'
        document.querySelector('#image').addEventListener('change', function () {
            const preview = document.getElementById('image_preview_container');
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
