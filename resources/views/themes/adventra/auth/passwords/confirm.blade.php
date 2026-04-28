@extends('layouts.app')
@section('title','Confirm Password')


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
                        <h4 class="mt-3 pt-4 text-center">@lang('Confirm Password')</h4>
                        <p class="mt-3 pt-4 text-center">@lang('Please confirm your password before continuing.')</p>
                        <div class="contact-form">
                            @if (session('status'))
                                <div class="alert alert-success mt-3" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <form action="{{ route('password.confirm') }}" method="post" class="php-email-form">
                                @csrf

                                <div class="input-content">
                                    <label for="username"></label>
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <button type="submit"  class="theme-btn w-100 justify-content-center"> @lang('Confirm Password')  <span></span></button>
                            </form>
                        </div>
                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
