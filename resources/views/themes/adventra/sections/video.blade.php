@if(isset($video) && !empty($video['single']))
    <div class="vedio-area pt-0 fix bg-cover" style="background-image: url({{ getFile($video['single']['media']->background_image->driver, $video['single']['media']->background_image->path) }});">
        <div class="play-btn">
            <a href="{{ $video['single']['media']->my_link }}" class="video-btn video-popup">
                <i class="fas fa-play"></i>
            </a>
        </div>
        <div class="tour-section2 pt-0 fix">
            <div class="container">
                <div class="row g-4">
                    @foreach($video['multiple'] ?? [] as $key => $item)
                        <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".3s">
                            <div class="tour-main-item">
                                <div class="tour-box-items-3 {{ $key == 1 ? 'style-2' : ($key == 2 ? 'style-3' : '') }} bg-cover"
                                     style="background-image: url({{ getFile($item['media']->background_image->driver, $item['media']->background_image->path) }});">

                                <div class="tour-content">
                                        <h3>
                                            @lang($item['heading_one'] ?? '') <br> {{ $item['heading_two'] ?? '' }} <br>
                                            {{ $item['heading_three'] ?? '' }}
                                        </h3>
                                        <a href="{{ route('page','packages') }}" class="theme-btn">
                                            <span>{{ $item['button'] ?? 'Book Now' }}</span> <i class="far fa-long-arrow-right"></i>
                                        </a>
                                    </div>
                                    <div class="percent-image">
                                        <img src="{{ getFile($item['media']->image->driver, $item['media']->image->path) }}" alt="img">
                                    </div>
                                </div>
                                <div class="shape">
                                    <img src="{{ asset(template(true).'img/tour/shape2.png') }}" alt="img">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <style>
        .vedio-area{
            margin-bottom: 100px;
        }
    </style>
@endif

