@if(isset($destination_one) && !empty($destination_one['single']))
    <section class="destination-section-3 fix section-padding pt-0">
        <div class="container">
            <div class="section-title-area lg-center">
                <div class="section-title">
                    <span class="wow fadeInUp">{{ $destination_one['single']['title'] ?? '' }}</span>
                    <h2 class="wow fadeInUp" data-wow-delay=".3s">{{ $destination_one['single']['sub_title'] ?? '' }}</h2>
                </div>
                <a href="{{ route('destination') }}" class="theme-btn wow fadeInUp" data-wow-delay=".5s">
                    <span>{{ $destination_one['single']['button'] ?? 'Explore More' }}</span> <i class="far fa-long-arrow-right"></i>
                </a>
            </div>
            <div class="destination-wrapper-4">
                @foreach($destination_one['destinations'] ?? [] as $item)
                    <div class="destination-image-items-4 wow fadeInUp" data-wow-delay=".2s">
                        <img src="{{ getFile($item->country?->image_driver, $item->country?->image) }}" alt="{{ $item->country?->name ?? '' }}">
                        <a href="{{ route('destination', ['country' => $item->iso2]) }}" class="icon">
                            <i class="far fa-arrow-right"></i>
                        </a>
                        <div class="content">
                            <h4><a href="{{ route('destination', ['country' => $item->iso2]) }}">{{ $item->name .', '.$item->country?->name }}</a></h4>
                            <span><b>{{ $item->destinations_count ?? 0 }}</b> @lang('Destination')</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

