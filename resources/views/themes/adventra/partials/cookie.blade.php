<div class="cookies-alert" id="cookiesAlert">
    <img src="{{ getFile(basicControl()->cookie_image_driver, basicControl()->cookie_image ) }}" height="50" width="50"
         alt="{{ basicControl()->site_title }} cookies">
    <h4 class="mt-2">@lang(basicControl()->cookie_heading)</h4>
    <span class="d-block mt-2">@lang(basicControl()->cookie_description)
        <br>
        <a href="{{ basicControl()->cookie_button_link }}" class="link text-success">{{ basicControl()->cookie_button }}</a>
    </span>
    <a href="javascript:void(0);" class="mt-2 theme-btn justify-content-center" type="button" onclick="acceptCookiePolicy()">@lang('Accept')</a>
    <a href="javascript:void(0);" class="mt-2 theme-btn2" type="button" onclick="closeCookieBanner()">Close</a>
</div>

    <style>
        .cookies-alert {
            display: none;
            position: fixed;
            bottom: 15px;
            left: 15px;
            padding: 2rem;
            max-width: 360px;
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 10px 10px rgba(0, 0, 0, 0.06);
            text-align: center;
            z-index: 99999;
        }
        .cookies-alert .theme-btn {

            font-size: 16px;
            font-weight: 500;
            padding: 9px 15px 9px 15px;
        }

        .theme-btn2 {
            display: inline-block;
            vertical-align: middle;
            background-color: #fff;
            color: #000;
            font-size: 16px;
            font-weight: 500;
            padding: 9px 15px 9px 15px;
            transition: all 0.4s ease-in-out;
            text-transform: capitalize;
            position: relative;
            overflow: hidden;
            text-align: center;
            border: 1px solid #888686;
            border-radius: 72px;
            font-family: "Nunito", sans-serif;
            line-height: 1;
            z-index: 1;
        }
        .theme-btn2:hover {
            background-color: var(--theme-color-2);
            color: #fff !important;
        }
        .cookies-alert img {
            width: 20%;
        }

        .cookieHeading {
            font-size: 25px;
            font-weight: 600;
        }
    </style>

    <script>
        function setCookie(name, value, days) {
            var expires = "";
            if (days) {
                var date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + value + expires + "; path=/";
        }
        function hasAcceptedCookiePolicy() {
            return document.cookie.indexOf("cookie_policy_accepted=true") !== -1;
        }
        function acceptCookiePolicy() {
            setCookie("cookie_policy_accepted", "true", 365);
            document.getElementById("cookiesAlert").style.display = "none";
        }
        function closeCookieBanner() {
            document.getElementById("cookiesAlert").style.display = "none";
        }
        document.addEventListener('DOMContentLoaded', function () {
            if (!hasAcceptedCookiePolicy()) {
                document.getElementById("cookiesAlert").style.display = "block";
            }
        });
    </script>


