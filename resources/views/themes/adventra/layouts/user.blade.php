<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title> {{basicControl()->site_title}} @if(isset($pageSeo['page_title']))
            | {{str_replace(basicControl()->site_title, ' ',$pageSeo['page_title'])}}
        @else
            | @yield('page_title')
        @endif
    </title>

    <link href="{{ getFile(basicControl()->favicon_driver, basicControl()->favicon) }}" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/admin/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset(template(true). 'css/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset(template(true). 'css/hs-mega-menu.min.css') }}">

    <link rel="preload" href="{{ asset(template(true). 'css/theme.min.css') }}" data-hs-appearance="default" as="style">
    <link rel="preload" href="{{ asset(template(true). 'css/theme-dark.min.css') }}" data-hs-appearance="dark" as="style">
    <link rel="stylesheet" href="{{ asset(template(true). 'css/custom.css') }}"  as="style">

    <style data-hs-appearance-onload-styles>
        *
        {
            transition: unset !important;
        }
    </style>

    <script>
        window.hs_config = {"autopath":"@@autopath","deleteLine":"hs-builder:delete","deleteLine:build":"hs-builder:build-delete","deleteLine:dist":"hs-builder:dist-delete","previewMode":false,"startPath":"/index.html","vars":{"themeFont":"https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap","version":"?v=1.0"},"layoutBuilder":{"extend":{"switcherSupport":true},"header":{"layoutMode":"default","containerMode":"container-fluid"},"sidebarLayout":"default"},"themeAppearance":{"layoutSkin":"default","sidebarSkin":"default","styles":{"colors":{"primary":"#377dff","transparent":"transparent","white":"#fff","dark":"132144","gray":{"100":"#f9fafc","900":"#1e2022"}},"font":"Inter"}},"languageDirection":{"lang":"en"},"skipFilesFromBundle":{"dist":["assets/js/hs.theme-appearance.js","assets/js/hs.theme-appearance-charts.js","assets/js/demo.js"],"build":["assets/css/theme.css","assets/vendor/hs-navbar-vertical-aside/dist/hs-navbar-vertical-aside-mini-cache.js","assets/js/demo.js","assets/css/theme-dark.css","assets/css/docs.css","assets/vendor/icon-set/style.css","assets/js/hs.theme-appearance.js","assets/js/hs.theme-appearance-charts.js","node_modules/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js","assets/js/demo.js"]},"minifyCSSFiles":["assets/css/theme.css","assets/css/theme-dark.css"],"copyDependencies":{"dist":{"*assets/js/theme-custom.js":""},"build":{"*assets/js/theme-custom.js":"","node_modules/bootstrap-icons/font/*fonts/**":"assets/css"}},"buildFolder":"","replacePathsToCDN":{},"directoryNames":{"src":"./src","dist":"./dist","build":"./build"},"fileNames":{"dist":{"js":"theme.min.js","css":"theme.min.css"},"build":{"css":"theme.min.css","js":"theme.min.js","vendorCSS":"vendor.min.css","vendorJS":"vendor.min.js"}},"fileTypes":"jpg|png|svg|mp4|webm|ogv|json"}
        window.hs_config.gulpRGBA = (p1) => {
            const options = p1.split(',')
            const hex = options[0].toString()
            const transparent = options[1].toString()

            var c;
            if(/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)){
                c= hex.substring(1).split('');
                if(c.length== 3){
                    c= [c[0], c[0], c[1], c[1], c[2], c[2]];
                }
                c= '0x'+c.join('');
                return 'rgba('+[(c>>16)&255, (c>>8)&255, c&255].join(',')+',' + transparent + ')';
            }
            throw new Error('Bad Hex');
        }
        window.hs_config.gulpDarken = (p1) => {
            const options = p1.split(',')

            let col = options[0].toString()
            let amt = -parseInt(options[1])
            var usePound = false

            if (col[0] == "#") {
                col = col.slice(1)
                usePound = true
            }
            var num = parseInt(col, 16)
            var r = (num >> 16) + amt
            if (r > 255) {
                r = 255
            } else if (r < 0) {
                r = 0
            }
            var b = ((num >> 8) & 0x00FF) + amt
            if (b > 255) {
                b = 255
            } else if (b < 0) {
                b = 0
            }
            var g = (num & 0x0000FF) + amt
            if (g > 255) {
                g = 255
            } else if (g < 0) {
                g = 0
            }
            return (usePound ? "#" : "") + (g | (b << 8) | (r << 16)).toString(16)
        }
        window.hs_config.gulpLighten = (p1) => {
            const options = p1.split(',')

            let col = options[0].toString()
            let amt = parseInt(options[1])
            var usePound = false

            if (col[0] == "#") {
                col = col.slice(1)
                usePound = true
            }
            var num = parseInt(col, 16)
            var r = (num >> 16) + amt
            if (r > 255) {
                r = 255
            } else if (r < 0) {
                r = 0
            }
            var b = ((num >> 8) & 0x00FF) + amt
            if (b > 255) {
                b = 255
            } else if (b < 0) {
                b = 0
            }
            var g = (num & 0x0000FF) + amt
            if (g > 255) {
                g = 255
            } else if (g < 0) {
                g = 0
            }
            return (usePound ? "#" : "") + (g | (b << 8) | (r << 16)).toString(16)
        }
    </script>
    @stack('style')

    @laravelPWA
