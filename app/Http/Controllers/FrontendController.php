<?php

namespace App\Http\Controllers;

use App\Helpers\UserSystemInfo;
use App\Jobs\UserOwnGatewayJob;
use App\Mail\SendMail;
use App\Models\Content;
use App\Models\ContentDetails;
use App\Models\Deposit;
use App\Models\Destination;
use App\Models\DestinationVisitor;
use App\Models\Language;
use App\Models\Package;
use App\Models\Page;
use App\Models\PageDetail;
use App\Models\Subscriber;
use App\Traits\Frontend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class FrontendController extends Controller
{
    use Frontend;

    public function page(Request $request, $slug = '/')
    {
        try {
            $selectedTheme = getTheme();
            $homeVersion = getHomeStyle();
            if ($request->has('theme')) {
                $themeName = $request->theme;
                if (!in_array($themeName, array_keys(config('themes')))) {
                    throw new \Exception("Invalid  Theme Request",404);
                }

                $selectedTheme = $request->theme;
                if ($request->has('home_version')) {
                    $homeVersion = $request->home_version;
                    if (!in_array($homeVersion, array_keys(config('themes')[$themeName]['home_version']))) {
                        throw new \Exception("Invalid  Home Request",404);
                    }
                }

                $page = Page::where('home_name', $homeVersion)->first();
                if ($page) {
                    $slug = $page->slug;
                }
            }
            $existingSlugs = collect([]);


            DB::table('pages')->select('slug')->get()
                ->map(function ($item) use ($existingSlugs) {
                    $existingSlugs->push($item->slug);
                });
            if (!in_array($slug, $existingSlugs->toArray())) {
                throw new \Exception("Page Not Found",404);
            }

            $pageDetails = PageDetail::with('page')
                ->whereHas('page', function ($query) use ($slug, $selectedTheme) {
                    $query->where(['slug' => $slug, 'template_name' => $selectedTheme]);
                })
                ->first();

            if ($request->has('theme') && $request->has('home_version')) {
                $status = 1;
            } else {
                $status = $pageDetails->page->status;
            }
            $pageSeo = [
                'page_title' => optional(optional($pageDetails)->page)->page_title ?? '',
                'meta_title' => optional(optional($pageDetails)->page)->meta_title,
                'meta_keywords' => implode(',', optional(optional($pageDetails)->page)->meta_keywords ?? []),
                'meta_description' => optional(optional($pageDetails)->page)->meta_description,
                'og_description' => optional(optional($pageDetails)->page)->og_description,
                'meta_robots' => optional(optional($pageDetails)->page)->meta_robots,
                'meta_image' => $pageDetails?->page
                    ? getFile($pageDetails->page->meta_image_driver, $pageDetails->page->meta_image)
                    : null,
                'breadcrumb_status' => $pageDetails?->page?->breadcrumb_status,
                'breadcrumb_image' => $pageDetails?->page?->breadcrumb_status
                    ? getFile($pageDetails->page->breadcrumb_image_driver, $pageDetails->page->breadcrumb_image)
                    : null,
            ];

            $sectionsData = $this->getSectionsData($pageDetails->sections, $pageDetails->content, $selectedTheme);
            return view("themes.{$selectedTheme}.page", compact('sectionsData', 'pageSeo'));

        } catch (\Exception $exception) {
            \Cache::forget('ConfigureSetting');
            if ($exception->getCode() == 404) {
                abort(404);
            }
            if ($exception->getCode() == 403) {
                abort(403);
            }
            if ($exception->getCode() == 401) {
                abort(401);
            }
            if ($exception->getCode() == 503) {
                return redirect()->route('maintenance');
            }
            if ($exception->getCode() == "42S02") {
                die($exception->getMessage());
            }
            if ($exception->getCode() == 1045) {
                die("Access denied. Please check your username and password.");
            }
            if ($exception->getCode() == 1044) {
                die("Access denied to the database. Ensure your user has the necessary permissions.");
            }
            if ($exception->getCode() == 1049) {
                die("Unknown database. Please verify the database name exists and is spelled correctly.");
            }
            if ($exception->getCode() == 2002) {
                die("Unable to connect to the MySQL server. Check the database host and ensure the server is running.");
            }
            return redirect()->route('instructionPage');
        }
    }

    public function trackVisitor(Request $request)
    {
        $destinationId = $request->input('destination_id');
        $ipAddress = $request->ip();

        $key = "bouncing_time_{$destinationId}_{$ipAddress}";

        $bouncingTime = now();

        $visitor = new  DestinationVisitor();
        $visitor->destination_id = $destinationId;
        $visitor->ip_address = $ipAddress;
        $visitor->bouncing_time = $bouncingTime;
        $visitor->browser_info = UserSystemInfo::get_browsers();
        $visitor->os = UserSystemInfo::get_os();
        $visitor->device = UserSystemInfo::get_device();
        $visitor->save();

        Cache::put($key, $bouncingTime, now()->addMinutes(30));

        return response()->json(['message' => 'Destination visit traced']);
    }

    public function contactSend(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|max:91',
            'subject' => 'required|max:100',
            'message' => 'required|max:1000',
            'recaptcha_token' => 'required',
        ]);

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_V3_SECRET_KEY'),
            'response' => $request->input('recaptcha_token'),
        ]);

        $responseData = $response->json();

        if (!isset($responseData['success']) || !$responseData['success'] || $responseData['score'] < 0.5) {
            return back()->withErrors(['recaptcha' => 'reCAPTCHA verification failed.']);
        }

        $requestData = $request->except('_token', '_method');

        $name = $requestData['name'];
        $email_from = $requestData['email'];
        $subject = $requestData['subject'];
        $message = $requestData['message'] . "<br>Regards<br>" . $name;
        $from = $email_from;

        Mail::to(basicControl()->sender_email)->send(new SendMail($from, $subject, $message));
        return back()->with('success', 'Mail has been sent');
    }

    public function topSearch(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return response()->json([]);
        }

        $packages = Package::select(['id', 'title', 'slug', 'thumb', 'thumb_driver'])->where('title', 'like', '%' . $query . '%')
            ->orderByRaw("CASE WHEN is_featured = 1 THEN 0 ELSE 1 END")
            ->where('status', 1)
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'image' => getFile($item->thumb_driver, $item->thumb),
                    'type' => 'package',
                    'url' => route('package.details', $item->slug),
                ];
            });

        $destinations = Destination::select(['id', 'title', 'slug', 'thumb', 'thumb_driver'])->where('title', 'like', '%' . $query . '%')
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'image' => getFile($item->thumb_driver, $item->thumb),
                    'type' => 'destination',
                    'url' => route('destination.details', $item->slug),
                ];
            });

        $results = collect($packages)->merge(collect($destinations))->values();

        return response()->json($results);
    }

    public function subscribe(Request $request)
    {

        $request->validate([
            'contactEmail' => 'required | unique:subscribers,email',
        ]);

        try {
            Subscriber::insert([
                'email' => $request->contactEmail
            ]);

            return back()->with('success', 'Subscription Completed, Welcome!!');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function language($locale)
    {
        $language = Language::where('short_name', $locale)->first();

        if (!$language) {
            $locale = 'en';
        }

        session()->put('lang', $locale);
        session()->put('rtl', $language ? $language->rtl : 0);

        return redirect()->back();
    }
}
