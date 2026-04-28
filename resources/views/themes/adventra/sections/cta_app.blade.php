@if(isset($cta_app) && !empty($cta_app['single']))
    <section class="cta-app-section fix section-padding section-bg pt-0">
        <div class="container">
            <div class="cta-app-wrapper bg-cover" style="background-image: url({{ getFile($cta_app['single']['media']->background_image->driver, $cta_app['single']['media']->background_image->path) }});">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="cta-app-content">
                            <div class="section-title mb-0">
                                <span class="text-white wow fadeInUp">{{ $cta_app['single']['title'] ?? '' }}</span>
                                <h2 class="text-white wow fadeInUp" data-wow-delay=".3s">{{ $cta_app['single']['sub_title'] ?? '' }}</h2>
                                <p class="mt-4 text-white wow fadeInUp" data-wow-delay=".5s">
                                    {{ $cta_app['single']['description'] ?? '' }}
                                </p>
                            </div>
                            <h6 class="app-text wow fadeInUp" data-wow-delay=".3s">Your all-in-one travel app</h6>
                            <div class="apps-items wow fadeInUp" data-wow-delay=".5s">
                                <a href="{{ $cta_app['single']['media']->apple_store_link }}">
                                    <img src="{{ getFile($cta_app['single']['media']->image->driver, $cta_app['single']['media']->image->path) }}" alt="img">
                                </a>
                                <a href="{{ $cta_app['single']['media']->play_store_link }}">
                                    <img src="{{ getFile($cta_app['single']['media']->image_two->driver, $cta_app['single']['media']->image_two->path) }}" alt="img">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6"></div>
                </div>
                <div class="app-image wow img-custom-anim-right">
                    <img src="{{ getFile($cta_app['single']['media']->image_three->driver, $cta_app['single']['media']->image_three->path) }}" alt="img">
                </div>
            </div>
        </div>
    </section>
@endif

