@extends(template().'layouts.app')
@section('title',trans('Register'))
@section('content')

    <section class="login dif">
        <div class="bg-layer" style="background: url({{ $content->image }});"></div>
        <div class="container">
            <div class="row">
                <div class="col-xl-8 offset-xl-2">
                    <div class="contact-container">
                        <div class="logo mb-3">
                            <a href="{{ route('page','/') }}">
                                <img src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}">
                            </a>
                        </div>
                        <h3 class="text-center ">{{ $content->title ?? '' }}</h3>
                        <div class="contact-form mt-3">
                            <form action="{{ route('register') }}" method="post" class="php-email-form">
                                @csrf

                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <div class="input-content">
                                            <input type="text" class="form-control cmn-input" id="fname" name="first_name" placeholder="@lang('First Name')" value="{{ old('first_name') }}">
                                            @error('fname')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="input-content">
                                            <input type="text" class="form-control cmn-input" id="lname" name="last_name" placeholder="@lang('Last Name')" value="{{ old('last_name') }}">
                                            @error('lname')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="input-content">
                                            <input type="text" class="form-control cmn-input" id="username" name="username" placeholder="@lang('User Name')" value="{{ old('username') }}">
                                            @error('username')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="input-content">
                                            <input type="email" class="form-control cmn-input" id="email" name="email" placeholder="@lang('Email')" value="{{ old('email') }}">
                                            @error('email')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="input-content">
                                            <select id="country" class="form-control js-select" name="country">
                                                <option value="" disabled selected>@lang('Select Country')</option>
                                                @foreach($countries as $item)
                                                    <option value="{{ $item->name }}" data-phone_code="{{ $item->phone_code }}" {{ old('country') == $item->name ? 'selected' : '' }}>@lang($item->name)</option>
                                                @endforeach
                                            </select>
                                            @error('country')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="input-content">
                                            <div class="register-form">
                                                <input type="hidden" id="phone_code" class="form-control cmn-input" name="phone_code">
                                                <input type="text" id="telephone" class="form-control cmn-input" name="phone" value="{{ old('phone') }}"  placeholder="@lang('Phone...')">

                                                @error('phone')
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="input-content">
                                            <div class="password-wrapper">
                                                <input type="password" class="form-control cmn-input" id="password" name="password" value="{{ old('password') }}" placeholder="@lang('Password')">
                                                <span toggle="#password" class="fa fa-eye toggle-password"></span>
                                            </div>
                                            @error('password')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-6">
                                        <div class="input-content">
                                            <div class="password-wrapper">
                                                <input type="password" class="form-control cmn-input" id="repeat_password" name="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="@lang('Repeat Password')">
                                                <span toggle="#repeat_password" class="fa fa-eye toggle-password"></span>
                                            </div>
                                            @error('password_confirmation')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <span id="password-error" class="text-danger d-none">@lang('Passwords do not match')</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        @if($basicControl->google_recaptcha == 1 && $basicControl->google_recaptcha_register == 1)
                                            <div class="row mt-3 mb-4">
                                                <div class="g-recaptcha @error('g-recaptcha-response') is-invalid @enderror" data-sitekey="{{ env('GOOGLE_RECAPTCHA_SITE_KEY') }}"></div>
                                                @error('g-recaptcha-response')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        @if($basicControl->manual_recaptcha == 1 && $basicControl->manual_recaptcha_register == 1)
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
                                                <div class="input-group capcha input-group-merge" data-hs-validation-validate-class>
                                                    <img src="{{route('captcha').'?rand='. rand()}}" id='captcha_image'>
                                                    <a class="input-group-append input-group-text"
                                                       href='javascript: refreshCaptcha();'>
                                                        <i class="far fa-refresh text-primary"></i>
                                                    </a>
                                                </div>
                                            </div>

                                        @endif
                                    </div>
                                </div>

                                <div class="checkbox-input">
                                    <input type="checkbox" class="form-check-input">
                                    <label>@lang('Remember me on the device ?')</label>
                                </div>
                                <button type="submit"  class="theme-btn w-100 justify-content-center">{{ $content->button_name }}<span></span></button>
                            </form>
                        </div>
                        <div class="contact-form-footer">
                            <p>{{ $content->sign_in_title }}? <a href="{{ route('login') }}">{{ $content->sign_in_button_name }}</a></p>
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
    <link rel="stylesheet" href="{{ asset(template(true) . 'css/intlTelInput.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <style>
        .ts-control{
            padding: 12px !important;
        }
        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }
    </style>
@endpush

@push('script')
    <script src="{{ asset(template(true) . 'js/intlTelInput.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new TomSelect('#country', {
                maxOptions: 250,
                placeholder: 'Select Country',
            });
        });
        document.addEventListener("DOMContentLoaded", function () {
            const password = document.getElementById("password");
            const repeatPassword = document.getElementById("repeat_password");
            const errorMsg = document.getElementById("password-error");

            const toggleIcons = document.querySelectorAll(".toggle-password");
            toggleIcons.forEach(icon => {
                icon.addEventListener("click", function () {
                    const input = document.querySelector(this.getAttribute("toggle"));
                    const type = input.getAttribute("type") === "password" ? "text" : "password";
                    input.setAttribute("type", type);
                    this.classList.toggle("fa-eye");
                    this.classList.toggle("fa-eye-slash");
                });
            });

            repeatPassword.addEventListener("keyup", function () {
                const passVal = password.value;
                const repeatVal = repeatPassword.value;

                if (passVal === repeatVal) {
                    errorMsg.classList.add("d-none");
                } else {
                    errorMsg.classList.remove("d-none");
                }
            });
        });

        $(document).ready(function () {


            const input = document.querySelector("#telephone");
            const iti = window.intlTelInput(input, {
                initialCountry: "us",
                separateDialCode: true,
            });

            document.querySelector("#phone_code").value = '+' + iti.getSelectedCountryData().dialCode;

            input.addEventListener("countrychange", function () {
                const selectedCountryData = iti.getSelectedCountryData();
                document.querySelector("#phone_code").value = '+' + selectedCountryData.dialCode;
            });

            const password = document.querySelector('.password');
            const passwordIcon = document.querySelector('.password-icon');

            passwordIcon.addEventListener("click", function () {
                if (password.type == 'password') {
                    password.type = 'text';
                    passwordIcon.classList.add('fa-eye-slash');
                } else {
                    password.type = 'password';
                    passwordIcon.classList.remove('fa-eye-slash');
                }
            })

            const confirmPassword = document.querySelector('.confirm_password');
            const confirmPasswordIcon = document.querySelector('.confirm-password-icon');

            confirmPasswordIcon.addEventListener("click", function () {
                if (confirmPassword.type == 'password') {
                    confirmPassword.type = 'text';
                    confirmPasswordIcon.classList.add('fa-eye-slash');
                } else {
                    confirmPassword.type = 'password';
                    confirmPasswordIcon.classList.remove('fa-eye-slash');
                }
            })
        });

        function refreshCaptcha() {
            let img = document.images['captcha_image'];
            img.src = img.src.substring(
                0, img.src.lastIndexOf("?")
            ) + "?rand=" + Math.random() * 1000;
        }

    </script>
@endpush
