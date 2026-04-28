@extends(template() . 'layouts.app')
@section('title',trans('Destinations'))
@section('content')
    <div class="trending-destinations section-padding">
        <div class="container">
            <div class="row g-4">
                @forelse($destinations ?? [] as $item)
                    <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".3s">
                        <div class="trending-destinations-card-items mt-0">
                            <div class="destinations-img">
                                <img src="{{ getFile($item->thumb_driver, $item->thumb) }}" alt="{{ $item->title ?? '' }}">
                                <ul class="destinations-content">
                                    <li class="title">
                                        <a href="{{ route('destination.details', $item->slug) }}">{{ $item->title.', '.$item->countryTake->name }}</a>
                                    </li>
                                </ul>
                                <div class="icon">
                                    <a href="{{ route('destination.details', $item->slug) }}">
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    @include('empty')
                @endforelse
            </div>
            {{ $destinations->appends(request()->query())->links(template().'partials.pagination') }}
        </div>
    </div>
@endsection
