
@if(isset($hero_one) && !empty($hero_one['single']))
    <section class="hero-section hero-4">
        <div class="array-button">
            <button class="array-prev"><i class="far fa-long-arrow-left"></i></button>
            <button class="array-next"><i class="far fa-long-arrow-right"></i></button>
        </div>
        <div class="swiper-container hero-slider fix">
            <div class="swiper-wrapper">
                @foreach($hero_one['multiple'] ?? [] as $item)
                    <div class="swiper-slide">
                        <div class="hero-items">
                            <div class="plane-shape">
                                <img src="{{ asset(template(true).'img/hero/new/plane-2.png') }}" alt="img">
                            </div>
                            <div class="plane-shape-2">
                                <img src="{{ asset(template(true).'img/hero/new/plane-3.png') }}" alt="img">
                            </div>
                            <div class="hero-bg bg-cover" style="background-image: url({{ getFile($item['media']->background_image->driver, $item['media']->background_image->path) }});"></div>
                            <div class="container">
                                <div class="row g-4">
                                    <div class="col-lg-12">
                                        <div class="hero-content">
                                            <h6 data-animation="fadeInUp" data-delay="1.3s">@lang($item['title'] ?? '')</h6>
                                            <h1 data-animation="fadeInUp" data-delay="1.5s">
                                                <span class="shape-text">@lang($item['sub_title_part_one'] ?? '')</span><span>@lang($item['sub_title_part_two'] ?? '')</span> <br>@lang($item['sub_title_part_three'] ?? '')
                                            </h1>
                                            <p data-animation="fadeInUp" data-delay="1.7s">{{ $item['description'] ?? '' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="container">
            <form action="{{ route('package') }}" method="GET">
                <div class="booking-list-area-1">
                    <div class="booking-list">
                        <div class="icon">
                            @if(isset($hero_one['single']['media']) && isset($hero_one['single']['media']->image))
                                <img src="{{ getFile($hero_one['single']['media']->image->driver, $hero_one['single']['media']->image->path) }}" alt="img">
                            @endif
                        </div>
                        <div class="content">
                            <h5>{{ $hero_one['single']['search_topic_one'] ?? '' }}</h5>
                            <div class="form-clt">
                                <div class="form">
                                    <select class="single-select w-100" name="destination">
                                        <option value="">@lang('Select Destination')</option>
                                        @foreach($hero_one['destination'] ?? [] as $item)
                                            <option value="{{ $item->slug }}">{{ $item->title ?? '' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="booking-list">
                        <div class="icon">
                            @if(isset($hero_one['single']['media']) && isset($hero_one['single']['media']->image_two))
                                <img src="{{ getFile($hero_one['single']['media']->image_two->driver, $hero_one['single']['media']->image_two->path) }}" alt="img">
                            @endif
                        </div>
                        <div class="content">
                            <h5>{{ $hero_one['single']['search_topic_two'] ?? '' }}</h5>
                            <div class="form-clt">
                                <div class="form">
                                    <select class="single-select w-100" name="duration">
                                        <option value="">@lang('Select Duration')</option>
                                        @foreach($hero_one['durations'] ?? [] as $duration)
                                            <option value="{{ $duration }}">{{ $duration }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="booking-list">
                        <div class="icon">
                            @if(isset($hero_one['single']['media']) && isset($hero_one['single']['media']->image_three))
                                <img src="{{ getFile($hero_one['single']['media']->image_three->driver, $hero_one['single']['media']->image_three->path) }}" alt="img">
                            @endif
                        </div>
                        <div class="content">
                            <h5>{{ $hero_one['single']['search_topic_three'] ?? '' }}</h5>
                            <div class="form-clt input-group">
                                <input id="datepicker" class="form-control" name="date" type="text" placeholder="e.g. 21-04-2025" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="booking-list">
                        <div class="icon">
                            @if(isset($hero_one['single']['media']) && isset($hero_one['single']['media']->image_four))
                                <img src="{{ getFile($hero_one['single']['media']->image_four->driver, $hero_one['single']['media']->image_four->path) }}" alt="img">
                            @endif
                        </div>
                        <div class="content">
                            <h5>{{ $hero_one['single']['search_topic_four'] ?? '' }}</h5>
                            <div class="form-clt">
                                <div class="form">
                                    <select class="single-select w-100" name="slot">
                                        <option value="">@lang('Select a slot')</option>
                                        @foreach($hero_one['slots'] ?? [] as $slot)
                                            <option value="{{ $slot }}"> {{ $slot }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="theme-btn">
                        <span>{{ $hero_one['single']['search_button'] ?? 'Search' }} <i class="far fa-search"></i></span>
                    </button>
                </div>
            </form>
        </div>
    </section>
@endif
