<!DOCTYPE html>
<html lang="{{ session()->get('lang') }}" dir="{{ optional(defaultLang())->rtl == 1 ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta content="{{ isset($pageSeo['meta_description']) ? $pageSeo['meta_description'] : '' }}" name="description">
    <meta content="{{ is_array(@$pageSeo['meta_keywords']) ? implode(', ', @$pageSeo['meta_keywords']) : @$pageSeo['meta_keywords'] }}" name="keywords">
    <meta name="theme-color" content="{{ basicControl()->primary_color }}">
    <meta name="author" content="{{basicControl()->site_title}}">
    <meta name="robots" content="{!! isset($pageSeo['meta_robots']) ? $pageSeo['meta_robots'] : ''  !!}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ isset(basicControl()->site_title) ? basicControl()->site_title : '' }}">
    <meta property="og:title" content="{{ isset($pageSeo['meta_title']) ? $pageSeo['meta_title'] : '' }}">
    <meta property="og:description" content="{{ isset($pageSeo['og_description']) ? $pageSeo['og_description'] : '' }}">
    <meta property="og:image" content="{{ @$pageSeo['meta_image'] }}">
    <meta name="twitter:card" content="{{ isset($pageSeo['meta_title']) ? $pageSeo['meta_title'] : '' }}">
    <meta name="twitter:title" content="{{ isset($pageSeo['meta_title']) ? $pageSeo['meta_title'] : '' }}">
    <meta name="twitter:description" content="{{ isset($pageSeo['meta_description']) ? $pageSeo['meta_description'] : '' }}">
    <meta name="twitter:image" content="{{ @$pageSeo['meta_image'] }}">

    <title> {{basicControl()->site_title}} @if(isset($pageSeo['page_title']))
            | {{str_replace(basicControl()->site_title, ' ',$pageSeo['page_title'])}}
        @else
             | @yield('title')
        @endif</title>

    <!-- Favicons -->
    <link href="{{ getFile(basicControl()->favicon_driver, basicControl()->favicon) }}" rel="icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset(template(true) . 'css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset(template(true) . 'css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset(template(true) . 'css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset(template(true) . 'css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset(template(true) . 'css/intlTelInput.min.css') }}" rel="stylesheet">
    <link href="{{ asset(template(true) . 'css/magnific-popup.css') }}" rel="stylesheet">
    <link href="{{ asset(template(true) . 'css/meanmenu.css') }}" rel="stylesheet">
    <link href="{{ asset(template(true) . 'css/odometer.css') }}" rel="stylesheet">
    <link href="{{ asset(template(true) . 'css/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset(template(true) . 'css/datepickerboot.css') }}" rel="stylesheet">
    <link href="{{ asset(template(true) . 'css/nice-select.css') }}" rel="stylesheet">
    <link href="{{ asset(template(true) . 'css/flatpickr.min.css') }}" rel="stylesheet">
    <link href="{{ asset(template(true) . 'css/main.css') }}" rel="stylesheet">
    <link href="{{ asset(template(true) . 'css/style.css') }}" rel="stylesheet">

    @stack('css-lib')
    @stack('style')

    @laravelPWA

</head>

<body class="{{ optional(defaultLang())->rtl == 1 ? 'rtlSet' : 'ltrSet' }}">

@include(template().'partials.preloader')
@include(template().'partials.fixed_area')
@if(!Route::is('login') && !Route::is('register'))
    @include(template().'partials.header')
@endif
@if(!request()->is('/') && !request()->is('/'))
    @if(isset($pageSeo) && $pageSeo['breadcrumb_status'] == 1)
        <div class="breadcrumb-wrapper section-padding  bg-cover" style="background-image: url({{ $pageSeo['breadcrumb_image'] }});">
            <div class="container-fluid">
                <div class="page-heading">
                    <ul class="breadcrumb-items wow fadeInUp" data-wow-delay=".3s">
                        <li>
                            <a href="{{ route('page','/') }}">
                                @lang('Home')
                            </a>
                        </li>
                        <li>
                            <span class="slash-icon">/</span>
                        </li>
                        <li>
                            {{ $pageSeo['page_title'] ?? '?' }}
                        </li>
                    </ul>
                    <h1 class="wow fadeInUp" data-wow-delay=".5s">{{ $pageSeo['page_title'] ?? '?' }}</h1>
                </div>
            </div>
            <div class="plane-shape float-bob-x">
                <img src="{{ asset(template(true).'img/breadcrumb-plane.png') }}" alt="img">
            </div>
        </div>
    @endif
@endif
@yield('content')
@if(!Route::is('login') && !Route::is('register'))
    @include(template().'sections.footer')
@endif

<button id="back-top" class="back-to-top">
    <i class="fas fa-long-arrow-up"></i>
</button>


<!-- Vendor JS Files -->
<script src="{{ asset(template(true) . 'js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset(template(true) . 'js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset(template(true) . 'js/jquery.nice-select.min.js') }}"></script>
<script src="{{ asset(template(true) . 'js/odometer.min.js') }}"></script>
<script src="{{ asset(template(true) . 'js/jquery.appear.min.js') }}"></script>
<script src="{{ asset(template(true) . 'js/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset(template(true) . 'js/swiper-bundle.min.js') }}"></script>
<script src="{{ asset(template(true) . 'js/jquery.meanmenu.min.js') }}"></script>
<script src="{{ asset(template(true) . 'js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset(template(true) . 'js/flatpickr.min.js') }}"></script>
<script src="{{ asset(template(true) . 'js/wow.min.js') }}"></script>

<script src="{{ asset(template(true) . 'js/main.js') }}"></script>

<script src="{{ asset('assets/global/js/notiflix-aio-3.2.6.min.js') }}"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>


@stack('js-lib')

@stack('script')

@if (session()->has('success'))
    <script>
        Notiflix.Notify.success("@lang(session('success'))");
    </script>
@endif

@if (session()->has('error'))
    <script>
        Notiflix.Notify.failure("@lang(session('error'))");
    </script>
@endif

@if (session()->has('warning'))
    <script>
        Notiflix.Notify.warning("@lang(session('warning'))");
    </script>
@endif

@include(template().'partials.pwa')
@if(basicControl()->cookie_status == 1 && auth()->user())
    @include(template().'partials.cookie')
@endif
@include('plugins')
</body>

</html>


