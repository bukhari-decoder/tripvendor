@extends(template().'layouts.user')
@section('page_title',trans('Chatting'))
@section('content')
    <div class="content container-fluid">
        <div class="row justify-content-lg-center">
            <div class="col-lg-10">
                <div class="page-header custom-header">
                    <div class="row align-items-end">
                        <div class="col-sm">
                            <h1 class="page-header-title">@lang("Chats")</h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-no-gutter">
                                    <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('user.dashboard') }}">@lang('Dashboard')</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">@lang("Chats")</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="message-container">
                    <div class="row g-0 messageRow">
                        <div class="col-md-4">
                            <div class="message-sidebar">
                                <div class="header-section">
                                    <div class="section-title">@lang('chats')</div>
                                    <div class="search-bar d-none d-md-block">
                                        <div class="search-form d-flex align-items-center">
                                            <input type="text" class="form-control" name="search" placeholder="Search"
                                                   title="Enter search keyword" autocomplete="off">
                                            <span class="search-icon" title="Search"><i
                                                    class="fa-regular fa-magnifying-glass"></i></span>
                                        </div>
                                    </div>
                                    <div class="btn-area d-md-none">
                                        <button class="cmn-btn4" type="button" data-bs-toggle="offcanvas"
                                                data-bs-target="#image-generator-offcanvas"
                                                aria-controls="offcanvasExample">
                                            <i class="fa-light fa-list"></i>
                                        </button>
                                    </div>
                                    <div class="refresh-icon btn btn-white" title="Refresh" onclick="location.reload()">
                                        <i class="fa-solid fa-rotate-right"></i>
                                    </div>
                                </div>
                                <div class="search-result-chat" id="searchResults">
                                </div>
                                <ul class="conversations-wrapper d-none d-md-block">
                                    @forelse($allChat as $item)
                                        <li class="chatList" data-id="{{ $item->id }}">
                                            <a href="{{ route('user.chat.list', ['id'=>$item->id]) }}"  class="item-link">
                                                <div class="item-header">
                                                    @php
                                                        $username = ($item->sender_id == auth()->user()->id)
                                                            ? ($item->receiver->firstname .' '. $item->receiver->lastname)
                                                            : ($item->sender->firstname .' '. $item->sender->lastname);
                                                    @endphp
                                                    <div class="chat-title">{{ $item->nickName ?? $username }}</div>
                                                </div>

                                                <div class="chat-info">
                                                    <div class="chat-count">{{ $item->reply_count + 1 }} @lang('messages')</div>
                                                    <div class="chat-date">{{ dateTime($item->last_reply) }}</div>
                                                </div>
                                            </a>
                                            <div class="chat-action">
                                                <div class="chat-edit"
                                                     type="button"
                                                     data-bs-target="#nickName"
                                                     data-bs-toggle="modal"
                                                     data-route="{{ route('user.chat.nickname', $item->id) }}"
                                                     data-nickname="{{ $item->nickName }}"
                                                >
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </div>
                                                <div
                                                    type="button"
                                                    class="chat-delete"
                                                    data-id="{{ $item->id }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteChat"
                                                    data-route="{{ route('user.chat.delete', $item->id) }}"
                                                >
                                                    <i class="fa-regular fa-trash-can"></i>
                                                </div>
                                            </div>
                                        </li>
                                    @empty
                                        <tr>
                                            <th colspan="100%" class="text-center text-dark">
                                                <div class="no_data_iamge text-center">
                                                    <img class="no_image_size" src="{{ asset('assets/global/img/oc-error.svg') }}">
                                                </div>
                                                <p class="text-center">@lang('Chat List is empty here!.')</p>
                                            </th>
                                        </tr>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-8 chatMessageAll">
                            @if($allChat)
                                @if($chat)
                                    <div class="chat-box">
                                        <div class="header-section">
                                            @if($chat->package->owner_id == auth()->id())
                                                <div class="profile-info">
                                                    <div class="thumbs-area">
                                                        <img src="{{ getFile(optional($chat->sender)->image_driver, optional($chat->sender)->image) }}" alt="">
                                                    </div>
                                                    <div class="content-area">
                                                        <div class="title">{{ optional($chat->sender)->firstname . ' '. optional($chat->sender)->lastname }}</div>
                                                        <div class="description">@lang('Customer')</div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="profile-info">
                                                    <div class="thumbs-area">
                                                        <img src="{{ getFile(optional($chat->package->owner)->image_driver, optional($chat->package->owner)->image) }}" alt="">
                                                    </div>
                                                    <div class="content-area">
                                                        <div class="title">{{ optional($chat->package->owner)->firstname . ' '. optional($chat->package->owner)->lastname }}</div>
                                                        <div class="description">@lang('Owner')</div>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="single-btn-box d-none d-sm-flex d-flex justify-content-sm-end ">
                                                <button type="button" href="javascript:void(0);" id="printButton" data-bs-toggle="tooltip" data-bs-placement="top"
                                                        data-bs-title="Save Document" class="single-btn active"><i class="fas fa-print"></i></button>
                                            </div>
                                        </div>

                                        @include(template().'user.chat.partials.chat')
                                        @include(template().'user.chat.partials.typing_area')
                                    </div>
                                @else
                                    <div class="withoutMessage">
                                        <div class="messageText">
                                            <h5 class="message-text">@lang("Click On Any Conversation.")</h5>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="withoutMessage">
                                    <div class="messageText">
                                        <h5 class="message-text">@lang("You don't have any active chats.")</h5>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteChat" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteChatLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="deleteChatLabel">
                        <span class="highlight"><i class="bi bi-list-ul"></i></span>
                        <span>@lang('Confirm Deletion')</span>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <small class="text-cap text-danger message-Text">
                        @lang('Deleting this conversation is permanent. Are you absolutely sure you want to proceed?')
                    </small>
                    <form id="deleteChatForm" method="POST" action="">
                        @csrf
                        @method('DELETE')


                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">@lang('Cancel')</button>
                            <button type="button" id="confirmDeleteButton" class="btn btn-danger">@lang('Delete')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="nickName" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="nickNameLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="nickNameLabel">
                        <span class="highlight"><i class="fa-regular fa-pen-to-square"></i></span>
                        <span>@lang('Nickname Setup')</span>
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="safetyTips">
                        <form id="editChatForm" method="POST" action="">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-12">
                                    <label class="form-label" for="nickname">@lang('Setup a nickname')</label>
                                    <input class="form-control" type="text" name="nickname" value="" id="nickname" />
                                </div>
                            </div>
                            <div class="d-flex justify-content-end pt-3">
                                <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">@lang('Cancel')</button>
                                <button type="button" id="confirmEditButton" class="btn btn-success">@lang('Set nickname')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include(template().'user.chat.partials.viewOffcanvas')
