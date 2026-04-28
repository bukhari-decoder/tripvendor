@extends(template().'layouts.error')
@section('title','Instruction Page')
@section('content')
    <section class="error-section">
        <div class="container">
            <div class="row g-5 align-items-center justify-content-center">

                @if(!auth()->guard('admin')->check())
                    <div class="col-sm-6">
                        <div class="error-thum">
                            <img src="{{ asset(template(true) . 'img/error/error2.png')}}" alt="...">
                        </div>
                    </div>
                @endif
                <div class="col-sm-6">
                    <div class="error-content">

                        <div class="error-info font-30">
                            @lang('Coming Soon Content in') `{{isset(config('languages.langCode')[app()->currentLocale()])? config('languages.langCode')[app()->currentLocale()] :'Unknown Language' }}`
                        </div>
                        <p class="my-3">
                            @lang('If there is no content available in') <span class="text-gradient">`{{isset(config('languages.langCode')[app()->currentLocale()]) ? config('languages.langCode')[app()->currentLocale()] : 'Unknown Language' }}`</span>, @lang('our administrators are working diligently to set up relevant content for our')
                            <span
                                class="text-gradient">`{{isset(config('languages.langCode')[app()->currentLocale()])? config('languages.langCode')[app()->currentLocale()] : 'Unknown Language' }}`</span> @lang('audience. We appreciate your patience as we strive to provide valuable information in your preferred language.')
                        </p>

                        @if(auth()->guard('admin')->check())
                            <a href="{{ route('admin.page.index', basicControl()->theme??'adventra') }}" class="theme-btn wow fadeInUp" data-wow-delay=".8s">
                                <span>@lang('Go To Settings')</span> <i class="far fa-long-arrow-right"></i>
                            </a>
                        @else
                            <a href="{{ route('switch.language') }}" class="theme-btn wow fadeInUp" data-wow-delay=".8s">
                                <span>@lang('Back To Default Language')</span> <i class="far fa-long-arrow-right"></i>
                            </a>
                        @endif
                    </div>
                </div>


                @if(auth()->guard('admin')->check())
                    <div class="col-sm-12">
                        <div class="instruction-thumbs">
                            <img src="{{ asset('assets/admin/img/content-add-instruction.png')}}" alt="...">
                        </div>
                    </div>
                @endif


            </div>
        </div>
    </section>
@endsection
