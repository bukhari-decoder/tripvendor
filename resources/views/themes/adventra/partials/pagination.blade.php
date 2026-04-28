<nav id="pagination" class="page-nav-wrap">
    @if ($paginator->hasPages())
        <ul class="pagination wow fadeInUp" data-wow-duration="1s" data-wow-delay="0.35s">
            @if ($paginator->onFirstPage())
                <li class="disabled page-item">
                    <a href="#" class="page-link page-numbers" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">@lang('Previous')</span>
                    </a>
                </li>
            @else
                <li class="page-item">
                    <a href="{{ $paginator->previousPageUrl() }}" class="page-link page-numbers" rel="prev">&laquo;</a>
                </li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class=" page-item">
                        <a href="#" class="page-link page-numbers">{{ $element }}</a>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active">
                                <a href="#" class="page-link page-numbers">{{ $page }}<span class="sr-only">(current)</span></a>
                            </li>
                        @else
                            <li class="page-item">
                                <a href="{{ $url}}" class="page-link page-numbers">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a href="{{ $paginator->nextPageUrl() }}" class="page-link page-numbers" rel="next">&raquo;</a>
                </li>
            @else
                <li class="disabled page-item">
                    <a href="#" class="disabled page-link page-numbers" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">@lang('Next')</span>
                    </a>
                </li>
            @endif
        </ul>
    @endif
</nav>
<style>
    .page-nav-wrap{
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 100px;
    }
    .page-numbers{
        display: flex !important;
        align-items: center;
        justify-content: center;
        border: none !important;
    }
    .page-item.active{
        color: #000;
    }
</style>
