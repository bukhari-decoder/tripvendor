<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\SupportTicketAttachment;
use App\Models\SupportTicketMessage;
use App\Traits\Notify;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class SupportTicketController extends Controller
{
    use Upload, Notify;

    public function __construct()
    {
        $this->theme = template();
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
    }

    public function index()
    {
        $userId = auth()->id();

        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfYear = Carbon::now()->startOfYear();

        $result = DB::table('support_tickets')
            ->where('user_id', $userId)
            ->selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN DATE(created_at) = ? THEN 1 ELSE 0 END) as today_count,
            SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as this_week_count,
            SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as this_month_count,
            SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as this_year_count
        ', [
                $today->toDateString(),
                $startOfWeek->toDateTimeString(),
                $startOfMonth->toDateTimeString(),
                $startOfYear->toDateTimeString(),
            ])
            ->first();

        $total = $result->total;
        $percent = fn($count) => $total > 0 ? round(($count / $total) * 100, 2) : 0;

        $data['count'] = [
            'total' => $total,
            'today_count' => $result->today_count,
            'this_week_count' => $result->this_week_count,
            'this_month_count' => $result->this_month_count,
            'this_year_count' => $result->this_year_count,
            'today_percent' => $percent($result->today_count),
            'this_week_percent' => $percent($result->this_week_count),
            'this_month_percent' => $percent($result->this_month_count),
            'this_year_percent' => $percent($result->this_year_count),
        ];

        return view(template() . 'user.support_ticket.list', $data);
    }

    public function search(Request $request)
    {
        $filterSubject = $request->subject;
        $filterStatus = $request->filterStatus;
        $search = $request->search['value'];
        $filterDate = explode('-', $request->filterDate);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;

        $supportTicket = SupportTicket::query()->where('user_id', auth()->id())
            ->orderBy('id', 'DESC')
            ->when(!empty($search), function ($query) use ($search) {
                return $query->where(function ($subquery) use ($search) {
                    $subquery->where('subject', 'LIKE', "%$search%");
                });
            })
            ->when(!empty($filterSubject), function ($query) use ($filterSubject) {
                return $query->where('subject', 'LIKE', "%$filterSubject%");
            })
            ->when(isset($filterStatus), function ($query) use ($filterStatus) {
                if ($filterStatus == "all") {
                    return $query->where('status', '!=', null);
                }
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

        return DataTables::of($supportTicket)
            ->addColumn('no', function () {
                static $counter = 0;
                $counter++;
                return $counter;
            })
            ->addColumn('username', function ($item) {
                $url = route("admin.user.edit", optional($item->user)->id);
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
            ->addColumn('subject', function ($item) {
                return Str::limit($item->subject, 30);

            })
            ->addColumn('status', function ($item) {
                if ($item->status == 0) {
                    return ' <span class="badge bg-soft-warning text-warning">
                                    <span class="legend-indicator bg-warning"></span> ' . trans('Open') . '
                                </span>';
                } else if ($item->status == 1) {
                    return '<span class="badge bg-soft-success text-success">
                                    <span class="legend-indicator bg-success"></span> ' . trans('Answered') . '
                                </span>';

                } else if ($item->status == 2) {
                    return '<span class="badge bg-soft-info text-info">
                                    <span class="legend-indicator bg-info"></span> ' . trans('Customer Reply') . '
                                </span>';

                } else if ($item->status == 3) {
                    return '<span class="badge bg-soft-danger text-danger">
                                    <span class="legend-indicator bg-danger"></span> ' . trans('Closed') . '
                                </span>';
                }
            })
            ->addColumn('lastReply', function ($item) {
                return dateTime($item->last_reply);
            })
            ->addColumn('action', function ($item) {
                $url = route('user.ticket.view', $item->ticket);
                return '<a class="btn btn-white btn-sm" href="' . $url . '">
                      <i class="bi-eye"></i> ' . trans("View") . '
                    </a>';
            })
            ->rawColumns(['username', 'subject', 'status', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view($this->theme . 'user.support_ticket.create');
    }

    public function store(Request $request)
    {
        $random = rand(100000, 999999);
        $this->newTicketValidation($request);
        $ticket = $this->saveTicket($request, $random);
        $message = $this->saveMsgTicket($request, $ticket);

        if (!empty($request->attachments)) {
            $numberOfAttachments = count($request->attachments);
            for ($i = 0; $i < $numberOfAttachments; $i++) {
                if ($request->hasFile('attachments.' . $i)) {
                    $file = $request->file('attachments.' . $i);
                    $supportFile = $this->fileUpload($file, config('filelocation.ticket.path'), null, null, 'webp', 80);
                    if (empty($supportFile['path'])) {
                        throw new \Exception('File could not be uploaded.');
                    }
                    $this->saveAttachment($message, $supportFile['path'], $supportFile['driver']);
                }
            }
        }
        $msg = [
            'user' => optional($ticket->user)->username,
            'ticket_id' => $ticket->ticket
        ];
        $action = [
            "name" => optional($ticket->user)->firstname . ' ' . optional($ticket->user)->lastname,
            "image" => getFile(optional($ticket->user)->image_driver, optional($ticket->user)->image),
            "link" => route('admin.ticket.view', $ticket->id),
            "icon" => "fas fa-ticket-alt text-white"
        ];
        $this->adminPushNotification('SUPPORT_TICKET_CREATE', $msg, $action);
        $this->adminMail('SUPPORT_TICKET_CREATE', $msg);
        return back()->with('success', 'Your ticket has been pending.');
    }

    public function ticketView($ticketId)
    {
        $ticket = SupportTicket::where('ticket', $ticketId)->with('messages')->first();
        $user = Auth::user();
        return view(template() . 'user.support_ticket.view', compact('ticket', 'user'));
    }

    public function reply(Request $request, $id)
    {
        try {
            $ticket = SupportTicket::where('id', $id)->firstOr(function () {
                throw new \Exception('The Support Ticket was not found.');
            });
            $message = new SupportTicketMessage();

            if ($request->message == null && $request->attachments == null) {
                return back()->with('warning', 'Minimum One field is required ');
            }

            if ($request->replayTicket == 1) {
                $images = $request->file('attachments');
                if ($images){
                    if (count($images) > 5) {
                        $message = 'Maximum 5 images can be uploaded';
                        return back()->with('error', $message);;
                    }
                }

                $allowedExtensions = array('jpg', 'png', 'jpeg', 'pdf', 'zip');

                $this->validate($request, [
                    'attachments' => [
                        'nullable',
                        'max:10240',
                        function ($fail) use ($images, $allowedExtensions) {
                            foreach ($images as $img) {
                                $ext = strtolower($img->getClientOriginalExtension());
                                if (($img->getSize() / 1000000) > 2) {
                                    return $fail("Images MAX  10MB ALLOW!");
                                }
                                if (!in_array($ext, $allowedExtensions)) {
                                    return $fail("Only png, jpg, jpeg, pdf images are allowed");
                                }
                            }
                        },
                    ],
                    'message' => 'nullable',
                ]);

                $ticket->status = 2;
                $ticket->last_reply = Carbon::now();
                $ticket->save();

                $message->support_ticket_id = $ticket->id;
                $message->message = $request->message;
                $message->save();


                if (!empty($request->attachments)) {
                    $numberOfAttachments = count($request->attachments);
                    for ($i = 0; $i < $numberOfAttachments; $i++) {
                        if ($request->hasFile('attachments.' . $i)) {
                            $file = $request->file('attachments.' . $i);
                            $supportFile = $this->fileUpload($file, config('filelocation.ticket.path'), null, null, 'webp', '60');
                            if (empty($supportFile['path'])) {
                                throw new \Exception('File could not be uploaded.');
                            }
                            $this->saveAttachment($message, $supportFile['path'], $supportFile['driver']);
                        }
                    }
                }

                $msg = [
                    'username' => optional($ticket->user)->username,
                    'ticket_id' => $ticket->ticket
                ];
                $action = [
                    "name" => optional($ticket->user)->firstname . ' ' . optional($ticket->user)->lastname,
                    "image" => getFile(optional($ticket->user)->image_driver, optional($ticket->user)->image),
                    "link" => route('admin.ticket.view', $ticket->id),
                    "icon" => "fas fa-ticket-alt text-white"
                ];
                $this->adminPushNotification('SUPPORT_TICKET_REPLY', $msg, $action);

                return back()->with('success', 'Ticket has been replied');
            } elseif ($request->replayTicket == 2) {
                $ticket->status = 3;
                $ticket->last_reply = Carbon::now();
                $ticket->save();

                return back()->with('success', 'Ticket has been closed');
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }


    public function download($ticket_id)
    {
        $attachment = SupportTicketAttachment::with('supportMessage', 'supportMessage.ticket')->findOrFail(decrypt($ticket_id));
        $file = $attachment->file;
        $full_path = getFile($attachment->driver, $file);
        $title = slug($attachment->supportMessage->ticket->subject) . '-' . $file;
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $full_path);
        return readfile($full_path);
    }


    public function newTicketValidation(Request $request): void
    {
        $images = $request->file('attachments');
        $allowedExtension = array('jpg', 'png', 'jpeg', 'pdf');

        $this->validate($request, [
            'attachments' => [
                'max:4096',
                function ($attribute, $value, $fail) use ($images, $allowedExtension) {
                    foreach ($images as $img) {
                        $ext = strtolower($img->getClientOriginalExtension());
                        if (($img->getSize() / 1000000) > 2) {
                            throw ValidationException::withMessages(['attachments' => "Images MAX  2MB ALLOW!"]);
                        }
                        if (!in_array($ext, $allowedExtension)) {
                            throw ValidationException::withMessages(['attachments' => "Only png, jpg, jpeg, pdf images are allowed"]);
                        }
                    }
                    if (count($images) > 5) {
                        throw ValidationException::withMessages(['attachments' => "Maximum 5 images can be uploaded"]);
                    }
                },
            ],
            'subject' => 'required|max:100',
            'message' => 'required'
        ]);
    }


    public function saveTicket(Request $request, $random)
    {
        try {
            $ticket = SupportTicket::create([
                'user_id' => auth()->id(),
                'ticket' => $random,
                'subject' => $request->subject,
                'status' => 0,
                'last_reply' => Carbon::now(),
            ]);

            if (!$ticket) {
                throw new \Exception('Something went wrong when creating the ticket.');
            }
            return $ticket;
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function saveMsgTicket(Request $request, $ticket)
    {
        try {
            $message = SupportTicketMessage::create([
                'support_ticket_id' => $ticket->id,
                'message' => $request->message
            ]);

            if (!$message) {
                throw new \Exception('Something went wrong when creating the ticket.');
            }
            return $message;
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function saveAttachment($message, $path, $driver)
    {
        try {
            $attachment = SupportTicketAttachment::create([
                'support_ticket_message_id' => $message->id,
                'file' => $path ?? null,
                'driver' => $driver ?? 'local',
            ]);

            if (!$attachment) {
                throw new \Exception('Something went wrong when creating the ticket.');
            }
            return true;
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function Close(Request $request, $id)
    {
        try {
            $ticket = SupportTicket::where('id', $id)->firstOr(function () {
                throw new \Exception('The Support was not found.');
            });

            if ($ticket->status == 3) {
                return back()->with('error', 'Already Closed');
            }

            $ticket->status = 3;
            $ticket->save();

            return back()->with('success', 'Ticket has been closed');
        }catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }

    }
}
