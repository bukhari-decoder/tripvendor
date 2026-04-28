@extends(template().'layouts.user')
@section('page_title',trans('Support Ticket'))
@section('content')
    <div class="content container-fluid">
        <div class="row justify-content-lg-center">
            <div class="col-lg-10">
                <div class="page-header custom-header d-flex align-items-center justify-content-between">
                    <div class="row align-items-end">
                        <div class="col-sm">
                            <h1 class="page-header-title">@lang("Support Ticket View")</h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-no-gutter">
                                    <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                                   href="{{ route('user.dashboard') }}">@lang('Dashboard')</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">@lang("Support Ticket View")</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <a href="{{ route('user.ticket.list') }}"
                       class="btn btn-white btn-sm">
                        <i class="fa-light fa-arrow-left pe-2"></i>@lang('Back')
                    </a>
                </div>

                <div class="card message_section">
                    <div class="card-header">
                        <div class="top-bar">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    @if($ticket->status == 0)
                                        <span class="badge bg-soft-warning text-warning">
                                            <span class="legend-indicator bg-warning"></span>@lang("Open")
                                        </span>
                                    @elseif($ticket->status == 1)
                                        <span class="badge bg-soft-success text-success">
                                             <span class="legend-indicator bg-success"></span>@lang("Answered")
                                        </span>
                                    @elseif($ticket->status == 2)
                                        <span class="badge bg-soft-info text-info">
                                            <span class="legend-indicator bg-info"></span>@lang("Customer Reply")
                                        </span>
                                    @elseif($ticket->status == 3)
                                        <span class="badge bg-soft-danger text-danger">
                                            <span class="legend-indicator bg-danger"></span>@lang("Closed")
                                        </span>
                                    @endif
                                    <span>[{{trans('Ticket#'). __($ticket->ticket) }}] {{ __($ticket->subject) }}</span>
                                </div>
                                @if($ticket->status != 3)
                                    <div>
                                        <button class="btn btn-white btn-sm set" type="button"
                                                data-route="{{ route('user.ticket.close', $ticket->id) }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#CloseTicketModal">
                                            <i class="bi bi-x-square"></i> @lang("Close")
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="message-wrapper">
                        <div class="row g-lg-0">
                            <div class="col-12">
                                <div class="inbox-wrapper">
                                    <div class="chats">
                                        @forelse($ticket->messages as $message)
                                            @if($message->admin_id == null)
                                                <div class="chat-box this-side">
                                                    <div class="text-wrapper">
                                                        <div class="text">
                                                            <p>@lang($message->message)</p>
                                                        </div>
                                                        @if(count($message->attachments) > 0)
                                                            <div class="text-info my-3 d-flex time">
                                                                @forelse($message->attachments as $k => $file)
                                                                    <a href="{{ route('user.ticket.download',encrypt($file->id)) }}"
                                                                       class="file" type="button">
                                                                        <i class="fal fa-file"></i>
                                                                        @lang('File(s)') {{ ++$k}}
                                                                    </a>
                                                                @empty
                                                                @endforelse
                                                            </div>
                                                        @endif
                                                        <span
                                                            class="time">{{ __($message->created_at->format('d M, Y h:i A')) }}</span>
                                                    </div>
                                                    <div class="img">
                                                        <img class="img-fluid"
                                                             src="{{ getFile(optional($ticket->user)->image_driver, optional($ticket->user)->image) }}"/>
                                                    </div>
                                                </div>
                                            @endif
                                            @if($message->admin_id)
                                                <div class="chat-box opposite-side">
                                                    <div class="img">
                                                        <img class="img-fluid"
                                                             src="{{ getFile($message->admin->image_driver, $message->admin->image) }}"
                                                             alt="Admin Image"/>
                                                    </div>
                                                    <div class="text-wrapper">
                                                        <div class="text">
                                                            <p>@lang($message->message)</p>
                                                        </div>
                                                        @if(count($message->attachments) > 0)
                                                            <div class="text-info my-3 d-flex time">
                                                                @forelse($message->attachments as $k => $file)
                                                                    <a href="{{ route('user.ticket.download',encrypt($file->id)) }}"
                                                                       class="file" type="button">
                                                                        <i class="fal fa-file"></i>
                                                                        @lang('File(s)') {{ ++$k}}
                                                                    </a>
                                                                @empty
                                                                @endforelse
                                                            </div>
                                                        @endif
                                                        <span
                                                            class="time">{{ __($message->created_at->format('d M, Y h:i A')) }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                        @empty
                                        @endforelse
                                    </div>

                                    <div class="typing-area">
                                        <form action="{{ route('user.ticket.reply', $ticket->id) }}"
                                              method="post" enctype="multipart/form-data">
                                            @csrf
                                            @method('put')
                                            <div class="col-sm-3">
                                                <div class="filepond" id="myFileInput" data-maxFiles="2"
                                                     data-fileSize="5000000"
                                                     data-oldFiles=""

                                                     data-label-idle="Drop files here or <span class='filepond--label-action'>Browse</span>">
                                                </div>
                                                <input name="attachments[]" class="filepond-files" type="file" hidden>
                                            </div>
                                            <div class="input-group">
                                                <div>
                                                    <button type="button" class="upload-img send-file-btn">
                                                        <i class="fal fa-paperclip"></i>
                                                    </button>
                                                </div>
                                                <input type="text"
                                                       class="form-control @error('reply_ticket') is-invalid @enderror"
                                                       name="message" value="{{old('message')}}" autocomplete="off"/>
                                                <button class="submit-btn" type="submit" name="replayTicket" value="1">
                                                    <i class="fal fa-paper-plane"></i>
                                                </button>
                                            </div>
                                            @error('message')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Close Modal -->
    <div class="modal fade" id="CloseTicketModal" tabindex="-1" role="dialog" aria-labelledby="CloseTicketModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="accountAddCardModalLabel"><i
                            class="bi bi-check2-square"></i> @lang("Confirmation")</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span>@lang("Do you want to closed this ticket?")</span>
                </div>
                <form action="" method="post" class="setRoute">
                    @csrf
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang("Close")</button>
                        <button type="submit" class="btn btn-primary">@lang("Confirm")</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/filepond.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/filepond-plugin-image-preview.css') }}">
@endpush

@push('script')
    <script src="{{ asset('assets/admin/js/filepond.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/filepond.jquery.js') }}"></script>
    <script src="{{ asset('assets/admin/js/filepond-plugin-image-preview.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/filepond-plugin-file-validate-size.js') }}"></script>
    <script src="{{ asset('assets/admin/js/filepond-plugin-file-validate-type.js') }}"></script>
    <script src="{{ asset('assets/admin/js/custom-filepond.js') }}" defer></script>
    <script>
        'use strict';
        $(document).on('click', '.set', function () {
            let url = $(this).data('route');
            $('.setRoute').attr('action', url);
        })
        $.fn.filepond.registerPlugin(
            FilePondPluginImagePreview,
            FilePondPluginFileValidateSize,
            FilePondPluginFileValidateType
        );

        $(document).ready(function () {
            $('.filepond').each(function (index, element) {
                const $element = $(element);
                const maxFiles = $element.data('maxfiles') || 1;
                const filesize = $element.data('filesize') || null;
                const fileType = $element.data('filetype') || null;
                const oldRawFiles = $element.data('oldfiles');
                const inputSelector = $element.next('.filepond-files')[0];

                let oldFiles = [];
                if (oldRawFiles) {
                    const fileList = oldRawFiles.split(",");
                    oldFiles = fileList.map(file => ({
                        source: file,
                        options: { type: 'local' }
                    }));
                }

                $element.filepond({
                    allowFileTypeValidation: !!fileType,
                    acceptedFileTypes: fileType ? fileType.split(',') : null,
                    allowMultiple: maxFiles > 1,
                    maxFiles: maxFiles,
                    allowFileSizeValidation: !!filesize,
                    maxFileSize: filesize,
                    labelMaxFileSizeExceeded: 'File is too large',
                    allowReorder: true,
                    allowRemove: true,
                    files: oldFiles
                });

                $element.on('FilePond:updatefiles', function () {
                    if (!inputSelector) return;

                    const files = $element.filepond('getFiles');
                    const dt = new DataTransfer();

                    files.forEach(fileItem => {
                        const file = fileItem.file;
                        if (file instanceof File || file instanceof Blob) {
                            dt.items.add(file instanceof File ? file : new File([file], file.name, { type: file.type }));
                        }
                    });

                    inputSelector.files = dt.files;
                });
            });
        });
    </script>
@endpush




