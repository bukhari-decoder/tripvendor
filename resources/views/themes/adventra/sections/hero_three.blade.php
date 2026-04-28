@if(isset($hero_three)  && !empty($hero_three['single']))
    <section class="hero-section hero-3 bg-cover" style="background-image: url({{ getFile($hero_three['single']['media']->background_image->driver, $hero_three['single']['media']->background_image->path) }});">
        <div class="container-fluid">
            <div class="row g-4 justify-content-between align-items-center">
                <div class="col-xxl-7 col-lg-6">
                    <div class="hero-content">
                        <h1 class="wow fadeInUp" data-wow-delay=".3s">
                            {{ $hero_three['single']['sub_title_one'] ?? '' }}<br>
                            {{ $hero_three['single']['sub_title_two'] ?? '' }}
                        </h1>
                        <p class="wow fadeInUp" data-wow-delay=".5s">
                            {{ $hero_three['single']['description'] ?? '' }}
                        </p>
                        <div class="button-items">
                            <a href="{{ route('page','packages') }}" class="theme-btn wow fadeInUp" data-wow-delay=".3s">
                                <span>{{ $hero_three['single']['button_one'] ?? '' }}</span> <i class="far fa-long-arrow-right"></i>
                            </a>
                            <a href="{{ route('page','destinations') }}" class="theme-btn bg-2 wow fadeInUp" data-wow-delay=".5s">
                                <span>{{ $hero_three['single']['button_two'] ?? '' }}</span> <i class="far fa-long-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-lg-6 col-md-8 col-sm-10">
                    <div class="destination-box-items wow img-custom-anim-left">
                        <h3>{{ $hero_three['single']['heading'] ?? '' }}</h3>
                        <p>{{ $hero_three['single']['sub_heading_one'] ?? '' }} <b>{{ $hero_three['single']['sub_heading_two'] ?? '' }}</b>{{ $hero_three['single']['sub_heading_three'] ?? '' }}</p>
                        <form action="{{ route('package') }}" method="GET">
                            <div class="booking-list-area">
                                <div class="booking-list">
                                    <div class="icon">
                                        <img src="{{ asset(template(true).'img/hero/location.png') }}" alt="img">
                                    </div>
                                    <div class="content">
                                        <h5>{{ $hero_three['single']['search_topic_one'] ?? '' }}</h5>
                                        <div class="form-clt">
                                            <div class="form">
                                                <select class="single-select w-100" name="duration">
                                                    <option value="">@lang('Select Duration')</option>
                                                    @foreach($hero_three['destination'] ?? [] as $destination)
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
                                        <h5>{{ $hero_three['single']['search_topic_two'] ?? '' }}</h5>
                                        <div class="form-clt">
                                            <div class="form">
                                                <select class="single-select w-100" name="duration">
                                                    <option value="">@lang('Select Duration')</option>
                                                    @foreach($hero_three['durations'] ?? [] as $duration)
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
                                        <h5>{{ $hero_three['single']['search_topic_three'] ?? '' }}</h5>
                                        <div class="form-clt">
                                            <div class="form-clt input-group">
                                                <input id="datepicker" class="form-control" name="date" type="text" placeholder="e.g. 21-04-2025" inputmode="none">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="booking-list style-2">
                                    <div class="icon">
                                        <img src="{{ asset(template(true).'img/hero/location.png') }}" alt="img">
                                    </div>
                                    <div class="content">
                                        <h5>{{ $hero_three['single']['search_topic_four'] ?? '' }}</h5>
                                        <div class="form-clt">
                                            <div class="form">
                                                <select class="single-select w-100" name="slot">
                                                    <option value="">@lang('Select a slot')</option>
                                                    @foreach($hero_three['slots'] ?? [] as $slot)
                                                        <option value="{{ $slot }}"> {{ $slot }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="theme-btn">
                                    <span>{{ $hero_three['single']['search_button'] ?? '' }}</span> <i class="far fa-long-arrow-right homeThreeSearch"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

