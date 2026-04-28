<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Guide;
use App\Models\Package;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'rating_services' => 'nullable|numeric|min:1|max:5',
            'rating_hotel' => 'nullable|numeric|min:1|max:5',
            'rating_places' => 'nullable|numeric|min:1|max:5',
            'rating_safety' => 'nullable|numeric|min:1|max:5',
            'rating_foods' => 'nullable|numeric|min:1|max:5',
            'rating_guides' => 'nullable|numeric|min:1|max:5',
            'guide' => 'nullable|string',
            'package_id' => 'nullable|exists:packages,id',
            'vendor_id' => 'nullable|exists:users,id',
            'parent_review_id' => 'nullable|exists:reviews,id',
        ]);

        DB::beginTransaction();

        try {
            $user = auth()->user();

            if ($request->package_id) {
                $package = Package::select(['id', 'owner_id'])->where('id', $request->package_id)
                    ->firstOr(function () {
                        throw new \Exception('Package not found.');
                    });

                if ($package->owner_id == $user->id) {
                    return back()->with('error', 'You cannot review your own package.');
                }

                $hasBooking = $user->forReviewBooking()
                    ->where('package_id', $request->package_id)
                    ->exists();

                if (! $hasBooking) {
                    throw new \Exception('You do not have a booking to review for this package.');
                }
            }

            if ($request->vendor_id) {
                if ($request->vendor_id == $user->id) {
                    return back()->with('error', 'You cannot review your own vendor profile.');
                }

                $hasBooking = $user->forReviewBooking()
                    ->whereHas('package', function ($query) use ($request) {
                        $query->where('owner_id', $request->vendor_id);
                    })
                    ->exists();

                if (! $hasBooking) {
                    throw new \Exception('You do not have a booking to review for this vendor.');
                }
            }

            $data = $request->only([
                'rating_services',
                'rating_hotel',
                'rating_places',
                'rating_safety',
                'rating_foods',
                'rating_guides',
            ]);
            $validRatings = array_filter($data, fn($value) => !is_null($value));
            $ratings = [];

            foreach ($validRatings as $key => $value) {
                $ratings[str_replace('rating_', '', $key)] = (int)$value;
            }

            $average = count($ratings) > 0 ? round(array_sum($ratings) / count($ratings), 1) : 0;

            $reviewData = [
                'parent_review_id' => $request->parent_review_id,
                'rating' => $ratings,
                'guide' => $request->guide,
                'avg_rating' => $average,
                'comment' => $request->message,
            ];

        $userId = auth()->id();

        $reviewConditions = [
            'user_id' => $userId,
        ];

        if ($request->filled('package_id')) {
            $reviewConditions['package_id'] = $request->package_id;
        }

        if ($request->filled('vendor_id')) {
            $reviewConditions['vendor_id'] = $request->vendor_id;
        }

            $review = Review::updateOrCreate($reviewConditions, $reviewData);

            if ($request->vendor_id && is_null($request->parent_review_id)) {
                $vendor = User::with('vendorInfo')->find($request->vendor_id);
                if ($vendor && $vendor->vendorInfo) {
                    $oldRating = $vendor->vendorInfo->avg_rating ?? 0;
                    $vendor->vendorInfo->avg_rating = $oldRating == 0 ? $average : round(($oldRating + $average) / 2, 1);
                    $vendor->vendorInfo->save();
                }
            }

            if (is_null($request->vendor_id) && is_null($request->parent_review_id)) {
                $package = Package::find($request->package_id);
                if ($package) {
                    $oldRating = $package->avg_rating ?? 0;
                    $package->avg_rating = $oldRating == 0 ? $average : round(($oldRating + $average) / 2, 1);
                    $package->save();
                }

                if ($request->guide && isset($data['rating_guides'])) {
                    $guide = Guide::where('code', $request->guide)->first();
                    if ($guide) {
                        $oldGuideRating = $guide->rating ?? 0;
                        $guide->rating = $oldGuideRating == 0 ? $data['rating_guides'] : round(($oldGuideRating + $data['rating_guides']) / 2, 1);
                        $guide->save();
                    }
                }
            }

            DB::commit();
            return back()->with('success', 'Review submitted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to submit review: ' . $e->getMessage());
        }
    }


    public function reply(Request $request)
    {
        $request->validate([
            'message' => 'required',
            'parent_review_id' => 'required',
            'package_id' => 'nullable',
            'vendor_id' => 'nullable',
        ]);

        try {
            $parentReview = Review::with(['package.owner'])->where('id', $request->parent_review_id)->where('status', 1)->first();

            if ($parentReview->user_id == auth()->id() || $parentReview->vendor_id == auth()->id() || $parentReview->package?->owner_id == auth()->id()){
                $review = new Review();
                $review->package_id = $request->package_id;
                $review->user_id = auth()->id();
                $review->vendor_id = $parentReview->vendor_id ?? $parentReview->package?->owner_id;
                $review->parent_review_id = $request->parent_review_id;
                $review->rating = $parentReview->rating;
                $review->guide = $parentReview->guide;
                $review->avg_rating = $parentReview->avg_rating;
                $review->comment = $request->message;
                $review->save();

                return back()->with('success', 'Review response submitted!');
            }else{
                return back()->with('error', 'You do not have permission to reply.');
            }

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function reviewList(Request $request)
    {

        try {
            $data['stats'] = Review::selectRaw("
                    COUNT(*) as total_reviews,
                    SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) as today_reviews,
                    SUM(CASE WHEN YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1) THEN 1 ELSE 0 END) as week_reviews,
                    SUM(CASE WHEN MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) THEN 1 ELSE 0 END) as month_reviews,
                    SUM(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) THEN 1 ELSE 0 END) as year_reviews
                ")
                ->whereHas('package', function ($query) {
                    $query->where('owner_id', auth()->id());
                })
                ->whereNull('parent_review_id')
                ->first();

            if ($data['stats']->total_reviews == 0) {
                $data['todayPercentage'] = $data['weekPercentage'] = $data['monthPercentage'] = $data['yearPercentage'] = 0;
            } else {
                $data['todayPercentage'] = ($data['stats']->today_reviews / $data['stats']->total_reviews) * 100;
                $data['weekPercentage']  = ($data['stats']->week_reviews / $data['stats']->total_reviews) * 100;
                $data['monthPercentage'] = ($data['stats']->month_reviews / $data['stats']->total_reviews) * 100;
                $data['yearPercentage']  = ($data['stats']->year_reviews / $data['stats']->total_reviews) * 100;
            }

            $data['slug'] = $request->slug ? $request->slug : null;

            return view(template().'user.package.review', $data);
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function reviewSearch(Request $request)
    {
        $search = $request->search['value']??null;
        $filterName = $request->input('name');
        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;
        $slug = $request->input('slug');
        $filterStatus = $request->input('filterStatus');
        $filterReview = $request->input('filterReview');

        $reviews = Review::query()
            ->with(['package', 'user', 'allReplies'])
            ->whereHas('package', function ($query) use ($slug) {
                $query->where('owner_id', auth()->id());

                if ($slug) {
                    $query->where('slug', $slug);
                }
            })
            ->where('parent_review_id', '=', null)
            ->when(!empty($search), function ($query) use ($search) {
                return $query->where(function ($subquery) use ($search) {
                    $subquery->where('comment', 'LIKE', "%{$search}%")
                        ->orWhereHas('package', function ($reviewQuery) use ($search) {
                            $reviewQuery->where('title', 'LIKE', "%{$search}%");
                        })
                        ->orWhereHas('user', function ($reviewerQuery) use ($search) {
                            $reviewerQuery->where('firstname', 'LIKE', "%{$search}%")
                                ->orWhere('lastname', 'LIKE', "%{$search}%");
                        });
                });
            })
            ->when(!empty($filterName), function ($query) use ($filterName) {
                $query->whereHas('package', function ($rquery) use ($filterName) {
                    $rquery->where('title', 'LIKE', "%{$filterName}%");
                });
            })
            ->when(!empty($request->filterDate) && $endDate != null, function ($query) use ($startDate, $endDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $endDate = Carbon::createFromFormat('d/m/Y', trim($endDate));
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->when(isset($filterStatus), function ($query) use ($filterStatus) {
                if ($filterStatus != "all") {
                    return $query->where('status', $filterStatus);
                }
            })
            ->when(isset($filterReview), function ($query) use ($filterReview) {
                if ($filterReview != "all") {
                    $min = (float) $filterReview;
                    $max = $min + 0.99;
                    if ($max > 5) {
                        $max = 5;
                    }
                    return $query->whereBetween('avg_rating', [$min, $max]);
                }
            });

        return DataTables::of($reviews)
            ->addColumn('package', function ($item) {
                $url = route('admin.package.edit', $item->package_id);
                $img = getFile($item->package->thumb_driver ?? null, $item->package->thumb ?? null);
                return '<a class="d-flex align-items-center" href="' . $url . '">
                    <div class="avatar">
                      <img class="avatar-img" src="' . $img . '" alt="Image Description">
                    </div>
                    <div class="flex-grow-1 ms-3">
                      <span class="card-title h5 text-dark text-inherit">' . $item->package?->title . '</span>
                    </div>
                  </a>
                 ';
            })
            ->addColumn('reviewer', function ($item) {
                $url = route('admin.user.view.profile', $item->user_id);
                return '<a class="d-flex align-items-center me-2" href="' . $url . '">
                                <div class="flex-shrink-0">
                                  ' . $item->user?->profilePicture() . '
                                </div>
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . $item->user?->firstname . ' ' . $item->user?->lastname . '</h5>
                                  <span class="fs-6 text-body">' . $item->user?->username . '</span>
                                </div>
                              </a>';

            })
            ->addColumn('review', function ($item) {
                $star = asset('assets/admin/img/star.svg');

                $ratingValues = (array) $item->rating;
                $averageRating = array_sum($ratingValues) / count($ratingValues);

                $starRating = '';
                for ($i = 0; $i < round($averageRating); $i++) {
                    $starRating .= '<img src="' . $star . '" alt="Review rating" width="14">';
                }

                return '<div class="text-wrap" style="width: 18rem;">
              <div class="d-flex gap-1 mb-2">
                ' . $starRating . '
              </div>
             <p>' . $item->comment . '</p>
            </div>';
            })
            ->addColumn('date', function ($item) {
                return dateTime($item->created_at, basicControl()->date_time_format);
            })
            ->addColumn('replies', function ($item) {
                $modalId = 'repliesModal_' . $item->id;
                $replies = $item->allReplies;

                $repliesHtml = '';
                foreach ($replies as $reply) {
                    $statusBadge = $reply->status == 1
                        ? '<span class="badge bg-soft-success text-success">Published</span>'
                        : '<span class="badge bg-soft-danger text-danger">Holded</span>';

                    $repliesHtml .= '<div class="mb-3 border-bottom pb-2">
                        <div class="d-flex justify-content-between ">
                            <div class="d-flex align-items-center justify-content-between w-100">
                                <div>
                                    <div class="fw-bold">
                                        ' . (($reply->user?->firstname . ' ' . $reply->user?->lastname) ?? 'Unknown') . '
                                        <small class="text-muted">- ' . $reply->created_at->diffForHumans() . '</small>
                                    </div>
                                    <p class="mb-1">' . ($reply->comment ?? '') . '</p>
                                </div>
                                ' . $statusBadge . '
                            </div>
                        </div>
                    </div>';
                }

                return '
                <button type="button" class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#' . $modalId . '">
                    <i class="bi bi-list"></i>
                </button>

                <div class="modal fade" id="' . $modalId . '" tabindex="-1" aria-labelledby="' . $modalId . 'Label" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="' . $modalId . 'Label">Replies</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        ' . ($repliesHtml ?: '<p>No replies found.</p>') . '
                      </div>
                    </div>
                  </div>
                </div>
            ';
            })

            ->addColumn('status', function ($item) {
                if ($item->status == 1) {
                    return '<span class="badge bg-soft-success text-success">
                    <span class="legend-indicator bg-success"></span>' . trans('Publish') . '
                  </span>';

                } else {
                    return '<span class="badge bg-soft-danger text-danger">
                    <span class="legend-indicator bg-danger"></span>' . trans('Hold') . '
                  </span>';
                }
            })
            ->rawColumns(['package','replies', 'reviewer', 'review', 'date', 'status'])
            ->make(true);
    }
}
