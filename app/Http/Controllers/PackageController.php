<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use App\Models\Booking;
use App\Models\Chat;
use App\Models\Content;
use App\Models\Country;
use App\Models\Destination;
use App\Models\FavouriteList;
use App\Models\Guide;
use App\Models\Language;
use App\Models\Package;
use App\Models\PackageCategory;
use App\Models\PackageVisitor;
use App\Models\Page;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use phpseclib3\File\ASN1\Maps\EncryptedData;

class PackageController extends Controller
{
    public function packageList(Request $request)
    {
        try {
            $data['categories'] = PackageCategory::where('status', 1)->withCount('packages')->get();

            $seoData = Page::where('name', 'packages')->select(['page_title','meta_title','meta_keywords','meta_description','og_description','meta_robots','meta_image_driver','meta_image','breadcrumb_status','breadcrumb_image','breadcrumb_image_driver'])->first();

            $data['pageSeo'] = [
                'page_title' => $seoData->page_title ?? '',
                'meta_title' => $seoData->meta_title,
                'meta_keywords' => implode(',', $seoData->meta_keywords ?? []),
                'meta_description' => $seoData->meta_description,
                'og_description' => $seoData->og_description,
                'meta_robots' => $seoData->meta_robots,
                'meta_image' => $seoData
                    ? getFile($seoData->meta_image_driver, $seoData->meta_image)
                    : null,
                'breadcrumb_status' => $seoData->breadcrumb_status ?? null,
                'breadcrumb_image' => $seoData->breadcrumb_status
                    ? getFile($seoData->breadcrumb_image_driver, $seoData->breadcrumb_image)
                    : null,
            ];

            $baseQuery = Package::with([
                'category:id,name',
                'review:id,rating,package_id,avg_rating',
                'countryTake:id,name',
                'stateTake:id,name',
                'cityTake:id,name',
                'destination:id,title,slug',
                'owner.vendorInfo'
            ])
                ->where('status', 1)
                ->whereHas('owner.vendorInfo', function ($query) {
                    $query->where(function ($q) {
                        $q->where(function ($sub) {
                            $sub->whereNotNull('active_plan')
                                ->where('current_plan_expiry_date', '>=', Carbon::now());
                        })
                            ->orWhere(function ($sub) {
                                $sub->whereNull('active_plan')
                                    ->where('posted_listing', 1)
                                    ->whereNull('current_plan_expiry_date');
                            });
                    });
                })
                ->orderByRaw("CASE
                    WHEN is_featured = 1 THEN 0
                    ELSE 1
                END");


            $priceQuery = (clone $baseQuery);
            $filterQuery = (clone $baseQuery);

            $prices = $priceQuery->pluck('adult_price');
            $rangeMin = $prices->min() ?? 10;
            $rangeMax = $prices->max() ?? 1000;

            $data['max'] = $request->has('max_price') ? $request->max_price : $rangeMax;
            $data['min'] = $request->has('min_price') ? $request->min_price : $rangeMin;
            $data['rangeMin'] = $rangeMin;
            $data['rangeMax'] = $rangeMax;

            $paginate = (getTourListStyle() == 'listThree') ? 9 : basicControl()->user_paginate;

            $data['packages'] = $filterQuery
                ->when($request->destination, function ($q) use ($request) {
                    $q->whereHas('destination', function ($q2) use ($request) {
                        $q2->where(function ($subQuery) use ($request) {
                            $subQuery->where('slug', 'like', "%{$request->destination}%")
                                ->orWhere('title', 'like', "%{$request->destination}%");
                        });
                    });
                })
                ->when($request->category, function ($q) use ($request) {
                    $q->whereHas('category', fn($q) => $q->where('name', 'like', '%' . kebab2Title($request->type) . '%'));
                })
                ->when($request->search, function ($q) use ($request) {
                    $q->where('title', 'like', "%{$request->search}%");
                })
                ->when($request->duration, function ($q) use ($request) {
                    $q->where('duration', 'like', "%{$request->duration}%");
                })
                ->when($request->slot, function ($q) use ($request) {
                    $q->whereRaw("JSON_CONTAINS(timeSlot, '\"{$request->slot}\"')");
                })
                ->when($request->country, function ($q) use ($request) {
                    $q->whereHas('countryTake', function ($q2) use ($request) {
                        $q2->where('iso2', $request->country);
                    });
                })
                ->when($request->min_price || $request->max_price, function ($q) use ($request) {
                    $q->whereBetween('adult_price', [$request->min_price, $request->max_price]);
                })
                ->when($request->filled('amenities'), function ($q) use ($request) {
                    $amenities = is_array($request->amenities)
                        ? $request->amenities
                        : explode(',', $request->amenities);

                    $q->where(function ($sub) use ($amenities) {
                        foreach ($amenities as $amenity) {
                            $sub->orWhereJsonContains('amenities->amenity', $amenity)
                                ->orWhereJsonContains('amenities->favourites', $amenity)
                                ->orWhereJsonContains('amenities->safety_item', $amenity);
                        }
                    });
                })
                ->with(['reviewSummary'])
                ->paginate($paginate);

            $data['packages']->getCollection()->transform(function ($package) {
                $package->average_rating = $package->reviewSummary->average_rating ?? 0;
                $package->review_count = $package->reviewSummary->review_count ?? 0;

                return $package;
            });

            $data['destinations'] = Destination::where('status', 1)->get();
            $data['countries'] = Country::where('status', 1)->get();
            $data['amenities'] = Amenity::where('status', 1)->get();

            return view(template() . 'frontend.package.' . getTourListStyle(), $data);
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong, Please try again.');
        }
    }
    public function packageDetails(Request $request, $slug = null)
    {

        try {
            $data['package'] = Package::withAllRelations()
                ->where('slug', $slug)
                ->withCount('reviews')
                ->firstOr(function () {
                    throw new \Exception('The package was not found.');
                });

            $data['combinedAmenityId'] = array_merge(
                $data['package']->amenities->amenity ?? [],
                $data['package']->amenities->favourites ?? [],
                $data['package']->amenities->safety_item ?? []
            );

            $data['package']->allAmenity = Amenity::select(['id','title','icon'])->whereIn('id', $data['combinedAmenityId'])->get();

            $seoData = Page::where('name', 'packages')->select(['page_title','meta_title','meta_keywords','meta_description','og_description','meta_robots','meta_image_driver','meta_image','breadcrumb_status','breadcrumb_image','breadcrumb_image_driver'])->first();

            $data['pageSeo'] = [
                'page_title' => 'Package Details',
                'meta_title' => $data['package']->meta_title,
                'meta_keywords' => implode(',', $data['package']->meta_keywords ?? []),
                'meta_description' => $data['package']->meta_description,
                'og_description' => $data['package']->og_description,
                'meta_robots' => $data['package']->meta_robots,
                'meta_image' => $data['package']
                    ? getFile($data['package']->meta_image_driver, $data['package']->meta_image)
                    : null,
                'breadcrumb_status' => $seoData->breadcrumb_status ?? null,
                'breadcrumb_image' => $seoData->breadcrumb_status
                    ? getFile($seoData->breadcrumb_image_driver, $seoData->breadcrumb_image)
                    : null,
            ];

            $data['bookingDate'] = $data['package']->getBookingDates();
            $data['booking'] = Auth::check()
                ? Auth::user()->getBookingForPackage($data['package']->id)
                : null;

            $totals = [
                'services' => 0,
                'safety' => 0,
                'guides' => 0,
                'foods' => 0,
                'hotel' => 0,
                'places' => 0,
            ];
            $count = 0;

            foreach ($data['package']->reviews as $review) {
                $rating = $review->rating;

                if (is_object($rating)) {
                    $rating = (array) $rating;
                }

                if (is_array($rating)) {
                    $totals['services'] += $rating['services'] ?? 0;
                    $totals['safety'] += $rating['safety'] ?? 0;
                    $totals['guides'] += $rating['guides'] ?? 0;
                    $totals['foods'] += $rating['foods'] ?? 0;
                    $totals['hotel'] += $rating['hotel'] ?? 0;
                    $totals['places'] += $rating['places'] ?? 0;
                    $count++;
                }
            }

            $averages = [];

            if ($count > 0) {
                foreach ($totals as $key => $total) {
                    $averages[$key] = round($total / $count, 2);
                }
            }

            $data['average_ratings'] = $averages;
            $guides = collect();
            if (!empty($data['package']->guides) && is_iterable($data['package']->guides)) {

                foreach ($data['package']->guides as $guide) {
                    $guides->push(Guide::where('code', $guide)->first());
                }
            }

            $chatData = [];
            if (auth()->check()) {
                $chatData = Chat::with(['reply'])->where('user_id', auth()->id())->where('package_id', $data['package']->id)->first();
            }


            $data['categories'] = PackageCategory::orderBy('id', 'DESC')->where('status', 1)->get();

            return view(template() . 'frontend.package.details', $data, compact('data', 'guides','chatData'));
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong, Please try again.');
        }
    }

    public function packageSearch(Request $request)
    {
        $segmentLimit = (getTourListStyle() == 'listThree') ? 9 : basicControl()->user_paginate;
        $currentSegment = $request->input('segment', 1);
        $offset = ($currentSegment - 1) * $segmentLimit;

        $query = Package::with([
            'category:id,name',
            'review:id,rating,package_id,avg_rating',
            'countryTake:id,name',
            'stateTake:id,name',
            'cityTake:id,name',
            'reviewSummary'
        ])
            ->where('status', 1)
            ->when($request->destination, function ($q) use ($request) {
                $q->whereHas('destination', function ($q) use ($request) {
                    $q->where('slug', 'like', "%{$request->destination}%");
                });
            })
            ->when($request->min_price || $request->max_price, function ($q) use ($request) {
                $q->whereBetween('adult_price', [$request->min_price, $request->max_price]);
            })
            ->when($request->category, function ($q) use ($request) {
                $q->whereHas('category', fn($q) => $q->where('name', 'like', '%' . kebab2Title($request->type) . '%'));
            })
            ->when($request->country, function ($q) use ($request) {
                $q->whereHas('countryTake', function ($q2) use ($request) {
                    $q2->where('iso2', $request->country);
                });
            })
            ->when($request->filled('amenities'), function ($q) use ($request) {
                $amenities = is_array($request->amenities)
                    ? $request->amenities
                    : explode(',', $request->amenities);

                $q->where(function ($sub) use ($amenities) {
                    foreach ($amenities as $amenity) {
                        $sub->orWhereJsonContains('amenities->amenity', $amenity)
                            ->orWhereJsonContains('amenities->favourites', $amenity)
                            ->orWhereJsonContains('amenities->safety_item', $amenity);
                    }
                });
            })
            ->orderByRaw("CASE WHEN is_featured = 1 THEN 0 ELSE 1 END")
            ->when(isset($request->sort_by), function ($q) use ($request) {
                switch ($request->sort_by) {
                    case 'htl':
                        $q->orderBy('adult_price', 'desc');
                        break;
                    case 'lth':
                        $q->orderBy('adult_price', 'asc');
                        break;
                    case 'asc':
                        $q->orderBy('created_at', 'asc');
                        break;
                    case 'desc':
                        $q->orderBy('created_at', 'desc');
                        break;
                    case 'mpv':
                        $q->orderBy('view_count', 'desc');
                        break;
                    case 'mps':
                        $q->orderBy('total_sell', 'desc');
                        break;
                }
            });

        $totalCount = $query->count();
        $packages = $query->skip($offset)->take($segmentLimit)->get();

        $packages->transform(function ($package) {
            $package->imageUrl = getFile($package->thumb_driver, $package->thumb);
            $package->detailsUrl = route('package.details', ['slug' => $package->slug]);
            $package->formatedPrice = currencyPosition($package->adult_price);
            $package->formatedDiscountPrice = discountPrice($package);
            $package->countryName = $package->countryTake?->name;
            $package->address = $package->countryTake?->name.', '.$package->stateTake?->name.', '.$package->cityTake?->name;;

            $places = is_array($package->places) ? $package->places : json_decode($package->places, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($places)) {
                $package->place_count = count($places);
            }

            $imagesUrl = [];
            if (!empty($package->media)) {
                foreach ($package->media as $media) {
                    $imagesUrl[] = getFile($media->driver, $media->image);
                }
            }
            $package->imagesUrl = $imagesUrl;

            return $package;
        });

        return response()->json([
            'packages' => $packages,
            'has_more' => $offset + $segmentLimit < $totalCount,
            'next_segment' => $currentSegment + 1
        ]);
    }

    public function packageAuthor(Request $request, $slug)
    {
        try {
            $data['author'] = User::with(['packages','vendorInfo:id,vendor_id,avg_rating'])
            ->withCount('packages')
                ->where('role', 1)
                ->where('slug', $slug)
                ->firstOr(function () {
                    throw new \Exception('This Author is not available now');
                });

            $data['reviews'] = $data['author']->reviews()->get();

            $totals = [
                'services' => 0,
                'safety' => 0,
                'guides' => 0,
                'foods' => 0,
                'hotel' => 0,
                'places' => 0,
            ];
            $count = 0;

            foreach ($data['reviews'] as $review) {
                $rating = $review->rating;

                if (is_object($rating)) {
                    $rating = (array) $rating;
                }

                if (is_array($rating)) {
                    $totals['services'] += $rating['services'] ?? 0;
                    $totals['safety'] += $rating['safety'] ?? 0;
                    $totals['guides'] += $rating['guides'] ?? 0;
                    $totals['foods'] += $rating['foods'] ?? 0;
                    $totals['hotel'] += $rating['hotel'] ?? 0;
                    $totals['places'] += $rating['places'] ?? 0;
                    $count++;
                }
            }

            $averages = [];

            foreach ($totals as $key => $total) {
                $averages[$key] = $count > 0 ? round($total / $count, 2) : 0.00;
            }

            $data['average_ratings'] = $averages;
            $guides = collect();
            if (!empty($data['author']->packages) && is_iterable($data['author']->packages)) {

                foreach ($data['author']->packages as $package) {
                    $guides = $package->guide_models;
                }
            }

            return view(template().'frontend.package.author', $data, compact('guides','count'));
        }catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }

    }
}
