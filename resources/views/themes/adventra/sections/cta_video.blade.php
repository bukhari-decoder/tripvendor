@if(isset($cta_video) && !empty($cta_video['single']))
    <div class="cta-video-section-2 section-padding bg-cover" style="background-image: url({{ getFile($cta_video['single']['media']->background_image->driver, $cta_video['single']['media']->background_image->path) }});">
        <div class="container">
            <a href="{{ $cta_video['single']['media']->my_link ?? '#' }}" class="video-btn ripple video-popup">
                <i class="fas fa-play"></i>
            </a>
        </div>
    </div>
@endif
