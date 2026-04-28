@if(isset($tour_package) && !empty($tour_package['single']))
    <section class="tour-package-section fix section-bg-2 section-padding">
        <div class="container">
            <div class="section-title text-center">
                <span class="wow fadeInUp">{{ $tour_package['single']['title'] ?? '' }}</span>
                <h2 class="wow fadeInUp" data-wow-delay=".3s">
                    {{ $tour_package['single']['sub_title'] ?? '' }}
                </h2>
                <p class="mt-3 wow fadeInUp" data-wow-delay=".5s">
                    {{ $tour_package['single']['description_one'] ?? '' }} <br> {{ $tour_package['single']['description_two'] ?? '' }}
                </p>
            </div>
            <div class="row">
                @php
                    $tourItems = is_array($tour_package['multiple']) ? $tour_package['multiple'] : $tour_package['multiple']->toArray();
                @endphp

                @foreach(array_slice($tourItems, 0, 3) as $item)
                    <div class="col-xl-4 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".3s">
                        <div class="tour-main-item mt-30">
                            <div class="tour-box-items-3 bg-cover" style="background-image: url({{ getFile($item['media']->background_image->driver, $item['media']->background_image->path) }});">
                                <div class="tour-content">
                                    <h3>
                                        @lang($item['heading_one'] ?? '') <br> {{ $item['heading_two'] ?? '' }} <br>
                                        {{ $item['heading_three'] ?? '' }}
                                    </h3>
                                    <a href="{{ route('package') }}" class="theme-btn">
                                        <span>{{ $item['button'] ?? 'Book Now' }}</span> <i class="far fa-long-arrow-right"></i>
                                    </a>
                                </div>
                                <div class="percent-image">
                                    <img src="{{ getFile($item['media']->image->driver, $item['media']->image->path) }}" alt="img">
                                </div>
                            </div>
                            <div class="shape">
                                <img src="{{ asset(template(true).'img/tour/shape1.png') }}" alt="img">
                            </div>
                        </div>
                    </div>
                @endforeach

                @foreach(array_slice($tourItems, 3, 2) as $item)
                    <div class="col-xl-6 col-lg-12 col-md-12 wow fadeInUp" data-wow-delay=".3s">
                        <div class="tour-box-items-5 bg-cover" style="background-image: url({{ getFile($item['media']->background_image->driver, $item['media']->background_image->path) }});">
                            <div class="tour-content">
                                <span>@lang($item['heading_one'] ?? '')</span>
                                <h3>
                                    {{ $item['heading_two'] ?? '' }}
                                </h3>
                                <p>{{ $item['heading_three'] ?? '' }}</p>
                                <a href="{{ route('package') }}" class="theme-btn">
                                    <span>{{ $item['button'] ?? 'Book Now' }}</span> <i class="far fa-long-arrow-right"></i>
                                </a>
                            </div>
                            <div class="percent-image">
                                <img src="{{ getFile($item['media']->image->driver, $item['media']->image->path) }}" alt="img">
                            </div>
                            <div class="shape-image">
                                <img src="{{ asset(template(true).'img/tour/shape1.png') }}" alt="img">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

