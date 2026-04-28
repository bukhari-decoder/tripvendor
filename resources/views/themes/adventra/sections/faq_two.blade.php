@if(isset($faq_two) && !empty($faq_two['single']))
    <section class="faq-section fix">
        <div class="container">
            <div class="faq-wrapper section-padding pt-0">
                <div class="row g-4 justify-content-between">
                    <div class="col-lg-5">
                        <div class="faq-content">
                            <div class="section-title">
                                <span class="wow fadeInUp" data-wow-delay=".3s">{{ $faq_two['single']['title'] ?? '' }}</span>
                                <h2 class="wow fadeInUp" data-wow-delay=".5s">
                                    {{ $faq_two['single']['sub_title_one'] ?? '' }}<br>
                                    {{ $faq_two['single']['sub_title_two'] ?? '' }} <br>
                                    {{ $faq_two['single']['sub_title_three'] ?? '' }}
                                </h2>
                            </div>
                            <div class="faq-image wow img-custom-anim-bottom">
                                <img src="{{ getFile($faq_two['single']['media']->image->driver, $faq_two['single']['media']->image->path)  }}" alt="img">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="faq-items">
                            <div class="faq-accordion">
                                <div class="accordion" id="accordion2">
                                    @foreach($faq_two['multiple'] ?? [] as $index => $item)
                                        @php
                                            $faqId = "faq" . $index;
                                            $delay = 0.2 * ($index + 1) . 's';
                                            $isFirst = $index === 0;
                                        @endphp
                                        <div class="accordion-item mb-3 wow fadeInUp" data-wow-delay="{{ $delay }}">
                                            <h5 class="accordion-header">
                                                <button class="accordion-button {{ $isFirst ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $faqId }}" aria-expanded="{{ $isFirst ? 'true' : 'false' }}" aria-controls="{{ $faqId }}">
                                                    {{ $item['question'] ?? '' }}
                                                </button>
                                            </h5>
                                            <div id="{{ $faqId }}" class="accordion-collapse collapse {{ $isFirst ? 'show' : '' }}" data-bs-parent="#accordion2">
                                                <div class="accordion-body">
                                                    {{ $item['answer'] ?? '' }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

