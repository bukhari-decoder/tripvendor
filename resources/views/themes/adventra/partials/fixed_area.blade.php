<div class="fix-area">
    @php
        $data = fixedAreaData();
    @endphp
    <div class="offcanvas__info">
        <div class="offcanvas__wrapper">
            <div class="offcanvas__content">
                <div class="offcanvas__top mb-5 d-flex justify-content-between align-items-center">
                    <div class="offcanvas__logo">
                        <a href="{{ route('page','/') }}">
                            <img src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}" alt="{{ basicControl()->site_title }}">
                        </a>
                    </div>
                    <div class="offcanvas__close">
                        <button>
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <div class="mobile-menu fix mb-3">

                    <ul>
                        @auth
                            <li class="dash mean-last">
                                <a class="nav-link text-capitalize" href="{{ route('user.dashboard') }}">@lang('Dashboard')</a>
                            </li>
                        @endauth
                        @guest
                            <li class="login mean-last">
                                <a class="nav-link text-capitalize" href="{{ route('login') }}">@lang('Login')</a>
                            </li>
                        @endguest
                    </ul>

                </div>

            </div>
        </div>
    </div>
</div>
<div class="offcanvas__overlay"></div>