@endsection
@push('style')
    <style>
        .chat-delete{
            border: none;
            background: none;
            cursor: pointer;
        }
        .search-avatar {
            width: 30px;
            height: 30px;
            border-radius: 62%;
            margin-right: 10px;
        }
        .modal-header {
            padding: 20px !important;
            border-bottom: 1px solid rgba(231, 234, 243, 0.7);
        }
        .message-Text{
            padding: 0 21px 24px 11px !important;
        }
        .refresh-icon {
            cursor: pointer;
            font-size: 1.2rem;
            color: #333;
        }
        .refresh-icon:hover {
            color: #007bff;
        }
        .message-container .messageRow{
            height: 660px;
        }
        .message-bubble-right .newImage{
            justify-content: right;
            align-items: end;
        }
    </style>
@endpush


@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const printBtn = document.getElementById('printButton');
            if (printBtn) {
                printBtn.addEventListener('click', function () {
                    window.print();
                });
            }
        });
        document.addEventListener('DOMContentLoaded', function () {
            let deleteModal = document.getElementById('deleteChat');
            let deleteForm = document.getElementById('deleteChatForm');
            let confirmDeleteButton = document.getElementById('confirmDeleteButton');

            deleteModal.addEventListener('show.bs.modal', function (event) {
                let button = event.relatedTarget;
                let route = button.getAttribute('data-route');
                deleteForm.action = route;
            });

            confirmDeleteButton.addEventListener('click', function () {
                deleteForm.submit();
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
            const editModal = document.getElementById('nickName');
            const editForm = document.getElementById('editChatForm');
            const confirmEditButton = document.getElementById('confirmEditButton');
            const nicknameInput = document.getElementById('nickname');

            editModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const route = button.getAttribute('data-route');
                const nickname = button.getAttribute('data-nickname');

                editForm.action = route;
                nicknameInput.value = nickname;
            });

            confirmEditButton.addEventListener('click', function () {
                editForm.submit();
            });
        });

        document.addEventListener('DOMContentLoaded', (event) => {
            document.querySelectorAll('.chat-edit').forEach(editButton => {
                editButton.addEventListener('click', function() {
                    const chatItem = this.closest('.chatList');
                    const chatTitle = chatItem.querySelector('.chat-title');
                    const editInput = chatItem.querySelector('.edit-input');

                    chatTitle.classList.add('d-none');
                    editInput.classList.remove('d-none');
                    editInput.focus();

                    editInput.addEventListener('blur', function() {
                        chatTitle.textContent = editInput.value;
                        chatTitle.classList.remove('d-none');
                        editInput.classList.add('d-none');
                    });
                });
            });
        });
        $(document).ready(function() {
            let currentChatId = @json($chat ? $chat->id : null);

            if (currentChatId !== null) {
                $('.chatList').each(function() {
                    if ($(this).data('id') == currentChatId) {
                        $(this).addClass('active');
                    }
                });
            }

            $('.chatList').on('click', function() {
                $('.chatList').removeClass('active');
                if ($(this).data('id') == currentChatId) {
                    $(this).addClass('active');
                }
            });

            function hideSearchResults() {
                $('#searchResults').empty().hide();
                $('#searchResultsSmall').empty().hide();
            }

            $('input[name="search"]').on('keyup', function () {
                let searchValue = $(this).val().trim();
                let searchResultsId = window.innerWidth > 767 ? '#searchResults' : '#searchResultsSmall';

                if (searchValue === '') {
                    hideSearchResults();
                    return;
                }

                $.ajax({
                    url: "{{ route('user.chat.search') }}",
                    method: 'GET',
                    data: { search: searchValue },
                    success: function(response) {
                        $(searchResultsId).empty();

                        if (response && response.length > 0) {
                            let resultsHTML = '';

                            response.forEach(function(result) {
                                resultsHTML += `
                                 <div class="search-item">
                                    <img src="${result.image}" alt="Avatar" class="search-avatar">
                                    <a href="${result.url}">${result.sender.firstname} ${result.sender.lastname}</a>
                                </div>`;
                            });

                            $(searchResultsId).html(resultsHTML).show();
                        } else {
                            $(searchResultsId).html('<div class="search-item">No results found</div>').show();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });
        });
    </script>

    @if($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                @foreach ($errors->all() as $error)
                Notiflix.Notify.failure(@json($error));
                @endforeach
            });
        </script>
    @endif
@endpush



