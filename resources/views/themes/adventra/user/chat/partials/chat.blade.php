<div class="chat-box-inner" id="chatContainer">
    @if(isset($chat))
        @php
            $bubbleClass = $chat->sender_id == auth()->user()->id ? 'message-bubble-right' : 'message-bubble-left';
            $hasMessage = !empty($chat->message);
            $isAudio = is_audio($chat->attachment);
        @endphp

        <div class="message-bubble {{ $bubbleClass }}">
            <div class="tfg">
                @if($hasMessage)
                    <div class="d-flex gap-2 insideTfg">
                        <div class="message-text">{{ $chat->message }}</div>
                        <div class="message-thumbs">
                            <img src="{{ getFile($chat->sender->image_driver, $chat->sender->image) }}" alt="Sender Image">
                        </div>
                    </div>
                @endif

                @if($chat->attachment)
                    <div class="attachment-wrapper">
                        <div class="row attachment-row">
                            @php
                                $driver = $chat->driver;
                                $images = null;
                                $audio = null;

                                if ($isAudio) {
                                    $audio = $chat->attachment;
                                } else {
                                    $images = json_decode($chat->attachment);
                                }
                            @endphp
                            @if($images && is_array($images))
                                @foreach($images as $file)
                                    <div class="col-md-6 attachment-col d-flex align-items-center gap-2">
                                        <a class="attachment">
                                            <img class="supportTicketImage" src="{{ getFile($driver, $file) }}" />
                                        </a>
                                        @if(!$hasMessage)
                                            <div class="message-thumbs image-thumb">
                                                <img src="{{ getFile($chat->sender->image_driver, $chat->sender->image) }}" alt="Sender Image">
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @elseif($audio)
                                <div class="col-md-6 attachment-col d-flex align-items-center gap-2">
                                    <div class="audio-wrapper">
                                        <audio controls>
                                            <source src="{{ getAudioFile($driver, $audio) }}" type="audio/mpeg">
                                        </audio>
                                    </div>
                                    @if(!$hasMessage)
                                        <div class="message-thumbs audio-thumb-left">
                                            <img src="{{ getFile($chat->sender->image_driver, $chat->sender->image) }}" alt="Sender Image">
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @foreach($chat->reply as $item)
            @php
                $bubbleClass = $item->sender_id == auth()->user()->id ? 'message-bubble-right' : 'message-bubble-left';
                $isAudio = is_audio($item->attachment);
                $hasMessage = !empty($item->message);
            @endphp

            <div class="message-bubble {{ $bubbleClass }}">
                <div class="fgfxvcd">
                    @if($item->attachment)
                        <div class="attachment-wrapper">
                            <div class="row attachment-row align-items-center">
                                @php
                                    if ($isAudio) {
                                        $audio = $item->attachment;
                                        $driver = $item->driver;
                                        $images = null;
                                    } else {
                                        $images = json_decode($item->attachment);
                                        $driver = $item->driver;
                                        $audio = null;
                                    }
                                @endphp

                                @if($images && is_array($images))
                                    <div class="d-flex align-items-end gap-3 insideImages">
                                        <div class="row attachment-row newImage g-2">
                                            @foreach($images as $file)
                                                <div class="col-6 attachment-col {{ $bubbleClass === 'message-bubble-right' ? 'col-right' : '' }}">
                                                    <a class="attachment">
                                                        <img class="supportTicketImage" src="{{ getFile($driver, $file) }}" />
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>

                                        @if(!$hasMessage)
                                            <div class="message-thumbs d-flex align-items-center imageThumbSingle">
                                                <img src="{{ getFile($item->sender->image_driver, $item->sender->image) }}" alt="Sender Image">
                                            </div>
                                        @endif
                                    </div>
                                @elseif($audio)
                                    <div class="col-md-6 attachment-col {{ $bubbleClass === 'message-bubble-right' ? 'col-right' : '' }} d-flex align-items-center gap-2">
                                        <div class="audio-wrapper">
                                            <audio controls>
                                                <source src="{{ getAudioFile($driver, $audio) }}" type="audio/mpeg">
                                            </audio>
                                        </div>
                                        @if(!$hasMessage || $isAudio)
                                            <div class="message-thumbs audio-thumb">
                                                <img src="{{ getFile($item->sender->image_driver, $item->sender->image) }}" alt="Sender Image">
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($hasMessage)
                        <div class="d-flex justify-content-end leftItem gap-2">
                            <div class="message-text">{{ $item->message }}</div>
                            <div class="message-thumbs">
                                <img src="{{ getFile($item->sender->image_driver, $item->sender->image) }}" alt="Sender Image">
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    @else
        <div class="messageText">
            <h5 class="message-text">@lang('How Can I Help You?')</h5>
        </div>
    @endif
</div>


@push('style')
    <style>
        .message-bubble {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 10px;
        }
        .message-bubble-left {
            align-self: flex-start;
        }
        .message-bubble-left .tfg .insideTfg{
            flex-direction: row-reverse;
        }
        .message-bubble-left .insideImages{
            flex-direction: row-reverse;
        }
        .message-bubble-right {
            align-self: flex-end;
            flex-direction: row-reverse;
        }
        .message-thumbs img {
            border-radius: 50%;
            width: 30px;
            height: 30px;
        }
        .attachment-wrapper {
            margin-top: 5px;
        }
        .attachment-col {
            margin-bottom: 5px;
        }
        .col-right .attachment {
            justify-content: flex-end;
        }
        .audio-wrapper audio {
            max-width: 250px;
            border-radius: 10px;
            display: block;
        }
        .audio-thumb{
            margin-right: 10px !important;
        }
        .audio-thumb-left{
            margin-left: 10px !important;
        }
        .newImage {
            --bs-gutter-x: 0.5rem;
            --bs-gutter-y: 0.5rem;
        }

        .newImage .attachment-col img.supportTicketImage {
            width: 100%;
            border-radius: 8px;
            object-fit: cover;
        }
        .imageThumbSingle{
            margin-left: 0 !important;
        }
        .message-bubble-left .fgfxvcd .message-thumbs{
            margin-left: 0 !important;
        }
        .message-bubble-left .fgfxvcd .newImage {
            padding-left: 40px !important;
        }
        .message-bubble-right .fgfxvcd .newImage {
            padding-right: 40px !important;
        }
        .message-bubble-right .fgfxvcd .message-thumbs{
            margin-left: 0 !important;
        }
    </style>
@endpush
@push('script')
    <script>
        window.addEventListener('load', function () {
            const chatContainer = document.getElementById('chatContainer');
            if (chatContainer) {
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }
        });
    </script>
@endpush
