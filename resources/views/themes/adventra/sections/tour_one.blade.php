@if(isset($tour_one) && !empty($tour_one['single']))
    <section class="tour-section fix section-padding">
        <div class="container">
            <div class="section-title text-center">
                <span class="wow fadeInUp">{{ $tour_one['single']['title'] ?? '' }}</span>
                <h2 class="wow fadeInUp" data-wow-delay=".3s">{{ $tour_one['single']['sub_title'] ?? '' }}</h2>
                <p class="mt-3 mt-mb-0 wow fadeInUp" data-wow-delay=".5s">
                    {{ $tour_one['single']['description'] ?? '' }}
                </p>
            </div>
            <div class="row">
                @foreach($tour_one['packages'] ?? [] as $package)
                    <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay=".2s">
                        <div class="tour-box-items tour-single">
                            <div class="tour-box-items-inner">
                                <div class="thumb">
                                    <img src="{{ getFile($package->thumb_driver, $package->thumb) }}" alt="{{ $package->title ?? '' }}">
                                </div>
                                <div class="content">
                                    <span>{{ $package->countryTake->name ?? '' }}</span>
                                    <h4>
                                        <a href="{{ route('package.details', $package->slug) }}">
                                            @lang($package->title)
                                        </a>
                                    </h4>
                                    <h6>@lang('From')
                                        <span>{{ discountPrice($package) }}</span>
                                        @if($package->discount == 1)
                                            <del>{{ $package->adult_price ?? 0 }}</del>
                                        @endif
                                    </h6>
                                    <ul class="list">
                                        <li>
                                            <i class="far fa-calendar"></i>
                                            {{ $package->duration ?? '' }}
                                        </li>
                                        <li>
                                            <i class="far fa-flag"></i>
                                            {{ $package->place_count .' places' }}
                                        </li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

