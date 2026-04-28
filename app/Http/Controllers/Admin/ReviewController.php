<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ReviewController extends Controller
{
    public function list(Request $request)
    {
        $data['reviews'] = collect(Review::where('parent_review_id', '=', null)
            ->selectRaw('COUNT(id) AS totalReview')
            ->selectRaw('COUNT(CASE WHEN status = 1 THEN id END) AS activeReview')
            ->selectRaw('(COUNT(CASE WHEN status = 1 THEN id END) / COUNT(id)) * 100 AS activeReviewPercentage')
            ->selectRaw('COUNT(CASE WHEN status = 0 THEN id END) AS inActiveReview')
            ->selectRaw('(COUNT(CASE WHEN status = 0 THEN id END) / COUNT(id)) * 100 AS inActiveReviewPercentage')
            ->selectRaw('COUNT(CASE WHEN DATE(created_at) = CURRENT_DATE THEN id END) AS todayReview')
            ->selectRaw('(COUNT(CASE WHEN DATE(created_at) = CURRENT_DATE THEN id END) / COUNT(id)) * 100 AS todayReviewPercentage')
            ->selectRaw('COUNT(CASE WHEN MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) THEN id END) AS thisMonthReview')
            ->selectRaw('(COUNT(CASE WHEN MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) THEN id END) / COUNT(id)) * 100 AS thisMonthReviewPercentage')
            ->get()
            ->toArray())->collapse();


        $data['reviews']['activeReviewPercentage'] = number_format($data['reviews']['activeReviewPercentage'], 0);
        $data['reviews']['inActiveReviewPercentage'] = number_format($data['reviews']['inActiveReviewPercentage'], 0);
        $data['reviews']['todayReviewPercentage'] = number_format($data['reviews']['todayReviewPercentage'], 0);
        $data['reviews']['thisMonthReviewPercentage'] = number_format($data['reviews']['thisMonthReviewPercentage'], 0);

        $data['package'] = $request->package ?? null;

        return view('admin.review', $data);
    }

    public function search(Request $request)
    {
        $search = $request->search['value'] ?? null;
        $filterName = $request->name;
        $package = $request->package;
        $filterStatus = $request->filterStatus;
        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;

        $cards = Review::with(['user:id,firstname,lastname,username,image,image_driver', 'package','allReplies'])
            ->where('parent_review_id', '=', null)
            ->has('package')
            ->has('user')
            ->latest()
            ->when(isset($filterName), function ($query) use ($filterName) {
                return $query->whereHas('package', function ($reviewQuery) use ($filterName) {
                    $reviewQuery->where('title', 'LIKE', '%' . $filterName . '%');
                });
            })
            ->when(isset($filterStatus), function ($query) use ($filterStatus) {
                if ($filterStatus != "all") {
                    return $query->where('status', $filterStatus);
                }
            })
            ->when(isset($package), function ($query) use ($package) {
                $query->whereHas('package', function ($reviewQuery) use ($package) {
                    $reviewQuery->where('id', $package);
                });
            })
            ->when(!empty($request->filterDate) && $endDate == null, function ($query) use ($startDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $query->whereDate('created_at', $startDate);
            })
            ->when(!empty($request->filterDate) && $endDate != null, function ($query) use ($startDate, $endDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $endDate = Carbon::createFromFormat('d/m/Y', trim($endDate));
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
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
            });
        return DataTables::of($cards)
            ->addColumn('checkbox', function ($item) {
                return '<input type="checkbox" id="chk-' . $item->id . '"
                                       class="form-check-input row-tic tic-check" name="check" value="' . $item->id . '"
                                       data-id="' . $item->id . '">';

            })
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
                        ? '<span class="badge bg-soft-success text-success">Publish</span>'
                        : '<span class="badge bg-soft-danger text-danger">Hold</span>';

                    $status_route = route('admin.review.statusChange', $reply->id);
                    $delete_route = route('admin.review.delete', $reply->id);

                    $repliesHtml .= '<div class="mb-3 border-bottom pb-2">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-bold">
                                    ' . (($reply->user?->firstname . ' ' . $reply->user?->lastname) ?? 'Unknown') . '
                                    <small class="text-muted">- ' . $reply->created_at->diffForHumans() . '</small>
                                </div>
                                <p class="mb-1">' . ($reply->comment ?? '') . '</p>
                                ' . $statusBadge . '
                            </div>
                            <div class="d-flex flex-row gap-2">
                                <a href="' . $status_route . '" class="btn btn-white btn-sm d-flex align-items-center gap-1">
                                    <i class="bi bi-check"></i>
                                </a>
                                <a href="' . $delete_route . '" class="btn btn-white btn-sm d-flex align-items-center gap-1">
                                    <i class="bi bi-trash"></i>
                                </a>
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
            ->rawColumns(['checkbox', 'package','replies', 'reviewer', 'review', 'date', 'status'])
            ->make(true);
    }

    public function multipleDelete(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You do not select row.');
            return response()->json(['error' => 1]);
        } else {
            Review::whereIn('id', $request->strIds)->get()->map(function ($query) {
                $query->delete();
                return $query;
            });
            session()->flash('success', 'Review has been deleted successfully');
            return response()->json(['success' => 1]);
        }
    }

    public function multipleStatusChange(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You do not select row.');
            return response()->json(['error' => 1]);
        } else {
            Review::whereIn('id', $request->strIds)->get()->map(function ($query) {
                if ($query->status) {
                    $query->status = 0;
                } else {
                    $query->status = 1;
                }
                $query->save();
                return $query;
            });
            session()->flash('success', 'Review has been changed successfully');
            return response()->json(['success' => 1]);
        }
    }

    public function statusChange($id)
    {
        try {
            $review = Review::where('id', $id)->firstOr(function () {
                throw new \Exception('This Rewview is not available now');
            });

            $review->status = $review->status == 1 ? 0 : 1 ;
            $review->save();

            return back()->with('success', 'Status has been updated successfully');
        }catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
    }
    public function deleteReview($id)
    {
        try {
            $review = Review::where('id', $id)->firstOr(function () {
                throw new \Exception('This Rewview is not available now');
            });

            $review->delete();

            return back()->with('success', 'Status has been deleted successfully');
        }catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
    }
}
