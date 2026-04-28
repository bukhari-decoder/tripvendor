@if(isset($testimonial_two) && !empty($testimonial_two['single']))
    <section class="testimonial-section-5 fix section-bg-2 section-padding">
        <div class="container">
            <div class="section-title-area">
                <div class="section-title style-2">
                    <span class="wow fadeInUp">{{ $testimonial_two['single']['title'] ?? '' }}</span>
                    <h2 class="wow fadeInUp" data-wow-delay=".3s">{{ $testimonial_two['single']['sub_title_one'] ?? '' }} <br>{{ $testimonial_two['single']['sub_title_two'] ?? '' }}</h2>
                </div>
                <div class="test-left-top">
                    <img src="{{ getFile($testimonial_two['single']['media']->image->driver, $testimonial_two['single']['media']->image->path) }}" alt="img">
                    <div class="content">
                        <h4>{{ $testimonial_two['single']['rating'] ?? '' }}</h4>
                        <p>{{ $testimonial_two['single']['rating_text'] ?? '' }}</p>
                    </div>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="testimonail-image-5">
                        <img src="{{ getFile($testimonial_two['single']['media']->background_image->driver, $testimonial_two['single']['media']->background_image->path) }}" alt="img">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="swiper testimonial-slider-5">
                        <div class="swiper-wrapper">
                            @foreach($testimonial_two['multiple'] ?? [] as $item)
                                <div class="swiper-slide">
                                    <div class="testimonial-box-items-4 box-shadow-none">
                                        <div class="testi-img">
                                            <img src="{{ getFile($item['media']->image->driver, $item['media']->image->path) }}" alt="{{ $item['name'] ?? '' }}">
                                        </div>
                                        <div class="star">
                                            @for ($i = 0; $i < $item['rating']; $i++)
                                                <i class="fas fa-star"></i>
                                            @endfor
                                        </div>
                                        <div class="icon">
                                            <img src="{{ asset(template(true).'img/testimonial/quote-01.png') }}" alt="icon">
                                        </div>
                                        <h3>
                                            {{ $item['message'] ?? '' }}
                                        </h3>
                                        <div class="client-info">
                                            <h4>{{ $item['name'] ?? '' }}</h4>
                                            <span>{{ $item['address'] ?? '' }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                        <div class="swiper-dot4 mt-4 text-center">
                            <div class="dot11"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

