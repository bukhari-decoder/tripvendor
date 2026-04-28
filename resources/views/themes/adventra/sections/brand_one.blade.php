@if(isset($brand_one) && !empty($brand_one['single']))
    <div class="brand-section fix section-padding sect-bg pt-5">
        <div class="container">
            <p class="brand-text wow fadeInUp">
                {{ $brand_one['single']['title'] ?? '' }}
            </p>
            <div class="swiper brand-slider">
                <div class="swiper-wrapper">
                    @foreach($brand_one['multiple'] ?? [] as $item)
                        <div class="swiper-slide">
                            <div class="brand-img text-center">
                                <img src="{{ getFile($item['media']->image->driver, $item['media']->image->path) }}" alt="@lang('Brand Image')">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif

