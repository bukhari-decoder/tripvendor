@extends(template().'layouts.app')
@section('title',trans('Login'))
@section('content')
    <section class="login dif">
        <div class="bg-layer" style="background: url({{ $content->image }});"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="contact-container">
                        <div class="logo mb-3">
                            <a href="{{ route('page','/') }}">
                                <img src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}">
                            </a>
                        </div>
                        <h3 class="mt-3 text-center">{{ $content->title ?? '' }} </h3>
                        <div class="contact-form">
                            <form action="{{ route('login') }}" method="post" class="php-email-form">
                                @csrf

                                <div class="input-content">
                                    <label for="username">@lang('Username')</label>
                                    <input type="text" class="form-control cmn-input"
                                           value="{{old('username',config('demo.IS_DEMO') ? (request()->username ?? 'demouser') : '')}}"
                                           id="username" name="username" placeholder="@lang('Username')">
                                    @error('username')
                                    <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="input-content">
                                    <label for="password">@lang('Password')</label>
                                    <a href="{{ route('password.request') }}"
                                       class="fpwd">@lang('Forget your password?')</a>
                                    <div class="password-wrapper position-relative">
                                        <input type="password"
                                               value="{{old('password',config('demo.IS_DEMO') ? (request()->password ?? 'demouser') : '')}}"
                                               class="form-control cmn-input" id="password" name="password"
                                               placeholder="Password">
                                        <span toggle="#password" class="fa fa-eye-slash toggle-password"></span>
                                    </div>
                                    @error('password')
                                    <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                @if($basicControl->google_recaptcha == 1 && $basicControl->google_recaptcha_login == 1)
                                    <div class="row mt-4 mb-4">
                                        <div class="g-recaptcha @error('g-recaptcha-response') is-invalid @enderror"
                                             data-sitekey="{{ env('GOOGLE_RECAPTCHA_SITE_KEY') }}"></div>
                                        @error('g-recaptcha-response')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                @endif

                                @if($basicControl->manual_recaptcha == 1 && $basicControl->manual_recaptcha_login == 1)
                                    <div class="capcha-container mb-4">
                                        <div class="">
                                            <label class="form-label" for="captcha">@lang('Captcha Code')</label>
                                            <input type="text" tabindex="2"
                                                   class="form-control @error('captcha') is-invalid @enderror"
                                                   name="captcha" id="captcha" autocomplete="off"
                                                   placeholder="Enter Captcha" required>
                                            @error('captcha')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="input-group capcha input-group-merge"
                                             data-hs-validation-validate-class>
                                            <img src="{{route('captcha').'?rand='. rand()}}" id='captcha_image'>
                                            <a class="input-group-append input-group-text"
                                               href='javascript: refreshCaptcha();'>
                                                <i class="far fa-refresh text-primary"></i>
                                            </a>
                                        </div>
                                    </div>

                                @endif

                                <div class="checkbox-input">
                                    <input type="checkbox" class="form-check-input" name="remember"
                                           id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label for="remember">@lang('Remember me on the device ?')</label>
                                </div>
                                <button type="submit"
                                        class="theme-btn w-100 justify-content-center"> {{ $content->button_name ?? 'Log In' }}
                                    <span></span></button>
                            </form>
                        </div>
                        <div class="contact-form-footer">
                            <p> {{ $content->sign_up_title ?? '' }} <a
                                        href="{{ route('register') }}">{{ $content->sign_up_button_name ?? 'Create Account' }}</a>
                            </p>
                        </div>

                        @if(config('socialite.google_status') || config('socialite.facebook_status') || config('socialite.github_status'))
                            <div class="row g-2">
                                @if(config('socialite.google_status'))
                                    <div class="col-sm-4">
                                        <a href="{{route('socialiteLogin','google')}}" class="social-btn w-100"><img
                                                    src="{{ asset(template(true).'img/google.png') }}"
                                                    alt="Google Icon">@lang('Google')</a>
                                    </div>
                                @endif
                                @if(config('socialite.facebook_status'))
                                    <div class="col-sm-4">
                                        <a href="{{route('socialiteLogin','facebook')}}"
                                           class="social-btn w-100"><img
                                                    src="{{ asset(template(true).'img/facebook.png') }}"
                                                    alt="Facebook Icon">@lang('Facebook')</a>
                                    </div>
                                @endif
                                @if(config('socialite.github_status'))
                                    <div class="col-sm-4">
                                        <a href="{{route('socialiteLogin','github')}}" class="social-btn w-100"><img
                                                    src="{{ asset(template(true).'img/github.png') }}"
                                                    alt="Github Icon">@lang('Github')</a>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('style')
    <style>
        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d; /* optional: adjust icon color */
        }
    </style>
@endpush

@push('script')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <script>
        'use strict';

        function refreshCaptcha() {
            let img = document.images['captcha_image'];
            img.src = img.src.substring(
                0, img.src.lastIndexOf("?")
            ) + "?rand=" + Math.random() * 1000;
        }

        function refreshCaptcha2() {
            let img = document.images['captcha_image2'];
            img.src = img.src.substring(
                0, img.src.lastIndexOf("?")
            ) + "?rand=" + Math.random() * 1000;
        }

        document.addEventListener("DOMContentLoaded", function () {
            const toggle = document.querySelector(".toggle-password");
            const password = document.querySelector("#password");

            toggle.addEventListener("click", function () {
                const type = password.getAttribute("type") === "password" ? "text" : "password";
                password.setAttribute("type", type);
                this.classList.toggle("fa-eye-slash");
                this.classList.toggle("fa-eye");
            });
        });
    </script>
@endpush

