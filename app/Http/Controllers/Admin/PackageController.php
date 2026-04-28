<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Amenity;
use App\Models\City;
use App\Models\Country;
use App\Models\Destination;
use App\Models\GoogleMapApi;
use App\Models\Package;
use App\Models\PackageCategory;
use App\Models\State;
use App\Traits\Notify;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class PackageController extends Controller
{
    use Upload, Notify;

    public function list(Request $request)
    {
        $query = DB::table('packages')
            ->selectRaw('COUNT(*) as totalPackage,
             SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as totalActivePackage,
             SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as totalInactivePackage,
             SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as totalCreatedToday,
             SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as totalCreatedThisMonth',
                [now()->startOfDay(), now()->startOfMonth()])
            ->first();

        $data['totalPackage'] = $query->totalPackage ?? 0;
        $data['totalActivePackage'] = $query->totalActivePackage ?? 0;
        $data['totalInactivePackage'] = $query->totalInactivePackage ?? 0;
        $data['totalCreatedToday'] = $query->totalCreatedToday ?? 0;
        $data['totalCreatedThisMonth'] = $query->totalCreatedThisMonth ?? 0;

        if ($data['totalPackage'] > 0) {
            $data['totalActivePercentage'] = ($data['totalActivePackage'] / $data['totalPackage']) * 100;
            $data['totalInactivePercentage'] = ($data['totalInactivePackage'] / $data['totalPackage']) * 100;
            $data['totalTotalCreatedThisMonthPercentage'] = ($data['totalCreatedThisMonth'] / $data['totalPackage']) * 100;
            $data['totalTotalCreatedTodayPercentage'] = ($data['totalCreatedToday'] / $data['totalPackage']) * 100;
        } else {
            $data['totalActivePercentage'] = 0;
            $data['totalInactivePercentage'] = 0;
            $data['totalTotalCreatedThisMonthPercentage'] = 0;
            $data['totalTotalCreatedTodayPercentage'] = 0;
        }

        $data['destination'] = $request->destination;
        $data['category'] = $request->category;

        return view('admin.package.list', $data);
    }


    public function  search(Request $request)
    {
        $search = trim(str_replace(['%', '_'], '', $request->search['value'] ?? ''));

        $filterName = $request->filterName;
        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;
        $destination = $request->destination;
        $category = $request->category;
        $filterStatus = $request->input('filterStatus');

        $packages = Package::query()->with(['category', 'destination:id,title', 'countryTake', 'stateTake', 'cityTake'])
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
            ->when(isset($destination) && $destination != null, function ($query) use ($destination) {
                return $query->where('destination_id', $destination);
            })
            ->when(isset($category) && $category != null, function ($query) use ($category) {
                return $query->where('package_category_id', $category);
            })
            ->when(isset($filterStatus) && $filterStatus != null, function ($query) use ($filterStatus) {
                if ($filterStatus == "all") {
                    return $query->where('status', '!=', null);
                }
                return $query->where('status', $filterStatus);
            })->get();


        return DataTables::of($packages)

            ->addColumn('checkbox', function ($item) {

                return ' <input type="checkbox" id="chk-' . $item->id . '"
                                       class="form-check-input row-tic tic-check" name="check" value="' . $item->id . '"
                                       data-id="' . $item->id . '">';
            })
            ->addColumn('package', function ($item) {
                $image = $item->thumb;
                $title = $item->title;
                $shortTitle = strlen($title) > 30 ? substr($title, 0, 30) . '...' : $title;

                if (!$image) {
                    $firstLetter = substr($title, 0, 1);
                    return '<div class="avatar avatar-sm avatar-soft-primary avatar-circle d-flex justify-content-start gap-2 w-100" title="' . $title . '">
                            <span class="avatar-initials">' . $firstLetter . '</span>
                            <p class="avatar-initials ms-3 mb-0" title="' . $title . '">' . $shortTitle . '</p>
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
                            <h5 class="text-hover-primary mb-0" title="' . $title . '">' . $shortTitle . '</h5>
                        </div>
                    </a>';
                }
            })
            ->addColumn('category', function ($item) {
                return optional($item->category)->name;
            })
            ->addColumn('time_slot', function ($item) {
                $timeSlots = is_string($item->timeSlot)
                    ? json_decode($item->timeSlot, true)
                    : (array) $item->timeSlot;

                if (empty($timeSlots)) return '';

                $badges = array_map(fn($slot) => "<span class='badge bg-soft-info text-info'>{$slot}</span>", $timeSlots);

                return implode('<br/>', $badges);
            })
            ->addColumn('amenities', function ($item) {
                $amenitiesData = is_string($item->amenities)
                    ? json_decode($item->amenities, true)
                    : (array) $item->amenities;

                $types = [
                    'Amenity' => $amenitiesData['amenity'] ?? [],
                    'Favourites' => $amenitiesData['favourites'] ?? [],
                    'Safety Item' => $amenitiesData['safety_item'] ?? [],
                ];

                $result = [];

                foreach ($types as $ids) {
                    if (!empty($ids)) {
                        $names = Amenity::whereIn('id', $ids)->pluck('title')->toArray();
                        if (!empty($names)) {
                            $badges = array_map(fn($name) => "<span class='badge bg-soft-info text-info'>{$name}</span>", $names);
                            $chunks = array_chunk($badges, 1);
                            $lines = array_map(fn($chunk) => implode(' ', $chunk), $chunks);
                            $result[] = implode('<br/>', $lines);
                        }
                    }
                }

                return implode('<br/>', $result);
            })
            ->addColumn('destination', function ($item) {
                return optional($item->destination)->title;
            })
            ->addColumn('status', function ($item) {
                if ($item->status == 0) {
                    return ' <span class="badge bg-soft-warning text-warning">
                                    <span class="legend-indicator bg-warning"></span> ' . trans('Pending') . '
                                </span>';
                } else if ($item->status == 1) {
                    return '<span class="badge bg-soft-success text-success">
                                    <span class="legend-indicator bg-success"></span> ' . trans('Accepted') . '
                                </span>';

                }else if ($item->status == 2) {
                    return '<span class="badge bg-soft-info text-info">
                                    <span class="legend-indicator bg-info"></span> ' . trans('Resubmitted') . '
                                </span>';

                }else if ($item->status == 3) {
                    return '<span class="badge bg-soft-secondary text-secondary">
                                    <span class="legend-indicator bg-secondary"></span> ' . trans('Holded') . '
                                </span>';

                }else if ($item->status == 4) {
                    return '<span class="badge bg-soft-dark text-dark">
                                    <span class="legend-indicator bg-dark"></span> ' . trans('Soft Rejected') . '
                                </span>';

                }else if ($item->status == 5) {
                    return '<span class="badge bg-soft-danger text-danger">
                                    <span class="legend-indicator bg-danger"></span> ' . trans('Rard Rejected') . '
                                </span>';

                }
            })
            ->addColumn('create-at', function ($item) {
                return dateTime($item->created_at);
            })
            ->addColumn('action', function ($item) {
                $editUrl = route('admin.package.edit', $item->id);
                $statusUrl = route('admin.package.status', $item->id);
                $bookingUrl = route('admin.all.booking', ['package' => $item->id]);
                $seoUrl = route('admin.package.seo', $item->id);
                $reviewUrl = route('admin.review.list', ['package' => $item->id]);
                return '<div class="btn-group" role="group">
                      <a href="' . $editUrl . '" class="btn btn-white btn-sm edit_user_btn">
                        <i class="bi-pencil-fill me-1"></i> ' . trans("Edit") . '
                      </a>
                    <div class="btn-group">
                      <button type="button" class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty" id="userEditDropdown" data-bs-toggle="dropdown" aria-expanded="false"></button>
                      <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="userEditDropdown">
                          <a class="dropdown-item discountBtn" href="javascript:void(0)"
                           data-route="' . route("admin.package.discount", $item->id) . '"
                           data-discount_type = "' . $item->discount_type . '"
                           data-discount_amount = "' . $item->discount_amount . '"
                           data-bs-toggle="modal"
                           data-bs-target="#discountModal">
                            <i class="bi bi-currency-dollar"></i>
                           ' . trans("Discount") . '
                        </a>
                        <a class="dropdown-item statusBtn" href="javascript:void(0)"
                           data-route="' . $statusUrl . '"
                           data-bs-toggle="modal"
                           data-bs-target="#statusModal">
                            <i class="bi bi-check-circle"></i>
                           ' . trans("Status") . '
                        </a>
                        <a class="dropdown-item" href="' . $bookingUrl . '"

                        >
                            <i class="bi bi-check-square"></i>
                           ' . trans("Tour Lists") . '
                        </a>
                        <a class="dropdown-item" href="' . $seoUrl . '"

                        >
                            <i class="fa-light fa-magnifying-glass"></i>
                           ' . trans("Seo") . '
                        </a>
                        <a class="dropdown-item" href="' . $reviewUrl . '"

                        >
                            <i class="bi bi-star"></i>
                           ' . trans("Reviews") . '
                        </a>
                        <a class="dropdown-item deleteBtn" href="javascript:void(0)"
                           data-route="' . route("admin.package.delete", $item->id) . '"
                           data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash"></i>
                           ' . trans("Delete") . '
                        </a>
                      </div>
                    </div>
                  </div>';
            })
            ->rawColumns(['action', 'checkbox', 'create-at', 'package', 'status', 'category', 'time_slot', 'amenities', 'destination'])
            ->make(true);
    }
    public function edit($id)
    {
        try {
            $package = Package::with('media', 'countryTake:id,name', 'stateTake:id,name', 'cityTake:id,name')->where('id', $id)
                ->firstOr(function () {
                    throw new \Exception('Package not found.');
                });

            $data = [
                'package' => $package,
                'categories' => PackageCategory::select('id', 'name')->where('status', 1)->get(),
                'destinations' => Destination::select('id', 'title','place')->where('status', 1)->get(),
                'images' => $package->media->map(fn($item) => getFile($item->driver, $item->image))->toArray(),
                'oldimg' => $package->media->pluck('id')->toArray(),
                'activity' => ActivityLog::where('property_id', $id)->with('activityable:id,username,image,image_driver')->orderBy('id', 'desc')->get(),
                'amenities' => Amenity::where('status', 1)->get(),
            ];


            return view('admin.package.edit', $data);
        }catch (\Exception $e){
            return back()->with('error', $e->getMessage());
        }

    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['required', 'string', Rule::unique('packages')->ignore($id),],
            'adult_price' => ['required', 'numeric', 'min:1'],
            'children_price' => ['required', 'numeric', 'min:1'],
            'infant_price' => ['required', 'numeric', 'min:1'],
            'category_id' => 'required|integer|exists:package_categories,id',
            'destination_id' => 'required|integer|exists:destinations,id',
            'tourDuration' => 'required|string',
            'address' => 'required|string|max:255',
            'minimumTravelers' => 'required|integer|min:1',
            'maximumTravelers' => 'required|integer|gte:minimumTravelers',
            'facility.*' => 'nullable',
            'excluded.*' => 'nullable',
            'expect.*' => 'nullable',
            'timeSlot.*' => 'nullable',
            'expect_details.*' => 'nullable',
            'details' => 'required|string',
            'thumb' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048',
            'video' => 'sometimes|nullable',
            'preloaded' => 'sometimes|array',
            'images' => ['required_without:preloaded', 'array'],
            'images.*' => ['required_without:preloaded', 'image', 'mimes:jpeg,png,jpg', 'max:10240'],
        ]);
        try {
            $package = Package::where('id', $id)->firstOr(function () {
                throw new \Exception('Package not found.');
            });

            if (!$package) {
                return back()->with('error', 'Package Is Missing');
            }

            $amenitiesIds = $request->amenities_id ?? [];

            if (!is_array($amenitiesIds)) {
                $amenitiesIds = (array) $amenitiesIds;
            }

            $amenities = Amenity::whereIn('id', $amenitiesIds)
                ->where('status', 1)
                ->get();

            $formattedAmenities = [
                'amenity' => [],
                'favourites' => [],
                'safety_item' => []
            ];

            foreach ($amenities as $amenity) {
                if ($amenity->type == 'amenity') {
                    $formattedAmenities['amenity'][] = (string) $amenity->id;
                } elseif ($amenity->type == 'favourites') {
                    $formattedAmenities['favourites'][] = (string) $amenity->id;
                } elseif ($amenity->type == 'safety_item') {
                    $formattedAmenities['safety_item'][] = (string) $amenity->id;
                }
            }

            $expectInfo = $this->formatExpectations(
                $request->input('expect', []),
                $request->input('expect_details', [])
            );

            if ($request->destination_id != $package->destination_id) {

                $newDestination = Destination::where('id', $request->destination_id)->firstOr(function () {
                    throw new \Exception('Destination not found.');
                });
                $country = Country::where('id', $newDestination->country)->firstOr(function () {
                    throw new \Exception('Country not found.');
                });
                $state = State::where('id', $newDestination->state)->firstOr(function () {
                    throw new \Exception('State not found.');
                });

                $city = City::where('id', $newDestination->city)->firstOr(function () {
                    throw new \Exception('City not found.');
                });

                $package->destination_id = $request->destination_id;
                $package->city = $city->id;
                $package->state = $state->id;
                $package->country = $country->id;
                $package->save();
            }

            $package->package_category_id = $request->category_id;
            $package->title = $request->name;
            $package->slug = $request->slug;
            $package->adult_price = $request->adult_price;
            $package->children_price = $request->children_price;
            $package->infant_price = $request->infant_price;
            $package->duration = $request->tourDuration;
            $package->address = $request->address ?? null;
            $package->minimumTravelers = $request->minimumTravelers;
            $package->maximumTravelers = $request->maximumTravelers;
            $package->facility = $request->facility;
            $package->excluded = $request->excluded;
            $package->expected = $expectInfo;
            $package->description = $request->details;
            $package->amenities = $formattedAmenities;
            $package->places = $request->places;
            $package->guides = $request->guides;
            $package->timeSlot = $request->timeSlot;
            $package->video = $request->video;
            $package->lat = $request->lat;
            $package->long = $request->long;
            $package->status = 1;
            $package->save();

            if ($request->hasFile('thumb')) {
                $thumb = $this->fileUpload($request->thumb, config('filelocation.package_thumb.path'), null, config('filelocation.package_thumb.size'), 'webp', 80);
                $package->update(['thumb' => $thumb['path'], 'thumb_driver' => $thumb['driver']]);
            }

            $old = $request->preloaded;
            $packageMedia = $package->media->where('package_id', $id)->whereNotIn('id', $old);

            foreach ($packageMedia as $item) {
                $this->fileDelete($item->image_driver, $item->image);
                $item->delete();
            }

            $path = [];
            if ($request->hasFile('images')) {
                foreach ($request->images as $img) {
                    $image = $this->fileUpload($img, config('filelocation.package.path'), null, config('filelocation.package.size'), 'webp', 80);
                    $path[] = $image['path'];
                    $driver = $image['driver'];
                }

                foreach ($path as $loc) {
                    $package->media()->updateOrCreate(['image' => $loc, 'driver' => $driver]);
                }
            }

            return back()->with('success', 'Package updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    private function formatExpectations($expects, $expectDetails)
    {
        return array_map(function ($expect, $expectDetail) {
            return [
                'expect' => $expect,
                'expect_detail' => $expectDetail
            ];
        }, $expects, $expectDetails);
    }

    public function delete($id)
    {
        try {
            $package = Package::where('id', $id)->firstOr(function () {
                throw new \Exception('Package not found.');
            });

            if ($package->media) {
                foreach ($package->media as $item) {
                    $this->fileDelete($item->driver, $item->image);
                    $item->delete();
                }
            }

            $package->delete();

            return back()->with('success', 'Package deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function deleteMultiple(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You do not select Data.');
            return response()->json(['error' => 1]);
        } else {
            Package::with(['media'])->whereIn('id', $request->strIds)->get()->map(function ($package) {
                if (!empty($package->media)){
                    foreach ($package->media as $media){
                        $this->fileDelete($media->driver, $media->image);
                        $media->delete();
                    }
                }
                $package->forceDelete();
            });
            session()->flash('success', 'Package has been deleted successfully');

            return response()->json(['success' => 1]);
        }
    }

    public function action(Request $request)
    {
        DB::beginTransaction();
        try {
            $property = Package::findOrFail($request->property_id);
            $property->status = $request->status;
            $property->save();

            if ($request->status == 0){
                $title = 'Pending';
            }elseif ($request->status == 1){
                $title = 'Approved';
            }elseif ($request->status == 2){
                $title = 'Resubmitted';
            }elseif(request()->status == 3){
                $title = 'Holded';
            }elseif (request()->status == 4){
                $title = 'Soft Rejected';
            }elseif (request()->status == 5){
                $title = 'Hard Rejected';
            }else{
                throw new \Exception('Invalid status.');
            }

            $admin = Auth::user();

            $activity = new ActivityLog();
            $activity->title = $title;
            $activity->property_id = $request->property_id;
            $activity->description = $request->comments;

            $admin->activities()->save($activity);
            DB::commit();

            $user = $property->owner;
            $msg = [
                'title' => $property->title,
                'status' => $title,
            ];
            $action = [
                "link" => route('package.details', $property->slug),
                "icon" => "fa fa-money-bill-alt text-white"
            ];
            $this->userPushNotification($user, 'PACKAGE_APPROVE', $msg, $action);

            $this->sendMailSms($user, 'PACKAGE_APPROVE', [
                'title' => $property->title,
                'status' => $title,
            ]);

            return back()->with('success', 'Update Successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function discount(Request $request, $id)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'discount_type' => ['required', 'in:0,1',],
        ]);
        try {
            $package = Package::where('id', $id)->firstOr(function () {
                throw new \Exception('Package not found.');
            });

            $package->discount = 1;
            $package->discount_type = $request->discount_type;
            $package->discount_amount = $request->amount;
            $package->save();

            return back()->with('success', 'Discount Added Successfully.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function status($id)
    {
        try {
            $package = Package::select('id', 'status')
                ->where('id', $id)
                ->firstOr(function () {
                    throw new \Exception('Package not found.');
                });

            $package->status = ($package->status == 1) ? 0 : 1;
            $package->save();

            return back()->with('success', 'Package Status Changed Successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function inactiveMultiple(Request $request)
    {
        if (!$request->has('strIds') || empty($request->strIds)) {
            session()->flash('error', 'You did not select any data.');
            return response()->json(['error' => 1]);
        }

        Package::select(['id', 'status'])->whereIn('id', $request->strIds)->get()->each(function ($package) {
            $package->status = ($package->status == 0) ? 1 : 0;
            $package->save();
        });

        session()->flash('success', 'Package status changed successfully');

        return response()->json(['success' => 1]);
    }

    public function packageSEO($id)
    {
        $data['packageSEO'] = Package::findOrFail($id);
        return view("admin.package.seo", $data);
    }
    public function packageSeoUpdate(Request $request, $id)
    {
        $request->validate([
            'page_title' => 'required|string|min:3|max:100',
            'meta_title' => 'required|string|min:3|max:100',
            'meta_keywords' => 'required|array',
            'meta_keywords.*' => 'required|string|min:1|max:1000',
            'meta_description' => 'required|string|min:1|max:1000',
            'og_description' => 'required|string|min:1|max:1000',
            'meta_image' => 'sometimes|required|mimes:jpeg,png,jpeg|max:2048'
        ]);

        try {
            $pageSEO = Package::findOrFail($id);

            if ($request->hasFile('meta_image')) {

                try {
                    $image = $this->fileUpload($request->meta_image, config('filelocation.seo.path'), null, null, 'webp', 60, $pageSEO->seo_meta_image, $pageSEO->seo_meta_image_driver);
                    throw_if(empty($image['path']), 'Image path not found');
                } catch (\Exception $exp) {
                    return back()->with('error', 'Meta image could not be uploaded.');
                }
            }

            $pageSEO->update([
                'page_title' => $request->page_title,
                'meta_title' => $request->meta_title,
                'meta_keywords' => $request->meta_keywords,
                'meta_description' => $request->meta_description,
                'og_description' => $request->og_description,
                'meta_robots' => $request->meta_robots,
                'meta_image' => $image['path'] ?? $pageSEO->meta_image,
                'meta_image_driver' => $image['driver'] ?? $pageSEO->meta_image_driver,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Seo has been updated.');

    }

    public function featuredAction(Request $request)
    {
        try {
            $property = Package::findOrFail($request->property_id);
            $property->is_featured = $request->confirm;
            $property->save();

            if ($property->is_featured == 1) {
                $message = 'Package has been added to featured property.';
            }elseif ($property->is_featured == 3) {
                $message = 'Featured request rejected by admin.';
            }

            $user = $property->owner;
            $msg = [
                'title' => $property->title,
                'message' => $message
            ];
            $action = [
                "link" => '#',
                "icon" => "fa fa-money-bill-alt text-white"
            ];
            $this->userPushNotification($user, 'FEATURED_STATUS', $msg, $action);

            $this->sendMailSms($user, 'FEATURED_STATUS', [
                'title' => $property->title,
                'message' => $message
            ]);

            return back()->with('success', $message);

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
