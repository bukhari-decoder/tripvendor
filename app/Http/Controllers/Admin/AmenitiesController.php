<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AmenitiesController extends Controller
{
    public function list(Request $request)
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $data['amenityStats'] = Amenity::selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as active,
            SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as inactive,
            SUM(CASE WHEN MONTH(created_at) = ? AND YEAR(created_at) = ? THEN 1 ELSE 0 END) as this_month,
            SUM(CASE WHEN YEAR(created_at) = ? THEN 1 ELSE 0 END) as this_year
        ", [$currentMonth, $currentYear, $currentYear])->first();

        $total = $data['amenityStats']->total ?? 1;

        $data['percentages'] = [
            'active' => round(($data['amenityStats']->active / $total) * 100, 2),
            'inactive' => round(($data['amenityStats']->inactive / $total) * 100, 2),
            'this_month' => round(($data['amenityStats']->this_month / $total) * 100, 2),
            'this_year' => round(($data['amenityStats']->this_year / $total) * 100, 2),
        ];

        return view('admin.amenities.index', $data);
    }

    public function listSearch(Request $request)
    {
        $search = $request->search['value'];
        $filterSearch = $request->filterSearch;
        $filterStatus = $request->filterStatus;
        $filterType = $request->filterType;
        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;

        $category = Amenity::query()
            ->orderBy('id', 'DESC')
            ->when(!empty($search), function ($query) use ($search) {
                return $query->where('title', 'LIKE', "%{$search}%");
            })
            ->when(isset($filterSearch) && !empty($filterSearch), function ($query) use ($filterSearch) {
                return $query->where('title', 'LIKE', "%{$filterSearch}%");
            })
            ->when(isset($filterStatus), function ($query) use ($filterStatus) {
                if ($filterStatus == 'all') {
                    return $query->where('status', '!=', null);
                }
                return $query->where('status','=', $filterStatus);
            })
            ->when(isset($filterType), function ($query) use ($filterType) {
                if ($filterType == 'all') {
                    return $query->where('type', '!=', null);
                }
                return $query->where('type','=', $filterType);
            })
            ->when(!empty($request->filterDate) && $endDate != null, function ($query) use ($startDate, $endDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $endDate = Carbon::createFromFormat('d/m/Y', trim($endDate));
                $query->whereBetween('created_at', [$startDate, $endDate]);
            });

        return DataTables::of($category)

            ->addColumn('checkbox', function ($item) {
                return ' <input type="checkbox" id="chk-' . $item->id . '"
                                       class="form-check-input row-tic tic-check" name="check" value="' . $item->id . '"
                                       data-id="' . $item->id . '">';
            })
            ->addColumn('type', function ($item) {
                return ' <span class="badge bg-soft-info text-dark">' . $item->type . '</span>';
            })
            ->addColumn('title', function ($item) {
                $image = $item->icon;
                return '<div class="d-flex align-items-center">
                    <div class="avatar avatar-sm d-flex align-items-center justify-content-start">
                        <i class="' . $image . ' fs-4 text-primary"></i>
                    </div>
                    <span class="fs-6 fw-semibold text-dark">' . $item->title . '</span>
                </div>';
            })
            ->addColumn('property', function ($item) {
                return ' <span class="badge bg-soft-primary text-dark">' . $item->property_count . '</span>';
            })
            ->addColumn('status', function ($item) {
                if ($item->status == 1) {
                    return '<span class="badge bg-soft-success text-success">
                                <span class="legend-indicator bg-success"></span>' . trans('Active') . '
                              </span>';
                } elseif ($item->status == 0) {
                    return '<span class="badge bg-soft-danger text-danger">
                                <span class="legend-indicator bg-danger"></span>' . trans('Inactive') . '
                              </span>';
                } else {
                    return '<span class="badge bg-soft-danger text-danger">
                                <span class="legend-indicator bg-danger"></span>' . trans('Unknown') . '
                              </span>';
                }
            })
            ->addColumn('created_at', function ($item) {
                return dateTime($item->created_at);
            })
            ->addColumn('action', function ($item) {
                $editUrl = route('admin.amenity.edit', $item->id);
                $deleteUrl = route('admin.amenity.delete', $item->id);
                $statusUrl = route('admin.amenity.status', $item->id);
                return '<div class="btn-group" role="group">
                      <a href="' . $editUrl . '" class="btn btn-white btn-sm edit_user_btn">
                        <i class="bi-pencil-fill me-1"></i> ' . trans("Edit") . '
                      </a>
                    <div class="btn-group">
                      <button type="button" class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty" id="userEditDropdown" data-bs-toggle="dropdown" aria-expanded="false"></button>
                      <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="userEditDropdown">
                         <a class="dropdown-item statusBtn" href="javascript:void(0)"
                           data-route="'. $statusUrl .'"
                           data-bs-toggle="modal" data-bs-target="#statusModal">
                            <i class="bi bi-check-circle dropdown-item-icon"></i>
                            '. trans("Status Change") .'
                        </a>
                        <a class="dropdown-item deleteSingleBtn text-danger" href="javascript:void(0)"
                           data-route="'. $deleteUrl .'"
                           data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash dropdown-item-icon text-danger"></i>
                            '. trans("Delete") .'
                        </a>
                      </div>
                    </div>
                  </div>';
            })->rawColumns(['action', 'checkbox', 'title','property', 'status', 'created_at', 'type'])
            ->make(true);
    }
    public function add(){
        return view('admin.amenities.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->except('_token');
        $validator = Validator::make($validatedData, [
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:favourites,amenity,safety_item',
            'icon' => 'required|string|max:255',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {

            $amenity = new Amenity();
            $amenity->title = $request->name;
            $amenity->icon = $request->icon;
            $amenity->type = $request->type;
            $amenity->status = $request->status;
            $amenity->save();

            return back()->with('success', 'Amenity added successfully.');
        }catch (\Exception $exception){
            return back()->with('error',$exception->getMessage());
        }
    }

    public function edit($id){
        try {
            $amenity = Amenity::where('id', $id)->firstOr(function () {
                throw new \Exception('This Amenity is not available now');
            });

            return view('admin.amenities.edit', compact('amenity'));
        }catch (\Exception $exception){
            return back()->with('error',$exception->getMessage());
        }
    }

    public function update(Request $request)
    {
        $validatedData = $request->except('_token');
        $validator = Validator::make($validatedData, [
            'amenity_id' => 'required|exists:amenities,id',
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:favourites,amenity,safety_item',
            'icon' => 'required|string|max:255',
            'status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $amenity = Amenity::where('id', $request->amenity_id)->firstOr(function () {
                throw new \Exception('This Amenity is not available now');
            });

            $amenity->title = $request->name;
            $amenity->icon = $request->icon;
            $amenity->type = $request->type;
            $amenity->status = $request->status;
            $amenity->save();

            return back()->with('success', 'Amenity updated successfully.');
        }catch (\Exception $exception){
            return back()->with('error',$exception->getMessage());
        }

    }

    public function status($id){
        try {
            $amenity = Amenity::select('id', 'status')
                ->where('id', $id)
                ->firstOr(function () {
                    throw new \Exception('Amenity not found.');
                });

            $amenity->status = $amenity->status == 1 ? 0 : 1;
            $amenity->save();

            return back()->with('success','Amenity Status Changed Successfully.');
        }catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $amenity = Amenity::with('properties')->where('id', $id)->firstOr(function () {
                throw new \Exception('This Amenity is not available now');
            });

            if ($amenity->properties->isNotEmpty()) {
                return back()->with('error', 'Selected amenity has related properties and cannot be deleted.');
            }

            $amenity->delete();

            return back()->with('success', 'Amenity deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function statusMultiple(Request $request)
    {
        if (!$request->has('strIds') || empty($request->strIds)) {
            session()->flash('error', 'You did not select any data.');
            return response()->json(['error' => 1]);
        }

        Amenity::select(['id', 'status'])->whereIn('id', $request->strIds)->get()->each(function ($amenity) {
            $amenity->status = ($amenity->status == 0) ? 1 : 0;
            $amenity->save();
        });

        session()->flash('success', 'Amenities status changed successfully');

        return response()->json(['success' => 1]);
    }

    public function deleteMultiple(Request $request)
    {
        if (!$request->has('strIds') || empty($request->strIds)) {
            session()->flash('error', 'You did not select any data.');
            return response()->json(['error' => 1]);
        }

        $ids = is_array($request->strIds) ? $request->strIds : explode(',', $request->strIds);
        $amenities = Amenity::with('properties')->whereIn('id', $ids)->get();

        foreach ($amenities as $amenity) {
            if ($amenity->properties->isNotEmpty() ) {
                session()->flash('error', 'One or more selected amenities have related property and cannot be deleted.');
                return response()->json(['error' => 1]);
            }
        }

        $amenities->each(function ($amenity) {
            $amenity->delete();
        });


        session()->flash('success', 'Selected Amenities deleted successfully.');
        return response()->json(['success' => 1]);
    }
}
