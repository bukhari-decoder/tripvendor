@if(isset($marquee_two))
    <div class="marquee-section fix section-padding pt-0">
        @php
            $items = collect($marquee_two['multiple'] ?? []);
            $chunks = $items->chunk(ceil($items->count() / 2));
        @endphp
        <div class="marque-wrapper">
            <div class="swiper text-slider">
                <div class="swiper-wrapper slide-transtion">
                    @foreach($chunks[0] ?? [] as $item)
                        <div class="swiper-slide brand-slide-element">
                            <div class="marque-text">
                                <img src="{{ asset(template(true).'img/marque.png') }}" alt="img">
                                <h3>{{ $item['title'] ?? '' }}</h3>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="marque-wrapper style-2">
            <div dir="rtl" class="swiper text-slider-2">
                <div class="swiper-wrapper slide-transtion">
                    @foreach($chunks[1] ?? [] as $item)
                        <div class="swiper-slide brand-slide-element">
                            <div class="marque-text">
                                <img src="{{ asset(template(true).'img/marque.png') }}" alt="img">
                                <h3>{{ $item['title'] ?? '' }}</h3>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif

