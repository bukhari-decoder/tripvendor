@extends(template().'layouts.app')
@section('title','Reset Password')
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
                        <h4 class="mt-3 pt-4 text-center">@lang('Reset Password')</h4>
                        <div class="contact-form">
                            @if (session('status'))
                                <div class="alert alert-success mt-3" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <form action="{{ route('password.email') }}" method="post" class="php-email-form">
                                @csrf

                                <div class="input-content">
                                    <label for="username"></label>
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="@lang('Email')" autofocus>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <button type="submit"  class="theme-btn w-100 justify-content-center"> @lang('Send Password Reset Link')  <span></span></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
