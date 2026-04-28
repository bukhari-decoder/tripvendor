<!-- ========== HEADER ========== -->
<header id="header" class="navbar navbar-expand-lg navbar-bordered navbar-spacer-y-0 flex-lg-column">
    <div class="navbar-dark w-100 bg-dark py-2">
        <div class="container">
            <div class="navbar-nav-wrap">
                <!-- Logo -->
                <a class="navbar-brand" href="{{ route('page','/') }}" aria-label="Front">
                    <img class="navbar-brand-logo"
                         src="{{ getFile(basicControl()->admin_dark_mode_logo_driver, basicControl()->admin_dark_mode_logo) }}"
                         alt="Logo">
                </a>
                <div class="navbar-nav-wrap-content-start">
                    <!-- Search Form -->
                    <div class="d-none d-lg-block">
                        <div class="dropdown ms-2">
                            <div class="d-none d-lg-block">
                                <div
                                    class="input-group input-group-merge input-group-borderless input-group-hover-light navbar-input-group">
                                    <div class="input-group-prepend input-group-text">
                                        <i class="bi-search"></i>
                                    </div>

                                    <input type="search" class="js-form-search form-control global-search"
                                           placeholder="@lang("Search for a menu")"
                                           aria-label="@lang("Search for a menu")" data-hs-form-search-options='{
                                               "clearIcon": "#clearSearchResultsIcon",
                                               "dropMenuElement": "#searchDropdownMenu",
                                               "dropMenuOffset": 20,
                                               "toggleIconOnFocus": true,
                                               "activeClass": "focus"
                                             }'>
                                    <a class="input-group-append input-group-text" href="javascript:void(0)">
                                        <i id="clearSearchResultsIcon" class="bi-x-lg d-none"></i>
                                    </a>
                                </div>
                            </div>

                            <button
                                class="js-form-search js-form-search-mobile-toggle btn btn-ghost-secondary btn-icon rounded-circle d-lg-none"
                                type="button" data-hs-form-search-options='{
                                   "clearIcon": "#clearSearchResultsIcon",
                                   "dropMenuElement": "#searchDropdownMenu",
                                   "dropMenuOffset": 20,
                                   "toggleIconOnFocus": true,
                                   "activeClass": "focus"
                                 }'>
                                <i class="bi-search"></i>
                            </button>
                            <!-- End Input Group -->

                            <!-- Card Search Content -->
                            <div id="searchDropdownMenu"
                                 class="hs-form-search-menu-content dropdown-menu dropdown-menu-form-search navbar-dropdown-menu-borderless">
                                <div class="card">
                                    <!-- Body -->
                                    <div class="card-body-height search-result">
                                        <div class="d-lg-none">
                                            <div class="input-group input-group-merge navbar-input-group mb-5">
                                                <div class="input-group-prepend input-group-text">
                                                    <i class="bi-search"></i>
                                                </div>

                                                <input type="search" class="form-control global-search"
                                                       placeholder="@lang("Search for a menu")"
                                                       aria-label="@lang("Search for a menu")">
                                                <a class="input-group-append input-group-text"
                                                   href="javascript:void(0);">
                                                    <i class="bi-x-lg"></i>
                                                </a>
                                            </div>
                                        </div>

                                        <span class="dropdown-header">@lang("Result")</span>

                                        <div class="dropdown-divider"></div>

                                        <div class="content">


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- End Search Form -->
                </div>
                <!-- End Content Start -->

                <!-- Content End -->
                <div class="navbar-nav-wrap-content-end">
                    <!-- Navbar -->
                    <ul class="navbar-nav">
                        <li class="nav-item d-none d-sm-inline-block" id="messageNotificationArea">
                            <div class="dropdown">
                                <button type="button" class="btn btn-icon btn-ghost-secondary rounded-circle"
                                        id="navbarMessagesDropdown"
                                        data-bs-toggle="dropdown" aria-expanded="false" data-bs-dropdown-animation>
                                    <i class="bi-chat-dots"></i>
                                    <span class="btn-status btn-sm-status btn-status-danger" v-if="items.length > 0"
                                          v-cloak></span>
                                </button>

                                <div
                                    class="dropdown-menu dropdown-menu-end dropdown-card navbar-dropdown-menu navbar-dropdown-menu-borderless"
                                    aria-labelledby="navbarMessagesDropdown" style="width: 25rem;">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title mb-0">@lang('Messages')</h4>
                                        </div>

                                        <div class="card-body card-body-height">
                                            <ul class="list-group list-group-flush navbar-card-list-group"
                                                v-if="items.length > 0">
                                                <li class="list-group-item" v-for="(item, index) in items" :key="index">
                                                    <a href="javascript:void(0);"
                                                       @click.prevent="readAt(item.id, item.link)">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0">
                                                                <img class="avatar avatar-xs avatar-4x3"
                                                                     :src="item.sender.user_image" alt="User">
                                                            </div>
                                                            <div class="flex-grow-1 text-truncate ms-3">
                                                                <h5 class="mb-0">@{{ item.sender.fullname }}</h5>
                                                                <p class="card-text text-body">@{{ item.message }}</p>
                                                                <small class="text-muted">@{{ item.time }}</small>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>
                                            </ul>

                                            <div class="text-center p-4" v-else>
                                                <img class="mb-3 dataTables-image"
                                                     src="{{ asset('assets/admin/img/oc-error.svg') }}" alt="">
                                                <p class="mb-0">@lang("No Messages Found")</p>
                                            </div>
                                        </div>

                                        <a class="card-footer text-center" href="javascript:void(0);"
                                           @click.prevent="readAll" v-if="items.length > 0">
                                            @lang("Clear all messages") <i class="bi-chevron-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>

                        @if(basicControl()->in_app_notification)
                            <li class="nav-item d- d-sm-inline-block" id="pushNotificationArea">
                                <div class="dropdown">
                                    <button type="button" class="btn btn-ghost-secondary btn-icon rounded-circle"
                                            id="navbarNotificationsDropdown" data-bs-toggle="dropdown"
                                            aria-expanded="false"
                                            data-bs-auto-close="outside">
                                        <i class="bi-bell"></i>
                                        <span class="btn-status btn-sm-status btn-status-danger" v-if="items.length > 0"
                                              v-cloak></span>
                                    </button>
                                    <div
                                        class="dropdown-menu dropdown-menu-end dropdown-card navbar-dropdown-menu navbar-dropdown-menu-borderless navbarNotificationsDropdown data-bs-dropdown-animation"
                                        aria-labelledby="navbarNotificationsDropdown">
                                        <div class="card ">
                                            <div class="card-header card-header-content-between">
                                                <h4 class="card-title mb-0">@lang('Notifications')</h4>
                                            </div>
                                            <div class="card-body-height">
                                                <div id="notificationTabContent">
                                                    <ul class="list-group list-group-flush navbar-card-list-group"
                                                        v-if="items.length > 0">
                                                        <li class="list-group-item form-check-select"
                                                            v-for="(item, index) in items" :key="index">
                                                            <div class="row">
                                                                <div class="col-auto">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                   type="checkbox"
                                                                                   :id="'notificationCheck' + index">
                                                                            <label class="form-check-label"
                                                                                   :for="'notificationCheck' + index"></label>
                                                                            <span
                                                                                class="form-check-stretched-bg"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col ms-n2">
                                                                    <h5 class="mb-1">@{{ item.description.name }}</h5>
                                                                    <p class="text-body fs-5">@{{ item.description.text }}</p>
                                                                    <small class="col-auto text-muted text-cap">@{{
                                                                        item.formatted_date }}</small>
                                                                </div>
                                                            </div>
                                                            <a class="stretched-link" :href="item.description.link"></a>
                                                        </li>
                                                    </ul>

                                                    <!-- No Notifications Found -->
                                                    <div class="text-center p-4" v-else>
                                                        <img class="dataTables-image mb-3"
                                                             src="{{ asset('assets/admin/img/oc-error.svg') }}"
                                                             alt="Image Description" data-hs-theme-appearance="default">
                                                        <img class="dataTables-image mb-3"
                                                             src="{{ asset('assets/admin/img/oc-error-light.svg') }}"
                                                             alt="Image Description" data-hs-theme-appearance="dark">
                                                        <p class="mb-0">@lang("No Notifications Found")</p>
                                                    </div>
                                                </div>


                                            </div>
                                            <a class="card-footer text-center" href="javascript:void(0)"
                                               v-if="items.length > 0"
                                               @click.prevent="readAll">
                                                @lang("Clear all notifications") <i class="bi-chevron-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endif

                        <li class="nav-item">
                            <!-- Account -->
                            <div class="dropdown">
                                <a class="navbar-dropdown-account-wrapper" href="javascript:;"
                                   id="accountNavbarDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                                   data-bs-auto-close="outside" data-bs-dropdown-animation>
                                    <div class="avatar avatar-sm avatar-circle">
                                        <img class="avatar-img"
                                             src="{{ getFile(auth()->user()->image_driver, auth()->user()->image) }}"
                                             alt="Image Description">
                                        <span class="avatar-status avatar-sm-status avatar-status-success"></span>
                                    </div>
                                </a>

                                <div
                                    class="dropdown-menu dropdown-menu-end navbar-dropdown-menu navbar-dropdown-menu-borderless navbar-dropdown-account"
                                    aria-labelledby="accountNavbarDropdown" style="width: 16rem;">
                                    <div class="dropdown-item-text">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm avatar-circle">
                                                <img class="avatar-img"
                                                     src="{{ getFile(auth()->user()->image_driver, auth()->user()->image) }}"
                                                     alt="Image Description">
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h5 class="mb-0">{{ auth()->user()->firstname.' '.auth()->user()->lastname }}</h5>
                                                <p class="card-text text-body">{{ auth()->user()->email }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="dropdown-divider"></div>

                                    <a class="dropdown-item" href="{{ route('user.profile') }}"><i
                                            class="fal fa-user pe-2"></i>@lang('Profile & account')</a>
                                    <a class="dropdown-item" href="{{ route('user.notification.permission.list') }}"><i
                                            class="fal fa-bell pe-2"></i>@lang('Notification Permissions')</a>
                                    @if(auth()->user()->role == 1)
                                        <a class="dropdown-item" href="#"
                                           data-bs-target="#renewPlan"
                                           data-bs-toggle="modal"
                                        >
                                            <i class="fal fa-refresh pe-2"></i>@lang('Auto Renew Plan')
                                        </a>
                                    @endif


                                    <a class="dropdown-item" href="{{ route('user.twostep.security') }}"><i
                                            class="fal fa-shield pe-2"></i>@lang('2FA Verification')</a>
                                    <a class="dropdown-item" href="#" onclick="showPwa()"> <i
                                            class="fal fa-download pe-1"></i> @lang('Install PWA') </a>

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                            class="fal fa-sign-out pe-2"></i>@lang('Sign out')</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        </li>

                        <li class="nav-item">
                            <!-- Toggler -->
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#navbarDoubleLineContainerNavDropdown"
                                    aria-controls="navbarDoubleLineContainerNavDropdown" aria-expanded="false"
                                    aria-label="Toggle navigation">
                                 <span class="navbar-toggler-default">
                                   <i class="bi-list"></i>
                                 </span>
                                <span class="navbar-toggler-toggled"><i class="bi-x"></i></span>
                            </button>
                            <!-- End Toggler -->
                        </li>
                    </ul>
                    <!-- End Navbar -->
                </div>
                <!-- End Content End -->
            </div>
        </div>
    </div>

    <div class="container">
        <nav class="js-mega-menu flex-grow-1">
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="navbarDoubleLineContainerNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a id="dashboardsMegaMenu" class="nav-link {{ menuActive(['user.dashboard']) }}"
                           href="{{ route('user.dashboard') }}" data-title="Dashboard">
                            <i class="bi-house-door"></i> @lang('Dashboards')
                        </a>
                    </li>
                    @if(auth()->user()->role == 1)
                        <li class="nav-item">
                            <a class="nav-link {{ menuActive(['user.all.guides','user.guide.edit','user.guide.add','user.payment.gateway.manage']) }}"
                               href="{{ route('user.all.guides') }}" data-title="Manage Team">
                                <i class="bi-people dropdown-item-icon"></i>@lang('Manage Team')
                            </a>
                        </li>
                        <li class="hs-has-sub-menu nav-item">
                            <a id="pagesMegaMenu"
                               class="hs-mega-menu-invoker nav-link dropdown-toggle {{ menuActive(['user.all.package','user.package.add','user.package.edit','user.package.seo']) }}"
                               href="{{ route('user.all.package') }}" role="button" data-title="Packages">
                                <i class="bi-box dropdown-item-icon"></i> @lang('Packages')
                            </a>
                            <div class="hs-sub-menu dropdown-menu navbar-dropdown-menu-borderless"
                                 aria-labelledby="pagesMegaMenu" style="min-width: 14rem;">
                                <a class="hs-mega-menu-invoker dropdown-item {{ menuActive(['user.all.package','user.package.edit','user.package.seo']) }}"
                                   href="{{ route('user.all.package') }}" data-title="List"><i
                                        class="bi bi-list pe-1"></i>@lang('List')</a>
                                <a class="hs-mega-menu-invoker dropdown-item {{ menuActive(['user.package.add']) }}"
                                   href="{{ route('user.package.add') }}" data-title="Add"><i
                                        class="bi bi-plus-circle pe-1"></i>@lang('Add')</a>
                                <a class="hs-mega-menu-invoker dropdown-item {{ menuActive(['user.review.list']) }}"
                                   href="{{ route('user.review.list') }}" data-title="Reviews"><i
                                        class="bi bi-journal-check pe-1"></i>@lang('Reviews')</a>
                                <a class="hs-mega-menu-invoker dropdown-item {{ menuActive(['user.chat.list']) }}"
                                   href="{{ route('user.chat.list') }}" data-title="Chats"><i
                                        class="bi bi-chat pe-1"></i>@lang('Chats')</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ menuActive(['user.vendor.booking.list','user.view.booking']) }}"
                               href="{{ route('user.vendor.booking.list') }}" data-title="Tour History">
                                <i class="bi-clock-history dropdown-item-icon"></i>@lang('Tour History')
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ menuActive(['user.payment.gateway.index','user.payment.gateway.delete','user.payment.gateway.edit']) }}"
                               href="{{ route('user.payment.gateway.index') }}" data-title="Manage Gateway">
                                <i class="bi-credit-card dropdown-item-icon"></i>@lang('Manage Gateway')
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->role == 0)
                        <li class="nav-item">
                            <a class="nav-link {{ menuActive(['user.booking.list']) }}"
                               href="{{ route('user.booking.list') }}" data-title="Tour History">
                                <i class="bi-clock-history dropdown-item-icon"></i>@lang('Tour History')
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ menuActive(['user.chat.list']) }}"
                               href="{{ route('user.chat.list') }}" data-title="Chat Lists">
                                <i class="bi-chat dropdown-item-icon"></i>@lang('Chats')
                            </a>
                        </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link {{ menuActive(['user.fund.index']) }}" href="{{ route('user.fund.index') }}"
                           data-placement="left" data-title="Payment History">
                            <i class="bi-receipt dropdown-item-icon"></i>@lang('Payment History')
                        </a>
                    </li>
                    @if(auth()->user()->role == 1)
                        @if(isPayoutAccess())
                            <li class="hs-has-sub-menu nav-item">
                                <a id="payoutsMenu"
                                   class="hs-mega-menu-invoker nav-link dropdown-toggle {{ menuActive(['user.payout.index','user.payout']) }}"
                                   href="{{ route('user.payout.index') }}" role="button" data-title="Payouts">
                                    <i class="bi-wallet2 dropdown-item-icon"></i> @lang('Payouts')</a>
                                <div class="hs-sub-menu dropdown-menu navbar-dropdown-menu-borderless"
                                     aria-labelledby="payoutsMenu" style="min-width: 14rem;">
                                    <a class="hs-mega-menu-invoker dropdown-item {{ menuActive(['user.payout']) }}"
                                       href="{{ route('user.payout') }}" data-title="Make Payout"><i
                                            class="bi-box-arrow-up-right pe-2"></i>@lang('Make Payout')</a>
                                    <a class="hs-mega-menu-invoker dropdown-item {{ menuActive(['user.payout.index']) }}"
                                       href="{{ route('user.payout.index') }}" data-title="Payouts History"><i
                                            class="bi-file-earmark-text pe-2"></i>@lang('History')</a>
                                </div>
                            </li>
                        @endif
                    @endif
                    <li class="nav-item">
                        <a class="nav-link {{ menuActive(['user.transaction']) }}"
                           href="{{ route('user.transaction') }}" data-placement="left" data-title="Transactions">
                            <i class="fal fa-money-bill dropdown-item-icon"></i>@lang('Transactions')
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ menuActive(['user.ticket.list','user.ticket.view']) }}"
                           href="{{ route('user.ticket.list') }}" data-placement="left" data-title="Support Ticket">
                            <i class="bi-ticket dropdown-item-icon"></i>@lang('Support Ticket')
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ menuActive(['user.kyc.settings']) }}"
                           href="{{ route('user.kyc.settings') }}" data-placement="left" data-title="KYC Settings">
                            <i class="bi-person-badge dropdown-item-icon"></i>@lang('KYC Settings')
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>

