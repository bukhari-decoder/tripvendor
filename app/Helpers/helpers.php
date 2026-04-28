<?php

use App\Models\BasicControl;
use App\Models\Content;
use App\Models\ContentDetails;
use App\Models\ManageMenu;
use App\Models\Package;
use App\Models\Page;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use App\Models\Language;
use App\Models\PageDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

function template($asset = false)
{
    $activeTheme = getTheme();
    $currentRouteName = Route::currentRouteName();

    if (Str::startsWith($currentRouteName, 'user.') || request()->param == 'user') {
        if ($asset) return 'assets/themes/adventra/';

        return 'themes.adventra.';
    }

    if ($asset) return 'assets/themes/' . $activeTheme . '/';
    return 'themes.' . $activeTheme . '.';
}

if (!function_exists('getTheme')) {
    function getTheme()
    {
        $theme = session('theme') ?? basicControl()->theme ?? 'adventra';
        return $theme;

    }
}
if (!function_exists('getHomeStyle')) {
    function getHomeStyle()
    {
        $homeStyle = session('home_version') ?? basicControl()->home_style ?? 'home-101';
        return $homeStyle;

    }
}
if (!function_exists('getTourListStyle')) {
    function getTourListStyle()
    {
        $homeStyle = session('tour_list_version') ?? basicControl()->package_list_style ?? 'list';
        return $homeStyle;

    }
}

function is_audio($filename)
{
    $audioExtensions = ['mp3', 'wav', 'ogg'];
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($extension, $audioExtensions);
}

function is_image($filename)
{
    $imageExtensions = ['jpg', 'jpeg', 'png', 'webp', 'webm', 'gif', 'bmp'];
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($extension, $imageExtensions);
}

if (!function_exists('getAudioFile')) {
    function getAudioFile($disk = 'local', $file = '', $upload = false)
    {
        $default = ($upload == true) ? asset(config('filelocation.default2')) : asset(config('filelocation.default'));
        try {
            if ($disk == 'local') {
                $localFile = asset('/assets/upload') . '/' . $file;
                return !empty($file) && Storage::disk($disk)->exists($file) ? $localFile : $default;
            } else {
                $audioExtensions = ['mp3', 'wav', 'ogg'];
                $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($extension, $audioExtensions)) {
                    return !empty($file) && Storage::disk($disk)->exists($file) ? Storage::disk($disk)->url($file) : $default;
                } else {
                    return !empty($file) && Storage::disk($disk)->exists($file) ? $localFile : $default;
                }
            }
        } catch (Exception $e) {
            return $default;
        }
    }
}

if (!function_exists('getThemesNames')) {
    function getThemesNames()
    {
        $directory = resource_path('views/themes');
        return File::isDirectory($directory) ? array_map('basename', File::directories($directory)) : [];
    }
}

if (!function_exists('stringToTitle')) {
    function stringToTitle($string)
    {
        return implode(' ', array_map('ucwords', explode(' ', preg_replace('/[^a-zA-Z0-9]+/', ' ', $string))));
    }
}

if (!function_exists('getTitle')) {
    function getTitle($title)
    {
        if ($title == "sms") {
            return strtoupper(preg_replace('/[^A-Za-z0-9]/', ' ', $title));
        }
        return ucwords(preg_replace('/[^A-Za-z0-9]/', ' ', $title));
    }
}

if (!function_exists('getRoute')) {
    function getRoute($route, $params = null)
    {
        return isset($params) ? route($route, $params) : route($route);
    }
}

if (!function_exists('getPageSections')) {
    function getPageSections()
    {
        $sectionsPath = resource_path('views/') . str_replace('.', '/', template()) . 'sections';
        $pattern = $sectionsPath . '/*';
        $files = glob($pattern);

        $fileBaseNames = [];

        foreach ($files as $file) {
            if (is_file($file)) {
                $basename = basename($file);
                $basenameWithoutExtension = str_replace('.blade.php', '', $basename);
                $fileBaseNames[$basenameWithoutExtension] = $basenameWithoutExtension;
            }
        }

        return $fileBaseNames;
    }
}

if (!function_exists('hex2rgba')) {
    function hex2rgba($color, $opacity = false)
    {
        $default = 'rgb(0,0,0)';

        if (empty($color))
            return $default;

        if ($color[0] == '#') {
            $color = substr($color, 1);
        }

        if (strlen($color) == 6) {
            $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
        } elseif (strlen($color) == 3) {
            $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
        } else {
            return $default;
        }

        $rgb = array_map('hexdec', $hex);

        if ($opacity) {
            if (abs($opacity) > 1)
                $opacity = 1.0;
            $output = 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';
        } else {
            $output = 'rgb(' . implode(",", $rgb) . ')';
        }
        return $output;
    }
}

if (!function_exists('basicControl')) {
    function basicControl()
    {
        if (session()->get('themeMode') == null) {
            session()->put('themeMode', 'auto');
        }

        try {
            DB::connection()->getPdo();
            $configure = \Cache::get('ConfigureSetting');
            if (!$configure) {
                $configure = BasicControl::firstOrCreate();
                \Cache::put('ConfigureSetting', $configure);
            }

            return $configure;
        } catch (\Exception $e) {
        }
    }
}

if (!function_exists('checkTo')) {
    function checkTo($currencies, $selectedCurrency = 'USD')
    {
        foreach ($currencies as $key => $currency) {
            if (property_exists($currency, strtoupper($selectedCurrency))) {
                return $key;
            }
        }
    }
}


if (!function_exists('controlPanelRoutes')) {
    function controlPanelRoutes()
    {
        $listRoutes = collect([]);
        $listRoutes->push(config('generalsettings.settings'));
        $listRoutes->push(config('generalsettings.plugin'));
        $listRoutes->push(config('generalsettings.in-app-notification'));
        $listRoutes->push(config('generalsettings.push-notification'));
        $listRoutes->push(config('generalsettings.email'));
        $listRoutes->push(config('generalsettings.sms'));
        $list = $listRoutes->collapse()->map(function ($item) {
            return $item['route'];
        })->values()->push('admin.settings')->unique();
        return $list;
    }
}


