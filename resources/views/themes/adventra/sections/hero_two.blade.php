@if(isset($hero_two) && !empty($hero_two['single']))
    <section class="hero-section hero-5 bg-cover" style="background-image: url({{ getFile($hero_two['single']['media']->background_image->driver, $hero_two['single']['media']->background_image->path) }});">
        <div class="light-shape">
            <img src="{{ asset(template(true).'img/hero/new/light-shape.png') }}" alt="img">
        </div>
        <div class="frame-shape">
            <img src="{{ asset(template(true).'img/hero/new/frame-shape.png') }}" alt="img">
        </div>
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-xl-8">
                    <div class="hero-content">
                        <h6 class="wow fadeInUp">@lang($hero_two['single']['title'] ?? '')</h6>
                        <h1 class="wow fadeInUp" data-wow-delay=".3s">
                            <span class="shape-text">{{ $hero_two['single']['sub_title_one'] ?? '' }}</span><span>{{ $hero_two['single']['sub_title_two'] ?? '' }}</span> <br>
                            {{ $hero_two['single']['sub_title_three'] ?? '' }}
                        </h1>
                        <p class="wow fadeInUp" data-wow-delay=".5s">
                            {{ $hero_two['single']['description'] ?? '' }}
                        </p>
                    </div>
                    <form action="{{ route('package') }}" method="GET">
                        <div class="booking-list-area wow fadeInUp" data-wow-delay=".7s">
                            <div class="plane-shape float-bob-x">
                                <img src="{{ asset(template(true).'img/hero/new/plane.png') }}" alt="img">
                            </div>
                            <div class="booking-list">
                                <div class="icon">
                                    <img src="{{ asset(template(true).'img/hero/location.png') }}" alt="img">
                                </div>
                                <div class="content">
                                    <h5>{{ $hero_two['single']['search_topic_one'] ?? '' }}</h5>
                                    <div class="form-clt">
                                        <div class="form">
                                            <select class="single-select w-100" name="duration">
                                                <option value="">@lang('Select Duration')</option>
                                                @foreach($hero_two['destination'] ?? [] as $destination)
                                                    <option value="{{ $destination->slug }}">{{ $destination->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="booking-list">
                                <div class="icon">
                                    <img src="{{ asset(template(true).'img/hero/location.png') }}" alt="img">
                                </div>
                                <div class="content">
                                    <h5>{{ $hero_two['single']['search_topic_two'] ?? '' }}</h5>
                                    <div class="form-clt">
                                        <div class="form">
                                            <select class="single-select w-100" name="duration">
                                                <option value="">@lang('Select Duration')</option>
                                                @foreach($hero_two['durations'] ?? [] as $duration)
                                                    <option value="{{ $duration }}">{{ $duration }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="booking-list">
                                <div class="icon">
                                    <img src="{{ asset(template(true).'img/hero/location.png') }}" alt="img">
                                </div>
                                <div class="content">
                                    <h5>{{ $hero_two['single']['search_topic_three'] ?? '' }}</h5>
                                    <div class="form-clt input-group">
                                        <input id="datepicker" class="form-control" name="date" type="text" placeholder="e.g. 21-04-2025" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="booking-list">
                                <div class="icon">
                                    <img src="{{ asset(template(true).'img/hero/location.png') }}" alt="img">
                                </div>
                                <div class="content">
                                    <h5>{{ $hero_two['single']['search_topic_four'] ?? '' }}</h5>
                                    <div class="form-clt">
                                        <div class="form">
                                            <select class="single-select w-100" name="slot">
                                                <option value="">@lang('Select a slot')</option>
                                                @foreach($hero_two['slots'] ?? [] as $slot)
                                                    <option value="{{ $slot }}"> {{ $slot }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="theme-btn">
                                <span>{{ $hero_two['single']['search_button'] ?? 'Search' }} <i class="far fa-search"></i></span>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-xl-4">
                    <div class="hero-image-items">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="hero-image wow fadeInUp" data-wow-delay=".3s">
                                    <img src="{{ getFile($hero_two['single']['media']->image_four->driver, $hero_two['single']['media']->image_four->path) }}" alt="img">
                                </div>
                                <div class="hero-image style-2 wow fadeInUp" data-wow-delay=".5s">
                                    <img src="{{ getFile($hero_two['single']['media']->image_five->driver, $hero_two['single']['media']->image_five->path) }}" alt="img">
                                </div>
                            </div>
                            <div class="col-md-6 wow fadeInUp" data-wow-delay=".4s">
                                <div class="hero-image style-3">
                                    <img src="{{ getFile($hero_two['single']['media']->image_six->driver, $hero_two['single']['media']->image_six->path) }}" alt="img">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

