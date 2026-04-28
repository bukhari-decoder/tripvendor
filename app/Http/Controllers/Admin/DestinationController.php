<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\Destination;
use App\Models\GoogleMapApi;
use App\Models\Package;
use App\Models\Property;
use App\Models\State;
use App\Traits\Notify;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use function App\Http\Controllers\Admin\Module\getMap;

class DestinationController extends Controller
{
    use Upload, Notify;

    public function list(Request $request)
    {
        $query = DB::table('destinations')
            ->selectRaw('COUNT(*) as totalDestination,
             SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as totalActiveDestination,
             SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as totalInactiveDestination,
             SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as totalCreatedToday,
             SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as totalCreatedThisMonth',
                [now()->startOfDay(), now()->startOfMonth()])
            ->first();

        $data['totalDestination'] = $query->totalDestination ?? 0;
        $data['totalActiveDestination'] = $query->totalActiveDestination ?? 0;
        $data['totalInactiveDestination'] = $query->totalInactiveDestination ?? 0;
        $data['totalCreatedToday'] = $query->totalCreatedToday ?? 0;
        $data['totalCreatedThisMonth'] = $query->totalCreatedThisMonth ?? 0;

        $data['totalActivePercentage'] = $data['totalDestination'] > 0 ? ($data['totalActiveDestination'] / $data['totalDestination']) * 100 : 0;
        $data['totalInactivePercentage'] = $data['totalDestination'] > 0 ? ($data['totalInactiveDestination'] / $data['totalDestination']) * 100 : 0;
        $data['totalTotalCreatedTodayPercentage'] = $data['totalDestination'] > 0 ? ($data['totalCreatedToday'] / $data['totalDestination']) * 100 : 0;
        $data['totalTotalCreatedThisMonthPercentage'] = $data['totalDestination'] > 0 ? ($data['totalCreatedThisMonth'] / $data['totalDestination']) * 100 : 0;

        return view('admin.destination.list', $data);
    }