if (!function_exists('menuActive')) {
    function menuActive($routeName, $type = null)
    {
        $class = 'active';
        if ($type == 3) {
            $class = 'active collapsed';
        } elseif ($type == 2) {
            $class = 'show';
        }

        if (is_array($routeName)) {
            foreach ($routeName as $key => $value) {
                if (request()->routeIs($value)) {
                    return $class;
                }
            }
        } elseif (request()->routeIs($routeName)) {
            return $class;
        }
    }
}

if (!function_exists('isMenuActive')) {
    function isMenuActive($routes, $type = 0)
    {
        $class = [
            '0' => 'active',
            '1' => 'style=display:block',
            '2' => true
        ];

        if (is_array($routes)) {
            foreach ($routes as $key => $route) {
                if (request()->routeIs($route)) {
                    return $class[$type];
                }
            }
        } elseif (request()->routeIs($routes)) {
            return $class[$type];
        }

        if ($type == 1) {
            return 'style=display:none';
        } else {
            return false;
        }
    }
}


if (!function_exists('strRandom')) {
    function strRandom($length = 12)
    {
        $characters = 'ABCDEFGHJKMNOPQRSTUVWXYZ123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}


if (!function_exists('getFile')) {
    function getFile($disk = 'local', $image = '', $upload = false)
    {
        $default = ($upload == true) ? asset(config('filelocation.default2')) : asset(config('filelocation.default'));
        try {
            if ($disk == 'local') {
                $localImage = asset('/assets/upload') . '/' . $image;
                return !empty($image) && Storage::disk($disk)->exists($image) ? $localImage : $default;
            } else {
                return !empty($image) && Storage::disk($disk)->exists($image) ? Storage::disk($disk)->url($image) : $default;
            }
        } catch (Exception $e) {
            return $default;
        }
    }
}

if (!function_exists('getFileForEdit')) {
    function getFileForEdit($disk = 'local', $image = null)
    {
        try {
            if ($disk == 'local') {
                $localImage = asset('/assets/upload') . '/' . $image;
                return !empty($image) && Storage::disk($disk)->exists($image) ? $localImage : null;
            } else {
                return !empty($image) && Storage::disk($disk)->exists($image) ? Storage::disk($disk)->url($image) : asset(config('location.default'));
            }
        } catch (Exception $e) {
            return null;
        }
    }
}

if (!function_exists('title2snake')) {
    function title2snake($string)
    {
        return Str::title(str_replace(' ', '_', $string));
    }
}

if (!function_exists('snake2Title')) {
    function snake2Title($string)
    {
        return Str::title(str_replace('_', ' ', $string));
    }
}

if (!function_exists('kebab2Title')) {
    function kebab2Title($string)
    {
        return Str::title(str_replace('-', ' ', $string));
    }
}

if (!function_exists('getMethodCurrency')) {
    function getMethodCurrency($gateway)
    {
        foreach ($gateway->currencies as $key => $currency) {
            if (property_exists($currency, $gateway->currency)) {
                if ($key == 0) {
                    return $gateway->currency;
                } else {
                    return 'USD';
                }
            }
        }
    }
}

if (!function_exists('twoStepPrevious')) {
    function twoStepPrevious($deposit)
    {
        if ($deposit->depositable_type == \App\Models\Fund::class) {
            return route('fund.initialize');
        }
    }
}


if (!function_exists('slug')) {
    function slug($title)
    {
        return Str::slug($title);
    }
}

if (!function_exists('clean')) {
    function clean($string)
    {
        $string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }
}

if (!function_exists('diffForHumans')) {
    function diffForHumans($date)
    {
        $lang = session()->get('lang');
        \Carbon\Carbon::setlocale($lang);
        return \Carbon\Carbon::parse($date)->diffForHumans();
    }
}
if (!function_exists('defaultLang')) {
    function defaultLang()
    {
        $langCode = session()->get('lang');

        $lang = Language::where('short_name', $langCode)->first();

        return $lang;
    }
}



if (!function_exists('loopIndex')) {
    function loopIndex($object)
    {
        return ($object->currentPage() - 1) * $object->perPage() + 1;
    }
}

if (!function_exists('dateTime')) {
    function dateTime($date, $format = 'd/m/Y H:i')
    {
        $format = basicControl()->date_time_format;
        return date($format, strtotime($date));
    }
}


if (!function_exists('getProjectDirectory')) {
    function getProjectDirectory()
    {
        return str_replace((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]", "", url("/"));
    }
}

if (!function_exists('defaultLang')) {
    function defaultLang()
    {
        return Language::where('default_status', true)->first();
    }
}

if (!function_exists('removeHyphenInString')) {
    function removeHyphenInString($string)
    {
        return str_replace("_", " ", $string);
    }
}

function updateBalance($user_id, $amount, $action = 0)
{
    $user = User::where('id', $user_id)->firstOr(function () {
        throw new \Exception('User not found!');
    });
    if ($amount > $user->balance) {
        return back()->with('error', 'Insufficient Balance to deducted.');
    }
    if ($action == 1) {
        $balance = $user->balance + $amount;
        $user->balance = $balance;
    } elseif ($action == 0) {
        $balance = $user->balance - $amount;
        $user->balance = $balance;
    }
    $user->save();
}


function getAmount($amount, $length = 0)
{
    if ($amount == 0) {
        return 0;
    }
    if ($length == 0) {
        preg_match("#^([\+\-]|)([0-9]*)(\.([0-9]*?)|)(0*)$#", trim($amount), $o);
        return $o[1] . sprintf('%d', $o[2]) . ($o[3] != '.' ? $o[3] : '');
    }

    return round($amount, $length);
}

if (!function_exists('currencyPosition')) {
    function currencyPosition($amount)
    {
        $basic = basicControl();
        $amount = fractionNumber($amount);
        return $basic->is_currency_position == 'left' && $basic->has_space_between_currency_and_amount ? "{$basic->currency_symbol} {$amount}" :
            ($basic->is_currency_position == 'left' && !$basic->has_space_between_currency_and_amount ? "{$basic->currency_symbol}{$amount}" :
                ($basic->is_currency_position == 'right' && $basic->has_space_between_currency_and_amount ? "{$amount} {$basic->base_currency} " :
                    "{$amount}{$basic->base_currency}"));
    }
}

if (!function_exists('discountPrice')) {
    function discountPrice($package)
    {
        $discountAmount = $package->adult_price;
        if ($package->discount == 1) {
            if ($package->discount_type == 0) {
                $discountAmount = $package->adult_price - $package->discount_amount;
            } else {
                $discountAmount = $package->adult_price - (($package->adult_price * $package->discount_amount) / 100);
            }
        }

        $basic = basicControl();
        $amount = fractionNumber($discountAmount);

        if ($basic->is_currency_position == 'left') {
            return $basic->has_space_between_currency_and_amount
                ? "{$basic->currency_symbol} {$amount}"
                : "{$basic->currency_symbol}{$amount}";
        } else {
            return $basic->has_space_between_currency_and_amount
                ? "{$amount} {$basic->base_currency}"
                : "{$amount}{$basic->base_currency}";
        }
    }
}


if (!function_exists('fractionNumber')) {
    function fractionNumber($amount, $afterComma = true)
    {
        $basic = basicControl();
        if (!$afterComma) {
            return number_format($amount + 0);
        }
        $formattedAmount = number_format($amount, $basic->fraction_number ?? 2);

        return rtrim(rtrim($formattedAmount, '0'), '.');

    }
}


function hextorgb($hexstring)
{
    $integar = hexdec($hexstring);
    return array("red" => 0xFF & ($integar >> 0x10),
        "green" => 0xFF & ($integar >> 0x8),
        "blue" => 0xFF & $integar);
}

function renderCaptCha($rand)
{
//    session_start();
    $captcha_code = '';
    $captcha_image_height = 50;
    $captcha_image_width = 130;
    $total_characters_on_image = 6;

    $possible_captcha_letters = 'bcdfghjkmnpqrstvwxyz23456789';
    $captcha_font = 'assets/monofont.ttf';

    $random_captcha_dots = 50;
    $random_captcha_lines = 25;
    $captcha_text_color = "0x142864";
    $captcha_noise_color = "0x142864";


    $count = 0;
    while ($count < $total_characters_on_image) {
        $captcha_code .= substr(
            $possible_captcha_letters,
            mt_rand(0, strlen($possible_captcha_letters) - 1),
            1);
        $count++;
    }


    $captcha_font_size = $captcha_image_height * 0.65;
    $captcha_image = @imagecreate(
        $captcha_image_width,
        $captcha_image_height
    );

    /* setting the background, text and noise colours here */
    $background_color = imagecolorallocate(
        $captcha_image,
        255,
        255,
        255
    );

    $array_text_color = hextorgb($captcha_text_color);
    $captcha_text_color = imagecolorallocate(
        $captcha_image,
        $array_text_color['red'],
        $array_text_color['green'],
        $array_text_color['blue']
    );

    $array_noise_color = hextorgb($captcha_noise_color);
    $image_noise_color = imagecolorallocate(
        $captcha_image,
        $array_noise_color['red'],
        $array_noise_color['green'],
        $array_noise_color['blue']
    );

    /* Generate random dots in background of the captcha image */
    for ($count = 0; $count < $random_captcha_dots; $count++) {
        imagefilledellipse(
            $captcha_image,
            mt_rand(0, $captcha_image_width),
            mt_rand(0, $captcha_image_height),
            2,
            3,
            $image_noise_color
        );
    }

    /* Generate random lines in background of the captcha image */
    for ($count = 0; $count < $random_captcha_lines; $count++) {
        imageline(
            $captcha_image,
            mt_rand(0, $captcha_image_width),
            mt_rand(0, $captcha_image_height),
            mt_rand(0, $captcha_image_width),
            mt_rand(0, $captcha_image_height),
            $image_noise_color
        );
    }

    /* Create a text box and add 6 captcha letters code in it */
    $text_box = imagettfbbox(
        $captcha_font_size,
        0,
        $captcha_font,
        $captcha_code
    );
    $x = ($captcha_image_width - $text_box[4]) / 2;
    $y = ($captcha_image_height - $text_box[5]) / 2;
    imagettftext(
        $captcha_image,
        $captcha_font_size,
        0,
        $x,
        $y,
        $captcha_text_color,
        $captcha_font,
        $captcha_code
    );

    /* Show captcha image in the html page */
// defining the image type to be shown in browser widow
    header('Content-Type: image/jpeg');
    imagejpeg($captcha_image); //showing the image
    imagedestroy($captcha_image); //destroying the image instance
//    $_SESSION['captcha'] = $captcha_code;

    session()->put('captcha', $captcha_code);
}

function getIpInfo()
{
//	$ip = '210.1.246.42';
    $ip = null;
    $deep_detect = TRUE;

    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }
    $xml = @simplexml_load_file("http://www.geoplugin.net/xml.gp?ip=" . $ip);

    $country = @$xml->geoplugin_countryName;
    $city = @$xml->geoplugin_city;
    $area = @$xml->geoplugin_areaCode;
    $code = @$xml->geoplugin_countryCode;
    $long = @$xml->geoplugin_longitude;
    $lat = @$xml->geoplugin_latitude;


    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $os_platform = "Unknown OS Platform";
    $os_array = array(
        '/windows nt 10/i' => 'Windows 10',
        '/windows nt 6.3/i' => 'Windows 8.1',
        '/windows nt 6.2/i' => 'Windows 8',
        '/windows nt 6.1/i' => 'Windows 7',
        '/windows nt 6.0/i' => 'Windows Vista',
        '/windows nt 5.2/i' => 'Windows Server 2003/XP x64',
        '/windows nt 5.1/i' => 'Windows XP',
        '/windows xp/i' => 'Windows XP',
        '/windows nt 5.0/i' => 'Windows 2000',
        '/windows me/i' => 'Windows ME',
        '/win98/i' => 'Windows 98',
        '/win95/i' => 'Windows 95',
        '/win16/i' => 'Windows 3.11',
        '/macintosh|mac os x/i' => 'Mac OS X',
        '/mac_powerpc/i' => 'Mac OS 9',
        '/linux/i' => 'Linux',
        '/ubuntu/i' => 'Ubuntu',
        '/iphone/i' => 'iPhone',
        '/ipod/i' => 'iPod',
        '/ipad/i' => 'iPad',
        '/android/i' => 'Android',
        '/blackberry/i' => 'BlackBerry',
        '/webos/i' => 'Mobile'
    );
    foreach ($os_array as $regex => $value) {
        if (preg_match($regex, $user_agent)) {
            $os_platform = $value;
        }
    }
    $browser = "Unknown Browser";
    $browser_array = array(
        '/msie/i' => 'Internet Explorer',
        '/firefox/i' => 'Firefox',
        '/safari/i' => 'Safari',
        '/chrome/i' => 'Chrome',
        '/edge/i' => 'Edge',
        '/opera/i' => 'Opera',
        '/netscape/i' => 'Netscape',
        '/maxthon/i' => 'Maxthon',
        '/konqueror/i' => 'Konqueror',
        '/mobile/i' => 'Handheld Browser'
    );
    foreach ($browser_array as $regex => $value) {
        if (preg_match($regex, $user_agent)) {
            $browser = $value;
        }
    }

    $data['country'] = $country;
    $data['city'] = $city;
    $data['area'] = $area;
    $data['code'] = $code;
    $data['long'] = $long;
    $data['lat'] = $lat;
    $data['os_platform'] = $os_platform;
    $data['browser'] = $browser;
    $data['ip'] = request()->ip();
    $data['time'] = date('d-m-Y h:i:s A');

    return $data;
}


if (!function_exists('convertRate')) {
    function convertRate($currencyCode, $payout)
    {
        $convertRate = 0;
        $rate = optional($payout->method)->convert_rate;
        if ($rate) {
            $convertRate = $rate->$currencyCode;
        }
        return (float)$convertRate;
    }
}
if (!function_exists('stringToRouteName')) {
    function stringToRouteName($string)
    {
        $result = preg_replace('/[^a-zA-Z0-9]+/', '.', $string);
        return empty($result) || $result == '.' ? 'home' : $result;
    }
}
function browserIcon($string)
{
    $list = [
        "Unknown Browser" => "unknown",
        'Internet Explorer' => 'internetExplorer',
        'Firefox' => 'firefox',
        'Safari' => 'safari',
        'Chrome' => 'chrome',
        'Edge' => 'edge',
        'Opera' => 'opera',
        'Netscape' => 'netscape',
        'Maxthon' => 'maxthon',
        'Konqueror' => 'unknown',
        'UC Browser' => 'ucBrowser',
        'Safari Browser' => 'safari'];
    return $list[$string] ?? 'unknown';

}


function deviceIcon($string)
{
    $list = [
        'Tablet' => 'bi-laptop',
        'Mobile' => 'bi-phone',
        'Computer' => 'bi-display'];
    return $list[$string] ?? '';

}

if (!function_exists('timeAgo')) {
    function timeAgo($timestamp)
    {
        //$time_now = mktime(date('h')+0,date('i')+30,date('s'));
        $datetime1 = new DateTime("now");
        $datetime2 = date_create($timestamp);
        $diff = date_diff($datetime1, $datetime2);
        $timemsg = '';
        if ($diff->y > 0) {
            $timemsg = $diff->y . ' year' . ($diff->y > 1 ? "s" : '');

        } else if ($diff->m > 0) {
            $timemsg = $diff->m . ' month' . ($diff->m > 1 ? "s" : '');
        } else if ($diff->d > 0) {
            $timemsg = $diff->d . ' day' . ($diff->d > 1 ? "s" : '');
        } else if ($diff->h > 0) {
            $timemsg = $diff->h . ' hour' . ($diff->h > 1 ? "s" : '');
        } else if ($diff->i > 0) {
            $timemsg = $diff->i . ' minute' . ($diff->i > 1 ? "s" : '');
        } else if ($diff->s > 0) {
            $timemsg = $diff->s . ' second' . ($diff->s > 1 ? "s" : '');
        }
        if ($timemsg == "")
            $timemsg = "Just now";
        else
            $timemsg = $timemsg . ' ago';

        return $timemsg;
    }
}

if (!function_exists('code')) {
    function code($length)
    {
        if ($length == 0) return 0;
        $min = pow(10, $length - 1);
        $max = 0;
        while ($length > 0 && $length--) {
            $max = ($max * 10) + 9;
        }
        return random_int($min, $max);
    }
}


if (!function_exists('recursive_array_replace')) {
    function recursive_array_replace($find, $replace, $array)
    {
        if (!is_array($array)) {
            return str_ireplace($find, $replace, $array);
        }
        $newArray = [];
        foreach ($array as $key => $value) {
            $newArray[$key] = recursive_array_replace($find, $replace, $value);
        }
        return $newArray;
    }
}

if (!function_exists('getHeaderMenuData')) {
    function getHeaderMenuData()
    {
        $activeTheme = getTheme();
        $activeStyle = getHomeStyle();

        $menu = ManageMenu::where('menu_section', 'header')->first();
        $menuData = [];

        if ($menu) {
            foreach ($menu->menu_items as $key => $menuItem) {
                $page = Page::where('name', $menuItem)->where('template_name', $activeTheme)->where('status', 1)->first();

                $homes = config('themes')[$activeTheme]['home_version'];
                $menuIDetails = [];

                if ($page) {
                    $page->isHomePage = array_key_exists($page->home_name, $homes);
                    $page->activeHomePage = $page->isHomePage && ($activeStyle == $page->home_name);

                    if (is_numeric($key) && (!$page->isHomePage || $page->activeHomePage)) {
                        $pageDetails = getPageDetails($page->home_name);

                        $menuIDetails = [
                            'name' => $pageDetails->slug == '/' ? 'Home' : $pageDetails->page_name ?? $pageDetails->name ?? $menuItem,
                            'route' => isset($pageDetails->slug)
                                ? route('page', $pageDetails->slug)
                                : ($pageDetails->custom_link ?? staticPagesAndRoutes($menuItem)),
                        ];
                    } elseif (is_array($menuItem)) {
                        $pageDetails = getPageDetails($key);
                        $child = getHeaderChildMenu($menuItem);

                        $menuIDetails = [
                            'name' => $pageDetails->slug == '/' ? 'Home' : $pageDetails->page_name ?? $pageDetails->name,
                            'route' => isset($pageDetails->slug)
                                ? route('page', $pageDetails->slug)
                                : ($pageDetails->custom_link ?? staticPagesAndRoutes($key)),
                            'child' => $child
                        ];
                    }

                    if (!empty($menuIDetails)) {
                        $menuData[] = $menuIDetails;
                    }
                }
            }
        }
        return $menuData;
    }
}

if (!function_exists('staticPagesAndRoutes')) {
    function staticPagesAndRoutes($name)
    {
        return [
            'blog' => 'blog',
        ][$name] ?? $name;
    }
}


if (!function_exists('getHeaderChildMenu')) {
    function getHeaderChildMenu($menuItem, $menuData = [])
    {
        foreach ($menuItem as $key => $item) {
            if (is_numeric($key)) {
                $pageDetails = getPageDetails($item);
                $menuData[] = [
                    'name' => $pageDetails->page_name ?? $pageDetails->name ?? $item,
                    'route' => isset($pageDetails->slug) ? route('page', $pageDetails->slug) : ($pageDetails->custom_link ?? staticPagesAndRoutes($item)),
                ];
            } elseif (is_array($item)) {
                $pageDetails = getPageDetails($key);
                $child = getHeaderChildMenu($item);
                $menuData[] = [
                    'name' => $pageDetails->page_name ?? $pageDetails->name ?? $key,
                    'route' => isset($pageDetails->slug) ? route('page', $pageDetails->slug) : ($pageDetails->custom_link ?? staticPagesAndRoutes($key)),
                    'child' => $child
                ];
            } else {
                $pageDetails = getPageDetails($key);
                $child = getHeaderChildMenu([$item]);
                $menuData[] = [
                    'name' => $pageDetails->page_name ?? $pageDetails->name ?? $key,
                    'route' => isset($pageDetails->slug) ? route('page', $pageDetails->slug) : ($pageDetails->custom_link ?? staticPagesAndRoutes($key)),
                    'child' => $child
                ];
            }
        }
        return $menuData;
    }
}


if (!function_exists('getPageDetails')) {
    function getPageDetails($name)
    {
        try {
            DB::connection()->getPdo();
            $lang = session('lang');
            return Cache::remember("page_details_{$name}_{$lang}", now()->addMinutes(30),
                function () use ($name, $lang) {
                    return Page::select('id', 'name', 'slug', 'custom_link', 'home_name')
                        ->where('name', $name)
                        ->orWhere('home_name', $name)
                        ->addSelect([
                            'page_name' => PageDetail::with('language')
                                ->select('name')
                                ->whereHas('language', function ($query) use ($lang) {
                                    $query->where('short_name', $lang);
                                })
                                ->whereColumn('page_id', 'pages.id')
                                ->limit(1)
                        ])
                        ->first();
                });
        } catch (\Exception $e) {
            \Log::error("Error fetching page details: " . $e->getMessage());
            return null;
        }
    }
}

if (!function_exists('getLastSegment')) {
    function getLastSegment()
    {
        return basename(parse_url(url()->current(), PHP_URL_PATH));
    }
}

if (!function_exists('renderHeaderMenu')) {
    function renderHeaderMenu($menuItems)
    {
        $currentSegment = strtolower(getLastSegment());

        if ($menuItems) {
            echo '<ul>';
            foreach ($menuItems as $menuItem) {
                $hasChild = isset($menuItem['child']);
                $menuSegment = strtolower(basename($menuItem['route'] ?? ''));

                $isActive = $currentSegment === $menuSegment;
                $childIsActive = false;

                if ($hasChild) {
                    foreach ($menuItem['child'] as $child) {
                        $childSegment = strtolower(basename($child['route'] ?? ''));
                        if ($currentSegment === $childSegment) {
                            $childIsActive = true;
                            break;
                        }
                    }
                }

                $activeClass = ($isActive || $childIsActive) ? ' active' : '';
                $liClass = $hasChild ? 'has-dropdown' : '';

                echo '<li class="' . $liClass . $activeClass . '">';

                if ($hasChild) {
                    echo '<a href="javascript:void(0)" class="dropdown-toggle">';
                    echo '<span>' . $menuItem['name'] . '</span> <i class="far fa-chevron-down"></i>';
                    echo '</a>';

                    echo '<ul class="submenu">';
                    foreach ($menuItem['child'] as $child) {
                        echo '<li>';
                        if (isset($child['child'])) {
                            echo '<a href="javascript:void(0)" class="dropdown-toggle">';
                            echo '<span>' . $child['name'] . '</span> <i class="far fa-chevron-down"></i>';
                            echo '</a>';

                            echo '<ul class="submenu">';
                            renderHeaderMenu($child['child']);
                            echo '</ul>';
                        } else {
                            echo '<a class="nav-link text-capitalize" href="' . $child['route'] . '">' . $child['name'] . '</a>';
                        }
                        echo '</li>';
                    }
                    echo '</ul>';
                } else {
                    echo '<a class="nav-link text-capitalize' . $activeClass . '" href="' . $menuItem['route'] . '">' . $menuItem['name'] . '</a>';
                }

                echo '</li>';
            }
            echo '</ul>';
        }
        return '';
    }
}




if (!function_exists('getFooterMenuData')) {
    function getFooterMenuData($type)
    {
        $menu = ManageMenu::where('menu_section', 'footer')->first();
        $menuData = [];

        if (isset($menu->menu_items[$type])) {
            foreach ($menu->menu_items[$type] as $key => $menuItem) {
                $pageDetails = getPageDetails($menuItem);
                $menuIDetails = [
                    'name' => $pageDetails->page_name ?? $pageDetails->name ?? $menuItem,
                    'route' => isset($pageDetails->slug) ? route('page', $pageDetails->slug) : ($pageDetails->custom_link ?? staticPagesAndRoutes($menuItem)),
                ];
                $menuData[] = $menuIDetails;
            }
            foreach ($menuData as $item) {
                $che = '<li><i class="bi bi-chevron-right"></i><a class="text-capitalize" href="' . $item['route'] . '">' . $item['name'] . '</a></li>';
                $flattenedMenuData[] = $che;
            }
            return $flattenedMenuData;
        }
    }
}

function getPageName($name)
{
    try {
        DB::connection()->getPdo();
        $defaultLanguage = Cache::remember('default_language', now()->addMinutes(30), function () {
            return Language::query()->where('default_status', true)->first();
        });

        $pageDetails = Cache::remember("page_details_{$defaultLanguage->id}_{$name}", now()->addMinutes(30),
            function () use ($defaultLanguage, $name) {
                return PageDetail::select('id', 'page_id', 'name')
                    ->with('page:id,name,slug')
                    ->where('language_id', $defaultLanguage->id)
                    ->whereHas('page', function ($query) use ($name) {
                        $query->where('name', $name);
                    })
                    ->first();
            });
        return $pageDetails->name ?? $pageDetails->page->name ?? $name;
    } catch (\Exception $e) {

    }
}


function filterCustomLinkRecursive($collection, $lookingKey = '')
{

    $filterCustomLinkRecursive = function ($array) use (&$filterCustomLinkRecursive, $lookingKey) {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = $filterCustomLinkRecursive($value);
            } elseif ($value === $lookingKey || $key === $lookingKey) {
                unset($array[$key]);
            }
        }
        return $array;
    };
    $filteredCollection = $filterCustomLinkRecursive($collection);

    return $filteredCollection;
}

