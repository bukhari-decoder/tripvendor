@if(isset($plans))
    <section class="business-pricing-plan pricing">
        <div class="container">
            <div class="common-title sec-title-animation animation-style1">
                <h6><i class="fa-sharp far fa-star-of-life"></i> {{ $plans['single']['title'] ?? '' }}</h6>
                <h3 class="title-animation">{{ $plans['single']['sub_title'] ?? '' }}</h3>
            </div>
            <div class="row">
                @forelse($plans['planList'] ?? [] as $plan)
                    <div class="col-lg-4 col-md-6 mt-4">
                        <div class="business-pricing-card">
                            <div class="business-pricing-card-inner">
                                <div class="badge_area d-flex align-items-center justify-content-center">
                                    <div class="business-pricing-card-badge">
                                        <div class="icon">
                                            <img class="plan_icon" src="{{ getFile($plan->driver, $plan->image) }}" alt="{{ $plan->name.' icon' }}"/>
                                        </div>
                                        <h6>{{ $plan->name ?? '' }}</h6>
                                        @php
                                            if ($plan->validity_type == 'daily'){
                                                $type = 'Days';
                                            }elseif ($plan->validity_type == 'weekly'){
                                                $type = 'Weeks';
                                            }elseif ($plan->validity_type == 'monthly'){
                                                $type = 'Months';
                                            }elseif ($plan->validity_type == 'yearly'){
                                                $type = 'Years';
                                            }else{
                                                $type = 'Unknown';
                                            }
                                        @endphp
                                        <p>{{ $plan->validity.' '.$type }}</p>
                                    </div>
                                </div>

                                <div class="business-pricing-card-list">
                                    <ul>
                                        <li><i class="far fa-circle-check"></i> {{ $plan->validity.' '. $type }} @lang(' Validity')</li>
                                        <li><i class="far fa-circle-check"></i> {{ $plan->listing_allowed }} @lang(' Package Allowed')</li>
                                        <li><i class="far fa-circle-check"></i> {{ $plan->featured_listing }} @lang(' Featured Package')</li>
                                        @if($plan->ai_feature == 1)
                                            <li><i class="far fa-circle-check"></i> @lang('AI Feature Available')</li>
                                        @else
                                            <li><i class="fas fa-times-circle text-danger"></i> @lang('AI Feature Not Available')</li>
                                        @endif
                                        @foreach($plan->features ?? [] as $feature)
                                            <li><i class="far fa-circle-check"></i> {{ $feature ?? '' }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="pricing-bottom">
                                    <div class="business-pricing-amount">{{ currencyPosition($plan->price ?? '0') }}<sub>/{{ $plan->validity.' '.$type }}</sub></div>
                                    <div class="business-pricing-card-btn">
                                        <form action="{{ route('user.purchase.planSelect') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="selectedPlan" value="{{ $plan->id }}">
                                            <button type="submit" class="theme-btn">
                                                @lang('Purchase Now') <i class="fa-sharp far fa-arrow-right"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <section class="no-data-section section-padding text-center">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-lg-6">
                                    <div class="no-data-content">
                                        <div class="no-data-image mb-2">
                                            <img src="{{ asset('assets/global/img/oc-error.svg') }}" alt="No Data" class="img-fluid noDataImage">
                                        </div>
                                        <h2 class="no-data-title mb-3">@lang('No Data Found')</h2>
                                        <p class="no-data-text mb-4">@lang("We couldn't find what you're looking for. Please try again later or modify your search.")</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <style>
                        .noDataImage{
                            height: 250px !important;
                            width: 250px !important;
                        }
                    </style>
                @endforelse
            </div>
        </div>
    </section>
@endif

