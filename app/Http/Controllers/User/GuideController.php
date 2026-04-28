<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Guide;
use App\Models\Package;
use App\Traits\Notify;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class GuideController extends Controller
{
    use Notify, Upload;
    public function list(Request $request)
    {
        $userId = auth()->id();

        $currentMonth = now()->month;
        $currentYear = now()->year;

        $result = DB::table('guides')
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as active_count,
                SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as inactive_count,
                SUM(CASE WHEN MONTH(created_at) = ? AND YEAR(created_at) = ? THEN 1 ELSE 0 END) as this_month_count,
                SUM(CASE WHEN YEAR(created_at) = ? THEN 1 ELSE 0 END) as this_year_count
            ', [$currentMonth, $currentYear, $currentYear])
            ->where('created_by', $userId)
            ->first();

        $total = $result->total;
        $percent = fn($count) => $total > 0 ? round(($count / $total) * 100, 2) : 0;

        $data['count'] = [
            'total' => $total,
            'active_count' => $result->active_count,
            'inactive_count' => $result->inactive_count,
            'this_month_count' => $result->this_month_count,
            'this_year_count' => $result->this_year_count,
            'active_percent' => $percent($result->active_count),
            'inactive_percent' => $percent($result->inactive_count),
            'this_month_percent' => $percent($result->this_month_count),
            'this_year_percent' => $percent($result->this_year_count),
        ];

        return view(template().'user.guide.list', $data);
    }
    public function search(Request $request)
    {

        $search = $request->search['value'];
        $filterStatus = $request->filterStatus;
        $filtername = $request->filtername;

        $guide = Guide::query()
            ->where('created_by', auth()->id())
            ->when(!empty($search), function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('code', 'like', '%' . $search . '%')
                        ->orWhere('designation', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->when(!empty($filtername), function ($query) use ($filtername) {
                $query->where(function ($q) use ($filtername) {
                    $q->where('name', 'like', '%' . $filtername . '%')
                        ->orWhere('code', 'like', '%' . $filtername . '%')
                        ->orWhere('designation', 'like', '%' . $filtername . '%')
                        ->orWhere('email', 'like', '%' . $filtername . '%');
                });
            })
            ->when(isset($filterStatus), function ($query) use ($filterStatus) {
                if ($filterStatus === 'all') {
                    return $query->whereNotNull('status');
                } elseif ($filterStatus === '1') {
                    return $query->where('status', 1);
                } elseif ($filterStatus === '0') {
                    return $query->where('status', 0);
                }
            })
            ->get();

        return DataTables::of($guide)
            ->addColumn('serial', function () {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('code', function ($item) {
                return $item->code;
            })
            ->addColumn('name', function ($item) {
                $url = route('user.guide.edit', $item->slug);
                return '<a class="d-flex align-items-center me-2" href="' . $url . '">
                            <div class="flex-shrink-0">
                                <div class="avatar avatar-sm avatar-circle">
                                    <img class="avatar-img" src="'. getFile($item->driver, $item->image) .'" alt="'. $item->name .'">
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                              <h5 class="text-hover-primary mb-0">'. $item->name .'</h5>
                            </div>
                          </a>';

            })
            ->addColumn('email', function ($item) {
                return $item->email;
            })
            ->addColumn('phone', function ($item) {
                return $item->phone;
            })
            ->addColumn('experience', function ($item) {
                return ' <span class="badge bg-soft-info text-dark">' . $item->years_of_experience.' Years' . '</span>';
            })
            ->addColumn('tour_completed', function ($item) {
                return ' <span class="badge bg-soft-warning text-dark">' . $item->tour_completed . '</span>';
            })
            ->addColumn('rating', function ($item) {
                $rating = floatval($item->rating);
                $stars = '';

                for ($i = 1; $i <= 5; $i++) {
                    if ($rating >= $i) {
                        $stars .= '<i class="fas fa-star text-warning"></i>';
                    } elseif ($rating >= ($i - 0.5)) {
                        $stars .= '<i class="fas fa-star-half-alt text-warning"></i>';
                    } else {
                        $stars .= '<i class="far fa-star text-muted"></i>';
                    }
                }

                return '<div>' . $stars . ' <span class="ms-1 text-dark">(' . number_format($rating, 1) . ')</span></div>';
            })
            ->addColumn('status', function ($item) {
                if ($item->status == 1) {
                    return '<span class="badge bg-soft-success text-success">
                        <span class="legend-indicator bg-success"></span>' . trans('Active') . '
                    </span>';
                } elseif($item->status == 0) {
                    return '<span class="badge bg-soft-warning text-warning">
                        <span class="legend-indicator bg-warning"></span>' . trans('Inactive') . '
                    </span>';
                } else{
                    return '<span class="badge bg-soft-secondary text-secondary">
                        <span class="legend-indicator bg-secondary"></span>' . trans('Unknown') . '
                    </span>';
                }
            })
            ->addColumn('action', function ($item) {
                $editUrl = route('user.guide.edit', $item->slug);
                $deleteurl = route('user.guide.delete', $item->slug);

                return '<div class="btn-group" role="group">
                      <a href="' . $editUrl . '" class="btn btn-white btn-sm edit_user_btn">
                        <i class="bi-pencil-square me-1"></i> ' . trans("Edit") . '
                      </a>
                    <div class="btn-group">
                      <button type="button" class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty" id="userEditDropdown" data-bs-toggle="dropdown" aria-expanded="false"></button>
                      <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="userEditDropdown">
                        <a class="dropdown-item" href="' . route("user.all.package", ['guideCode' => $item->code]) . '">
                           <i class="fa-regular fa-user pe-2"></i> ' . trans("Tours") . '
                        </a>
                        <a class="dropdown-item statusBtn" href="javascript:void(0)"
                           data-route="' . route("user.guide.status", $item->slug) . '"
                           data-bs-toggle="modal"
                           data-bs-target="#statusModal">
                            <i class="bi bi-check-circle pe-2"></i>
                           ' . trans("Status") . '
                        </a>
                       <a class="dropdown-item deleteBtn " href="javascript:void(0)"
                           data-route="' . $deleteurl . '"
                           data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash pe-2"></i>
                           ' . trans("  Delete") . '
                        </a>
                      </div>
                    </div>
                  </div>';
            })->rawColumns(['name', 'action', 'serial','code','email','status','phone','experience','tour_completed','rating'])
            ->make(true);
    }

    public function add(Request $request)
    {
        return view(template().'user.guide.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:guides,slug',
            'code' => 'required|string|max:100|unique:guides,code',
            'email' => 'required|email|max:255|unique:guides,email',
            'phone' => 'required|string|max:20',
            'years_of_experience' => 'required|integer|min:0',
            'tour_completed' => 'required|integer|min:0',
            'designation' => 'required|string|max:100',
            'description' => 'required|string',
            'thumb' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        try {
            if ($request->hasFile('thumb')) {
                $thumb = $this->fileUpload($request->thumb, config('filelocation.guide.path'), null, config('filelocation.guide.size'), 'webp', 80);
            }

            $guide = new Guide();
            $guide->name = $request->name;
            $guide->created_by = auth()->id();
            $guide->slug = $request->slug;
            $guide->code = $request->code;
            $guide->email = $request->email;
            $guide->phone = $request->phone;
            $guide->years_of_experience = $request->years_of_experience;
            $guide->tour_completed = $request->tour_completed;
            $guide->designation = $request->designation;
            $guide->description = $request->description;
            $guide->image = $thumb['path'];
            $guide->driver = $thumb['driver'];
            $guide->save();

            return back()->with('success', 'Guide created successfully.');
        }catch (\Exception $exception){
            return back()->withErrors($exception->getMessage());
        }
    }

    public function edit($slug)
    {
        try {
            $data['guide'] = Guide::where('slug', $slug)->where('created_by', auth()->id())->firstOr(function () {
                throw new \Exception('Guide not found.');
            });

            return view(template().'user.guide.edit', $data);
        }catch (\Exception $exception){
            return back()->withErrors($exception->getMessage());
        }
    }

    public function update(Request $request)
    {
        $guide = Guide::where('slug', $request->guide_slug)->where('created_by', auth()->id())->first();
        if (!$guide){
            return back()->withErrors('Guide not found.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('guides', 'slug')->ignore($guide->id),
            ],
            'code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('guides', 'code')->ignore($guide->id),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('guides', 'email')->ignore($guide->id),
            ],
            'phone' => 'required|string|max:20',
            'years_of_experience' => 'required|integer|min:0',
            'tour_completed' => 'required|integer|min:0',
            'designation' => 'required|string|max:100',
            'description' => 'required|string',
            'thumb' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        try {

            if ($request->hasFile('thumb')) {
                $thumb = $this->fileUpload($request->thumb, config('filelocation.guide.path'), null, config('filelocation.guide.size'), 'webp', 80);

                $guide->image = $thumb['path'];
                $guide->driver = $thumb['driver'];
                $guide->save();
            }

            $guide->name = $request->name;
            $guide->created_by = auth()->id();
            $guide->slug = $request->slug;
            $guide->code = $request->code;
            $guide->email = $request->email;
            $guide->phone = $request->phone;
            $guide->years_of_experience = $request->years_of_experience;
            $guide->tour_completed = $request->tour_completed;
            $guide->designation = $request->designation;
            $guide->description = $request->description;
            $guide->save();

            return back()->with('success', 'Guide created successfully.');
        }catch (\Exception $exception){
            return back()->withErrors($exception->getMessage());
        }
    }
    public function status($slug){
        try {
            $guide = Guide::select(['id','created_by','slug','status'])
                ->where('slug', $slug)
                ->where('created_by', auth()->id())
                ->firstOr(function () {
                    throw new \Exception('Guide not found.');
                });

            $guide->status = ($guide->status == 1) ? 0 : 1;
            $guide->save();

            return back()->with('success','Guide Status Changed Successfully.');
        }catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    public function delete($slug)
    {
        try {
            $guide = Guide::where('slug', $slug)
                ->where('created_by', auth()->id())
                ->firstOr(function () {
                    throw new \Exception('This Guide is not available now');
                });

            $relatedPackages = Package::whereJsonContains('guides', $guide->code)->get();

            if ($relatedPackages->isNotEmpty()) {
                return back()->with('error', 'Selected Guide has related packages and cannot be deleted.');
            }

            $this->fileDelete($guide->driver, $guide->image);

            $guide->delete();

            return back()->with('success', 'Guide deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

}
