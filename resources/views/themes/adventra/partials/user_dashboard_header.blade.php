<!-- ========== LEFT SIDEBAR ========== -->
<style>
    /* ── Sidebar shell ── */
    #appSidebar {
        position: fixed;
        top: 0; left: 0;
        width: 220px;
        height: 100vh;
        background: #111827;
        display: flex;
        flex-direction: column;
        z-index: 1040;
        transition: width .25s ease, transform .25s ease;
        overflow: hidden;
    }
    #appSidebar.collapsed { width: 72px; }

    /* ── Push main content ── */
    body { margin-left: 220px; transition: margin-left .25s ease; }
    body.sidebar-collapsed { margin-left: 72px; }

    /* ── Logo row ── */
    .sb-logo-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 14px 14px;
        border-bottom: 1px solid rgba(255,255,255,.07);
        flex-shrink: 0;
    }
    .sb-logo-row img { height: 32px; object-fit: contain; max-width: 130px; }
    .sb-logo-row .logo-icon { height: 32px; width: 32px; object-fit: contain; display: none; }
    #appSidebar.collapsed .sb-logo-row img.logo-full  { display: none; }
    #appSidebar.collapsed .sb-logo-row .logo-icon     { display: block; }

    .sb-toggle {
        background: none; border: none; cursor: pointer;
        color: #9ca3af; padding: 4px; border-radius: 6px;
        display: flex; align-items: center; justify-content: center;
        transition: background .15s, color .15s;
        flex-shrink: 0;
    }
    .sb-toggle:hover { background: rgba(255,255,255,.08); color: #fff; }
    .sb-toggle svg { width: 18px; height: 18px; }

    /* ── Utility bar (top-right area things) ── */
    .sb-utility {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 10px 12px;
        border-bottom: 1px solid rgba(255,255,255,.07);
        flex-shrink: 0;
    }
    #appSidebar.collapsed .sb-utility { justify-content: center; flex-wrap: wrap; gap: 4px; padding: 8px 6px; }

    .sb-util-btn {
        position: relative;
        background: none; border: none; cursor: pointer;
        color: #9ca3af; padding: 7px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        transition: background .15s, color .15s;
    }
    .sb-util-btn:hover { background: rgba(255,255,255,.08); color: #fff; }
    .sb-util-btn svg, .sb-util-btn i { font-size: 1rem; }
    .sb-util-label {
        font-size: 0.75rem; color: #d1d5db;
        white-space: nowrap; overflow: hidden;
        transition: opacity .2s;
    }
    #appSidebar.collapsed .sb-util-label { display: none; }

    .sb-badge {
        position: absolute; top: 4px; right: 4px;
        width: 8px; height: 8px; border-radius: 50%;
        background: #ef4444; border: 2px solid #111827;
    }

    /* ── Scrollable nav area ── */
    .sb-nav {
        flex: 1; overflow-y: auto; overflow-x: hidden;
        padding: 10px 10px 6px;
    }
    .sb-nav::-webkit-scrollbar { width: 4px; }
    .sb-nav::-webkit-scrollbar-track { background: transparent; }
    .sb-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.12); border-radius: 4px; }

    /* ── 2-column tile grid ── */
    .sb-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 6px;
    }
    #appSidebar.collapsed .sb-grid { grid-template-columns: 1fr; }

    .sb-tile {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 5px;
        padding: 12px 6px 10px;
        border-radius: 10px;
        text-decoration: none;
        color: #9ca3af;
        background: rgba(255,255,255,.03);
        border: 1px solid rgba(255,255,255,.05);
        transition: background .15s, color .15s, border-color .15s, transform .12s;
        cursor: pointer;
        min-height: 68px;
    }
    .sb-tile:hover {
        background: rgba(255,255,255,.08);
        color: #f3f4f6;
        border-color: rgba(255,255,255,.12);
        transform: translateY(-1px);
        text-decoration: none;
    }
    .sb-tile.active {
        background: linear-gradient(135deg, #7c3aed, #6d28d9);
        color: #fff;
        border-color: #7c3aed;
        box-shadow: 0 4px 14px rgba(124,58,237,.4);
    }
    .sb-tile i, .sb-tile .bi {
        font-size: 1.25rem;
        line-height: 1;
    }
    .sb-tile-label {
        font-size: 0.65rem;
        font-weight: 600;
        text-align: center;
        line-height: 1.2;
        letter-spacing: .01em;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
    }
    #appSidebar.collapsed .sb-tile { min-height: 48px; padding: 10px 6px; }
    #appSidebar.collapsed .sb-tile-label { display: none; }

    /* Section label */
    .sb-section-label {
        font-size: 0.6rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #4b5563;
        padding: 10px 4px 4px;
        white-space: nowrap;
        overflow: hidden;
    }
    #appSidebar.collapsed .sb-section-label { opacity: 0; height: 0; padding: 0; }

    /* ── User profile footer ── */
    .sb-footer {
        border-top: 1px solid rgba(255,255,255,.07);
        padding: 10px 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
        position: relative;
    }
    #appSidebar.collapsed .sb-footer { justify-content: center; }
    .sb-avatar {
        width: 34px; height: 34px; border-radius: 50%;
        object-fit: cover; border: 2px solid #374151;
        flex-shrink: 0;
    }
    .sb-user-info { overflow: hidden; flex: 1; }
    .sb-user-info .name {
        font-size: 0.75rem; font-weight: 600; color: #f3f4f6;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .sb-user-info .email {
        font-size: 0.65rem; color: #6b7280;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    #appSidebar.collapsed .sb-user-info { display: none; }
    .sb-signout {
        background: none; border: none; cursor: pointer;
        color: #6b7280; padding: 4px;
        border-radius: 6px; display: flex;
        transition: color .15s;
    }
    .sb-signout:hover { color: #ef4444; }
    #appSidebar.collapsed .sb-signout { display: none; }

    /* ── Dropdown menus inside sidebar ── */
    .sb-dropdown { position: relative; }
    .sb-dropdown-menu {
        display: none;
        position: absolute;
        left: calc(100% + 8px);
        top: 0;
        background: #1f2937;
        border: 1px solid rgba(255,255,255,.1);
        border-radius: 10px;
        min-width: 170px;
        padding: 6px;
        z-index: 1050;
        box-shadow: 0 8px 30px rgba(0,0,0,.5);
    }
    .sb-dropdown-menu.show { display: block; }
    #appSidebar:not(.collapsed) .sb-dropdown { display: contents; }
    #appSidebar:not(.collapsed) .sb-dropdown-menu {
        position: static; display: block !important;
        background: transparent; border: none;
        box-shadow: none; padding: 0;
        margin-top: 2px; left: auto; top: auto;
    }
    #appSidebar:not(.collapsed) .sb-dropdown-menu { display: block; }

    .sb-sub-tile {
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        gap: 4px; padding: 8px 4px;
        border-radius: 8px;
        text-decoration: none;
        color: #9ca3af;
        background: rgba(255,255,255,.02);
        border: 1px solid rgba(255,255,255,.04);
        transition: background .15s, color .15s;
        font-size: 0.62rem; font-weight: 600;
        text-align: center; min-height: 52px;
    }
    .sb-sub-tile:hover { background: rgba(255,255,255,.07); color: #f3f4f6; text-decoration: none; }
    .sb-sub-tile.active { background: rgba(124,58,237,.25); color: #a78bfa; border-color: rgba(124,58,237,.3); }
    .sb-sub-tile i { font-size: 0.9rem; }

    /* ── Mobile overlay toggle ── */
    #sidebarMobileToggle {
        display: none;
        position: fixed; top: 12px; left: 12px; z-index: 1050;
        background: #111827; color: #fff;
        border: none; border-radius: 8px; padding: 8px;
        cursor: pointer;
    }
    @media (max-width: 991px) {
        body { margin-left: 0 !important; }
        #appSidebar { transform: translateX(-100%); width: 220px !important; }
        #appSidebar.mobile-open { transform: translateX(0); }
        #sidebarMobileToggle { display: flex; }
        .sb-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.5); z-index: 1039;
        }
        .sb-overlay.show { display: block; }
    }
</style>

<!-- Mobile toggle btn -->
<button id="sidebarMobileToggle" onclick="toggleMobileSidebar()">
    <i class="bi-list" style="font-size:1.2rem;"></i>
</button>
<div class="sb-overlay" id="sbOverlay" onclick="toggleMobileSidebar()"></div>

<aside id="appSidebar">

    <!-- Logo -->
    <div class="sb-logo-row">
        <a href="{{ route('page','/') }}">
            <img class="logo-full"
                 src="{{ getFile(basicControl()->admin_dark_mode_logo_driver, basicControl()->admin_dark_mode_logo) }}"
                 alt="Logo">
            <img class="logo-icon"
                 src="{{ getFile(basicControl()->admin_dark_mode_logo_driver, basicControl()->admin_dark_mode_logo) }}"
                 alt="Logo">
        </a>
        <button class="sb-toggle" onclick="toggleSidebar()" title="Toggle sidebar">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
            </svg>
        </button>
    </div>

    <!-- Utility: messages, notifications, language -->
    <div class="sb-utility">
        {{-- Messages --}}
        <div id="messageNotificationArea">
            <button class="sb-util-btn" data-bs-toggle="dropdown" id="sbMsgBtn" title="Messages">
                <i class="bi-chat-dots"></i>
                <span class="sb-badge" v-if="items.length > 0" v-cloak></span>
            </button>
            <div class="dropdown-menu dropdown-menu-end dropdown-card navbar-dropdown-menu navbar-dropdown-menu-borderless"
                 aria-labelledby="sbMsgBtn" style="width:25rem; left:100%; top:0; position:absolute;">
                <div class="card">
                    <div class="card-header"><h4 class="card-title mb-0">@lang('Messages')</h4></div>
                    <div class="card-body card-body-height">
                        <ul class="list-group list-group-flush navbar-card-list-group" v-if="items.length > 0">
                            <li class="list-group-item" v-for="(item,index) in items" :key="index">
                                <a href="javascript:void(0);" @click.prevent="readAt(item.id,item.link)">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <img class="avatar avatar-xs avatar-4x3" :src="item.sender.user_image" alt="">
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
                            <img class="mb-3 dataTables-image" src="{{ asset('assets/admin/img/oc-error.svg') }}" alt="">
                            <p class="mb-0">@lang("No Messages Found")</p>
                        </div>
                    </div>
                    <a class="card-footer text-center" href="javascript:void(0);" @click.prevent="readAll" v-if="items.length > 0">
                        @lang("Clear all messages") <i class="bi-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Notifications --}}
        @if(basicControl()->in_app_notification)
            <div id="pushNotificationArea">
                <button class="sb-util-btn" data-bs-toggle="dropdown" id="sbNotiBtn" title="Notifications">
                    <i class="bi-bell"></i>
                    <span class="sb-badge" v-if="items.length > 0" v-cloak></span>
                </button>
                <div class="dropdown-menu dropdown-menu-end dropdown-card navbar-dropdown-menu navbar-dropdown-menu-borderless"
                     aria-labelledby="sbNotiBtn">
                    <div class="card">
                        <div class="card-header card-header-content-between">
                            <h4 class="card-title mb-0">@lang('Notifications')</h4>
                        </div>
                        <div class="card-body-height">
                            <ul class="list-group list-group-flush navbar-card-list-group" v-if="items.length > 0">
                                <li class="list-group-item form-check-select" v-for="(item,index) in items" :key="index">
                                    <div class="row">
                                        <div class="col ms-n2">
                                            <h5 class="mb-1">@{{ item.description.name }}</h5>
                                            <p class="text-body fs-5">@{{ item.description.text }}</p>
                                            <small class="col-auto text-muted text-cap">@{{ item.formatted_date }}</small>
                                        </div>
                                    </div>
                                    <a class="stretched-link" :href="item.description.link"></a>
                                </li>
                            </ul>
                            <div class="text-center p-4" v-else>
                                <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error.svg') }}" alt="">
                                <p class="mb-0">@lang("No Notifications Found")</p>
                            </div>
                        </div>
                        <a class="card-footer text-center" href="javascript:void(0)" v-if="items.length > 0" @click.prevent="readAll">
                            @lang("Clear all notifications") <i class="bi-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <span class="sb-util-label ms-auto text-truncate" style="max-width:90px;">
            {{ auth()->user()->firstname }}
        </span>
    </div>

    <!-- Navigation tiles -->
    <div class="sb-nav">

        <div class="sb-section-label">@lang('Navigation')</div>

        <div class="sb-grid">

            {{-- Dashboard --}}
            <a href="{{ route('user.dashboard') }}"
               class="sb-tile {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                <i class="bi-house-door"></i>
                <span class="sb-tile-label">@lang('Dashboards')</span>
            </a>

            {{-- Payment History --}}
            <a href="{{ route('user.fund.index') }}"
               class="sb-tile {{ request()->routeIs('user.fund.index') ? 'active' : '' }}">
                <i class="bi-receipt"></i>
                <span class="sb-tile-label">@lang('Payment History')</span>
            </a>

            {{-- Transactions --}}
            <a href="{{ route('user.transaction') }}"
               class="sb-tile {{ request()->routeIs('user.transaction') ? 'active' : '' }}">
                <i class="fal fa-money-bill"></i>
                <span class="sb-tile-label">@lang('Transactions')</span>
            </a>

            {{-- Support Ticket --}}
            <a href="{{ route('user.ticket.list') }}"
               class="sb-tile {{ request()->routeIs('user.ticket.list','user.ticket.view') ? 'active' : '' }}">
                <i class="bi-ticket"></i>
                <span class="sb-tile-label">@lang('Support Ticket')</span>
            </a>

            {{-- KYC --}}
            <a href="{{ route('user.kyc.settings') }}"
               class="sb-tile {{ request()->routeIs('user.kyc.settings') ? 'active' : '' }}">
                <i class="bi-person-badge"></i>
                <span class="sb-tile-label">@lang('KYC Settings')</span>
            </a>

            {{-- Chats (customer role) --}}
            @if(auth()->user()->role == 0)
                <a href="{{ route('user.booking.list') }}"
                   class="sb-tile {{ request()->routeIs('user.booking.list') ? 'active' : '' }}">
                    <i class="bi-clock-history"></i>
                    <span class="sb-tile-label">@lang('Tour History')</span>
                </a>
                <a href="{{ route('user.chat.list') }}"
                   class="sb-tile {{ request()->routeIs('user.chat.list') ? 'active' : '' }}">
                    <i class="bi-chat"></i>
                    <span class="sb-tile-label">@lang('Chats')</span>
                </a>
            @endif

            {{-- Vendor-only items --}}
            @if(auth()->user()->role == 1)

                <a href="{{ route('user.all.guides') }}"
                   class="sb-tile {{ request()->routeIs('user.all.guides','user.guide.edit','user.guide.add') ? 'active' : '' }}">
                    <i class="bi-people"></i>
                    <span class="sb-tile-label">@lang('Manage Team')</span>
                </a>

                <a href="{{ route('user.vendor.booking.list') }}"
                   class="sb-tile {{ request()->routeIs('user.vendor.booking.list','user.view.booking') ? 'active' : '' }}">
                    <i class="bi-clock-history"></i>
                    <span class="sb-tile-label">@lang('Tour History')</span>
                </a>

                <a href="{{ route('user.payment.gateway.index') }}"
                   class="sb-tile {{ request()->routeIs('user.payment.gateway.index','user.payment.gateway.edit','user.payment.gateway.delete') ? 'active' : '' }}">
                    <i class="bi-credit-card"></i>
                    <span class="sb-tile-label">@lang('Manage Gateway')</span>
                </a>

                @if(isPayoutAccess())
                    <a href="{{ route('user.payout') }}"
                       class="sb-tile {{ request()->routeIs('user.payout','user.payout.index') ? 'active' : '' }}">
                        <i class="bi-wallet2"></i>
                        <span class="sb-tile-label">@lang('Payouts')</span>
                    </a>
                @endif

            @endif
        </div>

        {{-- Packages sub-section (vendor only) --}}
        @if(auth()->user()->role == 1)
            <div class="sb-section-label mt-2">@lang('Packages')</div>
            <div class="sb-grid">
                <a href="{{ route('user.all.package') }}"
                   class="sb-sub-tile {{ request()->routeIs('user.all.package','user.package.edit','user.package.seo') ? 'active' : '' }}">
                    <i class="bi bi-list"></i>
                    <span>@lang('List')</span>
                </a>
                <a href="{{ route('user.package.add') }}"
                   class="sb-sub-tile {{ request()->routeIs('user.package.add') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle"></i>
                    <span>@lang('Add')</span>
                </a>
                <a href="{{ route('user.review.list') }}"
                   class="sb-sub-tile {{ request()->routeIs('user.review.list') ? 'active' : '' }}">
                    <i class="bi bi-journal-check"></i>
                    <span>@lang('Reviews')</span>
                </a>
                <a href="{{ route('user.chat.list') }}"
                   class="sb-sub-tile {{ request()->routeIs('user.chat.list') ? 'active' : '' }}">
                    <i class="bi bi-chat"></i>
                    <span>@lang('Chats')</span>
                </a>
            </div>
        @endif

    </div>

    <!-- Footer: user profile -->
    <div class="sb-footer">
        <div class="dropdown dropup w-100">
            <a href="javascript:;" data-bs-toggle="dropdown" data-bs-auto-close="outside"
               style="display:flex; align-items:center; gap:10px; text-decoration:none;">
                <img class="sb-avatar"
                     src="{{ getFile(auth()->user()->image_driver, auth()->user()->image) }}" alt="">
                <div class="sb-user-info">
                    <div class="name">{{ auth()->user()->firstname.' '.auth()->user()->lastname }}</div>
                    <div class="email">{{ auth()->user()->email }}</div>
                </div>
                <button class="sb-signout" title="More">
                    <i class="bi-three-dots-vertical" style="font-size:.85rem;"></i>
                </button>
            </a>
            <div class="dropdown-menu dropdown-menu-end navbar-dropdown-menu navbar-dropdown-menu-borderless"
                 style="width:14rem; margin-bottom:8px;">
                <a class="dropdown-item" href="{{ route('user.profile') }}">
                    <i class="fal fa-user pe-2"></i>@lang('Profile & account')
                </a>
                <a class="dropdown-item" href="{{ route('user.notification.permission.list') }}">
                    <i class="fal fa-bell pe-2"></i>@lang('Notification Permissions')
                </a>
                @if(auth()->user()->role == 1)
                    <a class="dropdown-item" href="#" data-bs-target="#renewPlan" data-bs-toggle="modal">
                        <i class="fal fa-refresh pe-2"></i>@lang('Auto Renew Plan')
                    </a>
                @endif
                <a class="dropdown-item" href="{{ route('user.twostep.security') }}">
                    <i class="fal fa-shield pe-2"></i>@lang('2FA Verification')
                </a>
                <a class="dropdown-item" href="#" onclick="showPwa()">
                    <i class="fal fa-download pe-1"></i> @lang('Install PWA')
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger"
                   href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fal fa-sign-out pe-2"></i>@lang('Sign out')
                </a>
            </div>
        </div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    </div>
</aside>

<!-- Auto-Renew Plan Modal -->
<div class="modal fade" id="renewPlan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-close">
                <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm" data-bs-dismiss="modal" aria-label="Close">
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
                    <small class="text-cap text-muted">
                        @lang('Confirm your interest to renew. The plan will automatically renew after your current one ends.')
                    </small>
                    <div class="modal-footer-button">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal" aria-label="Close">@lang('Cancel')</button>
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
        /* ── Sidebar collapse ── */
        function toggleSidebar() {
            const sb   = document.getElementById('appSidebar');
            const body = document.body;
            sb.classList.toggle('collapsed');
            body.classList.toggle('sidebar-collapsed');
            localStorage.setItem('sbCollapsed', sb.classList.contains('collapsed') ? '1' : '0');
        }

        function toggleMobileSidebar() {
            document.getElementById('appSidebar').classList.toggle('mobile-open');
            document.getElementById('sbOverlay').classList.toggle('show');
        }

        // Restore state on load
        (function () {
            if (localStorage.getItem('sbCollapsed') === '1') {
                document.getElementById('appSidebar').classList.add('collapsed');
                document.body.classList.add('sidebar-collapsed');
            }
        })();

        /* ── Vue: Messages ── */
        let messageNotificationArea = new Vue({
            el: "#messageNotificationArea",
            data: { items: [] },
            beforeMount() { this.getMessages(); this.listenForMessages(); },
            methods: {
                getMessages() {
                    axios.get("{{ route('user.message.show') }}")
                        .then(res => { this.items = res.data; });
                },
                readAt(id, link) {
                    let url = "{{ route('user.message.readAt', 0) }}".replace(/0$/, id);
                    axios.get(url).then(res => {
                        if (res.status) { this.getMessages(); if (link && link !== '#') window.location.href = link; }
                    });
                },
                readAll() {
                    axios.get("{{ route('user.message.readAll') }}")
                        .then(res => { if (res.status) this.items = []; });
                },
                listenForMessages() {
                    Pusher.logToConsole = false;
                    let pusher  = new Pusher("{{ env('PUSHER_APP_KEY') }}", { encrypted: true, cluster: "{{ env('PUSHER_APP_CLUSTER') }}" });
                    let channel = pusher.subscribe('user-messages.' + "{{ Auth::id() }}");
                    channel.bind('App\\Events\\UserMessage', () => this.getMessages());
                    channel.bind('App\\Events\\UpdateUserMessage', () => this.getMessages());
                }
            }
        });

        /* ── Vue: Notifications ── */
        @if(basicControl()->in_app_notification)
        let pushNotificationArea = new Vue({
            el: "#pushNotificationArea",
            data: { items: [] },
            beforeMount() { this.getNotifications(); this.pushNewItem(); },
            methods: {
                getNotifications() {
                    axios.get("{{ route('user.push.notification.show') }}")
                        .then(res => { this.items = res.data; });
                },
                readAt(id, link) {
                    let url = "{{ route('user.push.notification.readAt', 0) }}".replace(/.$/, id);
                    axios.get(url).then(res => { if (res.status) { this.getNotifications(); if (link !== '#') window.location.href = link; } });
                },
                readAll() {
                    axios.get("{{ route('user.push.notification.readAll') }}")
                        .then(res => { if (res.status) this.items = []; });
                },
                pushNewItem() {
                    let pusher  = new Pusher("{{ env('PUSHER_APP_KEY') }}", { encrypted: true, cluster: "{{ env('PUSHER_APP_CLUSTER') }}" });
                    let channel = pusher.subscribe('user-notification.' + "{{ Auth::id() }}");
                    channel.bind('App\\Events\\UserNotification', data => this.items.unshift(data.message));
                    channel.bind('App\\Events\\UpdateUserNotification', () => this.getNotifications());
                }
            }
        });
        @endif
    </script>
@endpush
