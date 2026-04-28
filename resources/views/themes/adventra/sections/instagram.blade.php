@if(isset($instagram))
    <div class="instagram-section fix">
        <div class="swiper instagram-slider">
            <div class="swiper-wrapper">
                @foreach($instagram['multiple'] ?? [] as $item)
                    <div class="swiper-slide">
                        <div class="instagram-image">
                            <img src="{{ getFile($item['media']->image->driver, $item['media']->image->path) }}" alt="img">
                            <a href="{{ $item['media']->my_link }}" class="icon">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

