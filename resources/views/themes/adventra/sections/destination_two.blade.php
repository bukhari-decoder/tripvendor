@if(isset($destination_two))
    <section class="destination-section-22 fix section-padding pt-0">
        <div class="destination-wrapper-22">
            <div class="swiper destination-auto-slider">
                <div class="swiper-wrapper slide-transtion">
                    @foreach($destination_two['destinations'] ?? [] as $item)
                        <div class="swiper-slide brand-slide-element">
                            <div class="destination-items">
                                <div class="destination-thumb">
                                    <img src="{{ getFile($item->thumb_driver, $item->thumb) }}" alt="{{ $item->title ?? '' }}">
                                </div>
                                <div class="destination-content">
                                    <h3><a href="{{ route('destination.details', $item->slug) }}">{{ $item->title ?? '' }}</a></h3>
                                    <p>{{ count($item->place) }} + @lang('Places')</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="destination-wrapper-22 style-2">
            <div dir="rtl" class="swiper destination-auto-slider-2">
                <div class="swiper-wrapper slide-transtion">
                    @foreach($destination_two['destinations'] ?? [] as $item)
                        <div class="swiper-slide brand-slide-element">
                            <div class="destination-items">
                                <div class="destination-thumb">
                                    <img src="{{ getFile($item->thumb_driver, $item->thumb) }}" alt="{{ $item->title ?? '' }}">
                                </div>
                                <div class="destination-content">
                                    <h3><a href="{{ route('destination.details', $item->slug) }}">{{ $item->title ?? '' }}</a></h3>
                                    <p>{{ count($item->place) }} + @lang('Places')</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <script>
            window.addEventListener('load', () => {
                const slides = document.querySelectorAll('.swiper-slide.brand-slide-element');

                slides.forEach((slide, index) => {
                    if ((index + 1) % 2 === 0) {
                        const img = slide.querySelector('.destination-thumb img');
                        if (img) {
                            img.style.borderRadius = '50%';
                            img.style.objectFit = 'cover';
                            img.style.width = '192px';
                            img.style.height = '185px';
                        }
                    }
                });
            });
        </script>
    </section>
@endif

