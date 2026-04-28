@if(isset($chose_us) && !empty($chose_us['single']))
    <section class="choose-us-section section-padding">
        <div class="container">
            <div class="choose-us-wrapper">
                <div class="row g-4">
                    <div class="col-xl-5 col-lg-4 col-md-6">
                        <div class="choose-us-left-content">
                            <div class="section-title">
                                <span class="wow fadeInUp">{{ $chose_us['single']['title'] ?? '' }}</span>
                                <h2 class="wow fadeInUp" data-wow-delay=".3s">@lang($chose_us['single']['sub_title'] ?? '')</h2>
                            </div>
                            <p class="mt-4 mt-md-0 wow fadeInUp" data-wow-delay=".5s">
                                {{ $chose_us['single']['description'] ?? '' }}
                            </p>

                            <ul class="list-items wow fadeInUp" data-wow-delay=".3s">
                                @foreach($chose_us['multiple'] ?? [] as $item)
                                    <li>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                            <path d="M23.1017 4.53435L13.8817 13.7544C13.3324 14.3036 12.5865 14.611 11.8089 14.611C11.766 14.611 11.7231 14.611 11.6778 14.6088C10.8573 14.5703 10.0888 14.1906 9.56215 13.5577L7.0102 10.3005C6.68923 9.87784 6.76156 9.27884 7.1752 8.94658C7.59337 8.60978 8.20818 8.67532 8.54499 9.09577L11.0811 12.3326C11.2529 12.5315 11.5015 12.6513 11.766 12.6604C12.0418 12.6739 12.3085 12.5699 12.5006 12.3755L21.7229 3.15554C21.7297 3.14649 21.7387 3.1397 21.7477 3.13068C22.1343 2.75545 22.7513 2.76676 23.1266 3.15554C23.4995 3.54205 23.4905 4.15912 23.1017 4.53435Z" fill="#4D40CA"/>
                                            <path d="M20.0776 12.8158C19.8538 15.3361 18.6898 17.5649 16.947 19.1674C15.2065 20.7701 12.8874 21.742 10.3558 21.7555C4.96711 21.7555 0.600098 17.3885 0.600098 11.9999C0.600098 6.61114 4.96711 2.24414 10.3558 2.24414C12.6478 2.24414 14.8652 3.05108 16.6215 4.52257C17.0374 4.86163 17.0985 5.47646 16.7594 5.89463C16.4181 6.31052 15.8033 6.37382 15.3851 6.0325C15.3783 6.02798 15.3738 6.02122 15.367 6.01669C12.0624 3.25002 7.13931 3.68625 4.3749 6.99089C1.60822 10.2978 2.04447 15.2186 5.34911 17.9853C8.65376 20.752 13.5768 20.3135 16.3435 17.0088C17.3742 15.7769 18.0003 14.2557 18.1337 12.6553C18.1721 12.1219 18.6332 11.7218 19.1644 11.7602C19.1735 11.7602 19.1802 11.7625 19.187 11.7625C19.7227 11.8077 20.1228 12.2779 20.0776 12.8158Z" fill="#4D40CA"/>
                                        </svg>
                                        {{ $item['title'] ?? '' }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="choose-us-img wow img-custom-anim-left">
                            <img src="{{ getFile($chose_us['single']['media']->image->driver, $chose_us['single']['media']->image->path) }}" alt="img">
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="choose-us-right">
                            <h4 class="wow fadeInUp" data-wow-delay=".3s">
                                {{ $chose_us['single']['right_title'] ?? '' }}
                            </h4>
                            <p class="wow fadeInUp" data-wow-delay=".5s">
                                {{ $chose_us['single']['right_sub_title'] ?? '' }}
                            </p>
                            <div class="client-img wow fadeInUp" data-wow-delay=".3s">
                                <img src="{{ getFile($chose_us['single']['media']->image_two->driver, $chose_us['single']['media']->image_two->path) }}" alt="img">
                            </div>
                            <p class="wow fadeInUp" data-wow-delay=".5s">
                                {{ $chose_us['single']['right_description'] ?? '' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

