@if(isset($testimonial_one) && !empty($testimonial_one['single']))
    <section class="testimonial-section-4 section-padding">
        <div class="container">
            <div class="section-title-area">
                <div class="section-title">
                    <span class="wow fadeInUp">{{ $testimonial_one['single']['title'] ?? '' }}</span>
                    <h2 class="wow fadeInUp" data-wow-delay=".3s">
                        {{ $testimonial_one['single']['sub_title_one'] ?? '' }} <br> {{ $testimonial_one['single']['sub_title_two'] ?? '' }}
                    </h2>
                </div>
                <p class="wow fadeInUp" data-wow-delay=".5">{{ $testimonial_one['single']['description_one'] ?? '' }}<br>{{ $testimonial_one['single']['description_two'] ?? '' }}<br>{{ $testimonial_one['single']['description_three'] ?? '' }}</p>
            </div>
            <div class="swiper testimonial-slider-4 overflow-hidden">
                <div class="swiper-wrapper">
                    @foreach($testimonial_one['multiple'] ?? [] as $item)
                        <div class="swiper-slide">
                            <div class="testimonial-box-items-4">
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
            </div>
        </div>
    </section>
@endif