if (!function_exists('maskString')) {
    function maskString($input)
    {
        $length = strlen($input);
        $visibleCharacters = 2;
        $maskedString = '<span class="masked ms-2">' . substr($input, 0, $visibleCharacters) . '<span class="highlight">' . str_repeat('*', $length - 2 * $visibleCharacters) . '</span>' . substr($input, -$visibleCharacters) . '</span>';
        return $maskedString;
    }
}

if (!function_exists('maskEmail')) {
    function maskEmail($email)
    {
        list($username, $domain) = explode('@', $email);
        $usernameLength = strlen($username);
        $visibleCharacters = 2;
        $maskedUsername = substr($username, 0, $visibleCharacters) . str_repeat('*', $usernameLength - 2 * $visibleCharacters) . substr($username, -$visibleCharacters);
        $maskedEmail = $maskedUsername . '@' . $domain;
        return $maskedEmail;
    }
}

if (!function_exists('removeValue')) {
    function removeValue(&$array, $value)
    {
        foreach ($array as $key => &$subArray) {
            if (is_array($subArray)) {
                removeValue($subArray, $value);
            } else {
                if ($subArray === $value) {
                    unset($array[$key]);
                }
            }
        }
    }
}


if (!function_exists('strRandomNum')) {
    function strRandomNum($length = 15)
    {
        $characters = '1234567890';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if (!function_exists('getFirebaseFileName')) {
    function getFirebaseFileName()
    {
        return 'firebase-service.json';
    }
}

if (!function_exists('footerData')) {
    function footerData()
    {

        $contentDetails = \Cache::get("footer_content");
        if (!$contentDetails || $contentDetails->isEmpty()) {
            $contentDetails = ContentDetails::with('content')
                ->whereIn('content_id', Content::whereIn('name', ['footer'])->pluck('id'))
                ->get()
                ->groupBy(function ($item) {
                    return $item->content->name;
                });

            \Cache::put('footer_content', $contentDetails);
        }

        $languages = \Cache::get("footer_language");
        if (!$languages || $languages->isEmpty()) {
            $languages = Language::all();
            \Cache::put('footer_language', $languages);
        }

        $language = new App\Http\Middleware\Language();
        $code = $language->getCode();
        $defaultLanguage = $languages->where('short_name', $code)->first();

        return prepareContentData($contentDetails->get('footer'), $languages, $defaultLanguage);
    }
}
if (!function_exists('prepareContentData')) {
    function prepareContentData($footerContents, $languages, $defaultLanguage)
    {
        if (is_null($footerContents)) {
            return [
                'single' => [],
                'multiple' => collect(),
                'language' => $languages,
                'defaultLanguage' => $defaultLanguage,
            ];
        }

        $singleContent = $footerContents->where('content.name', 'footer')
            ->where('content.type', 'single')
            ->first();

        $multipleContents = $footerContents->where('content.name', 'footer')
            ->where('content.type', 'multiple')
            ->values()
            ->map(function ($content) {
                return collect($content->description)->merge($content->content->only('media'));
            });

        return [
            'single' => $singleContent ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media')) : [],
            'multiple' => $multipleContents,
            'language' => $languages,
            'defaultLanguage' => $defaultLanguage,
        ];
    }
}
if (!function_exists('getSocialData')) {
    function getSocialData()
    {
        $content = 'social';
        $contentData = \Cache::get("social_data");
        if (!$contentData || $contentData->isEmpty()) {
            $contentData = ContentDetails::with('content')
                ->whereHas('content', function ($query) use ($content) {
                    $query->where('name', $content);
                })
                ->get();
            \Cache::put('social_data', $contentData);
        }

        $singleContent = $contentData->where('content.name', $content)->where('content.type', 'single')->first() ?? [];
        $multipleContents = $contentData->where('content.name', $content)->where('content.type', 'multiple')->values()->map(function ($multipleContentData) {
            return collect($multipleContentData->description)->merge($multipleContentData->content->only('media'));
        });
        return [
            'single' => $singleContent,
            'multiple' => $multipleContents,
        ];
    }
}

if (!function_exists('fixedAreaData')) {
    function fixedAreaData()
    {
        $content = 'fixed_area';
        $contentData = \Cache::get("fixed_area");
        if (!$contentData || $contentData->isEmpty()) {
            $contentData = ContentDetails::with('content')
                ->whereHas('content', function ($query) use ($content) {
                    $query->where('name', $content);
                })
                ->get();
            \Cache::put('fixed_area', $contentData);
        }

        $singleContent = $contentData->where('content.name', $content)->where('content.type', 'single')->first() ?? [];
        $multipleContents = $contentData->where('content.name', $content)->where('content.type', 'multiple')->values()->map(function ($multipleContentData) {
            return collect($multipleContentData->description)->merge($multipleContentData->content->only('media'));
        });
        return [
            'single' => $singleContent,
            'multiple' => $multipleContents,
        ];
    }
}

if (!function_exists('getHeaderData')) {
    function getHeaderData()
    {
        $content = 'header';
        $contentData = \Cache::get("header_data");
        if (!$contentData || $contentData->isEmpty()) {
            $contentData = ContentDetails::with('content')
                ->whereHas('content', function ($query) use ($content) {
                    $query->where('name', $content);
                })->get();
            \Cache::put('header_data', $contentData);
        }

        $singleContent = $contentData->where('content.name', $content)->where('content.type', 'single')->first() ?? [];
        $multipleContents = $contentData->where('content.name', $content)->where('content.type', 'multiple')->values()->map(function ($multipleContentData) {
            return collect($multipleContentData->description)->merge($multipleContentData->content->only('media'));
        });
        return [
            'single' => $singleContent,
            'multiple' => $multipleContents,
        ];
    }
}

if (!function_exists('getPackagesCountByDate')) {
    function getPackagesCountByDate($user, $relation)
    {
        $dates = [];
        $counts = [];

        for ($i = 30; $i >= 0; $i--) {
            $date = \Carbon\Carbon::today()->subDays($i);
            $dates[] = $date->format('d M');

            $count = $relation->whereDate('created_at', $date)->count();
            $counts[] = $count;
        }

        return [
            'labels' => $dates,
            'data' => $counts
        ];
    }
}
if (!function_exists('getAmountByDateTwo')) {
    function getAmountByDateTwo($user, $query)
    {
        $dates = [];
        $amounts = [];

        for ($i = 30; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $formatted = $date->format('d M');

            $dates[] = $formatted;

            $sum = (clone $query)->whereDate('created_at', $date)->sum('total_price');
            $amounts[] = round($sum, 2);
        }

        return [
            'labels' => $dates,
            'data' => $amounts
        ];
    }
}

if (!function_exists('renderTravellerFields')) {
    function renderTravellerFields($travellerInfo, $travellerType, $travellerIndex, $errors)
    {
        $firstName = old('fname_' . $travellerType . '.' . $travellerIndex, isset($travellerInfo[$travellerIndex]['first_name']) ? $travellerInfo[$travellerIndex]['first_name'] : '');
        $lastName = old('lname_' . $travellerType . '.' . $travellerIndex, isset($travellerInfo[$travellerIndex]['last_name']) ? $travellerInfo[$travellerIndex]['last_name'] : '');
        $birthDate = old('date_' . $travellerType . '.' . $travellerIndex, isset($travellerInfo[$travellerIndex]['birth_date']) ? $travellerInfo[$travellerIndex]['birth_date'] : '');

        $fnameError = $errors->first('fname_' . $travellerType . '.' . $travellerIndex);
        $lnameError = $errors->first('lname_' . $travellerType . '.' . $travellerIndex);
        $dateError = $errors->first('date_' . $travellerType . '.' . $travellerIndex);

        return '
        <h6>' . __('Traveller ' . ($travellerIndex + 1) . ' (' . ucfirst($travellerType) . ')') . '</h6>
        <div class="col-12 traveler-info-show">
            <div class="row inputList">
                <div class="col-md-6 pt-1">
                    <div>
                        <label for="First-Name-' . ucfirst($travellerType) . '-' . $travellerIndex . '" class="mt-1 form-label">' . __('First Name') . '</label>
                        <input type="text" class="form-control' . ($fnameError ? ' is-invalid' : '') . '" name="fname_' . $travellerType . '[' . $travellerIndex . ']" value="' . htmlspecialchars($firstName, ENT_QUOTES) . '" id="First-Name-' . ucfirst($travellerType) . '-' . $travellerIndex . '" placeholder="First Name">
                        ' . ($fnameError ? '<div class="invalid-feedback d-block">' . $fnameError . '</div>' : '') . '
                    </div>
                    <span class="p-0 text-danger error_fname_' . $travellerType . '_' . $travellerIndex . '"></span>
                </div>

                <div class="col-md-6 pt-1">
                    <div>
                        <label for="Last-Name-' . ucfirst($travellerType) . '-' . $travellerIndex . '" class="mt-1 form-label">' . __('Last Name') . '</label>
                        <input type="text" class="form-control' . ($lnameError ? ' is-invalid' : '') . '" name="lname_' . $travellerType . '[' . $travellerIndex . ']" value="' . htmlspecialchars($lastName, ENT_QUOTES) . '" id="Last-Name-' . ucfirst($travellerType) . '-' . $travellerIndex . '" placeholder="Last Name">
                        ' . ($lnameError ? '<div class="invalid-feedback d-block">' . $lnameError . '</div>' : '') . '
                    </div>
                    <span class=" p-0 error text-danger error_lname_' . $travellerType . '_' . $travellerIndex . '"></span>
                </div>

                <div class="col-md-6 pt-1">
                    <div>
                        <label for="Date-' . ucfirst($travellerType) . '-' . $travellerIndex . '" class="mt-1 form-label">' . __('Birth Date') . '</label>
                        <input type="text" class="form-control flatpickr' . ($dateError ? ' is-invalid' : '') . '" name="date_' . $travellerType . '[' . $travellerIndex . ']" value="' . htmlspecialchars($birthDate, ENT_QUOTES) . '" placeholder="e.g 2024-07-25" id="Date-' . ucfirst($travellerType) . '-' . $travellerIndex . '">
                        ' . ($dateError ? '<div class="invalid-feedback d-block">' . $dateError . '</div>' : '') . '
                    </div>
                    <span class="p-0 error text-danger error_date_' . $travellerType . '_' . $travellerIndex . '"></span>
                </div>
            </div>
            <hr class="formHr">
        </div>
    ';
    }
}

if (!function_exists('renderDisabledTravellerFields')) {
    function renderDisabledTravellerFields($travellerInfo, $travellerType, $travellerIndex)
    {
        $firstName = isset($travellerInfo[$travellerIndex]['first_name']) ? $travellerInfo[$travellerIndex]['first_name'] : '';
        $lastName = isset($travellerInfo[$travellerIndex]['last_name']) ? $travellerInfo[$travellerIndex]['last_name'] : '';
        $birthDate = isset($travellerInfo[$travellerIndex]['birth_date']) ? $travellerInfo[$travellerIndex]['birth_date'] : '';

        return '
        <h6>' . __('Traveller ' . ($travellerIndex + 1) . ' (' . ucfirst($travellerType) . ')') . '</h6>
        <div class="col-12 traveler-info-show">
            <div class="row">
                <div class="col-md-6 pt-1">
                    <label for="First-Name-' . ucfirst($travellerType) . '-' . $travellerIndex . '" class="mt-1 form-label">' . __('First Name') . '</label>
                    <input type="text" class="form-control" name="fname_' . $travellerType . '[' . $travellerIndex . ']" value="' . $firstName . '" id="First-Name-' . ucfirst($travellerType) . '-' . $travellerIndex . '" placeholder="First Name" readonly>
                </div>
                <div class="col-md-6 pt-1">
                    <label for="Last-Name-' . ucfirst($travellerType) . '-' . $travellerIndex . '" class="mt-1 form-label">' . __('Last Name') . '</label>
                    <input type="text" class="form-control" name="lname_' . $travellerType . '[' . $travellerIndex . ']" value="' . $lastName . '" id="Last-Name-' . ucfirst($travellerType) . '-' . $travellerIndex . '" placeholder="Last Name" readonly>
                </div>
                <div class="col-md-6 pt-1">
                    <label for="Date-' . ucfirst($travellerType) . '-' . $travellerIndex . '" class="mt-1 form-label">' . __('Birth Date') . '</label>
                    <input type="text" class="form-control flatpickr" name="date_' . $travellerType . '[' . $travellerIndex . ']" value="' . $birthDate . '" placeholder="e.g 2024-07-25" id="Date-' . ucfirst($travellerType) . '-' . $travellerIndex . '" readonly>
                </div>
            </div>
            <hr class="formHr">
        </div>
    ';
    }
}

if (!function_exists('displayStarRating')) {
    function displayStarRating($rating)
    {
        $fullStars = floor($rating);
        $decimalPart = $rating - $fullStars;

        $output = '';

        for ($i = 0; $i < $fullStars; $i++) {
            $output .= '<i class="fas fa-star text-warning"></i>';
        }

        if ($decimalPart > 0) {
            if ($decimalPart <= 0.5) {
                $output .= '<i class="fas fa-star-half-alt text-warning"></i>';
            } else {
                $output .= '<i class="fas fa-star text-warning"></i>';
            }
            $fullStars++;
        }
        $emptyStars = 5 - $fullStars;
        for ($i = 0; $i < $emptyStars; $i++) {
            $output .= '<i class="far fa-star text-warning"></i>';
        }

        return $output;
    }
}

if (!function_exists('getPwaData')) {
    function getPwaData()
    {
        $contentDetails = \Cache::get("pwa_content");

        if (!$contentDetails) {
            $contentDetails = ContentDetails::with('content')
                ->whereIn('content_id', Content::whereIn('name', ['pwa_popup'])->pluck('id'))
                ->get()
                ->groupBy(function ($item) {
                    return $item->content->name;
                });

            \Cache::put('pwa_content', $contentDetails);
        }

        $pwaPopupContent = $contentDetails->get('pwa_popup') ?? collect();

        $singleContent = $pwaPopupContent
            ->where('content.type', 'single')
            ->first();

        $multipleContents = $pwaPopupContent
            ->where('content.type', 'multiple')
            ->values()
            ->map(fn($content) => collect($content->description)->merge($content->content->only('media')));

        return [
            'single' => $singleContent
                ? collect($singleContent->description ?? [])->merge($singleContent->content->only('media'))
                : [],
            'multiple' => $multipleContents,
        ];
    }
}

if (!function_exists('isPayoutAccess')) {
    function isPayoutAccess()
    {
        $basic = BasicControl::firstOrCreate();
        $user = auth()->user();

        if ($basic->payment_collection && ($user->payment_collection_system == 1)) {
            return true;
        }

        if ((!$basic->payment_collection || ($user->payment_collection_system != 1)) && $user->balance > 0) {
            return true;
        }

        return false;
    }
}

if (!function_exists('getGatewayModel')) {
    function getGatewayModel($package)
    {
        $basic = BasicControl::firstOrCreate();
        $user = $package->owner;

        if ($basic->payment_collection && ($user->payment_collection_system == 1)) {

            return \App\Models\UserGateway::class;
        }

        if ($user->payment_collection_system == 1) {
            return \App\Models\UserGateway::class;
        }

        return \App\Models\Gateway::class;
    }
}

if (!function_exists('isAiAccess')) {
    function isAiAccess()
    {
        $basic = BasicControl::firstOrCreate();
        $user = auth()->user();

        if ($user->activePlan && $user->activePlan->ai_feature) {
            $plan = $user->activePlan;
            if ($basic->ai_feature && $plan->ai_feature) {
                return true;
            }

            if (!$basic->ai_feature && $plan->ai_feature) {
                return true;
            }
        }

        return false;
    }
}

function makeSubscriptionProduct($subscriptionPlan, $productId, $gatewayCode)
{
    $gateway_plan_id = $subscriptionPlan->gateway_plan_id;
    $parameters = [];
    if ($gateway_plan_id) {
        foreach ($gateway_plan_id as $key => $id) {
            $parameters[$key] = $id;
        }
    }
    $parameters[$gatewayCode] = $productId;
    $subscriptionPlan->gateway_plan_id = $parameters;
    $subscriptionPlan->save();

    return true;
}

function updateWallet($user_id, $credits, $balanceType, $action = 0)
{
    $user = User::find($user_id);
    $balance = 0;

    if ($action == 1) { //add credit
        $balance = $user->{$balanceType} + $credits;
        $user->{$balanceType} = $balance;
    } elseif ($action == 0) { //deduct money
        $balance = $user->{$balanceType} - $credits;
        $user->{$balanceType} = $balance;
    }

    $user->save();
    return $balance;
}

