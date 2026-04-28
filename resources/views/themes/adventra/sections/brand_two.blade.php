
@if(isset($brand_two)))
    <div class="brand-section fix section-padding">
        <div class="container">
            <p class="brand-text wow fadeInUp" data-wow-delay=".3s">
                {{ $brand_two['single']['title'] ?? '' }}
            </p>
            <div class="swiper brand-slider">
                <div class="swiper-wrapper">
                    @foreach($brand_two['multiple'] ?? [] as $item)
                        <div class="swiper-slide">
                            <div class="brand-img text-center">
                                <img src="{{ getFile($item['media']->image->driver, $item['media']->image->path) }}" alt="img">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif

