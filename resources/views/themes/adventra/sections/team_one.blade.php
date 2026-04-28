@if(isset($team_one) && !empty($team_one['single']))
    <section class="team-section-4 section-padding">
        <div class="container">
            <div class="section-title text-center">
                <span class="wow fadeInUp">{{ $team_one['single']['title'] ?? '' }}</span>
                <h2 class="wow fadeInUp" data-wow-delay=".3s">{{ $team_one['single']['sub_title'] ?? '' }}</h2>
                <p class="mt-3 mt-mb-0 wow fadeInUp" data-wow-delay=".5s">
                    {{ $team_one['single']['description_one'] ?? '' }} <br> {{ $team_one['single']['description_two'] ?? '' }}
                </p>
            </div>
            <div class="row">
                @foreach($team_one['multiple'] ?? [] as $team)
                    <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp" data-wow-delay=".2s">
                        <div class="team-card-items-4">
                            <div class="thumb">
                                <img src="{{ getFile($team['media']->image->driver,  $team['media']->image->path)}}" alt="{{ $team['name'] ?? '' }}">
                            </div>
                            <div class="content">
                                <span>{{ $team['designation'] ?? '' }}</span>
                                <h3><a href="#">{{ $team['name'] ?? '' }}</a></h3>
                                <div class="social-icon">
                                    <a href="{{ $team['facebook'] ?? '' }}"><i class="fab fa-facebook-f"></i></a>
                                    <a href="{{ $team['twitter'] ?? '' }}"><i class="fab fa-twitter"></i></a>
                                    <a href="{{ $team['website'] ?? '' }}"><i class="fas fa-basketball-ball"></i></a>
                                    <a href="{{ $team['instagram'] ?? '' }}"><i class="fab fa-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

