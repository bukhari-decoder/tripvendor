<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\Notify;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class BookingController extends Controller
{
    use Notify;
    public function bookingList(Request $request)
    {
        $userId = auth()->id();
        $now = Carbon::now()->toDateTimeString();

        $stats = DB::table('bookings')
            ->selectRaw("
                COUNT(*) as total_count,
                COUNT(CASE WHEN status = 2 THEN 1 END) as completed_count,
                COUNT(CASE WHEN status = 4 THEN 1 END) as refunded_count,
                COUNT(CASE WHEN status = 5 THEN 1 END) as pending_count,
                COUNT(CASE WHEN date < ? AND status NOT IN (2, 4) THEN 1 END) as expired_count
            ", [$now])
            ->where('user_id', $userId)
            ->first();

        if ($stats->total_count > 0) {
            $data['completedPercentage'] = ($stats->completed_count / $stats->total_count) * 100;
            $data['refundedPercentage'] = ($stats->refunded_count / $stats->total_count) * 100;
            $data['pendingPercentage'] = ($stats->pending_count / $stats->total_count) * 100;
            $data['expiredPercentage'] = ($stats->expired_count / $stats->total_count) * 100;
        } else {
            $data['completedPercentage'] = $data['refundedPercentage'] = $data['pendingPercentage'] = $data['expiredPercentage'] = 0;
        }
        $data['count']= $stats;

        return view(template() . 'user.tourBooking.list', $data);
    }

    public function bookingListSearch(Request $request)
    {

        $basicControl = basicControl();
        $search = $request->search['value'];

        $filterTransactionId = $request->filterTransactionID;
        $filterPackageTitle = $request->filterPackageTitle;
        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;

        $transaction = Booking::with(['package:id,title,thumb,thumb_driver,slug'])
            ->where('user_id', auth()->id())
            ->whereNotIn('status', [0])
            ->when(!empty($search), function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('trx_id', 'LIKE', "%{$search}%")
                        ->orWhereHas('package', function ($query) use ($search) {
                            $query->where('title', 'LIKE', "%{$search}%");
                        });
                });
            })
            ->when(!empty($filterPackageTitle), function ($query) use ($filterPackageTitle) {
                $query->WhereHas('package', function ($query) use ($filterPackageTitle) {
                    $query->where('title', 'LIKE', "%{$filterPackageTitle}%");
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
            ->addColumn('booking_id', function ($item) {
                return ' <span class="badge bg-soft-primary text-primary">' . $item->trx_id . '</span>';
            })
            ->addColumn('package', function ($item) {
                $url = route('package.details', $item->package?->slug);
                return '<a class="d-flex align-items-center me-2" href="' . $url . '">
                            <div class="flex-shrink-0">
                                <div class="avatar avatar-sm avatar-circle">
                                    <img class="avatar-img" src="'. getFile($item->package?->thumb_driver, $item->package?->thumb) .'" alt="'. $item->package?->title .'">
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                              <h5 class="text-hover-primary mb-0">'. $item->package?->title .'</h5>
                            </div>
                          </a>';

            })
            ->addColumn('amount', function ($item) {
                return ' <span class="badge bg-soft-info text-info">' . currencyPosition($item->total_price) . '</span>';
            })
            ->addColumn('person', function ($item) {
                return ' <span class="badge bg-soft-secondary text-secondary">' . $item->total_person . '</span>';
            })
            ->addColumn('date-time', function ($item) {
                return dateTime($item->date);
            })
            ->addColumn('status', function ($item) {
                if ($item->status == 1 && $item->date > now()) {
                    return '<span class="badge bg-soft-secondary text-secondary">
                        <span class="legend-indicator bg-secondary"></span>' . trans('Tour Pending') . '
                    </span>';
                } elseif ($item->status == 2) {
                    return '<span class="badge bg-soft-success text-success">
                        <span class="legend-indicator bg-success"></span>' . trans('Completed') . '
                    </span>';
                } elseif ($item->status == 4) {
                    return '<span class="badge bg-soft-info text-info">
                        <span class="legend-indicator bg-info"></span>' . trans('Refunded') . '
                    </span>';
                } elseif ($item->status == 5 && $item->date >= now()) {
                    return '<span class="badge bg-soft-warning text-warning">
                        <span class="legend-indicator bg-warning"></span>' . trans('Pending') . '
                    </span>';
                } elseif ($item->status == 5 && $item->date <= now()) {
                    return '<span class="badge bg-soft-danger text-danger">
                        <span class="legend-indicator bg-danger"></span>' . trans('Expired') . '
                    </span>';
                }elseif($item->status == 3) {
                    return '<span class="badge bg-soft-danger text-danger">
                        <span class="legend-indicator bg-danger"></span>' . trans('Rejected') . '
                    </span>';
                } else {
                    return '<span class="badge bg-soft-danger text-danger">
                        <span class="legend-indicator bg-danger"></span>' . trans('Expired') . '
                    </span>';
                }
            })
            ->addColumn('action', function ($item) {
                $reviewLink = '';
                if ($item->status == 2 && $item->package?->slug) {
                    $reviewLink = '
            <a class="dropdown-item" href="' . route('package.details', $item->package->slug) . '">
                <i class="bi-chat-dots dropdown-item-icon"></i> ' . trans("Give Review") . '
            </a>';
                }

                return '
        <div class="btn-group" role="group">
            <a class="btn btn-white bookingView btn-sm" href="javascript:void(0)"
               data-route="' . route('admin.user.update.balance', $item->id) . '"
               data-total_price="' . currencyPosition($item->total_price) . '"
               data-package="' . route('package.details', optional($item->package)->slug ?? '') . '"
               data-title="' . e($item->package_title) . '"
               data-date="' . dateTime($item->date) . '"
               data-start_price="' . currencyPosition($item->start_price) . '"
               data-total_adult="' . $item->total_adult . '"
               data-total_children="' . $item->total_children . '"
               data-total_infant="' . $item->total_infant . '"
               data-total_person="' . $item->total_person . '"
               data-trx_id="' . e($item->trx_id) . '"
               data-status="' . $item->status . '"
               data-bs-toggle="modal"
               data-bs-target="#viewInformation"
               data-bs-original-title="Booking Details">
                <i class="bi bi-eye dropdown-item-icon"></i> ' . trans("View") . '
            </a>
            <div class="btn-group">
                <button type="button" class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty" id="userEditDropdown" data-bs-toggle="dropdown" aria-expanded="false"></button>
                <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="userEditDropdown">'
                    . $reviewLink .
                    '</div>
            </div>
        </div>';
            })
            ->rawColumns(['amount', 'action','package','date-time','status','booking_id','person'])
            ->make(true);
    }

    public function vendorBookingList(Request $request)
    {
        $vendorId = auth()->id();
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfYear = Carbon::now()->startOfYear();

        $result = DB::table('bookings')
            ->join('packages', 'bookings.package_id', '=', 'packages.id')
            ->where('packages.owner_id', $vendorId)
            ->whereNotIn('bookings.status', [0])
            ->selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN DATE(bookings.created_at) = ? THEN 1 ELSE 0 END) as today_count,
            SUM(CASE WHEN bookings.created_at >= ? THEN 1 ELSE 0 END) as this_week_count,
            SUM(CASE WHEN bookings.created_at >= ? THEN 1 ELSE 0 END) as this_month_count,
            SUM(CASE WHEN bookings.created_at >= ? THEN 1 ELSE 0 END) as this_year_count
        ', [
                $today->toDateString(),
                $startOfWeek->toDateTimeString(),
                $startOfMonth->toDateTimeString(),
                $startOfYear->toDateTimeString(),
            ])
            ->first();
        $total = $result->total;
        $percent = fn($count) => $total > 0 ? round(($count / $total) * 100, 2) : 0;

        $data['count'] = [
            'total' => $total,
            'today_count' => $result->today_count,
            'this_week_count' => $result->this_week_count,
            'this_month_count' => $result->this_month_count,
            'this_year_count' => $result->this_year_count,
            'today_percent' => $percent($result->today_count),
            'this_week_percent' => $percent($result->this_week_count),
            'this_month_percent' => $percent($result->this_month_count),
            'this_year_percent' => $percent($result->this_year_count),
        ];
        return view(template() . 'user.packageBooking.list', $data);
    }

    public function vendorBookingListSearch(Request $request)
    {
        $basicControl = basicControl();
        $search = $request->search['value'];

        $filterTransactionId = $request->filterTransactionID;
        $filterPackageTitle = $request->filterPackageTitle;
        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;

        $filterTourDate = explode('-', $request->filterTourDate);
        $startTourDate = $filterTourDate[0];
        $endTourDate = isset($filterTourDate[1]) ? trim($filterTourDate[1]) : null;

        $booking = Booking::with(['package:id,owner_id,title,thumb,thumb_driver,slug'])
            ->whereHas('package', function ($query) {
                $query->where('owner_id', auth()->id());
            })
            ->whereNotIn('status', [0])
            ->when(!empty($search), function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('trx_id', 'LIKE', "%{$search}%")
                        ->orWhereHas('package', function ($query) use ($search) {
                            $query->where('title', 'LIKE', "%{$search}%");
                        });
                });
            })
            ->when(!empty($filterPackageTitle), function ($query) use ($filterPackageTitle) {
                $query->whereHas('package', function ($query) use ($filterPackageTitle) {
                    $query->where('title', 'LIKE', "%{$filterPackageTitle}%");
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
            ->when(!empty($request->filterTourDate) && $endTourDate != null, function ($query) use ($startTourDate, $endTourDate) {
                $startTourDate = Carbon::createFromFormat('d/m/Y', trim($startTourDate));
                $endTourDate = Carbon::createFromFormat('d/m/Y', trim($endTourDate));
                $query->whereBetween('date', [$startTourDate, $endTourDate]);
            })
            ->when(!empty($filterTransactionId), function ($query) use ($filterTransactionId) {
                return $query->where('trx_id', $filterTransactionId);
            })
            ->latest();


        return DataTables::of($booking)
            ->addColumn('booking_id', function ($item) {
                return ' <span class="badge bg-soft-primary text-primary">' . $item->trx_id . '</span>';
            })
            ->addColumn('package', function ($item) {
                $url = route('package.details', $item->package?->slug);
                return '<a class="d-flex align-items-center me-2" href="' . $url . '">
                            <div class="flex-shrink-0">
                                <div class="avatar avatar-sm avatar-circle">
                                    <img class="avatar-img" src="'. getFile($item->package?->thumb_driver, $item->package?->thumb) .'" alt="'. $item->package?->title .'">
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                              <h5 class="text-hover-primary mb-0">'. $item->package?->title .'</h5>
                            </div>
                          </a>';

            })
            ->addColumn('user', function ($item) {
                return '<a class="d-flex align-items-center me-2" href="#">
                            <div class="flex-shrink-0">
                                <div class="avatar avatar-sm avatar-circle">
                                    <img class="avatar-img" src="'. getFile($item->user?->image_driver, $item->user?->image) .'" alt="'. $item->user?->firstname.' '.$item->user?->lastname .'">
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                              <h5 class="text-hover-primary mb-0">'. $item->user?->firstname.' '.$item->user?->lastname .'</h5>
                              <p class="mb-0">Email: '.$item->user?->email.'</p>
                            </div>
                          </a>';

            })
            ->addColumn('amount', function ($item) {
                return ' <span class="badge bg-soft-info text-info">' . currencyPosition($item->total_price) . '</span>';
            })
            ->addColumn('person', function ($item) {
                return ' <span class="badge bg-soft-secondary text-secondary">' . $item->total_person . '</span>';
            })
            ->addColumn('date_time', function ($item) {
                return dateTime($item->date);
            })
            ->addColumn('status', function ($item) {
                if ($item->status == 1 && $item->date > now()) {
                    return '<span class="badge bg-soft-secondary text-secondary">
                        <span class="legend-indicator bg-secondary"></span>' . trans('Tour Pending') . '
                    </span>';
                } elseif ($item->status == 2) {
                    return '<span class="badge bg-soft-success text-success">
                        <span class="legend-indicator bg-success"></span>' . trans('Completed') . '
                    </span>';
                } elseif ($item->status == 4) {
                    return '<span class="badge bg-soft-info text-info">
                        <span class="legend-indicator bg-info"></span>' . trans('Refunded') . '
                    </span>';
                } elseif ($item->status == 5) {
                    return '<span class="badge bg-soft-warning text-warning">
                        <span class="legend-indicator bg-warning"></span>' . trans('Pending') . '
                    </span>';
                } elseif ($item->status == 1 && $item->date <= now()) {
                    return '<span class="badge bg-soft-danger text-danger">
                        <span class="legend-indicator bg-danger"></span>' . trans('Expired') . '
                    </span>';
                } elseif($item->status == 3) {
                    return '<span class="badge bg-soft-danger text-danger">
                        <span class="legend-indicator bg-danger"></span>' . trans('Rejected') . '
                    </span>';
                }else {
                    return '<span class="badge bg-soft-danger text-danger">
                        <span class="legend-indicator bg-danger"></span>' . trans('Expired') . '
                    </span>';
                }
            })
            ->addColumn('action', function ($item) {
                $today = now()->startOfDay();
                $itemDate = \Carbon\Carbon::parse($item->date)->startOfDay();

                $editUrl = route('user.view.booking', $item->uid);

                if ($item->status != 0 && $itemDate->gte($today)) {
                    return '
                        <div class="btn-group" role="group">
                          <a href="' . $editUrl . '" class="btn btn-white btn-sm edit_user_btn">
                            <i class="bi-pencil-fill me-1"></i> ' . trans("view") . '
                          </a>
                          <div class="btn-group">
                            <button type="button" class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty" id="userEditDropdown" data-bs-toggle="dropdown" aria-expanded="false"></button>
                            <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="userEditDropdown">
                              <a class="dropdown-item bookingView" href="javascript:void(0)"
                                 data-route="' . route('admin.user.update.balance', $item->id) . '"
                                 data-total_price="' . currencyPosition($item->total_price) . '"
                                 data-package="' . route('package.details', optional($item->package)->slug ?? '') . '"
                                 data-title="' . e($item->package_title) . '"
                                 data-date="' . dateTime($item->date) . '"
                                 data-start_price="' . currencyPosition($item->start_price) . '"
                                 data-total_adult="' . $item->total_adult . '"
                                 data-total_children="' . $item->total_children . '"
                                 data-total_infant="' . $item->total_infant . '"
                                 data-total_person="' . $item->total_person . '"
                                 data-trx_id="' . e($item->trx_id) . '"
                                 data-status="' . $item->status . '"
                                 data-accept_url="' . route("user.accept.booking", $item->uid) . '"
                                 data-bs-toggle="modal"
                                 data-bs-target="#viewInformation"
                                 data-bs-original-title="Booking Details">
                                  <i class="bi bi-arrow-clockwise dropdown-item-icon"></i> ' . trans("Action") . '
                              </a>
                              </div>
                          </div>
                        </div>';
                }

                return '
                    <div class="btn-group" role="group">
                      <a href="' . $editUrl . '" class="btn btn-white btn-sm edit_user_btn">
                        <i class="bi-pencil-fill me-1"></i> ' . trans("view") . '
                      </a>
                    </div>';
            })
            ->rawColumns(['amount', 'action', 'user','package','date_time','status','booking_id','person'])
            ->make(true);
    }

    public function viewBooking($uid)
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
                ->where('uid', $uid)->firstOr(function () {
                    throw new \Exception('Booking Record not found.');
                });

            return view(template().'user.packageBooking.view', $data);

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

    }

    public function acceptBooking(Request $request, $uid)
    {

        try {
            $booking = Booking::with(['user','package'])->select(['id', 'user_id', 'package_id', 'uid', 'status','package_id'])->with(['package','user'])->where('uid', $uid)->where('status', 5)->whereHas('package', function ($query){
                $query->where('status', 1)->where('owner_id', auth()->id());
            })->firstOr(function () {
                throw new \Exception('Booking Record not found.');
            });

            if ($request->confirm == 1){
                $booking->status = 1;
                $booking->save();

                $status = 'Rejected';
                $this->userSendMailNotify($booking, 'accept');
            }elseif ($request->confirm == 0){
                $booking->status = 3;
                $booking->save();

                if ($booking->package->total_sell > 0) {
                    $booking->package->decrement('total_sell');
                }

                $status = 'Rejected';
                $this->userSendMailNotify($booking, 'reject');
            }else{
                return back()->with('error', 'Booking request cannot be accepted.');
            }

            return back()->with((($request->confirm == 1) ? 'success' : 'error'), 'Booking '.$status);
        }catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function userSendMailNotify($booking, $type)
    {
        if ($type == 'accept') {
            $templateKey = 'BOOKING_ACCEPTED';
        } else {
            $templateKey = 'BOOKING_REJECTED';
        }

        $params = [
            'package_title' => $booking->package?->title,
            'trx_id' => currencyPosition($booking->trx_id ),
        ];
        $action = [
            "link" => route('user.booking.list'),
            "icon" => "fa fa-money-bill-alt text-white"
        ];
        $this->sendMailSms($booking->user, $templateKey, $params);
        $this->userPushNotification($booking->user, $templateKey, $params, $action);
        $this->userFirebasePushNotification($booking->user, $templateKey, $params);
        return 0;
    }
    public function refundBooking($uid)
    {
        try {
            $booking = Booking::select(['id', 'status', 'date', 'user_id'])
                ->with('user:id,lastname,firstname,image,image_driver,language_id')
                ->where('uid', $uid)
                ->firstOr(function () {
                    throw new \Exception('Booking Record not found.');
                });

            if ($booking->status == 0 || $booking->status == 4 || $booking->status == 2) {
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

    public function completeBooking(Request $request, $uid)
    {
        try {
            $booking = Booking::where('uid', $uid)->firstOr(function () {
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
}
