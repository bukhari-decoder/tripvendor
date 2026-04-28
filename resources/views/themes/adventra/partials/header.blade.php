<header class="header-section header-inner">
    @php
        $header = getHeaderData();
    @endphp
    @if (!(getHomeStyle() == 'home_103' && request()->is('/')))
        <div class="header-top-section">
            <div class="container-fluid">
                <div class="header-top-wrapper">
                    <ul class="top-left">
                        <li class="text-lowercase">
                            <i class="far fa-envelope"></i>
                            <a href="mailto:{{ $header['single']->description->mail_value ?? '' }}">
                                {{ $header['single']->description->mail_value ?? '' }}
                            </a>
                        </li>
                        <li>
                            <i class="far fa-map-marker-alt"></i>
                            {{ $header['single']->description->address ?? '' }}
                        </li>
                    </ul>
                    <div class="social-icon">
                        <a href="{{ $header['single']->description->facebook ?? '' }}"><i class="fab fa-facebook-f"></i></a>
                        <a href="{{ $header['single']->description->instagram ?? '' }}"><i class="fab fa-instagram"></i></a>
                        <a href="{{ $header['single']->description->twitter ?? '' }}"><i class="fab fa-twitter"></i></a>
                        <a href="{{ $header['single']->description->linkedin ?? '' }}"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
    @endif


    @php
        if (getHomeStyle() == 'home_103' && request()->is('/')){
            $headerClass = 'header-section header-1 header-2 header-3';
        }else{
            $headerClass = 'header-1 header-4';
        }
    @endphp
    <div id="header-sticky" class="{{ $headerClass }}">
        <div class="container-fluid">
            <div class="mega-menu-wrapper">
                <div class="header-main">
                    <div class="header-left">
                        <div class="logo">
                            <a href="{{ route('page','/') }}" class="header-logo">
                                <img src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}" alt="logo-img">
                            </a>
                            <a href="{{ route('page','/') }}" class="header-logo-2">
                                <img src="{{ getFile(basicControl()->admin_dark_mode_logo_driver, basicControl()->admin_dark_mode_logo) }}" alt="logo-img">
                            </a>
                        </div>
                    </div>
                    <div class="header-right d-flex justify-content-end align-items-center">
                        <div class="mean__menu-wrapper">
                            <div class="main-menu">
                                <nav id="mobile-menu">
                                    {!! renderHeaderMenu(getHeaderMenuData()) !!}
                                </nav>
                            </div>
                        </div>
                        @guest
                            <div class="header-login">
                                <a class="login" href="{{ route('login') }}" title="@lang('Login')"><i class="fas fa-sign-in-alt"></i></a>
                            </div>
                        @endguest
                        @auth
                            <div class="header-login">
                                <a class="dash" href="{{ route('user.dashboard') }}" title="@lang('Dashboard')"><i class="far fa-user"></i></a>
                            </div>
                        @endauth
                        <div class="header-search">
                            <button class="d-flex align-items-center search-toggle"><i class="far fa-search"></i></button>
                        </div>
                        <a href="{{ route('page','packages') }}" class="theme-btn">
                            <span>@lang('Explore More')</span> <i class="far fa-long-arrow-right"></i>
                        </a>
                        <div class="header__hamburger d-xl-none my-auto">
                            <div class="sidebar__toggle">
                                <div class="header-bar">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Search Section Start -->
<div class="header-search-bar d-flex align-items-center">
    <button class="search-close">×</button>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="search-bar">
                    <div class="contact-form-box contact-search-form-box">
                        <form action="#">
                            <input type="text" name="search" id="searchInput" placeholder="Search here..." autocomplete="off">
                            <button type="submit"><i class="far fa-search"></i></button>
                        </form>
                        <p>@lang('Type above and press Enter to search. Press Close to cancel.')</p>
                    </div>
                </div>
                <div id="searchResults" class="search-results-box d-none"></div>
            </div>
        </div>
    </div>
</div>
@push('style')
    <style>
        .submenu {
            display: none;
        }

        .submenu.open {
            display: block;
        }

    </style>
@endpush

@push('script')
    <script>
        $(document).ready(function(){
            $('#searchInput').keyup(function(){
                let searchTerm = $(this).val();
                $.ajax({
                    url: '{{ route('top.search') }}',
                    method: 'GET',
                    data: {
                        query: searchTerm
                    },
                    success: function(response) {
                        $('#searchResults').removeClass('d-none');

                        if (response.length <= 0){
                            $('#searchResults').addClass('d-none');
                        }
                        let html = '';

                        if (response.length > 0) {
                            response.forEach(item => {
                                html += `
                                    <div class="search-result-item d-flex align-items-center">
                                        <img src="${item.image}" alt="${item.title}" class="result-thumb">
                                        <div class="result-text">
                                            <a href="${item.url}" class="result-title">${item.title}</a>
                                            <div class="result-type badge badge-secondary"><i class="fas fa-medal me-1"></i>${item.type}</div>
                                        </div>
                                    </div>
                                `;
                            });
                        } else {
                            html = '<div class="search-result-item">No results found.</div>';
                        }

                        $('#searchResults').html(html).show();
                    },
                    error: function() {
                        $('#searchResults').addClass('d-none');
                    }
                });
            });
        });
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".dropdown-toggle").forEach(function (toggle) {
                toggle.addEventListener("click", function (e) {
                    e.preventDefault();

                    const parentLi = this.closest("li.has-dropdown");
                    const submenu = parentLi.querySelector(".submenu");
                    const icon = this.querySelector("i");

                    if (submenu) {
                        const isVisible = submenu.style.display === "block";
                        submenu.style.display = isVisible ? "none" : "block";

                        if (icon) {
                            icon.className = isVisible ? "far fa-chevron-down" : "far fa-chevron-up";
                        }
                    }
                });
            });
        });


    </script>
@endpush
