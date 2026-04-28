@if(isset($faq_one)&& !empty($faq_one['single']))
    <section class="faq-section section-padding fix">
        <div class="plane-shape">
            <img src="{{ asset(template(true).'img/faq/plane-shape.png')  }}" alt="img">
        </div>
        <div class="frame-shape">
            <img src="{{ asset(template(true).'img/faq/frame-shape.png')  }}" alt="img">
        </div>
        <div class="light-shape">
            <img src="{{ asset(template(true).'img/faq/light-shape.png') }}" alt="img">
        </div>
        <div class="container">
            <div class="section-title style-2 text-center">
                <span class="wow fadeInUp">{{ $faq_one['single']['title'] ?? '' }}</span>
                <h2 class="wow fadeInUp" data-wow-delay=".3s">{{ $faq_one['single']['sub_title_one'] ?? '' }}<br>{{ $faq_one['single']['sub_title_two'] ?? '' }}</h2>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="faq-items style-2">
                        <div class="faq-accordion">
                            <div class="accordion" id="accordion2">
                                @foreach($faq_one['multiple'] ?? [] as $index => $item)
                                    @php
                                        $faqId = "faq" . $index;
                                        $delay = 0.2 * ($index + 1) . 's';
                                        $isFirst = $index === 0;
                                    @endphp
                                    <div class="accordion-item wow fadeInUp" data-wow-delay="{{ $delay }}">
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
    </section>
@endif

