<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CouponController extends Controller
{
    public function list()
    {
        $coupon = Coupon::selectRaw('
                COUNT(*) as totalCoupon,
                SUM(CASE WHEN end_date > NOW() THEN 1 ELSE 0 END) as activeCoupon,
                SUM(CASE WHEN end_date <= NOW() THEN 1 ELSE 0 END) as inactiveCoupon,
                SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as createdToday,
                SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as createdThisMonth',
            [now()->startOfDay(), now()->startOfMonth()])
            ->first();

        $data['totalCoupon'] = $coupon->totalCoupon;
        $data['totalActiveCoupon'] = $coupon->activeCoupon ?? 0;
        $data['totalInactiveCoupon'] = $coupon->inactiveCoupon ?? 0;
        $data['totalCreatedToday'] = $coupon->createdToday ?? 0;
        $data['totalCreatedThisMonth'] = $coupon->createdThisMonth ?? 0;
        $data['activeCouponPercentage'] = ($data['totalCoupon'] > 0) ? ($data['totalActiveCoupon'] / $data['totalCoupon']) * 100 : 0;
        $data['inactiveCouponPercentage'] = ($data['totalCoupon'] > 0) ? ($data['totalInactiveCoupon'] / $data['totalCoupon']) * 100 : 0;
        $data['totalCreatedTodayCouponPercentage'] = ($data['totalCreatedToday'] > 0) ? ($data['totalCreatedToday'] / $data['totalCoupon']) * 100 : 0;
        $data['totalCreatedThisMonthCouponPercentage'] = ($data['totalCreatedThisMonth'] > 0) ? ($data['totalCreatedThisMonth'] / $data['totalCoupon']) * 100 : 0;


        return view('admin.coupon.list', $data);
    }

    public function search(Request $request)
    {
        $search = $request->search['value'];

        $users = Coupon::query()->orderBy('id', 'DESC')
            ->when(!empty($search), function ($query) use ($search) {
                return $query->where('coupon_code', 'LIKE', "%{$search}%")
                    ->orWhere('discount', 'LIKE', "%{$search}%");
            });

        return DataTables::of($users)
            ->addColumn('checkbox', function ($item) {
                return ' <input type="checkbox" id="chk-' . $item->id . '"
                                       class="form-check-input row-tic tic-check" name="check" value="' . $item->id . '"
                                       data-id="' . $item->id . '">';
            })
            ->addColumn('coupon-code', function ($item) {
                return $item->coupon_code;

            })
            ->addColumn('start-date', function ($item) {
                return dateTime($item->start_date);
            })
            ->addColumn('end-date', function ($item) {
                return dateTime($item->end_date);
            })
            ->addColumn('status', function ($item) {
                if ($item->end_date < now()) {
                    return ' <span class="badge bg-soft-warning text-warning">
                                    <span class="legend-indicator bg-warning"></span> ' . trans('Expired') . '
                                </span>';
                } else {
                    return '<span class="badge bg-soft-success text-success">
                                    <span class="legend-indicator bg-success"></span> ' . trans('Active') . '
                                </span>';

                }
            })
            ->addColumn('action', function ($item) {
                $editUrl = route('admin.coupon.edit', $item->id);
                return '<div class="btn-group" role="group">
                      <a href="' . $editUrl . '" class="btn btn-white btn-sm edit_user_btn">
                        <i class="bi-pencil-fill me-1"></i> ' . trans("Edit") . '
                      </a>
                    <div class="btn-group">
                      <button type="button" class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty" id="userEditDropdown" data-bs-toggle="dropdown" aria-expanded="false"></button>
                      <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="userEditDropdown">
                          <a class="dropdown-item deleteBtn" href="javascript:void(0)"
                           data-route="' . route('admin.coupon.delete', $item->id) . '"
                           data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash"></i>
                           ' . trans("Delete") . '
                        </a>
                      </div>
                    </div>
                  </div>';
            })->rawColumns(['action', 'checkbox', 'coupon-code', 'status', 'start-date', 'end-date'])
            ->make(true);
    }

    public function add()
    {
        return view('admin.coupon.add');
    }

    public function store(Request $request)
    {

        $request->validate([
            'coupon_code' => 'required|string|max:255|unique:coupons,coupon_code',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'discount' => 'required|numeric|min:1',
            'discount_type' => 'required|in:0,1',
        ]);

        try {
            $response = new Coupon();
            $response->coupon_code = $request->coupon_code;
            $response->start_date = $request->start_date;
            $response->end_date = $request->end_date;
            $response->discount = $request->discount;
            $response->discount_type = $request->discount_type;
            $response->save();

            return back()->with('success', 'Coupon Added Successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }


    }

    public function edit($id)
    {

        try {
            $coupon = Coupon::where('id', $id)->firstOr(function () {
                throw new \Exception('Coupon not found.');
            });

            return view('admin.coupon.edit', compact('coupon'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:255|unique:coupons,coupon_code,' . $id,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'discount' => 'required|numeric|min:1',
            'discount_type' => 'required|in:0,1',
        ]);

        try {
            $coupon = Coupon::where('id', $id)->firstOr(function () {
                throw new \Exception('Coupon not found.');
            });

            $coupon->update($request->only([
                'coupon_code', 'start_date', 'end_date', 'discount', 'discount_type'
            ]));

            return back()->with('success', 'Coupon Updated Successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $coupon = Coupon::where('id', $id)->firstOr(function () {
                throw new \Exception('Coupon not found.');
            });

            $coupon->delete();

            return back()->with('success', 'Coupon Updated Successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

    }

    public function deleteMultiple(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You do not select any Coupon.');
            return response()->json(['error' => 1]);
        } else {
            $coupon = Coupon::whereIn('id', $request->strIds)->get();
            foreach ($coupon as $c) {
                $c->delete();
            }

            session()->flash('success', 'Selected Data deleted successfully');
            return response()->json(['success' => 1]);
        }
    }
}
