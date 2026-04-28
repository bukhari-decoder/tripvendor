<div id="preloader" class="preloader">
    <div class="animation-preloader">
        <div class="spinner">
        </div>
        <div class="txt-loading">
            @foreach(str_split(basicControl()->site_title) as $char)
                <span data-text-preloader="{{ $char }}" class="letters-loading">
                    {{ $char }}
                </span>
            @endforeach
        </div>
        <p class="text-center">@lang('Loading')</p>
    </div>
    <div class="loader">
        <div class="row">
            <div class="col-3 loader-section section-left">
                <div class="bg"></div>
            </div>
            <div class="col-3 loader-section section-left">
                <div class="bg"></div>
            </div>
            <div class="col-3 loader-section section-right">
                <div class="bg"></div>
            </div>
            <div class="col-3 loader-section section-right">
                <div class="bg"></div>
            </div>
        </div>
    </div>
</div>