</head>

<body>

<script src="{{ asset(template(true). 'js/hs.theme-appearance.js') }}"></script>

@include(template().'partials.user_dashboard_header')

<main id="content" role="main" class="main">
    @yield('content')
</main>

<!-- JS Global Compulsory  -->
<script src="{{ asset(template(true). 'js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset(template(true). 'js/jquery-migrate.min.js') }}"></script>
<script src="{{ asset(template(true). 'js/bootstrap.bundle.min.js') }}"></script>
@stack('script')

<!-- JS Implementing Plugins -->
<script src="{{ asset(template(true). 'js/hs-mega-menu.min.js') }}"></script>
<script src="{{ asset(template(true). 'js/hs-form-search.js') }}"></script>
<script src="{{ asset(template(true). 'js/chart.js') }}"></script>
<script src="{{ asset(template(true). 'js/chartjs-chart-matrix.js') }}"></script>

<script src="{{ asset('assets/global/js/notiflix-aio-3.2.6.min.js') }}"></script>
<script src="{{ asset('assets/global/js/vue.min.js') }}"></script>
<script src="{{ asset('assets/global/js/axios.min.js') }}"></script>

<!-- JS Front -->
<script src="{{ asset(template(true). 'js/main2.js') }}"></script>
<script src="{{ asset(template(true). 'js/theme.min.js') }}"></script>

<!-- JS Plugins Init. -->
<script>
    (function() {
        HSBsDropdown.init()

        new HSMegaMenu('.js-mega-menu', {
            desktop: {
                position: 'left'
            }
        })
        new HSFormSearch('.js-form-search')

    })()
</script>


<!-- Style Switcher JS -->

<script>
    (function () {
        // STYLE SWITCHER
        // =======================================================
        const $dropdownBtn = document.getElementById('selectThemeDropdown') // Dropdowon trigger
        const $variants = document.querySelectorAll(`[aria-labelledby="selectThemeDropdown"] [data-icon]`) // All items of the dropdown

        // Function to set active style in the dorpdown menu and set icon for dropdown trigger
        const setActiveStyle = function () {
            $variants.forEach($item => {
                if ($item.getAttribute('data-value') === HSThemeAppearance.getOriginalAppearance()) {
                    $dropdownBtn.innerHTML = `<i class="${$item.getAttribute('data-icon')}" />`
                    return $item.classList.add('active')
                }

                $item.classList.remove('active')
            })
        }

        // Add a click event to all items of the dropdown to set the style
        $variants.forEach(function ($item) {
            $item.addEventListener('click', function () {
                HSThemeAppearance.setAppearance($item.getAttribute('data-value'))
            })
        })

        // Call the setActiveStyle on load page
        setActiveStyle()

        // Add event listener on change style to call the setActiveStyle function
        window.addEventListener('on-hs-appearance-change', function () {
            setActiveStyle()
        })
    })()
</script>

<script>
    'use strict';


    // dashboard_toggle_sidemenu
    const toggleSideMenu = () => {
        document.getElementById("sidebar").classList.toggle("active");
        document.getElementById("content").classList.toggle("active");
    };
    const hideSidebar = () => {
        document.getElementById("formWrapper").classList.remove("active");
        document.getElementById("formWrapper2").classList.remove("active");
    };

    // tab
    const tabs = document.getElementsByClassName("tab");
    const contents = document.getElementsByClassName("content");
    for (const element of tabs) {
        const tabId = element.getAttribute("tab-id");
        const content = document.getElementById(tabId);
        element.addEventListener("click", () => {
            for (const t of tabs) {
                t.classList.remove("active");
            }
            for (const c of contents) {
                c.classList.remove("active");
            }
            element.classList.add("active");
            content.classList.add("active");
        });
    }
</script>


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

@include('plugins')
<!-- End Style Switcher JS -->
</body>
</html>
