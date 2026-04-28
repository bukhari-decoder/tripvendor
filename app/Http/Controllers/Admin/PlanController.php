<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gateway;
use App\Models\Plan;
use App\Models\PlanPurchase;
use App\Traits\Notify;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Stevebauman\Purify\Facades\Purify;
use Yajra\DataTables\Facades\DataTables;

class PlanController extends Controller
{
    use Upload, Notify;

    public function planList()
    {
        $currentMonthStart = now()->startOfMonth()->toDateTimeString();
        $currentMonthEnd = now()->endOfMonth()->toDateTimeString();

        $currentWeekStart = now()->startOfWeek()->toDateTimeString();
        $currentWeekEnd = now()->endOfWeek()->toDateTimeString();

        $planCounts = Plan::selectRaw('
        COUNT(*) as total,
        SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as active,
        SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as inactive,
        SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as thisMonth,
        SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as thisWeek
    ', [$currentMonthStart, $currentMonthEnd, $currentWeekStart, $currentWeekEnd])->first();

        $data['totalPlan'] = $planCounts->total ?? 0;
        $data['activePlan'] = $planCounts->active ?? 0;
        $data['inactivePlan'] = $planCounts->inactive ?? 0;
        $data['thisMonthPlan'] = $planCounts->thisMonth ?? 0;
        $data['thisWeekPlan'] = $planCounts->thisWeek ?? 0;

        $data['activePercentage'] = $data['totalPlan'] > 0
            ? ($data['activePlan'] / $data['totalPlan']) * 100
            : 0;

        $data['inactivePercentage'] = $data['totalPlan'] > 0
            ? ($data['inactivePlan'] / $data['totalPlan']) * 100
            : 0;

        $data['thisMonthPercentage'] = $data['totalPlan'] > 0
            ? ($data['thisMonthPlan'] / $data['totalPlan']) * 100
            : 0;

        $data['thisWeekPercentage'] = $data['totalPlan'] > 0
            ? ($data['thisWeekPlan'] / $data['totalPlan']) * 100
            : 0;

        return view('admin.plan.list', $data);
    }

    public function planListSearch(Request $request)
    {
        $search = $request->search['value'] ?? 0;
        $filterSearch = $request->filterSearch;
        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;
        $filterStatus = $request->filterStatus;

        $products = Plan::query()
            ->orderBy('sort_by')
            ->when(!empty($search), function ($query) use ($search) {
                return $query->where('name', 'LIKE', "%{$search}%");
            })
            ->when(isset($filterSearch) && !empty($filterSearch), function ($query) use ($filterSearch) {
                return $query->where('name', 'LIKE', "%{$filterSearch}%");
            })
            ->when(!empty($request->filterDate) && $endDate != null, function ($query) use ($startDate, $endDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $endDate = Carbon::createFromFormat('d/m/Y', trim($endDate));
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->when(isset($filterStatus), function ($query) use ($filterStatus) {
                if ($filterStatus == 'all') {
                    return $query->where('status', '!=', null);
                }elseif ($filterStatus == '1') {
                    return $query->where('status',1);
                }elseif ($filterStatus == '0') {
                    return $query->where('status',0);
                }
            });

        return DataTables::of($products)

            ->addColumn('serial', function ($item) {
                return '<i class="sortablejs-custom-handle bi-grip-horizontal list-group-icon me-2"></i>';
            })
            ->addColumn('name', function ($item) {
                return $item->name;
            })
            ->addColumn('price', function ($item) {
                return ' <span class="badge bg-soft-primary text-primary">' . currencyPosition($item->price) . '</span>';
            })
            ->addColumn('listing', function ($item) {
                return ' <span class="badge bg-soft-info text-info">' . $item->listing_allowed . '</span>';
            })
            ->addColumn('sell_count', function ($item) {
                return ' <span class="badge bg-soft-secondary text-secondary">' . $item->sell_count . '</span>';
            })
            ->addColumn('featured', function ($item) {
                return ' <span class="badge bg-soft-dark text-dark">' . $item->featured_listing . '</span>';
            })
            ->addColumn('validity', function ($item) {
                if ($item->validity_type == 'daily') {
                    $validityText = $item->validity.' Days';
                }elseif ($item->validity_type == 'weekly') {
                    $validityText = $item->validity.' Weeks';
                }elseif ($item->validity_type == 'monthly') {
                    $validityText = $item->validity.' Months';
                }elseif ($item->validity_type == 'yearly') {
                    $validityText = $item->validity.' Years';
                }else{
                    $validityText = 'Unknown';
                }

                return $validityText;
            })
            ->addColumn('status', function ($item) {
                if ($item->status == 1) {
                    return '<span class="badge bg-soft-success text-success">
                        <span class="legend-indicator bg-success"></span>' . trans('Active') . '
                    </span>';
                } else {
                    return '<span class="badge bg-soft-danger text-danger">
                        <span class="legend-indicator bg-danger"></span>' . trans('Inactive') . '
                    </span>';
                }
            })
            ->addColumn('action', function ($item) {
                $editUrl = route('admin.plan.edit', $item->id);
                $deleteUrl = route('admin.plan.delete', $item->id);
                return '<div class="btn-group" role="group">
                      <a href="' . $editUrl . '" class="btn btn-white btn-sm edit_user_btn">
                        <i class="bi-pencil-fill me-1"></i> ' . trans("Edit") . '
                      </a>
                    <div class="btn-group">
                      <button type="button" class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty" id="userEditDropdown" data-bs-toggle="dropdown" aria-expanded="false"></button>
                      <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="userEditDropdown">
                         <a class="dropdown-item deleteBtn" href="javascript:void(0)"
                           data-route="' . $deleteUrl . '"
                           data-bs-toggle="modal" data-bs-target="#delete-modal">
                            <i class="bi bi-trash dropdown-item-icon"></i>
                            ' . trans("Delete") . '
                        </a>
                      </div>
                    </div>
                  </div>';
            })->rawColumns(['action', 'serial', 'name', 'status','price', 'validity','listing','sell_count','featured'])
            ->setRowAttr([
                'data-code' => function($item) {
                    return $item->id;
                }
            ])
            ->make(true);
    }

    public function planCreate()
    {
        return view('admin.plan.create');
    }

    public function planStore(Request $request)
    {
        $request->validate([
            'name' => 'required|max:40',
            'price' => 'required|numeric',
            'listing' => 'required',
            'featured' => 'nullable',
            'validity' => 'required',
            'ai_feature' => 'required',
            'validity_type' => 'required',
            'image' => 'required|max:3072|image|mimes:jpg,jpeg,png',
        ]);

        try {
            $plan = new Plan();
            $plan->name = $request->name;
            $plan->price = $request->price;
            $plan->validity_type = $request->validity_type;
            $plan->validity = $request->validity;
            $plan->listing_allowed = $request->listing;
            $plan->featured_listing = $request->featured;
            $plan->features = $request->features;
            $plan->ai_feature = $request->ai_feature;
            $plan->status = $request->status;
            $plan->save();

            if ($request->hasFile('image')) {
                $imageData = $this->fileUpload($request->image, config('filelocation.plan.path'), null, config('filelocation.plan.size'), 'webp', '60');

                if (empty($imageData) || !isset($imageData['path'], $imageData['driver'])) {
                    throw new \Exception('Image upload failed: Missing required data.');
                }

                $plan->image = $imageData['path'];
                $plan->driver = $imageData['driver'];
                $plan->save();
            }

            return redirect()->route('admin.plan.list')->with('success', 'Plan Created Successfully');
        }catch (\Exception $e){
            return back()->with('error', $e->getMessage());
        }


    }

    public function planEdit($id)
    {
        $data['plans'] = Plan::findOrFail($id);
        $data['gateways'] = Gateway::select(['id', 'name', 'code', 'subscription_on'])->where('subscription_on', 1)->get();
        return view('admin.plan.edit', $data);
    }

    public function planUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:40',
            'price' => 'required|numeric',
            'listing' => 'required',
            'featured' => 'nullable',
            'validity' => 'required',
            'ai_feature' => 'required',
            'validity_type' => 'required',
            'image' => 'sometimes|required|max:3072|image|mimes:jpg,jpeg,png',
        ]);

        try {

            $arr = [];
            if ($request->gateway_plan_id && count($request->gateway_plan_id) > 0) {
                foreach ($request->gateway_plan_id as $key => $planId) {
                    $arr[$key] = $planId[0];
                }
            }
            $plan = Plan::findOrFail($id);

            $oldPlan = $plan;

            $plan->name = $request->name;
            $plan->price = $request->price;
            $plan->validity_type = $request->validity_type;
            $plan->validity = $request->validity;
            $plan->listing_allowed = $request->listing;
            $plan->featured_listing = $request->featured;
            $plan->features = $request->features;
            $plan->ai_feature = $request->ai_feature;
            $plan->status = $request->status;
            $plan->gateway_plan_id = $arr;
            $plan->save();

            if ($request->hasFile('image')) {
                $imageData = $this->fileUpload($request->image, config('filelocation.plan.path'), null, config('filelocation.plan.size'), 'webp', '60', $plan->image, $plan->driver);

                if (empty($imageData) || !isset($imageData['path'], $imageData['driver'])) {
                    throw new \Exception('Image upload failed: Missing required data.');
                }
                $plan->image = $imageData['path'];
                $plan->driver = $imageData['driver'];
                $plan->save();
            }


            return redirect()->back()->with('success', 'Plan Updated Successfully');
        }catch (\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }

    public function planDelete($id)
    {
        try {
            $plan = Plan::with('planPurchase')->where('id', $id)->firstOr(function () {
                throw new \Exception('Plan not found.');
            });

            if (count($plan->planPurchase) > 0) {
                return back()->with('error', 'Many plans are purchased by this plan');
            }

            $this->fileDelete($plan->driver, $plan->image);

            $plan->delete();

            return back()->with('success', 'Plan has been deleted');
        }catch (\Exception $e){
            return back()->with('error', $e->getMessage());
        }
    }
    public function planSoldList()
    {
        $currentMonthStart = now()->startOfMonth()->toDateTimeString();
        $currentMonthEnd = now()->endOfMonth()->toDateTimeString();

        $currentWeekStart = now()->startOfWeek()->toDateTimeString();
        $currentWeekEnd = now()->endOfWeek()->toDateTimeString();

        $planPurchaseCounts = PlanPurchase::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN (expiry_date >= NOW() AND status = 1) THEN 1 ELSE 0 END) as active,
            SUM(CASE WHEN (expiry_date < NOW() OR status = 0) THEN 1 ELSE 0 END) as inactive,
            SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as thisMonth,
            SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as thisWeek
        ', [$currentMonthStart, $currentMonthEnd, $currentWeekStart, $currentWeekEnd])->first();

        $data['totalPlanPurchases'] = $planPurchaseCounts->total ?? 0;
        $data['activePlanPurchases'] = $planPurchaseCounts->active ?? 0;
        $data['inactivePlanPurchases'] = $planPurchaseCounts->inactive ?? 0;
        $data['thisMonthPlanPurchases'] = $planPurchaseCounts->thisMonth ?? 0;
        $data['thisWeekPlanPurchases'] = $planPurchaseCounts->thisWeek ?? 0;

        $data['activePercentage'] = $data['totalPlanPurchases'] > 0
            ? ($data['activePlanPurchases'] / $data['totalPlanPurchases']) * 100
            : 0;

        $data['inactivePercentage'] = $data['totalPlanPurchases'] > 0
            ? ($data['inactivePlanPurchases'] / $data['totalPlanPurchases']) * 100
            : 0;

        $data['thisMonthPercentage'] = $data['totalPlanPurchases'] > 0
            ? ($data['thisMonthPlanPurchases'] / $data['totalPlanPurchases']) * 100
            : 0;

        $data['thisWeekPercentage'] = $data['totalPlanPurchases'] > 0
            ? ($data['thisWeekPlanPurchases'] / $data['totalPlanPurchases']) * 100
            : 0;

        return view('admin.plan_sold.plan_list', $data);
    }


