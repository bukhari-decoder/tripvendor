<?php

namespace App\Http\Controllers\User;

use App\Events\UpdateUserMessage;
use App\Events\UserMessage;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Package;
use App\Models\User;
use App\Traits\Notify;
use App\Traits\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChattingController extends Controller
{
    use Upload, Notify;

    public function view(Request $request)
    {
        try {
            $user = Auth::user();

            $allChat = Chat::with('reply', 'sender', 'receiver')
                ->withCount('reply')
                ->where(function ($query) use ($user) {
                    $query->where('receiver_id', $user->id)
                        ->orWhere('sender_id', $user->id);
                })
                ->whereNull('chat_id')
                ->get();

            $chat = null;
            if ($request->has('id')) {
                $chat = Chat::where('id', $request->id)
                    ->latest()
                    ->with('reply', 'sender', 'receiver')
                    ->firstOr(function () {
                        throw new \Exception('Chat not found.');
                    });
            }

            return view(template() . 'user.chat.view', compact('allChat', 'chat'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function reply(Request $request)
    {
        $request->validate([
            'message' => 'nullable|string|max:255',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,png,pdf|max:2048',
        ], [
            'attachments.*.mimes' => 'Each attachment must be a file of type: jpg, jpeg, png.',
            'attachments.*.max' => 'Each attachment may not be greater than 2MB.',
        ]);

        if (empty($request->input('message')) && !$request->hasFile('attachments')) {
            return back()->with('error', 'Either the message or at least one attachment is required.');
        }

        try {
            $product = Package::with('owner:id')->where('id', $request->product_id)->select('id', 'owner_id')->firstOr(function () {
                throw new \Exception('Product not found.');
            });

            if ($request->chat) {
                $OldChat = Chat::where('id', $request->chat)->first();
            }

            if ($request->replayChat == 1 || $request->replayChat == 2) {
                $previousChat = Chat::where('chat_id', '=', null)->where('sender_id', Auth::user()->id)->where('package_id', $product->id)->first();
            }

            $chat = new Chat();

            if ($request->hasFile('attachments')) {
                $path = [];
                $driver = '';

                foreach ($request->attachments as $img) {
                    $image = $this->fileUpload($img, config('filelocation.chat.path'), null, null, 'webp', 80);
                    $path[] = $image['path'];

                    if (!empty($image['driver'])) {
                        $driver = $image['driver'];
                    }
                }

                $chat->attachment = json_encode($path);
                $chat->driver = $driver;
                $chat->last_reply = now();
                $chat->save();
            }

            $chat->message = $request->message;
            $chat->user_id = !$request->chat ? Auth::user()->id : $OldChat->user_id;
            $chat->chat_id = $request->chat ?? $previousChat->id ?? null;
            $chat->package_id = $product->id;
            $chat->sender_id = Auth::user()->id;
            if ($request->replayChat == 1 || $request->replayChat == 2) {
                $chat->receiver_id = $product->owner_id;
            } elseif ($request->replayChat == 0 ) {
                if ($OldChat->sender_id == Auth::id()) {
                    $chat->receiver_id = $OldChat->receiver_id;
                } else {
                    $chat->receiver_id = $OldChat->sender_id;
                }
            }
            $chat->last_reply = now();
            $chat->save();
            $chat->link = route('user.chat.list') . '?id=' . $request->chat ?? $previousChat->id ?? $chat->id;
            $chat->save();

            event(new UserMessage($chat, $chat->receiver_id));
            return back()->with('success', 'Message Send.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function searchData(Request $request)
    {
        $value = $request->input('search');

        $chats = Chat::query()
            ->with('sender')
            ->whereNull('chat_id')
            ->when($value, function ($query, $value) {
                $query->whereHas('sender', function ($query) use ($value) {
                    $query->where('firstname', 'like', '%' . $value . '%')
                        ->orWhere('lastname', 'like', '%' . $value . '%');
                });
            })
            ->get();

        foreach ($chats as $chat) {
            $chat->url = route('user.chat.list', ['id' => $chat->id]);
            $chat->image = getFile($chat->sender->image_driver, $chat->sender->image);
        }

        return $chats;
    }

    public function delete($id)
    {
        try {
            $chat = Chat::where('id', $id)->with('reply')->firstOr(function () {
                throw new \Exception('Chat not found.');
            });

            if (!empty($chat->reply)) {
                foreach ($chat->reply as $item) {
                    $item->delete();
                    if ($item->attachment) {
                        $driver = $item->driver;
                        $attachments = json_decode($item->attachment);

                        if (is_array($attachments)) {
                            foreach ($attachments as $value) {
                                $this->fileDelete($driver, $value);
                            }
                        } elseif (is_string($attachments)) {
                            $this->fileDelete($driver, $attachments);
                        }
                    }
                }
            }

            $chat->delete();

            return back()->with('success', 'Chat Deleted Successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

    }

    public function nickname(Request $request, $id)
    {
        try {
            $chat = Chat::where('id', $id)->where('chat_id', '=', null)->firstOr(function () {
                throw new \Exception('Chat not found.');
            });

            $chat->nickname = $request->nickname;
            $chat->save();

            return back()->with('success', 'Nickname Setup Successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show()
    {
        $messages = Chat::with('sender:id,image,image_driver,firstname,lastname')->where('receiver_id', auth()->id())
            ->where('seen', 0)->latest()->get()->map(function ($query) {
                if ($query->chat_id) {
                    $query->message = 'You have new reply: ' . Str::limit($query->message, 15);
                } else {
                    $query->message = 'You have new message: ' . Str::limit($query->message, 15);
                }
                $query->created_at = dateTime($query->created_at);
                return $query;

            });

        return $messages;
    }

    public function readAt($id)
    {
        $message = Chat::find($id);
        if ($message) {
            $message->seen = 1;
            $message->save();

            event(new UpdateUserMessage(Auth::id()));
            return ['status' => true];
        }

        return ['status' => false];
    }

    public function readAll()
    {

        Chat::where('receiver_id', auth()->id())->where('seen', 0)->latest()->get()->map(function ($query) {
            $query->seen = 1;
            $query->save();
        });
        event(new UpdateUserMessage(Auth::id()));
        return ['status' => true];
    }
}
