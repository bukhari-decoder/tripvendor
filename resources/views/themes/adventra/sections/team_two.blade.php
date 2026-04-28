@if(isset($team_two) && !empty($team_two['single']))
    <section class="team-section fix section-padding section-bg">
        <div class="plane-shape float-bob-y">
            <img src="{{ asset(template(true).'img/team/plane-shape.png') }}" alt="img">
        </div>
        <div class="plane-shape-2 float-bob-y">
            <img src="{{ asset(template(true).'img/team/plane-2.png') }}" alt="img">
        </div>
        <div class="frame-shape float-bob-x">
            <img src="{{ asset(template(true).'img/team/frame.png') }}" alt="img">
        </div>
        <div class="container">
            <div class="section-title text-center">
                <span class="wow fadeInUp">{{ $team_two['single']['title'] ?? '' }}</span>
                <h2 class="wow fadeInUp" data-wow-delay=".3s">{{ $team_two['single']['sub_title'] ?? '' }}</h2>
                <p class="mt-3 wow fadeInUp" data-wow-delay=".5s">
                    {{ $team_two['single']['description_one'] ?? '' }} <br> {{ $team_two['single']['description_two'] ?? '' }}
                </p>
            </div>
            <div class="row">
                @foreach($team_two['multiple'] ?? [] as $team)
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 wow fadeInUp" data-wow-delay=".2s">
                        <div class="team-box-items">
                            <div class="thumb">
                                <img src="{{ getFile($team['media']->image->driver, $team['media']->image->path) }}" alt="img">
                            </div>
                            <div class="content">
                                <p>{{ $team['designation'] ?? '' }}</p>
                                <h3><a href="#">{{ $team['name'] ?? '' }}</a></h3>
                                <div class="social-icon">
                                    <a href="{{ $team['facebook'] ?? '#' }}"><i class="fab fa-facebook-f"></i></a>
                                    <a href="{{ $team['instagram'] ?? '#' }}"><i class="fab fa-instagram"></i></a>
                                    <a href="{{ $team['twitter'] ?? '#' }}"><i class="fab fa-twitter"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

