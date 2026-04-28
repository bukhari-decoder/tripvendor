<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\PackageCategory;
use App\Traits\Notify;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class PackageCategoryController extends Controller
{
    use Notify, Upload;
    public function list()
    {
        $query = DB::table('package_categories')
            ->selectRaw('COUNT(*) as totalPackageCategory,
                 SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as totalActivePackageCategory,
                 SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as totalInactivePackageCategory,
                 SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as totalCreatedToday,
                 SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as totalCreatedThisMonth',
                [now()->startOfDay(), now()->startOfMonth()])
            ->first();

        $data['totalPackageCategory'] = $query->totalPackageCategory;
        $data['totalActivePackageCategory'] = $query->totalActivePackageCategory ?? 0;
        $data['totalInactivePackageCategory'] = $query->totalInactivePackageCategory ?? 0;
        $data['totalCreatedToday'] = $query->totalCreatedToday ?? 0;
        $data['totalCreatedThisMonth'] = $query->totalCreatedThisMonth ?? 0;

        if ($data['totalPackageCategory'] > 0) {
            $data['totalActivePercentageCategory'] = ($data['totalActivePackageCategory'] / $data['totalPackageCategory']) * 100;
            $data['totalInactivePercentageCategory'] = ($data['totalInactivePackageCategory'] / $data['totalPackageCategory']) * 100;
            $data['totalTotalCreatedTodayPercentageCategory'] = ($data['totalCreatedToday'] / $data['totalPackageCategory']) * 100;
            $data['totalTotalCreatedThisMonthPercentageCategory'] = ($data['totalCreatedThisMonth'] / $data['totalPackageCategory']) * 100;
        } else {
            $data['totalActivePercentageCategory'] = 0;
            $data['totalInactivePercentageCategory'] = 0;
            $data['totalTotalCreatedTodayPercentageCategory'] = 0;
            $data['totalTotalCreatedThisMonthPercentageCategory'] = 0;
        }

        return view('admin.package.category.list', $data);
    }

    public function search(Request $request)
    {

        $search = $request->input('search.value') ?? null;
        $filterName = $request->input('filterName');
        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;
        $filterStatus = $request->input('filterStatus');

        $packageCategory = PackageCategory::query()->orderBy('id', 'desc')
            ->withCount('packages')
            ->when(!empty($search), function ($query) use ($search) {
                return $query->where('name', 'LIKE', "%{$search}%");
            })
            ->when(isset($filterName) && !empty($filterName), function ($query) use ($filterName) {
                return $query->where('name', 'LIKE', "%{$filterName}%");
            })
            ->when(!empty($request->filterDate) && $endDate != null, function ($query) use ($startDate, $endDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $endDate = Carbon::createFromFormat('d/m/Y', trim($endDate));
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->when(isset($filterStatus), function ($query) use ($filterStatus) {
                if ($filterStatus == "all") {
                    return $query->where('status', '!=', null);
                }
                return $query->where('status', $filterStatus);
            });

        return DataTables::of($packageCategory)
            ->addColumn('checkbox', function ($item) {
                return ' <input type="checkbox" id="chk-' . $item->id . '"
                                       class="form-check-input row-tic tic-check" name="check" value="' . $item->id . '"
                                       data-id="' . $item->id . '">';
            })

            ->addColumn('name', function ($item) {
                $image = $item->thumb;
                $title = $item->name;
                $shortTitle = strlen($title) > 30 ? substr($title, 0, 30) . '...' : $title;

                if (!$image) {
                    $firstLetter = substr($title, 0, 1);
                    return '<div class="avatar avatar-sm avatar-soft-primary avatar-circle d-flex justify-content-start gap-2 w-100" title="' . $title . '">
                    <span class="avatar-initials">' . e($firstLetter) . '</span>
                    <p class="catTxt ms-2 mb-0 " title="' . $title . '">' . e($shortTitle) . '</p>
                </div>';
                } else {
                    $url = getFile($item->thumb_driver, $item->thumb);

                    return '<a class="d-flex align-items-center me-2" href="javascript:void(0)" title="' . $title . '">
                    <div class="flex-shrink-0">
                        <div class="avatar avatar-sm avatar-circle">
                            <img class="avatar-img" src="' . $url . '" alt="Image Description">
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="text-hover-primary mb-0" title="' . $title . '">' . e($shortTitle) . '</h5>
                    </div>
                </a>';
                }
            })
            ->addColumn('packages', function ($item) {
                return ' <span class="badge bg-soft-secondary text-dark">' . $item->packages_count . '</span>';
            })
            ->addColumn('status', function ($item) {
                if ($item->status == 0) {
                    return ' <span class="badge bg-soft-warning text-warning">
                                    <span class="legend-indicator bg-warning"></span> ' . trans('Inactive') . '
                                </span>';
                } else if ($item->status == 1) {
                    return '<span class="badge bg-soft-success text-success">
                                    <span class="legend-indicator bg-success"></span> ' . trans('Active') . '
                                </span>';

                }
            })
            ->addColumn('create-at', function ($item) {
                return dateTime($item->created_at);
            })
            ->addColumn('action', function ($item) {
                $editUrl = route('admin.package.category.edit', $item->id);
                return '<div class="btn-group" role="group">
                      <a href="' . $editUrl . '" class="btn btn-white btn-sm edit_user_btn">
                        <i class="bi-pencil-fill me-1"></i> ' . trans("Edit") . '
                      </a>
                    <div class="btn-group">
                      <button type="button" class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty" id="userEditDropdown" data-bs-toggle="dropdown" aria-expanded="false"></button>
                      <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="userEditDropdown">
                      <a class="dropdown-item" href="' . route("admin.all.package", ['category' => $item->id]) . '">
                                <i class="bi-boxes"></i> ' . trans("Manage Packages") . '
                            </a>
                        <a class="dropdown-item statusBtn" href="javascript:void(0)"
                           data-route="' . route("admin.packageCategory.status", $item->id) . '"
                           data-bs-toggle="modal"
                           data-bs-target="#statusModal">
                            <i class="bi bi-check-circle"></i>
                           ' . trans("Status") . '
                        </a>
                          <a class="dropdown-item deleteBtn" href="javascript:void(0)"
                           data-route="' . route("admin.package.category.delete", $item->id) . '"
                           data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash"></i>
                           ' . trans("Delete") . '
                        </a>
                      </div>
                    </div>
                  </div>';
            })->rawColumns(['action', 'checkbox', 'create-at', 'status', 'packages', 'name'])
            ->make(true);
    }

    public function add()
    {
        return view('admin.package.category.add');
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255|unique:package_categories,name',
        ]);

        try {
            if ($request->hasFile('thumb')) {
                $thumb = $this->fileUpload($request->thumb, config('filelocation.package_category.path'), null, config('filelocation.package_category.size'), 'webp', 80);
            }

            $response = new PackageCategory();
            $response->name = $request->name;
            $response->thumb = $thumb['path'];
            $response->thumb_driver = $thumb['driver'];
            $response->save();

            return back()->with('success', 'Category added successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $data['category'] = PackageCategory::findOrFail($id);
        return view('admin.package.category.edit', $data);
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('package_categories', 'name')->ignore($id)]
        ]);

        try {
            $response = PackageCategory::where('id', $id)->firstOr(function () {
                throw new \Exception('The package category was not found.');
            });

            if ($request->hasFile('thumb')) {
                $thumb = $this->fileUpload($request->thumb, config('filelocation.package_category.path'), null, config('filelocation.package_category.size'), 'webp', 80);
                $response->update(['thumb' => $thumb['path'], 'thumb_driver' => $thumb['driver']]);
            }

            $response->name = $request->name;
            $response->save();

            return back()->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $category = PackageCategory::with('packages:id')->where('id', $id)->firstOr(function () {
                throw new \Exception('The package category was not found.');
            });

            if ($category->packages->isNotEmpty()) {
                return back()->with('error', 'Package Category is not empty.');
            }

            $category->delete();

            return back()->with('success', 'Category deleted successfully.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

    }

    public function deleteMultiple(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You do not select Data.');
            return response()->json(['error' => 1]);
        }

        PackageCategory::with(['packages'])
            ->whereIn('id', $request->strIds)->get()->map(function ($package) {
                if ($package->packages->isEmpty()) {
                    $package->forceDelete();
                }
            });
        session()->flash('success', 'Package Category has been deleted successfully');

        return response()->json(['success' => 1]);

    }

    public function status($id)
    {
        $packageCategory = PackageCategory::select('id', 'status')
            ->findOrFail($id);

        $packageCategory->status = ($packageCategory->status == 1) ? 0 : 1;
        $packageCategory->save();

        return back()->with('success', 'Package Category Status Changed Successfully.');

    }

    public function inactiveMultiple(Request $request)
    {
        if (!$request->has('strIds') || empty($request->strIds)) {
            session()->flash('error', 'You did not select any data.');
            return response()->json(['error' => 1]);
        }

        PackageCategory::select(['id', 'status'])->whereIn('id', $request->strIds)->get()->each(function ($package) {
            $package->status = ($package->status == 0) ? 1 : 0;
            $package->save();
        });

        session()->flash('success', 'Package Categories status changed successfully');

        return response()->json(['success' => 1]);
    }
}
