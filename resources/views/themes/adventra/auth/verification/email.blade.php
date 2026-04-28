@extends(template().'layouts.app')
@section('title',$page_title)

@section('content')

    <section class="login">
        <div class="bg-layer" style="background: url({{ asset(template(true).'img/tour/bg2.jpg') }});"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="contact-container">
                        <div class="logo mb-3">
                            <a href="{{ route('page','/') }}">
                                <img src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}">
                            </a>
                        </div>
                        <h4 class="mt-3 pt-4 text-center">@lang('Verify Your Email')</h4>
                        <p class="mt-3 text-center">@lang("Your Email Address is") {!! maskEmail(auth()->user()->email) !!}</p>
                        <div class="contact-form">
                            <form action="{{ route('user.mail.verify') }}" method="post" class="php-email-form">
                                @csrf

                                <div class="input-content">
                                    <label for="username"></label>
                                    <input type="text" class="form-control cmn-input" name="code" value="{{old('code')}}" placeholder="@lang('Code')">
                                    @error('code')<span class="text-danger  mt-1">{{ $message }}</span>@enderror
                                    @error('error')<span class="text-danger  mt-1">{{ $message }}</span>@enderror
                                </div>

                                <button type="submit"  class="theme-btn w-100 justify-content-center"> @lang('Submit')  <span></span></button>
                            </form>
                        </div>
                        <div class="contact-form-footer">
                                <p>@lang('Didn\'t get Code? Click to') <a href="{{route('user.resend.code')}}?type=email"  class="text-info"> @lang('Resend code')</a></p>
                                @error('resend')
                                <p class="text-danger  mt-1">{{ $message }}</p>
                                @enderror
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection
