@if(isset($trending_destinations) && !empty($trending_destinations['single']))
    <section class="trending-destinations section-padding fix">
        <div class="container">
            <div class="section-title-area lg-center">
                <div class="section-title">
                    <span class="wow fadeInUp">{{ $trending_destinations['single']['title'] ?? '' }}</span>
                    <h2 class="wow fadeInUp" data-wow-delay=".3s">{{ $trending_destinations['single']['sub_title'] ?? '' }}</h2>
                </div>
                <a href="{{ route('destination') }}" class="theme-btn theme-btn-2 wow fadeInUp" data-wow-delay=".5s">
                    <span>{{ $trending_destinations['single']['button'] ?? 'Explore More' }}</span> <i class="far fa-long-arrow-right"></i>
                </a>
            </div>
            <div class="row">
                @foreach($trending_destinations['destinations'] ?? [] as $item)
                    <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".3s">
                        <div class="trending-destinations-card-items">
                            <div class="destinations-img">
                                <img src="{{ getFile($item->thumb_driver, $item->thumb) }}" alt="{{ $item->title ?? '' }}">
                                <div class="icon">
                                    <a href="{{ route('destination.details', $item->slug) }}">
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                                <ul class="destinations-content">
                                    <li class="title">
                                        <a href="{{ route('destination.details', $item->slug) }}">{{ $item->title ?? '' }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
