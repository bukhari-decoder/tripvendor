@if(isset($about_one) && !empty($about_one['single']))
    <section class="about-section section-padding sect-bg">
        <div class="shape-1">
            <img src="{{ getFile($about_one['single']['media']->background_shape->driver, $about_one['single']['media']->background_shape->path) }}" alt="img">
        </div>
        <div class="container">
            <div class="about-wrapper">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-5">
                        <div class="about-image wow img-custom-anim-left">
                            <img src="{{ getFile($about_one['single']['media']->image->driver, $about_one['single']['media']->image->path) }}" alt="img">
                            <div class="about-box float-bob-y">
                                <h2><span class="odometer" data-count="{{ $about_one['single']['count'] ?? '50' }}">00</span>+</h2>
                                <p>{{ $about_one['single']['experience_text'] ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="about-content">
                            <div class="section-title">
                                <span class="wow fadeInUp">{{ $about_one['single']['title'] ?? '' }}</span>
                                <h2 class="wow fadeInUp" data-wow-delay=".3s">{{ $about_one['single']['sub_title'] ?? '' }}</h2>
                            </div>
                            <p class="wow fadeInUp mt-4 mt-md-0" data-wow-delay=".5s">
                                {{ $about_one['single']['description'] ?? '' }}
                            </p>
                            <div class="about-sideber wow fadeInUp" data-wow-delay=".3s">
                                <h5>
                                    {{ $about_one['single']['heilighted_sub_title'] ?? '' }}
                                </h5>
                            </div>
                            @php
                                $items = collect($about_one['multiple'] ?? []);
                                $chunks = $items->chunk(ceil($items->count() / 2));
                            @endphp

                            <div class="about-icon-items">
                                @foreach($chunks as $index => $chunk)
                                    <ul class="wow fadeInUp" data-wow-delay="{{ $index == 0 ? '.3s' : '.5s' }}">
                                        @foreach($chunk as $item)
                                            <li>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                    <path d="M23.1017 4.53411L13.8817 13.7541C13.3324 14.3034 12.5865 14.6108 11.8089 14.6108C11.766 14.6108 11.7231 14.6108 11.6778 14.6085C10.8573 14.5701 10.0888 14.1904 9.56215 13.5575L7.0102 10.3003C6.68923 9.8776 6.76156 9.2786 7.1752 8.94633C7.59337 8.60954 8.20818 8.67507 8.54499 9.09552L11.0811 12.3323C11.2529 12.5313 11.5015 12.6511 11.766 12.6601C12.0418 12.6737 12.3085 12.5697 12.5006 12.3753L21.7229 3.15529C21.7297 3.14624 21.7387 3.13946 21.7477 3.13043C22.1343 2.7552 22.7513 2.76652 23.1266 3.15529C23.4995 3.54181 23.4905 4.15888 23.1017 4.53411Z" fill="#4D40CA"/>
                                                    <path d="M20.0776 12.8161C19.8538 15.3364 18.6898 17.5651 16.947 19.1677C15.2065 20.7703 12.8874 21.7422 10.3558 21.7558C4.96711 21.7558 0.600098 17.3888 0.600098 12.0001C0.600098 6.61139 4.96711 2.24438 10.3558 2.24438C12.6478 2.24438 14.8652 3.05132 16.6215 4.52282C17.0374 4.86187 17.0985 5.47671 16.7594 5.89487C16.4181 6.31077 15.8033 6.37406 15.3851 6.03275C15.3783 6.02822 15.3738 6.02146 15.367 6.01694C12.0624 3.25026 7.13931 3.68649 4.3749 6.99114C1.60822 10.298 2.04447 15.2189 5.34911 17.9855C8.65376 20.7522 13.5768 20.3137 16.3435 17.009C17.3742 15.7772 18.0003 14.2559 18.1337 12.6556C18.1721 12.1222 18.6332 11.7221 19.1644 11.7605C19.1735 11.7605 19.1802 11.7628 19.187 11.7628C19.7227 11.8079 20.1228 12.2781 20.0776 12.8161Z" fill="#4D40CA"/>
                                                </svg>
                                                {{ $item['title'] }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @endforeach
                            </div>
                            <div class="about-btn">
                                <a href="{{ $about_one['single']['media']->my_link }}" class="theme-btn wow fadeInUp" data-wow-delay=".3s">
                                    <span>{{ $about_one['single']['button'] ?? 'More About Travil' }}</span> <i class="far fa-long-arrow-right"></i>
                                </a>
                                <div class="group-image wow fadeInUp" data-wow-delay=".5s">
                                    <img src="{{ getFile($about_one['single']['media']->image_two->driver, $about_one['single']['media']->image_two->path) }}" alt="img">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

