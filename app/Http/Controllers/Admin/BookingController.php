<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Destination;
use App\Models\DestinationVisitor;
use App\Models\PackageVisitor;
use App\Models\User;
use App\Traits\Notify;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    use Upload, Notify;

    public function all_booking(Request $request, $status = null)
    {
        try {
            $query = DB::table('bookings')
                ->selectRaw('
                    COUNT(CASE WHEN status IN (1, 2, 4) THEN 1 ELSE NULL END) as totalBooking,
                    SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as totalAcceptedBooking,
                    SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as totalPendingBooking,
                    SUM(CASE WHEN status = 4 THEN 1 ELSE 0 END) as totalRefundedBooking,
                    SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as createdToday
                ', [now()->startOfDay()])
                ->first();

            $data['totalBooking'] = $query->totalBooking;
            $data['totalAcceptedBooking'] = $query->totalAcceptedBooking ?? 0;
            $data['totalPendingBooking'] = $query->totalPendingBooking ?? 0;
            $data['totalRefundedBooking'] = $query->totalRefundedBooking ?? 0;
            $data['totalCreatedToday'] = $query->createdToday ?? 0;

            $data['totalAcceptedPercentage'] = ($data['totalBooking'] > 0) ? ($data['totalAcceptedBooking'] / $data['totalBooking']) * 100 : 0;
            $data['totalPendingPercentage'] = ($data['totalBooking'] > 0) ? ($data['totalPendingBooking'] / $data['totalBooking']) * 100 : 0;
            $data['totalRefundedBookingPercentage'] = ($data['totalBooking'] > 0 && $data['totalRefundedBooking'] > 0) ? ($data['totalRefundedBooking'] / $data['totalBooking']) * 100 : 0;
            $data['totalCreatedTodayPercentage'] = ($data['totalBooking'] > 0 && $data['totalCreatedToday'] > 0) ? ($data['totalCreatedToday'] / $data['totalBooking']) * 100 : 0;

            $data['packageId'] = $request->package;
            $data['status'] = $status;

            return view('admin.booking.all', $data);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

    }

    public function all_booking_search(Request $request)
    {
        $search = $request->search['value'];
        $filterStatus = $request->filterStatus;
        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;
        $filterName = $request->filterName;
        $packageId = $request->packageId;
        $status = $request->status;
        $filterPackageName = $request->filterPackageName;
        $filterBookingId = $request->filterBookingId;

        $Bookings = Booking::query()->with(['user', 'depositable.gateway:id,name,image,driver', 'package:id,thumb,thumb_driver'])
            ->orderBy('id', 'DESC')
            ->where('status', '!=', 0)
            ->when(!empty($search), function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('package_title', 'LIKE', "%{$search}%")
                        ->orWhere('total_price', 'LIKE', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('firstname', 'LIKE', "%{$search}%")
                                ->orWhere('lastname', 'LIKE', "%{$search}%");
                        });
                });
            })
            ->when(!empty($filterPackageName), function ($query) use ($filterPackageName) {
                $query->where('package_title', 'LIKE', "%{$filterPackageName}%");
            })
            ->when(!empty($filterName), function ($query) use ($filterName) {
                $query->whereHas('user', function ($q) use ($filterName) {
                    $q->where('firstname', 'LIKE', "%{$filterName}%")
                        ->orWhere('lastname', 'LIKE', "%{$filterName}%");
                });
            })
            ->when(!empty($filterBookingId), function ($query) use ($filterBookingId) {
                $query->where('trx_id', 'LIKE', "%{$filterBookingId}%");
            })
            ->when($status == 'all', function ($query) {
                return $query->whereIn('status', [1, 2, 4, 5]);
            })
            ->when($status == 'pending', function ($query) {
                return $query->where('status', 1)->where('date', '>', now());
            })
            ->when($status == 'completed', function ($query) {
                return $query->where('status', 2);
            })
            ->when($status == 'refunded', function ($query) {
                return $query->where('status', 4)->where('date', '>', now());
            })
            ->when($status == 'expired', function ($query) {
                return $query->where('status', 1)->where('date', '<', now());
            })
            ->when(isset($filterStatus), function ($query) use ($filterStatus) {
                if ($filterStatus == 'all') {
                    return $query->where('status', '!=', null);
                }
                if ($filterStatus == '5') {
                    return $query->where('date', '<', now());
                }
                return $query->where('status', $filterStatus)->where('date', '>', now());
            })
            ->when(isset($packageId) && !empty($packageId), function ($query) use ($packageId) {
                return $query->where('package_id', $packageId);
            })
            ->when(!empty($request->filterDate) && $endDate != null, function ($query) use ($startDate, $endDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $endDate = Carbon::createFromFormat('d/m/Y', trim($endDate));
                $query->whereBetween('created_at', [$startDate, $endDate]);
            });


        return DataTables::of($Bookings)
            ->addColumn('checkbox', function ($item) {
                return ' <input type="checkbox" id="chk-' . $item->id . '"
                                       class="form-check-input row-tic tic-check" name="check" value="' . $item->id . '"
                                       data-id="' . $item->id . '">';
            })

            ->addColumn('booking-id', function ($item) {
                return $item->trx_id;
            })
            ->addColumn('date', function ($item) {
                return dateTime($item->date) ?? 'N/A';
            })
            ->addColumn('price', function ($item) {
                return currencyPosition($item->total_price) ?? 'N/A';

            })
            ->addColumn('package', function ($item) {
                $image = optional($item->package)->thumb;
                if (!$image) {
                    $firstLetter = substr($item->package_title, 0, 1);
                    return '<div class="avatar avatar-sm avatar-soft-primary avatar-circle d-flex justify-content-start gap-2 w-100">
                                <span class="avatar-initials">' . $firstLetter . '</span>
                                <p class="avatar-initials ms-3">' . $item->title . '</p>
                            </div>';

                } else {
                    $url = getFile(optional($item->package)->thumb_driver, optional($item->package)->thumb);

                    return '<a class="d-flex align-items-center me-2" href="javascript:void(0)">
                                <div class="flex-shrink-0">
                                  <div class="avatar avatar-sm avatar-circle">
                                        <img class="avatar-img" src="' . $url . '" alt="Image Description">
                                  </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . Str::limit($item->package_title, 30) . '</h5>
                                </div>
                              </a>';

                }
            })
            ->addColumn('user', function ($item) {
                $url = route('admin.user.view.profile', $item->user->id);
                return '<a class="d-flex align-items-center me-2" href="' . $url . '">
                                <div class="flex-shrink-0">
                                  ' . $item->user->profilePicture() . '
                                </div>
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . $item->user?->firstname . ' ' . $item->user?->lastname . '</h5>
                                  <span class="fs-6 text-body">@' . $item->user?->username . '</span>
                                </div>
                              </a>';

            })
            ->addColumn('status', function ($item) {

                if ($item->status == 4) {
                    return '<span class="badge bg-soft-secondary text-secondary">
                    <span class="legend-indicator bg-secondary"></span>' . trans('Refunded') . '
                  </span>';
                }elseif ($item->status == 2) {
                    return '<span class="badge bg-soft-success text-success">
                    <span class="legend-indicator bg-success"></span>' . trans('Completed') . '
                  </span>';
                } elseif ($item->status == 1 && strtotime($item->date) > strtotime(date('Y-m-d'))) {
                    return '<span class="badge bg-soft-info text-info">
                    <span class="legend-indicator bg-info"></span>' . trans('Tour Pending') . '
                  </span>';
                } elseif ($item->status == 5) {
                    return '<span class="badge bg-soft-warning text-warning">
                    <span class="legend-indicator bg-warning"></span>' . trans('Approval Needed') . '
                  </span>';
                } elseif ($item->date < now()) {
                    return '<span class="badge bg-soft-danger text-danger">
                        <span class="legend-indicator bg-danger"></span>' . trans('Expired') . '
                    </span>';
                }
            })
            ->addColumn('create-at', function ($item) {

                return dateTime($item->created_at);
            })
            ->addColumn('action', function ($item) {
                $editUrl = route('admin.booking.edit', $item->id);
                $refundUrl = route('admin.booking.refund', $item->id);
                $actionUrl = route('admin.booking.action', $item->id);
                $acceptUrl = route('admin.booking.approve', $item->id);

                $dropdownMenu = '';
                if ($item->status == 1 && $item->date > now()) {
                    $dropdownMenu = '
                    <div class="btn-group">
                        <button type="button" class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty" id="userEditDropdown" data-bs-toggle="dropdown" aria-expanded="false"></button>
                        <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="userEditDropdown">
                            <a class="dropdown-item refundBtn" href="javascript:void(0)"
                                data-route="' . $refundUrl . '"
                                data-bs-toggle="modal" data-bs-target="#refundModal">
                                <i class="bi bi-arrow-up-circle"></i>
                                ' . trans("Refund") . '
                            </a>';

                    $dropdownMenu .= '
                        <a class="dropdown-item actionBtn" href="javascript:void(0)"
                            data-action="' . $actionUrl . '"
                            data-bs-toggle="modal"
                            data-bs-target="#Confirm"
                            data-amount="' . currencyPosition($item->total_price) . '"
                            data-id="' . $item->id . '"
                            data-trx_id = " ' . $item->trx_id . ' "
                            data-paid_date = " ' . dateTime($item->created_at) . ' "
                            >
                            <i class="bi bi-check-square"></i>
                            ' . trans("Completed") . '
                        </a>';
                }
                if ($item->status == 5 && $item->date > now()) {
                    $dropdownMenu = '
                    <div class="btn-group">
                        <button type="button" class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty" id="userEditDropdown" data-bs-toggle="dropdown" aria-expanded="false"></button>
                        <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="userEditDropdown">
                            <a class="dropdown-item approveButton" href="javascript:void(0)"
                                data-route="' . $acceptUrl . '"
                                data-amount="' . currencyPosition($item->total_price) . '"
                                data-id="' . $item->id . '"
                                data-trx_id = " ' . $item->trx_id . ' "
                                data-paid_date = " ' . dateTime($item->created_at) . ' "
                                data-bs-toggle="modal"
                                data-bs-target="#approveModal"
                                >
                                <i class="bi bi-arrow-up-circle"></i>
                                ' . trans("Approve") . '
                            </a>';
                }

                $dropdownMenu .= '</div>
                    </div>';

                return '
                <div class="btn-group" role="group">
                    <a href="' . $editUrl . '" class="btn btn-white btn-sm edit_user_btn">
                        <i class="bi-eye me-1"></i> ' . trans("View") . '
                    </a>
                    ' . $dropdownMenu . '
                </div>';
            })
            ->rawColumns(['action', 'checkbox', 'create-at', 'user', 'booking-id', 'package', 'status', 'date', 'price'])
            ->make(true);
    }

    public function complete(Request $request, $id)
    {
        try {
            $booking = Booking::where('id', $id)->firstOr(function () {
                throw new \Exception('This Booking is not available now');
            });
            $user = User::where('id', $booking->user_id)->firstOr(function () {
                throw new \Exception('This User is not available now');
            });

            if ($request->status == 2) {
                $booking->status = 2;
                $booking->save();

                $msg = [
                    'package_title' => $booking->package_title,
                    'status' => 'Completed',
                ];

                $action = [
                    "link" => route('user.booking.list'),
                    "icon" => "fa fa-money-bill-alt text-white"
                ];
                $this->userFirebasePushNotification($user, 'TOUR_COMPLETED', $msg);
                $this->userPushNotification($user, 'TOUR_COMPLETED', $msg, $action);
                $this->sendMailSms($user, 'TOUR_COMPLETED', $msg);
            }

            return back()->with('success', 'Booking has been completed.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    public function approve(Request $request, $id)
    {
        try {
            $booking = Booking::with(['user'])->where('id', $id)->firstOr(function () {
                throw new \Exception('This Booking is not available now');
            });

            if ($request->status == 2) {
                $booking->status = 1;
                $booking->save();

                $msg = [
                    'package_title' => $booking->package_title,
                    'status' => 'Completed',
                ];

                $action = [
                    "link" => route('user.booking.list'),
                    "icon" => "fa fa-money-bill-alt text-white"
                ];
                $this->userFirebasePushNotification($booking->user, 'BOOKING_ACCEPTED', $msg);
                $this->userPushNotification($booking->user, 'BOOKING_ACCEPTED', $msg, $action);
                $this->sendMailSms($booking->user, 'BOOKING_ACCEPTED', $msg);
            }

            return back()->with('success', 'Booking has been accepted.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

    }

    public function totalBooking()
    {
        $currentMonth = now()->format('Y-m');

        $propertyBooking = Booking::select(
            DB::raw('DAY(created_at) as day'),
            DB::raw('COUNT(*) as total_sales'),
            DB::raw('SUM(total_price) as total_amount')
        )
            ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = '$currentMonth'")
            ->groupBy('day')
            ->orderBy('day', 'asc')
            ->get();

        $data['labels'] = [];
        $data['TotalUnit'] = [];
        $data['totalPrice'] = [];

        $lastDayOfMonth = now()->endOfMonth()->format('d');

        for ($day = 1; $day <= $lastDayOfMonth; $day++) {
            $found = $propertyBooking->firstWhere('day', $day);

            if ($found) {
                $data['labels'][] = 'Day ' . $day;
                $data['TotalUnit'][] = $found->total_sales;
                $data['totalPrice'][] = $found->total_amount;
            } else {
                $data['labels'][] = 'Day ' . $day;
                $data['TotalUnit'][] = 0;
                $data['totalPrice'][] = 0;
            }
        }

        return response()->json([
            'labels' => $data['labels'],
            'Unit' => $data['TotalUnit'],
            'Price' => $data['totalPrice'],
        ]);
    }

    private function getTopVisitedProperties($startDate)
    {
        return PackageVisitor::with('package:id,title')
            ->select('package_id', DB::raw('COUNT(package_id) as visits'))
            ->where('created_at', '>=', $startDate)
            ->groupBy('package_id')
            ->orderByDesc('visits')
            ->take(10)
            ->get();
    }

    public function vendorActivity()
    {

        $vendors = User::select(['id','firstname','lastname','role','image','image_driver'])->with(['packages.booking:id,package_id,total_price'])->withCount(['packages'])->where('role', 1)->get();


        $labels = [];
        $packageCounts = [];
        $earnedAmounts = [];

        foreach ($vendors as $vendor) {
            $fullname = $vendor->firstname . ' ' . $vendor->lastname;
            $labels[] = $fullname;
            $packageCounts[] = $vendor->packages_count;

            $totalEarned = 0;
            foreach ($vendor->packages as $package) {
                if ($package->booking) {
                    $totalEarned += $package->booking->sum('total_price');
                }
            }
            $earnedAmounts[] = $totalEarned;
        }


        return response()->json([
            'labels' => $labels,
            'package_count' => $packageCounts,
            'earned_amount' => $earnedAmounts,
        ]);
    }

    public function topVisitedDestination()
    {
        $startDateCurrentMonth = now()->startOfMonth();
        $visitorDataCurrentMonth = $this->getTopVisitedDestination($startDateCurrentMonth);

        $labels = $visitorDataCurrentMonth->map(function ($item) {
            if ($item->destination) {
                return Str::limit($item->destination->title, 15);
            } else {
                return 'Unknown Destination';
            }
        })->toArray();

        return response()->json([
            'destinationLabels' => $labels,
            'currentMonthDestinationVisitsData' => $visitorDataCurrentMonth->pluck('visits')->toArray(),
        ]);
    }

    public function topDestinations(Request $request)
    {
        $result = DB::select("
        SELECT
            COUNT(*) as total_users,
            SUM(CASE WHEN role = 1 THEN 1 ELSE 0 END) as total_vendors
        FROM users
    ");

        $totalUser = $result[0]->total_users;
        $totalVendors = $result[0]->total_vendors;
        $vendorPercentage = $totalUser > 0 ? ($totalVendors / $totalUser) * 100 : 0;

        $stats = Booking::selectRaw("
            COUNT(CASE WHEN status = 5 AND date >= ? THEN 1 END) as upcoming_bookings,
            COUNT(CASE WHEN status IN (1,2,3,4,5) THEN 1 END) as all_bookings,
            COUNT(CASE WHEN status = 2 THEN 1 END) as completed_bookings
        ", [now()])->first();

        $completionRate = $stats->all_bookings > 0
            ? round(($stats->completed_bookings / $stats->all_bookings) * 100, 2)
            : 0;

        $avgBooking = $totalUser > 0
            ? round($stats->all_bookings / $totalUser, 2)
            : 0;

        $getDestination = Destination::withCount(['packages', 'bookings', 'visitor'])
            ->with(['countryTake'])
            ->get();

        $markers = $getDestination
            ->filter(fn($d) => $d->lat !== null && $d->long !== null)
            ->map(function ($destination) {
                return [
                    'name' => $destination->title . ' (' . $destination->packages_count . ' packages)',
                    'country' => $destination->countryTake ? $destination->countryTake->name : null,
                    'coords' => [(float) $destination->lat, (float) $destination->long]
                ];
            })
            ->values();

        return response()->json([
            'markers' => $markers,
            'stats' => [
                'total_users' => $totalUser,
                'vendor_percentage' => number_format($vendorPercentage, 2),
                'upcoming_bookings' => $stats->upcoming_bookings,
                'all_bookings' => $stats->all_bookings,
                'completed_bookings' => $stats->completed_bookings,
                'completion_rate' => $completionRate,
                'avg_booking_per_user' => $avgBooking,
            ],
            'destinations' => $getDestination->map(function ($d) {
                return [
                    'id' => $d->id,
                    'title' => $d->title,
                    'lat' => $d->lat,
                    'long' => $d->long,
                    'packages_count' => $d->packages_count,
                    'bookings_count' => $d->bookings_count,
                    'visitor_count' => $d->visitor_count,
                    'country_name' => $d->countryTake ? $d->countryTake->name : null,
                    'country_flag' => $d->countryTake ? getFile($d->thumb_driver, $d->thumb) : null,
                ];
            }),
        ]);
    }



    private function getTopVisitedDestination($startDate)
    {
        return DestinationVisitor::with('destination:id,title')
            ->select('destination_id', DB::raw('COUNT(destination_id) as visits'))
            ->where('created_at', '>=', $startDate)
            ->groupBy('destination_id')
            ->orderByDesc('visits')
            ->take(10)
            ->get();
    }

    public function bookingRefund($id)
    {
        try {
            $booking = Booking::select(['id', 'status', 'date', 'user_id'])
                ->with('user:id,lastname,firstname,image,image_driver,language_id')
                ->where('id', $id)
                ->firstOr(function () {
                    throw new \Exception('Booking Record not found.');
                });

            if ($booking->status == 0 || $booking->status == 4) {
                return back()->with('error', 'Booking is not refundable.');
            }

            $booking->status = 4;
            $booking->save();

            $params = [
                'package_title' => $booking->package_title,
                'user' => optional($booking->user)->firstname . ' ' . optional($booking->user)->lastname,
            ];

            $action = [
                "link" => route('user.booking.list'),
                "icon" => "fa fa-money-bill-alt text-white"
            ];
            $this->sendMailSms($booking->user, 'BOOKING_PAYMENT_REFUNDED', $params);
            $this->userPushNotification($booking->user, 'BOOKING_PAYMENT_REFUNDED', $params, $action);
            $this->userFirebasePushNotification($booking->user, 'BOOKING_PAYMENT_REFUNDED', $params);

            return back()->with('success', 'Booking Refunded Successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

    }

    public function bookingEdit($id)
    {
        try {
            $data['booking'] = Booking::with([
                'user' => function ($query) {
                    $query->select('id', 'image', 'image_driver', 'firstname', 'lastname', 'username', 'email', 'phone', 'phone_code')
                        ->withCount('booking');
                },
                'package' => function ($query) {
                    $query->select('id', 'title');
                },
                'depositable' => function ($query) {
                    $query->select('id', 'depositable_id', 'depositable_type', 'base_currency_charge', 'payable_amount_in_base_currency', 'payment_method_currency','percentage_charge','payable_amount','fixed_charge');
                }
            ])
                ->where('id', $id)->firstOr(function () {
                    throw new \Exception('Booking Record not found.');
                });

            return view('admin.booking.edit', $data);

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

    }

    public function bookingUpdate(Request $request)
    {

        $rules = [
            'name' => 'required|string',
            'total_price' => 'required',
            'bookingDate' => 'required',
            'total_adult' => 'required',
            'total_children' => 'required',
            'total_infant' => 'required',
        ];

        $request->validate($rules);

        try {
            $booking = Booking::with('user:id,firstname,lastname')->where('id', $request->booking)->firstOr(function () {
                throw new \Exception('Booking Record not found.');
            });

            if ($booking->status != 1 || $booking->date < now()){
                throw new \Exception('Booking Update Failled.');
            }

            $booking->package_title = $request->name;
            $booking->total_price = $request->total_price;
            $booking->date = $request->bookingDate;
            $booking->total_adult = $request->total_adult;
            $booking->total_children = $request->total_children;
            $booking->total_infant = $request->total_infant;
            $booking->total_person = $request->total_infant + $request->total_adult + $request->total_children;
            $booking->save();


            $params = [
                'package_title' => $booking->package_title,
                'user' => optional($booking->user)->firstname . ' ' . optional($booking->user)->lastname,
                'totalPrice' => $booking->total_price,
                'totalAdult' => $booking->total_adult,
                'totalChildren' => $booking->total_infant,
                'totalInfant' => $booking->total_infant,
            ];

            $action = [
                "link" => route('user.booking.list'),
                "icon" => "fa fa-money-bill-alt text-white"
            ];
            $this->sendMailSms($booking->user, 'BOOKING_REQUEST_UPDATED', $params);
            $this->userPushNotification($booking->user, 'BOOKING_REQUEST_UPDATED', $params, $action);
            $this->userFirebasePushNotification($booking->user, 'BOOKING_REQUEST_UPDATED', $params);

            return back()->with('success', 'Booking Updated Successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function travellerUpdate(Request $request)
    {
        $rules = [
            'booking' => 'required|integer|exists:bookings,id',
            'adult_info' => 'nullable|array',
            'adult_info.*.first_name' => 'required_with:adult_info|string|max:255',
            'adult_info.*.last_name' => 'required_with:adult_info|string|max:255',
            'adult_info.*.birth_date' => 'required_with:adult_info|date',
            'child_info' => 'nullable|array',
            'child_info.*.first_name' => 'required_with:child_info|string|max:255',
            'child_info.*.last_name' => 'required_with:child_info|string|max:255',
            'child_info.*.birth_date' => 'required_with:child_info|date',
            'infant_info' => 'nullable|array',
            'infant_info.*.first_name' => 'required_with:infant_info|string|max:255',
            'infant_info.*.last_name' => 'required_with:infant_info|string|max:255',
            'infant_info.*.birth_date' => 'required_with:infant_info|date',
        ];

        $messages = [
            'booking.required' => 'The booking ID is required.',
            'booking.integer' => 'The booking ID must be an integer.',
            'booking.exists' => 'The selected booking ID does not exist.',

            'adult_info.array' => 'The adult information must be an array.',
            'adult_info.*.first_name.required_with' => 'The first name is required.',
            'adult_info.*.first_name.string' => 'The first name must be a string.',
            'adult_info.*.first_name.max' => 'The first name cannot be longer than 255 characters.',
            'adult_info.*.last_name.required_with' => 'The last name is required.',
            'adult_info.*.last_name.string' => 'The last name must be a string.',
            'adult_info.*.last_name.max' => 'The last name cannot be longer than 255 characters.',
            'adult_info.*.birth_date.required_with' => 'The birth date is required.',
            'adult_info.*.birth_date.date' => 'The birth date must be a valid date.',

            'child_info.array' => 'The child information must be an array.',
            'child_info.*.first_name.required_with' => 'The first name is required.',
            'child_info.*.first_name.string' => 'The first name must be a string.',
            'child_info.*.first_name.max' => 'The first name cannot be longer than 255 characters.',
            'child_info.*.last_name.required_with' => 'The last name is required.',
            'child_info.*.last_name.string' => 'The last name must be a string.',
            'child_info.*.last_name.max' => 'The last name cannot be longer than 255 characters.',
            'child_info.*.birth_date.required_with' => 'The birth date is required.',
            'child_info.*.birth_date.date' => 'The birth date must be a valid date.',

            'infant_info.array' => 'The infant information must be an array.',
            'infant_info.*.first_name.required_with' => 'The first name is required.',
            'infant_info.*.first_name.string' => 'The first name must be a string.',
            'infant_info.*.first_name.max' => 'The first name cannot be longer than 255 characters.',
            'infant_info.*.last_name.required_with' => 'The last name is required.',
            'infant_info.*.last_name.string' => 'The last name must be a string.',
            'infant_info.*.last_name.max' => 'The last name cannot be longer than 255 characters.',
            'infant_info.*.birth_date.required_with' => 'The birth date is required.',
            'infant_info.*.birth_date.date' => 'The birth date must be a valid date.',
        ];

        $request->validate($rules, $messages);


        try {
            $booking = Booking::with('user:id,firstname,lastname')->where('id', $request->booking)->firstOr(function () {
                throw new \Exception('Booking Record not found.');
            });

            if ($booking->status != 1 || $booking->date < now()) {
                throw new \Exception('Booking update failed. Invalid status or expired booking date.');
            }

            if ($request->has('adult_info')) {
                $booking->adult_info = $request->adult_info;
            }
            if ($request->has('child_info')) {
                $booking->child_info = $request->child_info;
            }
            if ($request->has('infant_info')) {
                $booking->infant_info = $request->infant_info;
            }
            $booking->save();

            $params = [
                'package_title' => $booking->package_title,
                'user' => optional($booking->user)->firstname . ' ' . optional($booking->user)->lastname,
                'totalPrice' => $booking->total_price,
                'totalAdult' => $booking->total_adult,
                'totalChildren' => $booking->total_infant,
                'totalInfant' => $booking->total_infant,
            ];

            $action = [
                "link" => route('user.booking.list'),
                "icon" => "fa fa-money-bill-alt text-white"
            ];
            $this->sendMailSms($booking->user, 'BOOKING_REQUEST_UPDATED', $params);
            $this->userPushNotification($booking->user, 'BOOKING_REQUEST_UPDATED', $params, $action);
            $this->userFirebasePushNotification($booking->user, 'BOOKING_REQUEST_UPDATED', $params);

            return back()->with('success', 'Booking Updated Successfully');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    public function refundMultiple(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You do not select any Booking.');
            return response()->json(['error' => 1]);
        }
        $updatedCount = Booking::whereIn('id', $request->strIds)
            ->update(['status' => 4]);

        if ($updatedCount > 0) {
            session()->flash('success', 'Selected bookings updated successfully.');
            return response()->json(['success' => 1]);
        } else {
            session()->flash('error', 'No bookings were updated.');
            return response()->json(['error' => 1]);
        }

    }

    public function completedMultiple(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You do not select any Booking.');
            return response()->json(['error' => 1]);
        }
        $updatedCount = Booking::whereIn('id', $request->strIds)
            ->update(['status' => 2]);

        if ($updatedCount > 0) {
            session()->flash('success', 'Selected bookings updated successfully.');
            return response()->json(['success' => 1]);
        } else {
            session()->flash('error', 'No bookings were updated.');
            return response()->json(['error' => 1]);
        }

    }

    public function getBookingOverview()
    {
        $bookingCounts = Booking::selectRaw('
        sum(status = 0) as payment_pending_count,
        sum(status = 1) as paid_count,
        sum(status = 2) as completed_count,
        sum(status = 3) as rejected_count,
        sum(status = 4) as refunded_count,
        sum(status NOT IN (2, 3, 4) AND date < NOW()) as expired_count,
        count(*) as total_booking
    ')->first();

        $totalBookings = $bookingCounts->total_booking;

        $completedPercentage = $totalBookings > 0 ? ($bookingCounts->completed_count / $totalBookings) * 100 : 0;
        $pendingPercentage = $totalBookings > 0 ? ($bookingCounts->pending_count / $totalBookings) * 100 : 0;
        $refundedPercentage = $totalBookings > 0 ? ($bookingCounts->refunded_count / $totalBookings) * 100 : 0;
        $expiredPercentage = $totalBookings > 0 ? ($bookingCounts->expired_count / $totalBookings) * 100 : 0;

        return response()->json([
            'completedPercentage' => $completedPercentage,
            'pendingPercentage' => $pendingPercentage,
            'refundedPercentage' => $refundedPercentage,
            'expiredPercentage' => $expiredPercentage,
            'completedCount' => $bookingCounts->completed_count,
            'pendingCount' => $bookingCounts->pending_count,
            'refundedCount' => $bookingCounts->refunded_count,
            'expiredCount' => $bookingCounts->expired_count,
        ]);
    }

}
