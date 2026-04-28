@extends(template().'layouts.app')
@section('title','Create New Password')
@section('content')

    <section class="login">
        <div class="bg-layer" style="background: url({{ asset(template(true).'img/tour/bg2.jpg') }});"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="contact-container">
                        <div class="logo mb-3">
                            <a href="{{ route('page','/') }}">
                                <img src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}" alt="{{ basicControl()->site_title }}">
                            </a>
                        </div>
                        <h4 class="mt-3 pt-4 text-center">@lang('Reset Password')</h4>
                        <div class="contact-form">
                            @if (session('status'))
                                <div class="alert alert-success mt-3" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <form action="{{ route('password.reset.update') }}" method="post" class="php-email-form">
                                @csrf

                                <input type="hidden" name="token" value="{{ $token }}">
                                <input type="hidden" name="email" value="{{ $email }}">

                                <div class="input-content">
                                    <label for="username"></label>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="{{ old('password') }}" required autocomplete="off" placeholder="@lang('New Password')" autofocus>
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="input-content">
                                    <label for="username"></label>
                                    <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" value="{{ old('password_confirmation') }}" required autocomplete="off" placeholder="@lang('Retype Password')" autofocus>
                                    @error('password_confirmation')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <button type="submit"  class="theme-btn w-100 justify-content-center"> @lang('Reset Password')  <span></span></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
