
@if(isset($about_two) && !empty($about_two['single']))
    <section class="about-section section-padding fix">
        <div class="container">
            <div class="about-wrappper-2">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="about-image-2">
                            <div class="percent-shape">
                                <img src="{{ getFile($about_two['single']['media']->image->driver, $about_two['single']['media']->image->path) }}" alt="img">
                            </div>
                            <div class="row g-4">
                                <div class="col-lg-6 col-md-6">
                                    <div class="about-img-2 wow fadeInUp" data-wow-delay=".3s">
                                        <img src="{{ getFile($about_two['single']['media']->image_two->driver, $about_two['single']['media']->image_two->path) }}" alt="img">
                                    </div>
                                    <div class="about-img-2 style-2 wow fadeInUp" data-wow-delay=".5s">
                                        <img src="{{ getFile($about_two['single']['media']->image_three->driver, $about_two['single']['media']->image_three->path) }}" alt="img">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".4s">
                                    <div class="about-img-3">
                                        <img src="{{ getFile($about_two['single']['media']->image_four->driver, $about_two['single']['media']->image_four->path) }}" alt="img">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="about-content">
                            <div class="section-title">
                                <span class="wow fadeInUp">{{ $about_two['single']['title'] ?? '' }}</span>
                                <h2 class="wow fadeInUp" data-wow-delay=".3s">
                                    {{ $about_two['single']['sub_title_one'] ?? '' }} <br>
                                    {{ $about_two['single']['sub_title_two'] ?? '' }}
                                </h2>
                            </div>
                            <p class="mt-3 mt-md-0 wow fadeInUp" data-wow-delay=".5s">
                                {{ $about_two['single']['description'] ?? '' }}
                            </p>
                            <div class="about-area">
                                @foreach($about_two['multiple'] ?? [] as $item)
                                    <div class="about-items wow fadeInUp" data-wow-delay=".3s">
                                        <div class="icon">
                                            <i class="{{ $item['media']->icon }}"></i>
                                        </div>
                                        <div class="content">
                                            <h4>
                                                {{ $item['title'] ?? '' }}
                                            </h4>
                                            <p>
                                                {{ $item['sub_title_one'] ?? '' }} <br> {{ $item['sub_title_two'] ?? '' }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="author-items wow fadeInUp" data-wow-delay=".3s">
                                <a href="{{ $about_two['single']['media']->my_link }}" class="theme-btn">
                                    <span>{{ $about_two['single']['button'] ?? '' }}</span> <i class="far fa-long-arrow-right"></i>
                                </a>
                                <div class="author-contact">
                                    <div class="icon">
                                        <img src="{{ asset(template(true).'img/call.png') }}" alt="img">
                                    </div>
                                    <div class="content">
                                        <span>{{ $about_two['single']['call_text'] ?? '' }}</span>
                                        <h6><a href="tel:{{ $about_two['single']['call_value'] ?? '' }}">{{ $about_two['single']['call_value'] ?? '' }}</a></h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

