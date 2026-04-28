<div class="offcanvas offcanvas-end message-offcanvas" tabindex="-1" id="image-generator-offcanvas"
     aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
        <a class="logo" href="{{ route('user.profile') }}"><img src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}" alt=""></a>
        <button type="button" class="cmn-btn-close" data-bs-dismiss="offcanvas" aria-label="Close"><i
                class="fa-regular fa-arrow-right"></i></button>
    </div>
    <div class="offcanvas-body">
        <div class="message-sidebar">
            <div class="header-section">
                <div class="section-title">@lang('chats')</div>
                <div class="search-bar">
                    <div class="search-form d-flex align-items-center" >
                        <input type="text" class="form-control" name="search" placeholder="Search"
                               title="Enter search keyword">
                        <span class="search-icon" title="Search"><i
                                class="fa-regular fa-magnifying-glass"></i></span>
                    </div>
                </div>
            </div>
            <div class="search-result" id="searchResultsSmall">
            </div>
            <ul class="conversations-wrapper">
                @foreach($allChat as $item)
                    <li class="chatList" data-id="{{ $item->id }}">
                        <a href="{{ route('user.chat.list', ['id'=>$item->id]) }}"  class="item-link">
                            <div class="item-header">
                                <div class="chat-title">{{ $item->sender->firstname .' '. $item->sender->lastname }}</div>
                                <div class="chat-action">
                                    <div class="chat-edit">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </div>
                                    <div class="chat-delete">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="chat-info">
                                <div class="chat-count">{{ $item->reply_count.' messages' }}</div>
                                <div class="chat-date">{{ dateTime($item->last_reply) }}</div>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
