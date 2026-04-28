@if(isset($about_three) && !empty($about_three['single']))
    <section class="about-section-3 fix section-padding">
        <div class="container">
            <div class="about-wrapper-3">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-6">
                        <div class="about-image">
                            <img src="{{ getFile($about_three['single']['media']->image->driver, $about_three['single']['media']->image->path) }}" alt="img">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="about-content">
                            <div class="section-title mb-0">
                                <span class="wow fadeInUp">@lang($about_three['single']['title'] ?? '')</span>
                                <h2 class="wow fadeInUp" data-wow-delay=".3s">{{ $about_three['single']['sub_title'] ?? '' }}</h2>
                                <p class="mt-3 wow fadeInUp" data-wow-delay=".5s">
                                    {{ $about_three['single']['description'] ?? '' }}
                                </p>
                            </div>
                            <div class="about-icon-items wow img-custom-anim-left">
                                <div class="row g-4">
                                    @foreach($about_three['multiple'] ?? [] as $item)
                                        <div class="col-xl-4 col-lg-6 col-md-6">
                                            <div class="icon-items">
                                                <i class="{{ $item['media']->icon }}"></i>
                                                <h4>{{ $item['title'] }}</h4>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <a href="{{ $about_three['single']['media']->my_link }}" class="theme-btn wow fadeInUp" data-wow-delay=".3s">
                                <span>{{ $about_three['single']['button'] ?? 'More' }}</span> <i class="far fa-long-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

