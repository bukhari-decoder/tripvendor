<?php

namespace App\Http\Controllers\User;


use App\Helpers\GoogleAuthenticator;
use App\Http\Controllers\Controller;
use App\Jobs\UserOwnGatewayJob;
use App\Models\BasicControl;
use App\Models\Booking;
use App\Models\City;
use App\Models\Country;
use App\Models\Deposit;
use App\Models\Gateway;
use App\Models\Kyc;
use App\Models\Language;
use App\Models\Package;
use App\Models\State;
use App\Models\SupportTicket;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserKyc;
use App\Models\VendorInfo;
use App\Traits\Notify;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use PragmaRX\Google2FA\Google2FA;
use Yajra\DataTables\Facades\DataTables;


class HomeController extends Controller
{
    use Upload, Notify;

    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
        $this->theme = template();
    }

    public function saveToken(Request $request)
    {
        try {
            Auth::user()
                ->fireBaseToken()
                ->create([
                    'token' => $request->token,
                ]);
            return response()->json([
                'msg' => 'token saved successfully.',
            ]);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function transaction()
    {
        $now = Carbon::now();

        $totals = DB::table('transactions')
            ->where(function ($query) {
                $query->where('user_id', auth()->id())
                    ->orWhere('vendor_id', auth()->id());
            })
            ->selectRaw('
            COUNT(*) as total,
            COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN 1 END) as today,
            COUNT(CASE WHEN YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1) THEN 1 END) as this_week,
            COUNT(CASE WHEN MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) THEN 1 END) as this_month,
            COUNT(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) THEN 1 END) as this_year
        ')
            ->first();

        $total = max($totals->total, 1);

        $percentages = [
            'today' => round($totals->today / $total * 100, 2),
            'this_week' => round($totals->this_week / $total * 100, 2),
            'this_month' => round($totals->this_month / $total * 100, 2),
            'this_year' => round($totals->this_year / $total * 100, 2),
        ];

        return view(template() . 'user.transaction.index', compact('totals', 'percentages'));
    }

    public function transactionSearch(Request $request)
    {
        $search = $request->search['value'];
        $filterTransactionId = $request->filterTransactionID;
        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;

        $transaction = Transaction::query()->with(['user:id,username,firstname,lastname,image,image_driver'])
            ->where(function ($query) {
                $query->where('user_id', auth()->id())
                    ->orWhere('vendor_id', auth()->id());
            })
            ->orderBy('id', 'DESC')
            ->when(!empty($search), function ($query) use ($search) {
                return $query->where(function ($subquery) use ($search) {
                    $subquery->where('trx_id', 'LIKE', "%$search%")
                        ->orWhere('remarks', 'LIKE', "%{$search}%")
                        ->orWhereHas('user', function ($q) use ($search) {
                            $q->where('firstname', 'LIKE', "%$search%")
                                ->orWhere('lastname', 'LIKE', "%{$search}%")
                                ->orWhere('username', 'LIKE', "%{$search}%");
                        });
                });
            })
            ->when(!empty($request->filterDate) && $endDate == null, function ($query) use ($startDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $query->whereDate('created_at', $startDate);
            })
            ->when(!empty($request->filterDate) && $endDate != null, function ($query) use ($startDate, $endDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $endDate = Carbon::createFromFormat('d/m/Y', trim($endDate));
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->when(!empty($filterTransactionId), function ($query) use ($filterTransactionId) {
                return $query->where('trx_id', $filterTransactionId);
            })->get();

        return DataTables::of($transaction)
            ->addColumn('trx', function ($item) {
                return $item->trx_id;
            })
            ->addColumn('amount', function ($item) {
                if ($item->vendor_id) {
                    $currentTrxType = ($item->vendor_id == auth()->id()) ? '+' : '-';
                    $currentStatusClass = ($item->vendor_id == auth()->id()) ? 'text-success' : 'text-danger';
                }else{
                    $currentStatusClass =  ($item->trx_type == '+') ? 'text-success' : 'text-danger';
                    $currentTrxType =  $item->trx_type;
                }

                return "<h6 class='mb-0 $currentStatusClass '>" . $currentTrxType . ' ' . currencyPosition($item->amount) . "</h6>";
            })
            ->addColumn('charge', function ($item) {
                return "<span class='text-danger'>" . currencyPosition(getAmount($item->charge)) . "</span>";
            })
            ->addColumn('remarks', function ($item) {
                return $item->remarks;
            })
            ->addColumn('date-time', function ($item) {
                return dateTime($item->created_at, 'd M Y h:i A');
            })
            ->rawColumns(['user', 'amount', 'charge'])
            ->make(true);
    }


    public function index()
    {

        $data['user'] = Auth::user();
        $data['firebaseNotify'] = config('firebase');
        $data['total_booking'] = $data['user']->booking()->count();
        $data['total_vendor_booking'] = $data['user']->vendorBooking()->count();

        if (auth()->user()->role == 0) {
            $data['kycForm'] = Kyc::select(['id', 'input_form', 'is_automatic', 'apply_for', 'status'])->where('status', 1)->where('apply_for', 1)->first();
        }

        $data['charts'] = [
            [
                'title' => 'Upcoming Tour',
                'total' => $data['user']->booking()
                        ->where('status', 5)
                        ->whereDate('date', '>=', now()->toDateString())
                        ->count() ?? 0,
                'from_total' => $data['user']->booking()->whereIn('status', [1, 2, 3, 4, 5])->count() ?? 0,
                'icon' => 'bi-calendar-check',
                'icon_class' => 'icon-soft-success',
                'graph_class' => 'bg-soft-success text-success',
                'permission' => '0'
            ],
            [
                'title' => 'Completed Tour',
                'total' => $data['user']->booking()
                        ->where('status', 2)
                        ->count() ?? 0,
                'from_total' => $data['user']->booking()->count() ?? 0,
                'icon' => 'bi-check-circle',
                'icon_class' => 'icon-soft-info',
                'graph_class' => 'bg-soft-info text-info',
                'permission' => '0'
            ],
            [
                'title' => 'Expired Tour',
                'total' => $data['user']->booking()
                        ->whereNotIn('status', [2, 4])
                        ->whereDate('date', '<', now()->toDateString())
                        ->count() ?? 0,
                'from_total' => $data['user']->booking()->count() ?? 0,
                'icon' => 'bi-x-square',
                'icon_class' => 'icon-soft-danger',
                'graph_class' => 'bg-soft-danger text-danger',
                'permission' => '0'
            ],
            [
                'title' => 'Today Tour',
                'total' => $data['user']->vendorBooking()
                        ->whereIn('status', [1, 2, 3, 4])
                        ->whereDate('date', now()->toDateString())
                        ->count() ?? 0,

                'from_total' => Booking::whereHas('package', function ($query) {
                        $query->where('owner_id', auth()->id());
                    })
                        ->whereIn('status', [1, 2, 3, 4, 5])
                        ->count() ?? 0,

                'icon' => 'bi-calendar-day',
                'icon_class' => 'icon-soft-success',
                'graph_class' => 'bg-soft-success text-success',
                'permission' => '1'
            ],
            [
                'title' => 'Next 7 Days Tour',
                'total' => $data['user']->vendorBooking()
                        ->whereIn('status', [1, 2, 3, 4, 5])
                        ->whereBetween('date', [now()->toDateString(), now()->addDays(6)->toDateString()])
                        ->count() ?? 0,

                'from_total' => Booking::whereHas('package', function ($query) {
                        $query->where('owner_id', auth()->id());
                    })
                        ->whereIn('status', [1, 2, 3, 4, 5])
                        ->count() ?? 0,

                'icon' => 'bi-calendar-week',
                'icon_class' => 'icon-soft-info',
                'graph_class' => 'bg-soft-info text-info',
                'permission' => '1'
            ],
            [
                'title' => 'This Month Tour',
                'total' => $data['user']->vendorBooking()
                        ->whereIn('status', [1, 2, 3, 4, 5])
                        ->whereMonth('date', now()->month)
                        ->whereYear('date', now()->year)
                        ->count() ?? 0,

                'from_total' => Booking::whereHas('package', function ($query) {
                        $query->where('owner_id', auth()->id());
                    })
                        ->whereIn('status', [1, 2, 3, 4, 5])
                        ->count() ?? 0,

                'icon' => 'bi-calendar-month',
                'icon_class' => 'icon-soft-primary',
                'graph_class' => 'bg-soft-primary text-primary',
                'permission' => '1'
            ],
            [
                'title' => 'Support Tickets',
                'total' => $data['user']->tickets()->count() ?? 0,
                'from_total' => SupportTicket::has('user')->count() ?? 0,
                'icon' => 'bi-headset',
                'icon_class' => 'icon-soft-warning',
                'graph_class' => 'bg-soft-warning text-warning',
                'permission' => 'all'
            ],
        ];
        foreach ($data['charts'] as &$chart) {
            $cleanTotal = is_numeric($chart['total'])
                ? $chart['total']
                : floatval(preg_replace('/[^\d.]/', '', $chart['total']));

            $cleanFromTotal = is_numeric($chart['from_total'])
                ? $chart['from_total']
                : floatval(preg_replace('/[^\d.]/', '', $chart['from_total']));

            $chart['percentage'] = $cleanFromTotal > 0
                ? round(($cleanTotal / $cleanFromTotal) * 100, 2)
                : 0;
        }
        unset($chart);

        if (auth()->user()->role == 1) {
            $data['transactions'] = Transaction::where('user_id', auth()->id())->latest()->take(6)->get();
        }

        return view(template() . 'user.dashboard', $data);
    }

    public function fetchState(Request $request)
    {
        $data['states'] = State::where('country_id', $request->country_id)->where('status', 1)
            ->get(["name", "id"]);
        return response()->json($data);
    }

    public function fetchCity(Request $request)
    {
        $data['cities'] = City::where('state_id', $request->state_id)->where('status', 1)
            ->get(["name", "id"]);
        return response()->json($data);
    }

    public function profile()
    {
        $data['languages'] = Language::all();
        $data['counties'] = Country::where('status', 1)->get();
        $user = User::select(['id'])->with(['vendorInfo:id,vendor_id,facebook_link,twitter_link,instagram_link,linkedin_link'])->where('id', auth()->id())->first();
        $data['vendorInfo'] = $user->vendorInfo;

        return view(template() . 'user.profile.edit_profile', $data);
    }

    public function profileUpdateImage(Request $request)
    {
        $allowedExtensions = array('jpg', 'png', 'jpeg');
        $image = $request->image;
        $this->validate($request, [
            'image' => [
                'required',
                'max:4096',
                function ($fail) use ($image, $allowedExtensions) {
                    $ext = strtolower($image->getClientOriginalExtension());
                    if (($image->getSize() / 1000000) > 2) {
                        throw ValidationException::withMessages(['image' => "Images MAX  2MB ALLOW!"]);
                    }
                    if (!in_array($ext, $allowedExtensions)) {
                        throw ValidationException::withMessages(['image' => "Only png, jpg, jpeg images are allowed"]);
                    }
                }
            ]
        ]);
        $user = Auth::user();
        if ($request->hasFile('image')) {
            $image = $this->fileUpload($request->image, config('filelocation.userProfile.path'), null, null, 'webp', 80, $user->image, $user->image_driver);
            if ($image) {
                $profileImage = $image['path'];
                $ImageDriver = $image['driver'];
            }
        }
        $user->image = $profileImage ?? $user->image;
        $user->image_driver = $ImageDriver ?? $user->image_driver;
        $user->save();
        return back()->with('success', 'Updated Successfully.');
    }

    public function profileUpdate(Request $request)
    {
        $languages = Language::all()->map(function ($item) {
            return $item->id;
        });
        throw_if(!$languages, 'Language not found.');

        $req = $request->except('_method', '_token');
        $user = Auth::user();
        $rules = [
            'firstname' => 'required|string|min:1|max:100',
            'lastname' => 'required|string|min:1|max:100',
            'email' => 'required|email:rfc,dns',
            'phone' => 'required|min:1|max:50',
            'phone_code' => 'required|min:1|max:50',
            'username' => "sometimes|required|alpha_dash|min:5|unique:users,username," . $user->id,
            'address_one' => 'required|string|min:2|max:500',
            'address_two' => 'nullable|string|min:2|max:500',
            'linkedin_link' => 'nullable|string|min:2|max:500',
            'instagram_link' => 'nullable|string|min:2|max:500',
            'twitter_link' => 'nullable|string|min:2|max:500',
            'facebook_link' => 'nullable|string|min:2|max:500',
            'language' => Rule::in($languages),
        ];
        $message = [
            'firstname.required' => 'First name field is required',
            'lastname.required' => 'Last name field is required',
        ];

        $validator = Validator::make($req, $rules, $message);
        if ($validator->fails()) {
            $validator->errors()->add('profile', '1');
            return back()->withErrors($validator)->withInput();
        }
        try {

            $country = Country::select(['id', 'name', 'iso2'])->where('id', $req['country'])->firstOr(function () {
                throw new \Exception('Country not found.');
            });
            if ($request['state']) {
                $state = State::select(['id', 'name'])->where('id', $req['state'])->orWhere('name', $req['state'])->firstOr(function () {
                    throw new \Exception('State not found.');
                });
            }
            if ($request['city']) {
                $city = City::select(['id', 'name'])->where('id', $req['city'])->orWhere('name', $req['city'])->firstOr(function () {
                    throw new \Exception('City not found.');
                });
            }

            $response = $user->update([
                'language_id' => $req['language'],
                'firstname' => $req['firstname'],
                'lastname' => $req['lastname'],
                'slug' => Slug($req['firstname'] . ' ' . $req['lastname']),
                'email' => $req['email'],
                'phone' => $req['phone'],
                'phone_code' => $req['phone_code'],
                'username' => $req['username'],
                'address_one' => $req['address_one'],
                'address_two' => $req['address_two'],
                'zip_code' => $req['zipcode'],
                'country' => $country->name,
                'country_code' => $country->iso2,
                'state' => $state->name ?? null,
                'city' => $city->name ?? null,
                'about_me' => $req['about_me'],
            ]);

            if ($user->role == 1) {
                $vendorInfo = $user->vendorInfo;
                $vendorInfo->facebook_link = $req['facebook_link'];
                $vendorInfo->instagram_link = $req['instagram_link'];
                $vendorInfo->twitter_link = $req['twitter_link'];
                $vendorInfo->linkedin_link = $req['linkedin_link'];
                $vendorInfo->save();
            }

            throw_if(!$response, 'Something went wrong, While updating profile data');
            return back()->with('success', 'Profile updated Successfully.');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }


    public function updatePassword(Request $request)
    {
        $rules = [
            'current_password' => "required",
            'password' => "required|min:5|confirmed",
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $user = Auth::user();
        try {
            if (Hash::check($request->current_password, $user->password)) {
                $user->password = bcrypt($request->password);
                $user->save();
                return back()->with('success', 'Password Changes successfully.');
            } else {
                throw new \Exception('Current password did not match');
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }


    public function addFund()
    {
        $data['basic'] = basicControl();
        $data['gateways'] = Gateway::where('status', 1)->orderBy('sort_by', 'ASC')->get();
        return view(template() . 'user.fund.add_fund', $data);
    }

    public function fund(Request $request)
    {
        $basic = basicControl();
        $userId = Auth::id();
        $funds = Deposit::with(['depositable', 'gateway'])
            ->where('user_id', $userId)
            ->orderBy('id', 'desc')
            ->latest()->paginate($basic->paginate);
        return view(template() . 'user.fund.index', compact('funds'));
    }

    public function becomeVendor(Request $request)
    {
        try {
            if (auth()->user()->restrict_for_vendor_request == 1) {
                return back()->with('error', 'You are not allowed to become a vendor.');
            }
            if ($request->confirm) {

                $oldRequest = UserKyc::where('user_id', auth()->id())->where('apply_for', 1)->latest()->first();

                if ($oldRequest && $oldRequest->status != 2) {
                    if ($oldRequest->status == 0) {
                        $text = 'Pending';
                    } elseif ($oldRequest->status == 1) {
                        $text = 'Verified';
                    } else {
                        $text = 'Unknown';
                    }

                    return back()->with('error', 'Your Previous request is ' . $text);
                }

                $kyc = Kyc::where('status', 1)->where('apply_for', 1)->firstOrFail();
                $params = $kyc->input_form;

                $reqData = $request->except('_token', '_method');

                $rules = [];
                if ($params !== null) {
                    foreach ($params as $key => $cus) {
                        $rules[$key] = [$cus->validation == 'required' ? $cus->validation : 'nullable'];
                        if ($cus->type === 'file') {
                            $rules[$key][] = 'image';
                            $rules[$key][] = 'mimes:jpeg,jpg,png';
                            $rules[$key][] = 'max:2048';
                        } elseif ($cus->type === 'text') {
                            $rules[$key][] = 'max:191';
                        } elseif ($cus->type === 'number') {
                            $rules[$key][] = 'integer';
                        } elseif ($cus->type === 'textarea') {
                            $rules[$key][] = 'min:3';
                            $rules[$key][] = 'max:300';
                        }
                    }
                }


                $params = $kyc->input_form;
                $validator = Validator::make($reqData, $rules);
                if ($validator->fails()) {
                    $validator->errors()->add('kyc', 'Your unique error message for the kyc field');
                    return back()->withErrors($validator)->withInput();
                }

                $reqField = [];
                foreach ($request->except('_token', '_method', 'type') as $k => $v) {
                    foreach ($params as $inKey => $inVal) {
                        if ($k == $inKey) {
                            if ($inVal->type == 'file' && $request->hasFile($inKey)) {
                                try {
                                    $file = $this->fileUpload($request[$inKey], config('filelocation.kyc.path'));
                                    $reqField[$inKey] = [
                                        'field_name' => $inVal->field_name,
                                        'field_label' => $inVal->field_label,
                                        'field_value' => $file['path'],
                                        'field_driver' => $file['driver'],
                                        'validation' => $inVal->validation,
                                        'type' => $inVal->type,
                                    ];
                                } catch (\Exception $exp) {
                                    session()->flash('error', 'Could not upload your ' . $inKey);
                                    return back()->withInput();
                                }
                            } else {
                                $reqField[$inKey] = [
                                    'field_name' => $inVal->field_name,
                                    'field_label' => $inVal->field_label,
                                    'validation' => $inVal->validation,
                                    'field_value' => $v,
                                    'type' => $inVal->type,
                                ];
                            }
                        }
                    }
                }

                UserKyc::create([
                    'user_id' => auth()->id(),
                    'kyc_id' => $kyc->id,
                    'kyc_type' => $kyc->name,
                    'kyc_info' => $reqField,
                    'apply_for' => 1
                ]);

                $userKyc = new UserKyc();
                $userKyc->user_id = auth()->id();
                $userKyc->kyc_id = $kyc->id;
                $userKyc->kyc_type = $kyc->name;
                $userKyc->kyc_info = $reqField;
                $userKyc->apply_for = 1;

                if ($kyc->is_automatic == 1) {
                    $userKyc->status = 1;
                    $userKyc->approved_at = now();
                }
                $userKyc->save();

                if ($userKyc->status == 1) {
                    $vendorInfo = new VendorInfo();
                    $vendorInfo->vendor_id = $userKyc->user?->id;
                    $vendorInfo->save();

                    $userKyc->user->role = 1;
                    $userKyc->user->as_a_vendor_from = now();
                    $userKyc->user->save();

                    dispatch(new UserOwnGatewayJob($userKyc->user));

                    $message = 'Approved Successfully';
                    $this->userSendMailNotify($userKyc->user, 'approve');

                    return back()->with('success', $message);
                }

                return back()->with('success', 'Vendor Request Sent Successfully');
            } else {
                return back()->with('error', 'Please confirm as you are ready to join.');
            }
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function userSendMailNotify($user, $type)
    {
        if ($type == 'approve') {
            $templateKey = 'KYC_APPROVED';
        } else {
            $templateKey = 'KYC_REJECTED';
        }
        $action = [
            "link" => "#",
            "icon" => "fa-light fa-address-book"
        ];
        $this->sendMailSms($user, $templateKey);
        $this->userPushNotification($user, $templateKey, $action);
        $this->userFirebasePushNotification($user, $templateKey);
        return 0;
    }

    public function paymentLog(Request $request)
    {
        $userId = Auth::id();

        $today = Carbon::today()->toDateString();
        $thisWeekStart = Carbon::now()->startOfWeek()->toDateString();
        $thisMonthStart = Carbon::now()->startOfMonth()->toDateString();
        $thisYearStart = Carbon::now()->startOfYear()->toDateString();

        $data['stats'] = DB::table('deposits')
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN DATE(created_at) = ? THEN 1 ELSE 0 END) as today,
                SUM(CASE WHEN DATE(created_at) >= ? THEN 1 ELSE 0 END) as this_week,
                SUM(CASE WHEN DATE(created_at) >= ? THEN 1 ELSE 0 END) as this_month,
                SUM(CASE WHEN DATE(created_at) >= ? THEN 1 ELSE 0 END) as this_year
            ", [$today, $thisWeekStart, $thisMonthStart, $thisYearStart])
            ->where(function ($query) {
                $query->where('user_id', auth()->id())
                    ->orWhere('vendor_id', auth()->id());
            })
            ->whereIn('status', [1, 2, 3])
            ->first();

        $total = $data['stats']->total ?: 1;

        $data['percentages'] = [
            'today' => round(($data['stats']->today / $total) * 100, 2),
            'this_week' => round(($data['stats']->this_week / $total) * 100, 2),
            'this_month' => round(($data['stats']->this_month / $total) * 100, 2),
            'this_year' => round(($data['stats']->this_year / $total) * 100, 2),
        ];

        return view(template() . 'user.fund.index', $data);
    }

    public function paymentLogSearch(Request $request)
    {

        $basicControl = basicControl();
        $search = $request->search['value'];

        $filterTransactionId = $request->filterTransactionID;
        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;

        $transaction = Deposit::with(['gateway:id,name,image,driver'])
            ->where(function ($query) {
                $query->where('user_id', auth()->id())
                    ->orWhere('vendor_id', auth()->id());
            })
            ->whereIn('status', [1, 2, 3])
            ->when(!empty($search), function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('trx_id', 'LIKE', "%{$search}%")
                        ->orWhereHas('gateway', function ($query) use ($search) {
                            $query->where('name', 'LIKE', "%{$search}%");
                        });
                });
            })
            ->when(!empty($request->filterDate) && $endDate == null, function ($query) use ($startDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $query->whereDate('created_at', $startDate);
            })
            ->when(!empty($request->filterDate) && $endDate != null, function ($query) use ($startDate, $endDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $endDate = Carbon::createFromFormat('d/m/Y', trim($endDate));
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->when(!empty($filterTransactionId), function ($query) use ($filterTransactionId) {
                return $query->where('trx_id', $filterTransactionId);
            })
            ->orderBy('id', 'DESC')
            ->get();


        return DataTables::of($transaction)
            ->addColumn('serial', function () {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('method', function ($item) {
                $received = ($item->user_id != auth()->id()) ? '(Received)' : '';
                return '<a class="d-flex align-items-center me-2" href="#">
                            <div class="flex-shrink-0">
                                <div class="avatar avatar-sm avatar-circle">
                                    <img class="avatar-img" src="' . getFile($item->gatewayable?->driver, $item->gatewayable?->image) . '" alt="' . $item->gatewayable?->name . '">
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                              <h5 class="text-hover-primary mb-0">' . $item->gatewayable?->name . $received . '</h5>
                            </div>
                          </a>';

            })
            ->addColumn('amount', function ($item) {
                return currencyPosition($item->payable_amount_in_base_currency);
            })
            ->addColumn('trx_id', function ($item) {
                return $item->trx_id;
            })
            ->addColumn('date', function ($item) {
                return dateTime($item->created_at);
            })
            ->addColumn('status', function ($item) {
                if ($item->status == 1) {
                    return '<span class="badge bg-soft-success text-success">
                        <span class="legend-indicator bg-success"></span>' . trans('Success') . '
                    </span>';
                } elseif ($item->status == 2) {
                    return '<span class="badge bg-soft-info text-info">
                        <span class="legend-indicator bg-info"></span>' . trans('Requested') . '
                    </span>';
                } elseif ($item->status == 3) {
                    return '<span class="badge bg-soft-danger text-danger">
                        <span class="legend-indicator bg-danger"></span>' . trans('Rejected') . '
                    </span>';
                }
            })
            ->addColumn('action', function ($item) {
                $details = null;
                if ($item->information) {
                    $details = [];
                    foreach ($item->information as $k => $v) {
                        $details[kebab2Title($k)] = [
                            'type' => $v->type,
                            'field_name' => $v->field_name,
                            'field_value' => $v->type == "file"
                                ? getFile(config('filesystems.default'), $v->field_value)
                                : @$v->field_value ?? $v->field_name,
                        ];
                    }
                }

                if ($item->gatewayable->id > 999) {
                    return '<div class="btn-group" role="group">
                        <a class="btn btn-white bookingView btn-sm" href="javascript:void(0)"
                           data-details_info=\'' . json_encode($details) . '\'
                           data-feedback="' . e($item->note) . '"
                           data-bs-toggle="modal"
                           data-bs-target="#detailModal"
                           data-bs-original-title="Booking Details">
                            <i class="bi bi-eye dropdown-item-icon"></i> ' . trans("View") . '
                        </a>
                    </div>';
                } else {
                    return '<div class="btn-group d-flex justify-content-center align-items-center" role="group">
                        -
                    </div>';
                }
            })
            ->rawColumns(['amount', 'action', 'serial', 'method', 'date', 'status', 'booking_id', 'trx_id'])
            ->make(true);
    }

    public function kycSettings()
    {
        $data['languages'] = Language::where('status', 1)->get();
        $data['countries'] = Country::where('status', 1)->get();
        $data['kyc'] = Kyc::where('status', 1)->where('apply_for', 0)->get();

        $userId = auth()->id();
        $startOfYear = Carbon::now()->startOfYear();

        $result = DB::table('user_kycs')
            ->where('user_id', $userId)
            ->selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as pending_count,
            SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as verified_count,
            SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as rejected_count,
            SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as this_year_count
        ', [$startOfYear->toDateTimeString()])
            ->first();

        $total = $result->total;
        $percent = fn($count) => $total > 0 ? round(($count / $total) * 100, 2) : 0;

        $data['count'] = [
            'total' => $total,
            'pending_count' => $result->pending_count,
            'verified_count' => $result->verified_count,
            'rejected_count' => $result->rejected_count,
            'this_year_count' => $result->this_year_count,
            'pending_percent' => $percent($result->pending_count),
            'verified_percent' => $percent($result->verified_count),
            'rejected_percent' => $percent($result->rejected_count),
            'this_year_percent' => $percent($result->this_year_count),
        ];

        $data['userKyc'] = UserKyc::where('user_id', $userId)->get();

        return view(template() . 'user.profile.partials.kyc_settings', $data);
    }

    public function notificationSettings()
    {
        $data['languages'] = Language::all();
        return view(template() . 'user.profile.partials.notification_settings', $data);
    }

    public function changePassword()
    {
        return view(template() . 'user.profile.partials.password');
    }

    public function bookings(Request $request)
    {
        $now = Carbon::now();

        $thisMonthBookings = Booking::whereHas('package', function ($query) {
            $query->where('owner_id', auth()->id());
        })
            ->selectRaw('DAY(created_at) as day, COUNT(*) as booking_count')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->where('status', '!=', 0)
            ->where('status', '!=', 3)
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('booking_count', 'day');

        $lastMonthDate = $now->copy()->subMonth();
        $lastMonthBookings = Booking::whereHas('package', function ($query) {
            $query->where('owner_id', auth()->id());
        })
            ->selectRaw('DAY(created_at) as day, COUNT(*) as booking_count')
            ->whereMonth('created_at', $lastMonthDate->month)
            ->whereYear('created_at', $lastMonthDate->year)
            ->where('status', '!=', 0)
            ->where('status', '!=', 3)
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('booking_count', 'day');

        $daysInThisMonth = $now->daysInMonth;
        $daysInLastMonth = $lastMonthDate->daysInMonth;

        $thisMonth = [];
        $lastMonth = [];

        $thisMonthTotal = 0;
        $lastMonthTotal = 0;

        for ($i = 1; $i <= $daysInThisMonth; $i++) {
            $count = $thisMonthBookings->get($i, 0);
            $thisMonth[] = [
                'day' => $i,
                'booking_count' => $count
            ];
            $thisMonthTotal += $count;
        }

        for ($i = 1; $i <= $daysInLastMonth; $i++) {
            $count = $lastMonthBookings->get($i, 0);
            $lastMonth[] = [
                'day' => $i,
                'booking_count' => $count
            ];
            $lastMonthTotal += $count;
        }

        $growthPercentage = 0;
        if ($lastMonthTotal > 0) {
            $growthPercentage = (($thisMonthTotal - $lastMonthTotal) / $lastMonthTotal) * 100;
        } elseif ($lastMonthTotal == 0 && $thisMonthTotal > 0) {
            $growthPercentage = ($thisMonthTotal * 100) . '%';
        } else {
            $growthPercentage = 0;
        }

        return response()->json([
            'thisMonth' => $thisMonth,
            'lastMonth' => $lastMonth,
            'thisMonthTotal' => $thisMonthTotal,
            'lastMonthTotal' => $lastMonthTotal,
            'growthPercentage' => $growthPercentage
        ]);
    }

    public function packages(Request $request)
    {
        $dataset = $request->query('dataset', 'totalView');
        $column = $dataset == 'totalView' ? 'view_count' : 'total_sell';
        $endPart = $dataset == 'totalView' ? 'Views' : 'Sells';

        $popularPackages = Package::select('id', 'owner_id', 'title', $column)
            ->where('owner_id', auth()->id())
            ->orderByDesc($column)
            ->take(10)
            ->get();

        $totalCount = Package::where('owner_id', auth()->id())
            ->sum($column);

        return response()->json([
            'popularPackages' => $popularPackages,
            'totalCount' => $totalCount . ' ' . $endPart,
        ]);
    }

    public function bookingCalender(Request $request)
    {
        $vendor_id = $request->input('vendor_id');
        $month = $request->input('month');

        $bookings = Booking::selectRaw('DATE(date) as date, COUNT(*) as booking_count, SUM(total_person) as total_person_date')
            ->whereHas('package', function ($query) use ($vendor_id) {
                $query->where('owner_id', $vendor_id);
            })
            ->where('status', '!=', 0)
            ->where('status', '!=', 3)
            ->whereRaw('DATE_FORMAT(date, "%Y-%m") = ?', [$month])
            ->groupBy('date')
            ->get();
        return response()->json($bookings);
    }

    public function deleteAccount(Request $request)
    {

        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You need to be logged in to delete your account.');
        }

        if ($request->has('deleteConfirm') && $request->deleteConfirm == 1) {
            auth()->user()->delete();

            return redirect()->route('page', '/')->with('success', 'Your account has been deleted. After 15 days, it cannot be recovered.');
        }

        return back()->with('error', 'Deletion not confirmed. Please confirm to delete your account.');
    }

}