    public function search(Request $request)
    {

        $search = $request->input('search.value') ?? null;

        $filterName = $request->filterName;
        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;
        $category = $request->category;
        $filterStatus = $request->input('filterStatus');

        $packages = Destination::query()
            ->with(['countryTake:id,name', 'stateTake:id,name', 'cityTake:id,name'])
            ->withCount('packages')
            ->orderBy('id', 'DESC')
            ->when(!empty($search), function ($query) use ($search) {
                $query->where('title', 'LIKE', "%{$search}%");
            })
            ->when(!empty($filterName), function ($query) use ($filterName) {
                $query->where('title', 'LIKE', "%{$filterName}%");
            })
            ->when(!empty($request->filterDate) && $endDate != null, function ($query) use ($startDate, $endDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $endDate = Carbon::createFromFormat('d/m/Y', trim($endDate));
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->when(isset($category), function ($query) use ($category) {
                return $query->where('destination_category_id', $category);
            })
            ->when(isset($filterStatus), function ($query) use ($filterStatus) {
                if ($filterStatus == "all") {
                    return $query->where('status', '!=', null);
                }
                return $query->where('status', $filterStatus);
            });
        return DataTables::of($packages)
            ->addColumn('checkbox', function ($item) {
                return ' <input type="checkbox" id="chk-' . $item->id . '"
                                       class="form-check-input row-tic tic-check" name="check" value="' . $item->id . '"
                                       data-id="' . $item->id . '">';
            })
            ->addColumn('destination', function ($item) {
                $image = $item->thumb;
                if (!$image) {
                    $firstLetter = substr($item->title, 0, 1);
                    return '<div class="avatar avatar-sm avatar-soft-primary avatar-circle d-flex justify-content-start gap-2 w-100">
                                <span class="avatar-initials">' . $firstLetter . '</span>
                                <p class="avatar-initials ms-3">' . $item->title . '</p>
                            </div>';

                } else {
                    $url = getFile($item->thumb_driver, $item->thumb);

                    return '<a class="d-flex align-items-center me-2" href="javascript:void(0)">
                                <div class="flex-shrink-0">
                                  <div class="avatar avatar-sm avatar-circle">
                                        <img class="avatar-img" src="' . $url . '" alt="Image Description">
                                  </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . $item->title . '</h5>
                                </div>
                              </a>';

                }
            })
            ->addColumn('properties', function ($item) {
                return ' <span class="badge bg-soft-secondary text-dark">' . $item->packages_count . '</span>';
            })
            ->addColumn('country', function ($item) {
                return $item->countryTake?->name ?? '-';
            })
            ->addColumn('state', function ($item) {
                return $item->stateTake?->name ?? '-';
            })
            ->addColumn('city', function ($item) {
                return $item->cityTake?->name ?? '-';
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
            ->addColumn('created_at', function ($item) {
                return dateTime($item->created_at);
            })
            ->addColumn('action', function ($item) {
                $editUrl = route('admin.destination.edit', $item->id);
                $packUrl = route('admin.all.package', ['destination' => $item->id]);
                return '<div class="btn-group" role="group">
                      <a href="' . $editUrl . '" class="btn btn-white btn-sm edit_user_btn">
                        <i class="bi-pencil-fill me-1"></i> ' . trans("Edit") . '
                      </a>
                    <div class="btn-group">
                      <button type="button" class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty" id="userEditDropdown" data-bs-toggle="dropdown" aria-expanded="false"></button>
                      <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="userEditDropdown">
                      <a class="dropdown-item" href="'.$packUrl.'">
                                <i class="bi-boxes"></i> ' . trans("Manage Properties") . '
                            </a>
                        <a class="dropdown-item statusBtn" href="javascript:void(0)"
                           data-route="' . route("admin.destination.status", $item->id) . '"
                           data-bs-toggle="modal"
                           data-bs-target="#statusModal">
                            <i class="bi bi-check-circle"></i>
                           ' . trans("Status") . '
                        </a>
                          <a class="dropdown-item deleteBtn" href="javascript:void(0)"
                           data-route="' . route("admin.destination.delete", $item->id) . '"
                           data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash"></i>
                           ' . trans("Delete") . '
                        </a>
                      </div>
                    </div>
                  </div>';
            })
            ->rawColumns(['action', 'checkbox','created_at', 'status', 'properties', 'destination','country','state','city'])
            ->make(true);
    }

    public function deleteMultiple(Request $request)
    {
        if (!$request->strIds) {
            session()->flash('error', 'You did not select any data.');
            return response()->json(['error' => 1]);
        }

        $ids = is_array($request->strIds) ? $request->strIds : explode(',', $request->strIds);

        $relatedProperties = Package::whereIn('destination_id', $ids)->exists();

        if ($relatedProperties) {
            session()->flash('error', 'One or more selected destinations have related properties and cannot be deleted.');
            return response()->json(['error' => 1]);
        }

        Destination::whereIn('id', $ids)->each(function ($destination) {
            $this->fileDelete($destination->thumb_driver, $destination->thumb);
            $destination->forceDelete();
        });

        session()->flash('success', 'Destinations have been deleted successfully.');
        return response()->json(['success' => 1]);
    }

    public function add()
    {
        $data['location'] = Country::where('status', 1)->orderBy('name', 'asc')->get();

        return view('admin.destination.add', $data);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'long' => 'required',
            'lat' => 'required',
            'area' => 'required',
            'currency' => 'required',
            'main_language' => 'required',
            'thumb' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'offer_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'slug' => 'required|string|max:255|unique:destinations,slug',
            'details' => 'required',
        ]);

        try {
            $thumb = $thumbDriver = null;

            if ($request->hasFile('thumb')) {
                $photo = $this->fileUpload($request->thumb, config('filelocation.destination.path'), null, config('filelocation.destination.size'), 'webp', 60);
                $thumb = $photo['path'];
                $thumbDriver = $photo['driver'];
            }

            if ($request->hasFile('offer_image')) {
                $offer = $this->fileUpload($request->offer_image, config('filelocation.destination.offer_size'), null, config('filelocation.destination.offer_size'), 'webp', 60);
                $offerPath = $offer['path'];
                $offerDriver = $offer['driver'];
            }


            $destination = new Destination();
            $destination->title = $request->name;
            $destination->slug = $request->slug;
            $destination->details = $request->details;
            $destination->city = $request->city;
            $destination->state = $request->state;
            $destination->country = $request->country;
            $destination->lat = $request->lat;
            $destination->long = $request->long;
            $destination->main_language = $request->main_language;
            $destination->currency = $request->currency;
            $destination->area = $request->area;
            $destination->thumb = $thumb ?? null;
            $destination->thumb_driver = $thumbDriver ?? null;
            $destination->offer_image = $offerPath ?? null;
            $destination->offer_image_driver = $offerDriver ?? null;
            $destination->place = $request->place ?? null;
            $destination->save();

            return back()->with('success', 'Destination added successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $data['destination'] = Destination::with('countryTake:id,name', 'stateTake:id,name', 'cityTake:id,name')->where('id', $id)->firstOr(function () {
                throw new \Exception('Destination not found.');
            });

            $data['location'] = Country::where('status', 1)->get();


            return view('admin.destination.edit', $data);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'long' => 'required',
            'lat' => 'required',
            'area' => 'required',
            'currency' => 'required',
            'main_language' => 'required',
            'thumb' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'offer_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'slug' => 'required|string|max:255|unique:destinations,slug,' . $id,
            'details' => 'required',
        ]);

        try {
            $destination = Destination::where('id', $id)->firstOr(function () {
                throw new \Exception('Destination not found.');
            });

            $destination->title = $request->name;
            $destination->slug = $request->slug;
            $destination->details = $request->details;
            $destination->city = $request->city;
            $destination->state = $request->state;
            $destination->country = $request->country;
            $destination->lat = $request->lat;
            $destination->long = $request->long;
            $destination->main_language = $request->main_language;
            $destination->currency = $request->currency;
            $destination->area = $request->area;
            $destination->place = $request->place;
            $destination->save();

            if ($request->hasFile('thumb')) {
                $thumb = $this->fileUpload($request->thumb, config('filelocation.destination.path'), null, config('filelocation.destination.size'), 'webp', 60, $destination->thumb, $destination->thumb_driver);
                $destination->update(['thumb' => $thumb['path'], 'thumb_driver' => $thumb['driver']]);
            }

            if ($request->hasFile('offer_image')) {
                $offer = $this->fileUpload($request->offer_image, config('filelocation.destination.offer_size'), null, config('filelocation.destination.offer_size'), 'webp', 60, $destination->offer_image, $destination->offer_image_driver);
                $destination->update(['offer_image' => $offer['path'], 'offer_image_driver' => $offer['driver']]);
            }

            return back()->with('success', 'Destination updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

    }

    public function delete($id)
    {
        try {
            $destination = Destination::where('id', $id)->firstOr(function () {
                throw new \Exception('Destination not found.');
            });
            $this->fileDelete($destination->thumb_driver, $destination->thumb);
            $destination->delete();

            return back()->with('success', 'Destination deleted successfully.');
        }catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

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

    public function status($id)
    {

        try {
            $destination = Destination::select(['id','status'])->where('id', $id)->firstOr(function () {
                throw new \Exception('Destination not found.');
            });

            $destination->status = ($destination->status == 1) ? 0 : 1;
            $destination->save();

            return back()->with('success', 'Destination Status Changed Successfully.');
        }catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

    }

    public function statusMultiple(Request $request)
    {
        if (!$request->has('strIds') || empty($request->strIds)) {
            session()->flash('error', 'You did not select any data.');
            return response()->json(['error' => 1]);
        }

        Destination::whereIn('id', $request->strIds)->get()->each(function ($destination) {
            $destination->status = ($destination->status == 0) ? 1 : 0;
            $destination->save();
        });

        session()->flash('success', 'Destination status changed successfully');

        return response()->json(['success' => 1]);
    }


}
