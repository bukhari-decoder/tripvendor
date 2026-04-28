@extends(template().'layouts.app')
@section('title',$page_title)

@section('content')
    <section class="login">
        <div class="bg-layer" style="background: url({{ asset(template(true).'img/tour/bg2.jpg') }});"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="contact-container">
                        <div class="logo mb-3">
                            <a href="{{ route('page','/') }}">
                                <img src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}">
                            </a>
                        </div>
                        <h4 class="mt-3 pt-4 text-center">@lang($page_title)</h4>
                        <div class="contact-form">
                            <form action="{{ route('user.twoFA-Verify') }}" method="post" class="php-email-form">
                                @csrf

                                <div class="input-content">
                                    <label for="username"></label>
                                    <input class="form-control" type="text" name="code" value="{{old('code')}}" placeholder="@lang('Code')" autocomplete="off">

                                    @error('code')<span class="text-danger  mt-1">{{ $message }}</span>@enderror
                                    @error('error')<span class="text-danger  mt-1">{{ $message }}</span>@enderror
                                </div>

                                <button type="submit"  class="theme-btn w-100 justify-content-center"> @lang('Submit')  <span></span></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