    public function searchPlanSoldList(Request $request)
    {
        $search = $request->search['value'];
        $filterStatus = $request->filterStatus;
        $filterSearch = $request->filterSearch;
        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;

        $planPurchases = PlanPurchase::query()
            ->with(['plans', 'users'])
            ->latest()
            ->when(!empty($search), function ($query) use ($search) {
                return $query->WhereHas('users', function ($q) use($search) {
                    $q->where('username', 'LIKE', "%{$search}%")
                        ->orWhere('firstname', 'LIKE', "%{$search}%")
                        ->orWhere('lastname', 'LIKE', "%{$search}%")
                        ->orWhere('phone', 'LIKE', "%{$search}%");
                })
                    ->orWhereHas('plans', function ($q) use($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    });
            })
            ->when(isset($filterSearch) && !empty($filterSearch), function ($query) use ($filterSearch) {
                return $query->WhereHas('users', function ($q) use($filterSearch) {
                    $q->where('username', 'LIKE', "%{$filterSearch}%")
                        ->orWhere('firstname', 'LIKE', "%{$filterSearch}%")
                        ->orWhere('lastname', 'LIKE', "%{$filterSearch}%")
                        ->orWhere('phone', 'LIKE', "%{$filterSearch}%");
                })
                    ->orWhereHas('plans', function ($q) use($filterSearch) {
                        $q->where('name', 'LIKE', "%{$filterSearch}%");
                    });
            })
            ->when(!empty($request->filterDate) && $endDate != null, function ($query) use ($startDate, $endDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $endDate = Carbon::createFromFormat('d/m/Y', trim($endDate));
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->when(isset($filterStatus), function ($query) use ($filterStatus) {
                if ($filterStatus == 'all') {
                    return $query;
                } elseif ($filterStatus == '1') {
                    return $query->where('status', 1)->where('expiry_date', '>=', now());
                } elseif ($filterStatus == '0') {
                    return $query->where(function ($q) {
                        $q->where('status', 0)
                            ->orWhere('expiry_date', '<', now());
                    });
                }
            });

        return DataTables::of($planPurchases)

            ->addColumn('serial', function ($item) {
                static $serial = 1;
                return $serial++;
            })
            ->addColumn('name', function ($item) {
                $image = optional($item->plans)->image;
                if (!$image) {
                    $firstLetter = substr(optional($item->plans)->name, 0, 1);
                    return '<div class="avatar avatar-sm avatar-soft-primary avatar-circle">
                                <span class="avatar-initials">' . $firstLetter . '</span>
                                <span class="fs-6 text-body">' . optional($item->plans)->name . '</span>
                            </div>';
                } else {
                    $url = getFile(optional($item->plans)->driver, optional($item->plans)->image);
                    return '<div class="avatar avatar-sm avatar-circle">
                                <img class="avatar-img" src="' . $url . '" alt="Service Thumb Image" />
                                <span class="fs-6 text-body">' . optional($item->plans)->name . '</span>
                            </div>
                            ';

                }
            })
            ->addColumn('date', function ($item) {
                return dateTime($item->created_at);
            })
            ->addColumn('status', function ($item) {
                $isExpired = \Carbon\Carbon::parse($item->expiry_date)->lt(now());
                $isInactive = ($item->status == 0) || $isExpired;

                if (!$isInactive) {
                    return '<span class="badge bg-soft-success text-success">
                        <span class="legend-indicator bg-success"></span>' . trans('Active') . '
                    </span>';
                } else {
                    return '<span class="badge bg-soft-danger text-danger">
                        <span class="legend-indicator bg-danger"></span>' . trans('Inactive') . '
                    </span>';
                }
            })
            ->addColumn('user', function ($item) {
                $url = route('admin.user.view.profile', optional($item->users)->id);
                return '<a class="d-flex align-items-center me-2" href="' . $url . '">
                            <div class="flex-shrink-0">
                              ' . optional($item->users)->profilePicture() . '
                            </div>
                            <div class="flex-grow-1 ms-3">
                              <h5 class="text-hover-primary mb-0">' . optional($item->users)->firstname . ' ' . optional($item->users)->lastname . '</h5>
                              <span class="fs-6 text-body">@' . optional($item->users)->username . '</span>
                            </div>
                          </a>';

            })->rawColumns(['user', 'serial', 'name','status', 'date'])
            ->make(true);
    }

    public function sortPlan(Request $request)
    {
        $sortItems = $request->sort;
        foreach ($sortItems as $key => $value) {
            Plan::where('id', $value)->update(['sort_by' => $key + 1]);
        }
    }
}
