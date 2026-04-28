@php
    $footer = footerData();
    $socialData = getSocialData();
@endphp
@if(isset($footer) && !empty($footer['single']))
    <footer class="footer-section fix section-bg bg-cover" style="background-image: url('<?php
        echo (basicControl()->home_style == 'home_102')
            ? getFile($footer['single']['media']->background_image_two->driver, $footer['single']['media']->background_image_two->path)
            : getFile($footer['single']['media']->background_image->driver, $footer['single']['media']->background_image->path);
        ?>');
        ">
        <div class="container">
            <form method="post" action="{{ route('subscribe') }}">
                @csrf
                <div class="footer-newsletter-items">
                    <h2>{{ $footer['single']['news_letter_title'] ?? '' }}</h2>

                    <div class="footer-input">
                        <input type="email" id="email2" placeholder="Enter your email" name="contactEmail">
                        <button class="newsletter-btn" type="submit">
                            {{ $footer['single']['news_letter_button'] ?? '' }} <i class="far fa-search"></i>
                        </button>
                        @error('contactEmail')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
            </form>
            <div class="footer-wrapper">
                <div class="row">
                    <div class="col-xl-5 col-lg-7 col-md-12">
                        <div class="footer-widget-items">
                            <div class="widget-title">
                                <a href="{{ route('page','/') }}" class="footer-logo">
                                    <img src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}" alt="{{ basicControl()->site_title }}">
                                </a>
                            </div>
                            <div class="footer-content">
                                <div class="contact-info-items">
                                    <div class="contact-items">
                                        <div class="icon">
                                            <img src="{{ asset(template(true).'img/call.png') }}" alt="@lang('Phone')">
                                        </div>
                                        <div class="content">
                                            <span>{{ $footer['single']['call_text'] ?? '' }}</span>
                                            <h6><a href="tel:{{ $footer['single']['call_value'] ?? '' }}">{{ $footer['single']['call_value'] ?? '' }}</a></h6>
                                        </div>
                                    </div>

                                    <div class="contact-items">
                                        <div class="icon">
                                            <img src="{{ asset(template(true).'img/mail.png') }}" alt="img">
                                        </div>
                                        <div class="content">
                                            <span>{{ $footer['single']['mail_text'] ?? '' }}</span>
                                            <h6 class="transform-none"><a href="mailto:{{ $footer['single']['mail_value'] ?? '' }}">{{ $footer['single']['mail_value'] ?? '' }}</a></h6>
                                        </div>
                                    </div>
                                </div>
                                <h6 class="app-text">{{ $footer['single']['app_text'] ?? '' }}</h6>
                                <div class="apps-items">
                                    <a href="{{ $footer['single']['media']->apple_store_link ?? '' }}">
                                        <img src="{{ asset(template(true).'img/apply-store.png') }}" alt="img">
                                    </a>
                                    <a href="{{ $footer['single']['media']->play_store_link ?? '' }}">
                                        <img src="{{ asset(template(true).'img/play-store.jpg') }}" alt="img">
                                    </a>
                                </div>
                                <div class="social-icon">
                                    <a href="{{ $footer['single']['facebook'] }}"><i class="fab fa-facebook-f"></i></a>
                                    <a href="{{ $footer['single']['instagram'] }}"><i class="fab fa-instagram"></i></a>
                                    <a href="{{ $footer['single']['twitter'] }}"><i class="fab fa-twitter"></i></a>
                                    <a href="{{ $footer['single']['linkedin'] }}"><i class="fab fa-linkedin-in"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-5 col-md-4 col-sm-6">
                        <div class="footer-widget-items">
                            <div class="widget-title">
                                <h3>@lang('Quick Links')</h3>
                            </div>
                            <ul class="list-items">
                                @if(getFooterMenuData('useful_link') != null)
                                    @foreach(getFooterMenuData('useful_link') as $list)
                                        {!! $list !!}
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6">
                        <div class="footer-widget-items">
                            <div class="widget-title">
                                <h3>@lang('Useful Links')</h3>
                            </div>
                            <ul class="list-items">
                                @if(getFooterMenuData('support_link') != null)
                                    @foreach(getFooterMenuData('support_link') as $list)
                                        {!! $list !!}
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-4">
                        <div class="footer-widget-items">
                            <div class="widget-title">
                                <h3>{{ $footer['single']['gallery_text'] ?? '' }}</h3>
                            </div>
                            <div class="footer-gallery">
                                @php
                                    $items = collect($footer['multiple'] ?? []);
                                    $chunks = $items->chunk(ceil($items->count() / 3));
                                @endphp

                                <div class="gallery-wrap">
                                    @foreach($chunks as $chunk)
                                        <div class="gallery-item">
                                            @foreach($chunk as $item)
                                                <div class="thumb">
                                                    <a href="{{ getFile(@$item['media']->background_image->driver, @$item['media']->background_image->path) }}" class="img-popup">
                                                        <img src="{{ getFile($item['media']->image->driver, $item['media']->image->path) }}" alt="gallery-img">
                                                        <div class="icon">
                                                            <i class="far fa-plus"></i>
                                                        </div>
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom-1">
            <div class="container">
                <div class="footer-bottom-wrapper d-flex align-items-center justify-content-between">
                    <p class="wow fadeInUp" data-wow-delay=".3s">
                        {{ $footer['single']['copyright_text'] ?? '' }}
                    </p>
                    <div class="footer-lan">
                        <select class="nice-select single-select" id="language-select">
                            @foreach($footer['language'] ?? [] as $lan)
                                <option class="text-capitalize" value="{{ $lan['short_name'] }}"
                                    {{ session('lang') == $lan['short_name'] ? 'selected' : '' }}>
                                    {{ strtoupper($lan['short_name']) }}
                                </option>
                            @endforeach
                        </select>
                        <img src="{{ getFile($footer['defaultLanguage']['flag_driver'], $footer['defaultLanguage']['flag']) }}"
                             alt="{{ $footer['defaultLanguage']['name'] }}">
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            const languageRoute = "{{ route('language', ['code' => 'LANG_CODE']) }}";

            $('#language-select').on('change', function () {
                const selectedLang = $(this).val();
                const url = languageRoute.replace('LANG_CODE', selectedLang);

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function () {
                        location.reload();
                    },
                    error: function (err) {
                        console.error("Language change failed", err);
                    }
                });
            });
        });
    </script>
@endif

