<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Amenity;
use App\Models\City;
use App\Models\Country;
use App\Models\Destination;
use App\Models\GoogleMapApi;
use App\Models\Package;
use App\Models\PackageCategory;
use App\Models\Plan;
use App\Models\Review;
use App\Models\State;
use App\Models\VendorInfo;
use App\Services\GeminiService;
use App\Services\OpenAiService;
use App\Traits\Notify;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class PackageController extends Controller
{
    use Upload, Notify;

    public function list(Request $request)
    {
        $userId = auth()->id();
        $now = Carbon::now();

        $startOfWeek = $now->copy()->startOfWeek();
        $startOfMonth = $now->copy()->startOfMonth();

        $query = Package::where('owner_id', $userId);
        $total = (clone $query)->count();

        $thisMonth = (clone $query)->where('created_at', '>=', $startOfMonth)->count();
        $thisWeek = (clone $query)->where('created_at', '>=', $startOfWeek)->count();
        $active = (clone $query)->where('status', 1)->count();
        $inactive = (clone $query)->where('status', '!=', 1)->count();

        $data['percent'] = [
            'this_month' => $total ? round(($thisMonth / $total) * 100, 2) : 0,
            'this_week' => $total ? round(($thisWeek / $total) * 100, 2) : 0,
            'active' => $total ? round(($active / $total) * 100, 2) : 0,
            'inactive' => $total ? round(($inactive / $total) * 100, 2) : 0,
        ];

        $data['count'] = [
            'total' => $total,
            'this_month' => $thisMonth,
            'this_week' => $thisWeek,
            'active' => $active,
            'inactive' => $inactive,
        ];

        $data['guide'] = $request->guideCode ?? null;
        return view(template().'user.package.list', $data);
    }

    public function search(Request $request)
    {
        $search = trim(str_replace(['%', '_'], '', $request->search['value'] ?? ''));
        $filterName = $request->filterName;
        $filterStart = $request->startDate;
        $filterEnd = $request->endDate;
        $destination = $request->destination;
        $category = $request->category;
        $guideCode = $request->guideCode;
        $filterStatus = $request->input('filterStatus');


        $packages = Package::query()->with(['category', 'destination:id,title', 'countryTake', 'stateTake', 'cityTake','latestActivity.activityable:id,username,image,image_driver'])
            ->where('owner_id', auth()->id())
            ->orderBy('id', 'DESC')
            ->when(!empty($search), function ($query) use ($search) {
                $query->where('title', 'LIKE', "%{$search}%");
            })
            ->when(!empty($filterName), function ($query) use ($filterName) {
                $query->where('title', 'LIKE', "%{$filterName}%");
            })
            ->when(isset($filterStart) && !empty($filterStart), function ($query) use ($filterStart) {
                return $query->whereDate('created_at', '>=', $filterStart);
            })
            ->when(isset($filterEnd) && !empty($filterEnd), function ($query) use ($filterEnd) {
                return $query->whereDate('created_at', '<=', $filterEnd);
            })
            ->when(isset($destination), function ($query) use ($destination) {
                return $query->where('destination_id', $destination);
            })
            ->when(isset($category), function ($query) use ($category) {
                return $query->where('package_category_id', $category);
            })
            ->when(isset($guideCode), function ($query) use ($guideCode) {
                return $query->whereJsonContains('guides', $guideCode);
            })
            ->when(isset($filterStatus), function ($query) use ($filterStatus) {
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
                $detail_route = route('package.details', $item->slug);
                $image = $item->thumb;
                $title = html_entity_decode($item->title);
                $shortTitle = strlen($title) > 20 ? substr($title, 0, 30) . '...' : $title;

                if (!$image) {
                    $firstLetter = substr($title, 0, 1);
                    return '<div class="avatar avatar-sm avatar-soft-primary avatar-circle d-flex justify-content-start gap-2 w-100" title="' . $title . '">
                    <span class="avatar-initials">' . e($firstLetter) . '</span>
                    <p class="avatar-initials ms-3 mb-0" title="' . $title . '">' . e($shortTitle) . '</p>
                </div>';
                } else {
                    $url = getFile($item->thumb_driver, $item->thumb);

                    return '<a class="d-flex align-items-center me-2" href="'.$detail_route.'" title="' . $title . '">
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

            ->addColumn('category', function ($item) {
                return optional($item->category)->name;
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
                $user = auth()->user();
                $vendorInfo = $user->vendorInfo;
                $plan = $vendorInfo->plan ?? null;

                $activity = $item->latestActivity;

                $editUrl = route('user.package.edit', $item->id);
                $reviewUrl = route('user.review.list', ['slug' => $item->slug]);
                $seoUrl = route('user.package.seo', $item->id);
                $featuredUrl = route('user.package.featured.request', $item->id);
                $discountUrl = route("user.package.discount", $item->id);
                $deleteUrl = route("user.package.delete", $item->id);

                $decodedAmenities = is_string($item->amenities) ? json_decode($item->amenities) : $item->amenities;
                $allIds = array_merge($decodedAmenities->amenity ?? [], $decodedAmenities->favourites ?? [], $decodedAmenities->safety_item ?? []);

                $allIds = array_unique($allIds);
                $amenityData = [];
                $amenityData = Amenity::whereIn('id', $allIds)->get();

                $featureBtn = '';
                if (
                    isset($vendorInfo->current_plan_expiry_date, $plan->featured_listing) &&
                    $vendorInfo->current_plan_expiry_date > now() &&
                    $plan->featured_listing > $user->featuredPackages->count() &&
                    $item->is_featured == 0 &&
                    $item->status == 1
                ) {
                    $featureBtn = '
                    <a class="dropdown-item featuredBtn" href="javascript:void(0)"
                       data-route="' . $featuredUrl . '"
                       data-package_id="' . $item->id . '"
                       data-bs-toggle="modal"
                       data-bs-target="#featuredModal">
                        <i class="bi bi-award"></i> ' . trans("Featured Request") . '
                    </a>';
                }
                return '
                    <div class="btn-group" role="group">
                        <a class="btn btn-white btn-sm edit_user_btn viewBtn"
                                href="javascript:void(0)"
                                data-item="' . htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8') . '"
                                data-thumbImage = "' . getFile($item->thumb_driver, $item->thumb) . '"
                                data-amenity_data="' . htmlspecialchars(json_encode($amenityData), ENT_QUOTES, 'UTF-8') . '"
                                data-activity="'. htmlspecialchars(json_encode($activity), ENT_QUOTES, 'UTF-8') .'"
                                data-bs-toggle="modal"
                                data-bs-target="#viewModal">
                            <i class="bi-eye me-1"></i> ' . trans("View") . '
                        </a>
                        <div class="btn-group">
                            <button type="button" class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty"
                                id="userEditDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            </button>
                            <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="userEditDropdown">
                                <a class="dropdown-item" href="' . $editUrl . '">
                                    <i class="bi-pencil-square me-1"></i> ' . trans("Edit") . '
                                </a>
                                <a class="dropdown-item discountBtn" href="javascript:void(0)"
                                   data-route="' . $discountUrl . '"
                                   data-discount_type="' . $item->discount_type . '"
                                   data-discount_amount="' . $item->discount_amount . '"
                                   data-bs-toggle="modal" data-bs-target="#discountModal">
                                    <i class="bi bi-currency-dollar me-1"></i> ' . trans("Discount") . '
                                </a>
                                ' . $featureBtn . '
                                <a class="dropdown-item" href="' . $reviewUrl . '">
                                    <i class="bi-journal-check me-1"></i> ' . trans("Reviews") . '
                                </a>
                                <a class="dropdown-item" href="' . $seoUrl . '">
                                    <i class="fa-light fa-magnifying-glass me-1"></i> ' . trans("Seo") . '
                                </a>

                                <a class="dropdown-item deleteBtn" href="javascript:void(0)"
                                   data-route="' . $deleteUrl . '"
                                   data-bs-toggle="modal" data-bs-target="#deleteModal">
                                    <i class="bi bi-trash me-1"></i> ' . trans("Delete") . '
                                </a>
                            </div>
                        </div>
                    </div>';
            })->rawColumns(['action', 'checkbox', 'create-at', 'package', 'status', 'category', 'destination'])
            ->make(true);
    }



    public function add()
    {
        $data['categories'] = PackageCategory::select('id', 'name')->where('status', 1)->get();
        $data['destinations'] = Destination::select('id', 'title','place')->where('status', 1)->orderBy('title','ASC')->get();
        $data['amenities'] = Amenity::where('status', 1)->get();

        $data['vendor'] = VendorInfo::with('plan')->where('vendor_id', auth()->id())->first();
        $data['freeLimit'] = basicControl()->free_listing;

        return view(template().'user.package.add', $data);
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'adult_price' => 'required|numeric|min:1',
            'children_price' => 'required|numeric|min:1',
            'infant_price' => 'required|numeric|min:1',
            'destination_id' => 'required|integer|exists:destinations,id',
            'tourDuration' => 'required|string',
            'category_id' => 'required|integer|exists:package_categories,id',
            'full_address' => 'required|string|max:255',
            'minimumTravelers' => 'required|integer|min:1',
            'maximumTravelers' => 'required|integer|gte:minimumTravelers',
            'facility.*' => 'nullable',
            'excluded.*' => 'nullable',
            'expect.*' => 'nullable',
            'timeSlot.*' => 'nullable',
            'places' => 'required|array',
            'guides' => 'required|array',
            'amenities_id' => 'required|array',
            'lat' => 'required',
            'long' => 'required',
            'expect_details.*' => 'nullable',
            'slug' => 'required|string|unique:packages,slug|max:255',
            'details' => 'required|string',
            'thumb' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'video' => 'nullable',
            'images' => 'required|array',
            'images.*' => 'required|mimes:jpeg,png,jpg|max:10240'
            ]);

        try {

            $vendor = VendorInfo::where('vendor_id', auth()->id())->first();
            $freeLimit = basicControl()->free_listing ?? 1;

            if (basicControl()->free_listing  <= 0 && empty($vendor->active_plan) ) {
                return back()->with('error', 'You do not have any free listings left. To create a package, please purchase a plan.');
            }

            if ($vendor->posted_listing >= $freeLimit) {
                if (!empty($vendor->active_plan)) {

                    if ($vendor->current_plan_expiry_date <= now()) {
                        return back()->with('error', 'Your plan has expired. Please renew your plan to continue posting.');
                    }

                    $plan = Plan::find($vendor->active_plan);
                    if (!$plan) {
                        return back()->with('error', 'Active plan not found. Please contact support or purchase a valid plan.');
                    }

                    $remainingListings = $plan->listing_allowed - $vendor->current_plan_posted_listing;

                    if ($remainingListings <= 0) {
                        return back()->with('error', 'You have used all your paid listings. Please upgrade or buy a new plan to continue posting.');
                    }

                } else {
                    return back()->with('error', 'To post more listings, please purchase a plan. You have used your free listing.');
                }
            }

            $thumbData = $this->handleFileUpload($request->file('thumb'), 'package_thumb');

            $destination = Destination::where('id', $request->destination_id)->firstOr(function () {
                throw new \Exception('Destination not found.');
            });

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

            $package = $this->createPackage($validatedData, $destination, $thumbData, $formattedAmenities);

            $this->handleImagesUpload($request->images, $package);

            $vendor->posted_listing += 1;
            $vendor->current_plan_posted_listing += 1;
            $vendor->save();

            return back()->with('success', 'Package added successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    private function handleFileUpload($file, $type)
    {
        $photo = $this->fileUpload($file, config("filelocation.{$type}.path"), null, config("filelocation.{$type}.size"), 'webp', 80);
        return ['path' => $photo['path'], 'driver' => $photo['driver']];
    }

    private function createPackage($data, $destination, $thumbData, $formattedAmenities)
    {
        return Package::create([
            'package_category_id' => $data['category_id'],
            'owner_id' => auth()->id(),
            'destination_id' => $destination->id,
            'title' => $data['name'],
            'slug' => $data['slug'],
            'adult_price' => $data['adult_price'],
            'children_Price' => $data['children_price'],
            'infant_price' => $data['infant_price'],
            'duration' => $data['tourDuration'],
            'address' => $data['full_address'],
            'minimumTravelers' => $data['minimumTravelers'],
            'maximumTravelers' => $data['maximumTravelers'],
            'facility' => $data['facility'],
            'excluded' => $data['excluded'],
            'amenities' => $formattedAmenities,
            'places' => $data['places'],
            'guides' => $data['guides'],
            'timeSlot' => $data['timeSlot'],
            'expected' => $this->formatExpectations($data['expect'], $data['expect_details']),
            'description' => $data['details'],
            'lat' => $data['lat'],
            'long' => $data['long'],
            'thumb' => $thumbData['path'],
            'thumb_driver' => $thumbData['driver'],
            'video' => $data['video'],
            'city' => $destination->city,
            'state' => $destination->state,
            'country' => $destination->country,
        ]);
    }

    private function handleImagesUpload($images, $package)
    {
        foreach ($images as $img) {
            $image = $this->fileUpload($img, config('filelocation.package.path'), null, config('filelocation.package.size'), 'webp', 80);
            $package->media()->updateOrCreate([
                'image' => $image['path'],
                'driver' => $image['driver'],
            ]);
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
                'amenities' => Amenity::where('status', 1)->get(),
                'vendor' => VendorInfo::with('plan')->where('vendor_id', auth()->id())->first()
            ];

            return view(template().'user.package.edit', $data);
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
            'expect_details.*' => 'nullable',
            'details' => 'required|string',
            'thumb' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:2048',
            'video' => 'sometimes|nullable',
            'preloaded' => 'sometimes|array',
            'images' => ['required_without:preloaded', 'array'],
            'images.*' => ['required_without:preloaded', 'image', 'mimes:jpeg,png,jpg', 'max:10240'],
        ]);

        try {
            $vendor = VendorInfo::with('user')->where('vendor_id', auth()->id())->first();
            if (empty($vendor->active_plan) ) {
                return back()->with('error', 'You Have not any active plan. To create a package, please purchase a plan.');
            }

            if (!empty($vendor->active_plan)) {
                if ($vendor->current_plan_expiry_date <= now()) {
                    return back()->with('error', 'Your plan has expired. Please renew your plan to continue posting.');
                }
            }

            $package = Package::where('id', $id)->where('owner_id', auth()->id())->firstOr(function () {
                throw new \Exception('Destination not found.');
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
            $package->status = 2;
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

            $user = auth()->user();

            $activity = new ActivityLog();
            $activity->title = 'Resubmitted';
            $activity->property_id = $package->id;
            $activity->description = 'Resubmitted Package';;
            $user->activities()->save($activity);

            return back()->with('success', 'Package updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
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

    public function packageSEO($id)
    {
        $data['packageSEO'] = Package::findOrFail($id);
        return view(template()."user.package.seo", $data);
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

    public function featuredRequest(Request $request)
    {

        try {

            if ($request->confirm == 1){
                $package = Package::select(['id','status','is_featured','title','slug'])->where('owner_id', auth()->id())->where('id', $request->package_id)->where('status', 1)->firstOr(function () {
                    throw new \Exception('Package not found.');
                });

                if ($package->is_featured == 2){
                    return back()->with('error', 'Previous request is pending.');
                }
                if ($package->is_featured == 3){
                    return back()->with('error', 'You cannot make this package as featured package.');
                }
                if ($package->is_featured == 1){
                    return back()->with('error', 'Already Featured Package.');
                }

                if (auth()->user()->vendorInfo->current_plan_expiry_date < now() &&
                    auth()->user()->vendorInfo->plan->featured_listing <= auth()->user()->featuredPackages->count()){
                    return back()->with('error', 'You cannot request featured package.');
                }

                $package->is_featured = 2;
                $package->save();

                $params = [
                    'username' => auth()->user()->username,
                    'package' => $package->title,
                ];
                $actionAdmin = [
                    "name" => auth()->user()->firstname . ' ' . auth()->user()->lastname,
                    "image" => getFile(auth()->user()->image_driver, auth()->user()->image),
                    "link" => route('admin.package.edit', $package->id),
                    "icon" => "fas fa-ticket-alt text-white"
                ];

                $this->adminMail('FEATURED_REQUEST', $params, $actionAdmin);
                $this->adminPushNotification('FEATURED_REQUEST', $params, $actionAdmin);
                $this->adminFirebasePushNotification('FEATURED_REQUEST', $params);
            }

            return back()->with('success', 'Featured request has been sent.');
        }catch (\Exception $exp) {
            return back()->with('error', $exp->getMessage());
        }
    }

    public function generate(Request $request)
    {
        if (isAiAccess()){
            $basicControl = basicControl();
            if ($basicControl->open_ai_status) {
                $openAiService = new OpenAiService();
                $res = $openAiService->generateRes($request);
            } elseif ($basicControl->gemini_status) {
                $geminiService = new GeminiService();
                $res = $geminiService->generateRes($request);
            }


        }else {
            $res = [
                'success' => false,
                'message' => 'Access to AI services is denied.',
            ];
        }

        return response()->json($res);
    }

    public function generateImage(Request $request)
    {
        if (isAiAccess()){
            $basicControl = basicControl();
            if ($basicControl->open_ai_status) {
                $openAiService = new OpenAiService();
                $res = $openAiService->generateImage($request);
            } elseif ($basicControl->gemini_status) {
                $geminiService = new GeminiService();
                $res = $geminiService->generateImage($request);
            }
        }else {
            $res = [
                'success' => false,
                'message' => 'Access to AI services is denied.',
            ];
        }

        return response()->json($res);
    }
}
