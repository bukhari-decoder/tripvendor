<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\UserOwnGatewayJob;
use App\Models\Kyc;
use App\Models\UserKyc;
use App\Models\VendorInfo;
use App\Traits\Notify;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Yajra\DataTables\Facades\DataTables;

class KycController extends Controller
{

    use Notify;

    public function index()
    {
        $data['kycList'] = Kyc::where('apply_for', '!=', 1)->get();
        return view('admin.kyc.list', $data);
    }

    public function create()
    {
        return view('admin.kyc.create');
    }

    public function store(Request $request)
    {
        $requestData = $request->all();
        $rules = [
            'name' => 'required|string',
            'field_name.*' => 'required|string',
            'input_type.*' => 'required|in:text,textarea,file,date,number',
            'is_required.*' => 'required|in:required,optional',
        ];

        $customMessages = [
            'field_name.*.required' => 'The form label field is required.',
            'input_type.*.required' => 'The input type field is required.',
            'is_required.*.required' => 'The required field is required.',
            'input_type.*.in' => 'The Input type is invalid.',
            'is_required.*.in' => 'The required value is invalid.',
        ];

        $validator = Validator::make($requestData, $rules, $customMessages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $inputForm = [];
            if ($request->has('field_name')) {
                for ($a = 0; $a < count($request->field_name); $a++) {
                    $arr = array();
                    $arr['field_name'] = clean($request->field_name[$a]);
                    $arr['field_label'] = ucwords($request->field_name[$a]);
                    $arr['type'] = $request->input_type[$a];
                    $arr['validation'] = $request->is_required[$a];
                    $inputForm[$arr['field_name']] = $arr;
                }
            }

            $kyc = Kyc::create([
                'name' => $request->name,
                'slug' => Slug($request->name),
                'input_form' => $inputForm,
                'status' => $request->status
            ]);

            if (!$kyc) {
                throw new Exception('something went wrong, Please try again');
            }

            return back()->with('success', 'KYC Store successfully');

        } catch (Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $data['kyc'] = Kyc::where('id', $id)->firstOr(function () {
                throw new Exception('No KYC found.');
            });

            return view('admin.kyc.edit', $data);

        } catch (Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function update(Request $request, $id)
    {

        $requestData = $request->all();
        $rules = [
            'name' => 'required|string',
            'field_name.*' => 'required|string',
            'input_type.*' => 'required|in:text,textarea,file,date,number',
            'is_required.*' => 'required|in:required,optional',
        ];

        $customMessages = [
            'field_name.*.required' => 'The form label field is required.',
            'input_type.*.required' => 'The input type field is required.',
            'is_required.*.required' => 'The required field is required.',
            'input_type.*.in' => 'The Input type is invalid.',
            'is_required.*.in' => 'The required value is invalid.',
        ];

        $validator = Validator::make($requestData, $rules, $customMessages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {

            $inputForm = [];
            if ($request->has('field_name')) {
                for ($a = 0; $a < count($request->field_name); $a++) {
                    $arr = array();
                    $arr['field_name'] = clean($request->field_name[$a]);
                    $arr['field_label'] = ucwords($request->field_name[$a]);
                    $arr['type'] = $request->input_type[$a];
                    $arr['validation'] = $request->is_required[$a];
                    $inputForm[$arr['field_name']] = $arr;
                }
            }

            $kyc = Kyc::where('id', $id)->firstOr(function () {
                throw new Exception('No KYC found.');
            });

            $kyc->update([
                'name' => $request->name,
                'slug' => slug($request->name),
                'input_form' => $inputForm,
                'status' => $request->status
            ]);

            if (!$kyc) {
                throw new Exception('Something went wrong');
            }

            return back()->with('success', 'KYC updated successfully');

        } catch (Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function userKycList($status = 'all')
    {

        $userKYCRecord = \Cache::get('userKYCRecord');
        if (!$userKYCRecord){
            $userKYCRecord = UserKyc::where('apply_for', 0)
                ->selectRaw('
                    COUNT(id) AS totalKYC,
                    COUNT(CASE WHEN status = 0 THEN id END) AS pendingKYC,
                    (COUNT(CASE WHEN status = 0 THEN id END) / COUNT(id)) * 100 AS pendingKYCPercentage,
                    COUNT(CASE WHEN status = 1 THEN id END) AS approvedKYC,
                    (COUNT(CASE WHEN status = 1 THEN id END) / COUNT(id)) * 100 AS approvedKYCPercentage,
                    COUNT(CASE WHEN status = 2 THEN id END) AS rejectedKYC,
                    (COUNT(CASE WHEN status = 2 THEN id END) / COUNT(id)) * 100 AS rejectedKYCPercentage,
                    COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN id END) AS todayKYC,
                    (COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN id END) / COUNT(id)) * 100 AS todayKYCPercentage
                ')
                ->get()
                ->toArray();

            \Cache::put('userKYCRecord', $userKYCRecord);
        }

        return view('admin.kyc.user_kyc', compact('status', 'userKYCRecord'));
    }

    public function userKycSearch(Request $request, $status = 'all')
    {
        $filterVerificationType = $request->filterVerificationType;

        $filterStatus = $request->filterStatus;
        $search = $request->search['value'];
        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;

        $userKyc = UserKyc::query()->with(['user:id,username,firstname,lastname,image,image_driver'])
            ->whereHas('user')
            ->orderBy('id', 'DESC')
            ->where('apply_for', 0)
            ->when(!empty($search), function ($query) use ($search) {
                return $query->where(function ($subquery) use ($search) {
                    $subquery->where('kyc_type', 'LIKE', "%$search%")
                        ->orWhereHas('user', function ($q) use ($search) {
                            $q->where('firstname', 'LIKE', "%$search%");
                            $q->orWhere('lastname', 'LIKE', "%$search%");
                            $q->orWhere('username', 'LIKE', "%$search%");
                            $q->orWhere('email', 'LIKE', "%$search%");
                        });
                });
            })
            ->when($status == 'all', function ($query) {
                return $query->whereIn('status', [0, 1, 2]);
            })
            ->when($status == 'pending', function ($query) {
                return $query->whereStatus(0);
            })
            ->when($status == 'approve', function ($query) {
                return $query->whereStatus(1);
            })
            ->when($status == 'rejected', function ($query) {
                return $query->whereStatus(2);
            })
            ->when(!empty($filterVerificationType), function ($query) use ($filterVerificationType) {
                return $query->where('kyc_type', 'LIKE', "%$filterVerificationType%" );
            })
            ->when(isset($filterStatus), function ($query) use ($filterStatus) {
                return $query->where('status', $filterStatus);
            })
            ->when(!empty($request->filterDate) && $endDate == null, function ($query) use ($startDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $query->whereDate('created_at', $startDate);
            })
            ->when(!empty($request->filterDate) && $endDate != null, function ($query) use ($startDate, $endDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $endDate = Carbon::createFromFormat('d/m/Y', trim($endDate));
                $query->whereBetween('created_at', [$startDate, $endDate]);
            });


        return DataTables::of($userKyc)
            ->addColumn('no', function () {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('name', function ($item) {
                $url = route('admin.user.view.profile', optional($item->user)->id);
                return '<a class="d-flex align-items-center me-2" href="' . $url . '">
                                <div class="flex-shrink-0">
                                  ' . optional($item->user)->profilePicture() . '
                                </div>
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . optional($item->user)->firstname . ' ' . optional($item->user)->lastname . '</h5>
                                  <span class="fs-6 text-body">@' . optional($item->user)->username . '</span>
                                </div>
                              </a>';
            })
            ->addColumn('verification type', function ($item) {
                return $item->kyc_type;
            })
            ->addColumn('status', function ($item) {
                $statusLabels = [
                    0 => '<span class="badge bg-soft-warning text-warning">
                            <span class="legend-indicator bg-warning"></span> ' . trans('Pending') . '
                        </span>',
                    1 => '<span class="badge bg-soft-success text-success">
                            <span class="legend-indicator bg-success"></span> ' . trans('Verified') . '
                        </span>',
                    2 => '<span class="badge bg-soft-danger text-danger">
                            <span class="legend-indicator bg-danger"></span> ' . trans('Rejected') . '
                        </span>'
                ];
                return isset($statusLabels[$item->status]) ? $statusLabels[$item->status] : '';
            })
            ->addColumn('action', function ($item) {
                $url = route('admin.kyc.view', $item->id);
                return '<a class="btn btn-white btn-sm" href="' . $url . '">
                      <i class="bi-eye"></i> ' . trans("View") . '
                    </a>';
            })
            ->rawColumns(['name', 'status', 'action'])
            ->make(true);
    }
    public function vendorKycRequests(Request $request)
    {
        $userKYCRecord = \Cache::get('vendorKYCRecord');
        if (!$userKYCRecord){
            $userKYCRecord = UserKyc::where('apply_for', 1)
                ->selectRaw('
                    COUNT(id) AS totalKYC,
                    COUNT(CASE WHEN status = 0 THEN id END) AS pendingKYC,
                    (COUNT(CASE WHEN status = 0 THEN id END) / COUNT(id)) * 100 AS pendingKYCPercentage,
                    COUNT(CASE WHEN status = 1 THEN id END) AS approvedKYC,
                    (COUNT(CASE WHEN status = 1 THEN id END) / COUNT(id)) * 100 AS approvedKYCPercentage,
                    COUNT(CASE WHEN status = 2 THEN id END) AS rejectedKYC,
                    (COUNT(CASE WHEN status = 2 THEN id END) / COUNT(id)) * 100 AS rejectedKYCPercentage,
                    COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN id END) AS todayKYC,
                    (COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN id END) / COUNT(id)) * 100 AS todayKYCPercentage
                ')
                ->get()
                ->toArray();

            \Cache::put('vendorKYCRecord', $userKYCRecord);
        }

        return view('admin.kyc.vendor_kyc', compact('userKYCRecord'));
    }

    public function vendorKycRequestsSearch(Request $request)
    {
        $filterStatus = $request->filterStatus;
        $name = $request->name;
        $search = $request->search['value'];
        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;

        $userKyc = UserKyc::query()->with(['user:id,username,firstname,lastname,image,image_driver'])
            ->whereHas('user')
            ->orderBy('id', 'DESC')
            ->where('apply_for', 1)
            ->when(!empty($search), function ($query) use ($search) {
                return $query->where(function ($subquery) use ($search) {
                    $subquery->where('kyc_type', 'LIKE', "%$search%")
                        ->orWhereHas('user', function ($q) use ($search) {
                            $q->where('firstname', 'LIKE', "%$search%");
                            $q->orWhere('lastname', 'LIKE', "%$search%");
                            $q->orWhere('username', 'LIKE', "%$search%");
                            $q->orWhere('email', 'LIKE', "%$search%");
                        });
                });
            })
            ->when(!empty($name), function ($query) use ($name) {
                return $query->where(function ($subquery) use ($name) {
                    $subquery->where('kyc_type', 'LIKE', "%$name%")
                        ->orWhereHas('user', function ($q) use ($name) {
                            $q->where('firstname', 'LIKE', "%$name%");
                            $q->orWhere('lastname', 'LIKE', "%$name%");
                            $q->orWhere('username', 'LIKE', "%$name%");
                            $q->orWhere('email', 'LIKE', "%$name%");
                        });
                });
            })
            ->when(isset($filterStatus), function ($query) use ($filterStatus) {
                return $query->where('status', $filterStatus);
            })
            ->when(!empty($request->filterDate) && $endDate == null, function ($query) use ($startDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $query->whereDate('created_at', $startDate);
            })
            ->when(!empty($request->filterDate) && $endDate != null, function ($query) use ($startDate, $endDate) {
                $startDate = Carbon::createFromFormat('d/m/Y', trim($startDate));
                $endDate = Carbon::createFromFormat('d/m/Y', trim($endDate));
                $query->whereBetween('created_at', [$startDate, $endDate]);
            });

        return DataTables::of($userKyc)
            ->addColumn('no', function () {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('name', function ($item) {
                $url = route('admin.user.view.profile', optional($item->user)->id);
                return '<a class="d-flex align-items-center me-2" href="' . $url . '">
                                <div class="flex-shrink-0">
                                  ' . optional($item->user)->profilePicture() . '
                                </div>
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . optional($item->user)->firstname . ' ' . optional($item->user)->lastname . '</h5>
                                  <span class="fs-6 text-body">@' . optional($item->user)->username . '</span>
                                </div>
                              </a>';
            })
            ->addColumn('verification type', function ($item) {
                return $item->kyc_type;
            })
            ->addColumn('status', function ($item) {
                $statusLabels = [
                    0 => '<span class="badge bg-soft-warning text-warning">
                            <span class="legend-indicator bg-warning"></span> ' . trans('Pending') . '
                        </span>',
                    1 => '<span class="badge bg-soft-success text-success">
                            <span class="legend-indicator bg-success"></span> ' . trans('Verified') . '
                        </span>',
                    2 => '<span class="badge bg-soft-danger text-danger">
                            <span class="legend-indicator bg-danger"></span> ' . trans('Rejected') . '
                        </span>'
                ];
                return isset($statusLabels[$item->status]) ? $statusLabels[$item->status] : '';
            })
            ->addColumn('action', function ($item) {
                $url = route('admin.kyc.view', $item->id);
                return '<a class="btn btn-white btn-sm" href="' . $url . '">
                      <i class="bi-eye"></i> ' . trans("View") . '
                    </a>';
            })
            ->rawColumns(['name', 'status', 'action'])
            ->make(true);
    }

    public function view($id)
    {
        try {
            $data['userKyc'] = UserKyc::with('user')->where('id', $id)->firstOr(function () {
                throw new Exception('No KYC found.');
            });
            return view('admin.kyc.view', $data);
        } catch (Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function action(Request $request, $id)
    {
        $req = $request->except('_token', '_method');
        $rules = [
            'rejected_reason' => 'sometimes|required|string|min:3',
            'status' => 'nullable|integer|in:0,1,2'
        ];

        $validator = Validator::make($req, $rules);
        if ($validator->fails()) {
            $newArr = $validator->getMessageBag();
            $newArr->add('rejectedMessage', 1);
            return back()->withErrors($newArr)->withInput();
        }

        try {
            $userKyc = UserKyc::findOrFail($id);
            if ($request->status == 1) {
                $userKyc->status = 1;
                $userKyc->approved_at = now();

                if ($userKyc->apply_for == 1) {
                    if ($userKyc->user->role != 1){

                        $userKyc->user->role = 1;
                        $userKyc->user->as_a_vendor_from = now();
                        $userKyc->user->save();

                        $vendorInfo = new VendorInfo();
                        $vendorInfo->vendor_id = $userKyc->user?->id;
                        $vendorInfo->save();

                        dispatch(new UserOwnGatewayJob($userKyc->user));
                    }
                }

                $message = 'Approved Successfully';
                $this->userSendMailNotify($userKyc->user, 'approve');
            } elseif ($request->status == 2) {
                $userKyc->status = 2;
                $userKyc->reason = $request->rejected_reason;

                if ($userKyc->apply_for == 1) {
                    $userKyc->user->restrict_for_vendor_request = $request->restrict_for_vendor_request;
                    $userKyc->user->save();
                }

                $message = 'Rejected Successfully';
                $this->userSendMailNotify($userKyc->user, 'reject');
            }

            $userKyc->save();
            return back()->with('success', $message);

        } catch (Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function userSendMailNotify($user, $type)
    {
        if ($type == 'approve') {
            $templateKey = 'KYC_APPROVED';
        } else {
            $templateKey = 'KYC_REJECTED';
        }
        $action = [
            "link" => "#",
            "icon" => "fa-light fa-address-book"
        ];
        $this->sendMailSms($user, $templateKey);
        $this->userPushNotification($user, $templateKey, $action);
        $this->userFirebasePushNotification($user, $templateKey);
        return 0;
    }

    public function vendorKyc()
    {
        $data['kyc'] = Kyc::where('apply_for', 1)->firstOrFail();
        return view('admin.kyc.vendor_kyc_form', $data);
    }

    public function vendorKycStore(Request $request)
    {
        $requestData = $request->all();
        $rules = [
            'name' => 'required|string',
            'field_name.*' => 'required|string',
            'input_type.*' => 'required|in:text,textarea,file,date,number',
            'is_required.*' => 'required|in:required,optional',
        ];

        $customMessages = [
            'field_name.*.required' => 'The form label field is required.',
            'input_type.*.required' => 'The input type field is required.',
            'is_required.*.required' => 'The required field is required.',
            'input_type.*.in' => 'The Input type is invalid.',
            'is_required.*.in' => 'The required value is invalid.',
        ];

        $validator = Validator::make($requestData, $rules, $customMessages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $inputForm = [];
            if ($request->has('field_name')) {
                for ($a = 0; $a < count($request->field_name); $a++) {
                    $arr = array();
                    $arr['field_name'] = clean($request->field_name[$a]);
                    $arr['field_label'] = ucwords($request->field_name[$a]);
                    $arr['type'] = $request->input_type[$a];
                    $arr['validation'] = $request->is_required[$a];
                    $inputForm[$arr['field_name']] = $arr;
                }
            }

            $kyc = Kyc::where('apply_for', 1)->firstOrFail();
            $kyc->name = $request->name;
            $kyc->slug = Slug($request->name);
            $kyc->input_form = $inputForm;
            $kyc->status = $request->status;
            $kyc->is_automatic = $request->is_automatic;
            $kyc->apply_for = 1;
            $kyc->save();

            if (!$kyc) {
                throw new Exception('something went wrong, Please try again');
            }

            return back()->with('success', 'Vendor KYC Updated successfully');

        } catch (Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }
}
