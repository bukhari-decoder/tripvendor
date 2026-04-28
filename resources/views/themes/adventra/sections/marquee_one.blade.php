@if(isset($marquee_one) && !empty($marquee_one['single']))
    <div class="marquee-section fix section-padding pt-0">
        @php
            $items = collect($marquee_one['multiple'] ?? []);
            $chunks = $items->chunk(ceil($items->count() / 2));
        @endphp

        <div class="marque-wrapper style-2">
            <div class="swiper text-slider">
                <div class="swiper-wrapper slide-transtion">
                    @foreach($chunks[0] ?? [] as $item)
                    <div class="swiper-slide brand-slide-element">
                        <div class="marque-text">
                            <img src="{{ getFile($item['media']->image->driver, $item['media']->image->path) }}" alt="img">
                            <h3>{{ $item['title'] ?? '' }}</h3>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="marque-wrapper style-3">
            <div dir="rtl" class="swiper text-slider-2">
                <div class="swiper-wrapper slide-transtion">
                    @foreach($chunks[1] ?? [] as $item)
                    <div class="swiper-slide brand-slide-element">
                        <div class="marque-text">
                            <img src="{{ getFile($item['media']->image->driver, $item['media']->image->path) }}" alt="img">
                            <h3>{{ $item['title'] ?? '' }}</h3>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif

