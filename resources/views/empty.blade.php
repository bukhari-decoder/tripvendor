@extends(template() . 'layouts.app')
@section('title',trans('Empty'))
@section('content')
    <section class="no-data-section section-padding text-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="no-data-content">
                        <div class="no-data-image mb-4">
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
@endsection
