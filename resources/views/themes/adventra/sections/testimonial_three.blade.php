@if(isset($testimonial_three) && !empty($testimonial_three['single']))
    <section class="testimonial-section-3 section-padding pt-0">
        <div class="array-button style-2">
            <button class="array-prev"><i class="far fa-long-arrow-left"></i></button>
            <button class="array-next"><i class="far fa-long-arrow-right"></i></button>
        </div>
        <div class="container">
            <div class="section-title text-center">
                <span class="wow fadeInUp">{{ $testimonial_three['single']['title'] ?? '' }}</span>
                <h2 class="wow fadeInUp" data-wow-delay=".3s">{{ $testimonial_three['single']['sub_title'] ?? '' }}</h2>
            </div>
            <div class="swiper testimonial-slider-3">
                <div class="swiper-wrapper">
                    @foreach($testimonial_three['multiple'] ?? [] as $item)
                        <div class="swiper-slide">
                            <div class="testimonial-box-items2">
                                <div class="star">
                                    @for ($i = 0; $i < $item['rating']; $i++)
                                        <i class="fas fa-star"></i>
                                    @endfor
                                </div>
                                <h3>
                                    {{ $item['message'] ?? '' }}
                                </h3>
                                <div class="client-info-items">
                                    <div class="client-info">
                                        <div class="client-img">
                                            <img src="{{ getFile($item['media']->image->driver, $item['media']->image->path) }}" alt="{{ $item['name'] ?? '' }}">
                                        </div>
                                        <div class="client-content">
                                            <h4>
                                                {{ $item['name'] ?? '' }}
                                            </h4>
                                            <span>
                                            {{ $item['address'] ?? '' }}
                                        </span>
                                        </div>
                                    </div>
                                    <div class="icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="43" height="43" viewBox="0 0 43 43" fill="none">
                                            <path d="M8.86625 25.8549C7.98376 28.0818 6.58884 30.2864 4.72084 32.4083C4.12639 33.0835 4.05092 34.0474 4.53318 34.8065C4.90455 35.3905 5.52918 35.7231 6.19056 35.7231C6.3769 35.7231 6.56653 35.6969 6.75353 35.6424C10.7126 34.4857 19.9476 30.377 20.1956 17.2439C20.2914 12.1727 16.5823 7.81603 11.7526 7.32524C9.09064 7.05884 6.42743 7.92362 4.45379 9.70764C2.47754 11.4936 1.34375 14.0433 1.34375 16.7039C1.34375 21.1426 4.49447 25.0243 8.86625 25.8549ZM6.25552 11.7016C7.51069 10.5672 9.09064 9.96286 10.7657 9.96286C11.0026 9.96286 11.2414 9.97467 11.4809 9.9996C14.9302 10.3493 17.5777 13.5092 17.5081 17.1927C17.3369 26.2801 12.4166 30.3711 8.34201 32.201C9.6313 30.457 10.6444 28.6632 11.3648 26.845C11.6456 26.1371 11.599 25.3458 11.2375 24.6739C10.8589 23.9692 10.1838 23.4693 9.38656 23.3019C6.28308 22.653 4.03125 19.8776 4.03125 16.7039C4.03125 14.8025 4.84222 12.9791 6.25552 11.7016Z" fill="#4D40CA"/>
                                            <path d="M25.9928 34.8068C26.3642 35.3908 26.9888 35.7234 27.6502 35.7234C27.8365 35.7234 28.0255 35.6972 28.2132 35.6427C32.1722 34.486 41.4066 30.3773 41.6546 17.2443C41.7491 12.173 38.0406 7.81634 33.2102 7.32556C30.545 7.05458 27.8864 7.92329 25.9128 9.70796C23.9365 11.4939 22.8027 14.0437 22.8027 16.7043C22.8027 21.143 25.9535 25.0246 30.3246 25.8553C29.4414 28.0841 28.0465 30.2887 26.1792 32.4093C25.5847 33.0851 25.5099 34.0483 25.9928 34.8068ZM32.8231 26.8467C33.1039 26.1387 33.058 25.3474 32.6971 24.6756C32.3179 23.9702 31.6434 23.4702 30.8455 23.3023C27.742 22.6534 25.4902 19.8779 25.4902 16.7043C25.4902 14.8021 26.3012 12.9794 27.7145 11.7019C28.969 10.5675 30.549 9.96318 32.2247 9.96318C32.4609 9.96318 32.6997 9.97499 32.9399 9.99992C36.3885 10.3496 39.0366 13.5096 38.9671 17.1931C38.7965 26.2811 33.8755 30.3714 29.801 32.2013C31.0896 30.4587 32.1014 28.6648 32.8231 26.8467Z" fill="#4D40CA"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif

