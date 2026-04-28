<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Models\Subscriber;
use App\Traits\Notify;
use App\Traits\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SubscriberController extends Controller
{
    use Upload, Notify;


    public function list(Request $request)
    {
        $data['subscribeRecord'] = collect(Subscriber::selectRaw('COUNT(id) AS totalSubscribe')
            ->selectRaw('COUNT(CASE WHEN DATE(created_at) = CURRENT_DATE THEN id END) AS todaySubscribe')
            ->selectRaw('(COUNT(CASE WHEN DATE(created_at) = CURRENT_DATE THEN id END) / COUNT(id)) * 100 AS todaySubscribePercentage')
            ->selectRaw('COUNT(CASE WHEN WEEK(created_at, 1) = WEEK(CURDATE(), 1) AND YEAR(created_at) = YEAR(CURDATE()) THEN id END) AS thisWeekSubscribe')
            ->selectRaw('(COUNT(CASE WHEN WEEK(created_at, 1) = WEEK(CURDATE(), 1) AND YEAR(created_at) = YEAR(CURDATE()) THEN id END) / COUNT(id)) * 100 AS thisWeekSubscribePercentage')
            ->selectRaw('COUNT(CASE WHEN MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) THEN id END) AS thisMonthSubscribe')
            ->selectRaw('(COUNT(CASE WHEN MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) THEN id END) / COUNT(id)) * 100 AS thisMonthSubscribePercentage')
            ->selectRaw('COUNT(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) THEN id END) AS thisYearSubscribe')
            ->selectRaw('(COUNT(CASE WHEN YEAR(created_at) = YEAR(CURDATE()) THEN id END) / COUNT(id)) * 100 AS thisYearSubscribePercentage')
            ->get()
            ->toArray())->collapse();

        return view('admin.subscribers.list', $data);
    }


    public function searchList(Request $request)
    {
        $search = $request->search['value'];

        $subscribers = Subscriber::orderBy('id', 'DESC')
            ->when(!empty($search), function ($query) use ($search) {
                return $query->where('email', 'LIKE', "%{$search}%");
            });

        return DataTables::of($subscribers)
            ->addColumn('checkbox', function ($item) {
                return ' <input type="checkbox" id="chk-' . $item->id . '"
                           class="form-check-input row-tic tic-check" name="check" value="' . $item->id . '"
                           data-id="' . $item->id . '">';
            })
            ->addColumn('email', function ($item) {
                return '<div class="d-flex">
                    <span class="d-block mb-0 ps-3">' . $item->email . '</span>
                </div>';
            })
            ->addColumn('subscribed_at', function ($item) {
                return '<div class="d-flex">
                <span class="d-block mb-0 ps-3">' . dateTime($item->created_at) . '</span>
            </div>';
            })
            ->addColumn('status', function ($item) {
                if ($item->status == 0) {
                    return ' <span class="badge bg-soft-warning text-warning">
                                <span class="legend-indicator bg-warning"></span> ' . trans('InActive') . '
                             </span>';
                } else {
                    return '<span class="badge bg-soft-success text-success">
                                <span class="legend-indicator bg-success"></span> ' . trans('Active') . '
                            </span>';
                }
            })
            ->addColumn('action', function ($item) {
                return '<div class="btn-group" role="group">
                      <a class="btn btn-white deleteBtn" href="javascript:void(0)"
                           data-route="' .route('admin.subscriber.delete',$item->id). '"
                           data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bi bi-trash"></i>
                        </a>
                  </div>';
            })->rawColumns(['checkbox', 'email','status', 'subscribed_at','action'])
            ->make(true);
    }
    public function deleteMultiple(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You do not select Item.');
            return response()->json(['error' => 1]);
        } else {
            Subscriber::whereIn('id', $request->strIds)->delete();
            session()->flash('success', 'User has been deleted successfully');
            return response()->json(['success' => 1]);
        }
    }

    public function delete($id){
        $data =  Subscriber::findOrFail($id);
        $data->delete();

        if (request()->wantsJson()) {
            return response([], 204);
        }


        return back()->with('success','Deleted');

    }

    public function sendEmailForm()
    {
        return view('admin.subscribers.send_mail_form');

    }

    public function sendMailUser(Request $request)
    {

        $rules = [
            'subject' => 'required',
            'description' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $basic = basicControl();
        $email_from = $basic->sender_email;
        $requestMessage = $request->message;
        $subject = $request->subject;
        $email_body = $basic->email_description;
        if (!Subscriber::first()) return back()->withInput()->with('error', 'No subscribers to send email.');
        $subscribers = Subscriber::all();
        foreach ($subscribers as $subscriber) {
            $name = explode('@', $subscriber->email)[0];
            $message = str_replace("[[name]]", $name, $email_body);
            $message = str_replace("[[message]]", $requestMessage, $message);
            @Mail::to($subscriber->email)->queue(new SendMail($email_from, $subject, $message));
        }
        return back()->with('success', 'Email has been sent to subscribers.');
    }
    public function statusMultiple(Request $request)
    {
        if ($request->strIds == null) {
            session()->flash('error', 'You have not selected any data.');
            return response()->json(['error' => 1]);
        }

        $categories = Subscriber::whereIn('id', $request->strIds)->get();

        foreach ($categories as $category) {
            $category->status = ($category->status == 1) ? 0 : 1;
            $category->save();
        }

        session()->flash('success', 'Subscriber Status Changed Successfully.');
        return response()->json(['success' => 1]);
    }
}