<div class="modal fade" id="renewPlan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-close">
                <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm" data-bs-dismiss="modal"
                        aria-label="Close">
                    <i class="bi-x-lg"></i>
                </button>
            </div>
            <form action="{{ route('user.plan.auto.renew') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-sm-5">
                    <div class="text-center">
                        <div class="w-75 w-sm-50 mx-auto mb-4">
                            <img class="img-fluid"
                                 src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}"
                                 alt="{{ basicControl()->site_title }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-block text-center py-sm-5">
                    <small
                        class="text-cap text-muted">@lang('Confirm your interest to renew. The plan will automatically renew after your current one ends.')</small>
                    <div class="modal-footer-button">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal"
                                aria-label="Close">@lang('Cancel')</button>
                        <button type="submit" class="btn btn-success" name="confirm" value="1">@lang('Confirm')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
    <script src="{{ asset('assets/global/js/pusher.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.7.14/dist/vue.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        let messageNotificationArea = new Vue({
            el: "#messageNotificationArea",
            data: {
                items: [],
            },
            beforeMount() {
                this.getMessages();
                this.listenForMessages();
            },
            methods: {
                getMessages() {
                    let app = this;
                    axios.get("{{ route('user.message.show') }}")
                        .then(function (res) {
                            app.items = res.data;
                        });
                },
                readAt(id, link) {
                    let app = this;
                    let url = "{{ route('user.message.readAt', 0) }}".replace(/0$/, id);
                    axios.get(url)
                        .then(function (res) {
                            if (res.status) {
                                app.getMessages();
                                if (link && link !== '#') {
                                    window.location.href = link;
                                }
                            }
                        });
                },
                readAll() {
                    let app = this;
                    axios.get("{{ route('user.message.readAll') }}")
                        .then(function (res) {
                            if (res.status) {
                                app.items = [];
                            }
                        });
                },
                listenForMessages() {
                    let app = this;
                    Pusher.logToConsole = false;
                    let pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
                        encrypted: true,
                        cluster: "{{ env('PUSHER_APP_CLUSTER') }}"
                    });
                    let channel = pusher.subscribe('user-messages.' + "{{ Auth::id() }}");

                    channel.bind('App\\Events\\UserMessage', function (data) {
                        console.log("hot");
                        app.getMessages();
                    });

                    channel.bind('App\\Events\\UpdateUserMessage', function () {
                        console.log("cit")
                        app.getMessages();
                    });
                }
            }
        });

        let pushNotificationArea = new Vue({
            el: "#pushNotificationArea",
            data: {
                items: [],
            },
            beforeMount() {
                this.getNotifications();
                this.pushNewItem();
            },
            methods: {
                getNotifications() {
                    let app = this;
                    axios.get("{{ route('user.push.notification.show') }}")
                        .then(function (res) {
                            app.items = res.data;
                        })
                },
                readAt(id, link) {
                    let app = this;
                    let url = "{{ route('user.push.notification.readAt', 0) }}";
                    url = url.replace(/.$/, id);
                    axios.get(url)
                        .then(function (res) {
                            if (res.status) {
                                app.getNotifications();
                                if (link !== '#') {
                                    window.location.href = link
                                }
                            }
                        })
                },
                readAll() {
                    let app = this;
                    let url = "{{ route('user.push.notification.readAll') }}";
                    axios.get(url)
                        .then(function (res) {
                            if (res.status) {
                                app.items = [];
                            }
                        })
                },
                pushNewItem() {
                    let app = this;
                    Pusher.logToConsole = false;
                    let pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
                        encrypted: true,
                        cluster: "{{ env('PUSHER_APP_CLUSTER') }}"
                    });
                    let channel = pusher.subscribe('user-notification.' + "{{ Auth::id() }}");
                    channel.bind('App\\Events\\UserNotification', function (data) {
                        app.items.unshift(data.message);
                    });
                    channel.bind('App\\Events\\UpdateUserNotification', function (data) {
                        app.getNotifications();
                    });
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.querySelector('.global-search');
            const menuLinks = document.querySelectorAll('.navbar-nav .nav-link, .hs-sub-menu .dropdown-item');
            const searchResultBox = document.querySelector('.search-result .content');

            function renderResults(searchText = '') {
                searchResultBox.innerHTML = '';
                let found = false;

                menuLinks.forEach(link => {
                    const dataTitle = link.getAttribute('data-title') || '';
                    const title = dataTitle.toLowerCase();

                    if (title.includes(searchText)) {
                        const newLink = document.createElement('a');
                        newLink.href = link.href;
                        newLink.className = 'dropdown-item';

                        const wrapper = document.createElement('div');
                        wrapper.className = 'd-flex align-items-center';

                        const iconWrapper = document.createElement('div');
                        iconWrapper.className = 'flex-shrink-0';
                        const iconSpan = document.createElement('span');
                        iconSpan.className = 'icon icon-soft-dark icon-xs icon-circle';

                        const originalIcon = link.querySelector('i');
                        if (originalIcon) {
                            const clonedIcon = originalIcon.cloneNode(true);
                            iconSpan.appendChild(clonedIcon);
                        }
                        iconWrapper.appendChild(iconSpan);

                        const textWrapper = document.createElement('div');
                        textWrapper.className = 'flex-grow-1 text-truncate ms-2';

                        const titleSpan = document.createElement('span');
                        titleSpan.className = 'd-block';
                        titleSpan.innerHTML = highlightMatch(dataTitle, searchText);
                        textWrapper.appendChild(titleSpan);

                        const breadcrumb = getMenuBreadcrumb(link);
                        if (breadcrumb) {
                            const descSpan = document.createElement('span');
                            descSpan.className = 'menu-description';
                            descSpan.innerText = breadcrumb;
                            textWrapper.appendChild(descSpan);
                        }

                        wrapper.appendChild(iconWrapper);
                        wrapper.appendChild(textWrapper);
                        newLink.appendChild(wrapper);

                        searchResultBox.appendChild(newLink);
                        found = true;
                    }
                });

                if (!found) {
                    searchResultBox.innerHTML = '<div class="text-center p-3">@lang("No Result Found")</div>';
                }
            }

            function highlightMatch(text, search) {
                if (!search) return text;
                const regex = new RegExp(`(${search})`, 'gi');
                return text.replace(regex, `<b>$1</b>`);
            }

            function getMenuBreadcrumb(link) {
                const currentTitle = link.getAttribute('data-title') || '';
                const parent = link.closest('.hs-sub-menu');

                if (parent) {
                    const parentToggle = parent.closest('li')?.querySelector('.nav-link[data-title]');
                    const parentTitle = parentToggle?.getAttribute('data-title') || '';
                    return `${parentTitle} > ${currentTitle}`;
                }

                return '';
            }

            searchInput.addEventListener('input', function () {
                const searchText = this.value.toLowerCase().trim();
                document.getElementById('searchDropdownMenu').classList.add('show');
                renderResults(searchText);
            });

            searchInput.addEventListener('focus', function () {
                document.getElementById('searchDropdownMenu').classList.add('show');
                renderResults();
            });

            document.addEventListener('click', function (event) {
                if (!event.target.closest('.dropdown')) {
                    document.getElementById('searchDropdownMenu').classList.remove('show');
                }
            });
        });

    </script>
@endpush
