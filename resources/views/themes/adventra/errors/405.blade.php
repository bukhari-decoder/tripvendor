@extends(template().'layouts.error')
@section('title','405')
@section('content')
    <section class="error-section section-padding fix">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="error-items">
                        <div class="error-image wow fadeInUp" data-wow-delay=".3s">
                            <img src="{{ asset(template(true) . 'img/error/error2.png')}}" alt="img">
                        </div>
                        <h2 class="wow fadeInUp" data-wow-delay=".5s">
                            @lang('405 Method Not Allowed')
                        </h2>
                        <p class="wow fadeInUp" data-wow-delay=".7s">

                        </p>
                        <a href="{{url('/')}}" class="theme-btn wow fadeInUp" data-wow-delay=".8s">
                            <span>@lang('Go To Homepage')</span> <i class="far fa-long-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
