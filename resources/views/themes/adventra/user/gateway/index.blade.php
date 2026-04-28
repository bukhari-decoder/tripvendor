@extends(template().'layouts.user')
@section('page_title', __('Payment Gateways'))
@section('content')
    <div class="content container-fluid">
        <div class="row justify-content-lg-center">
            <div class="col-lg-10">
                <div class="page-header custom-header">
                    <div class="row">
                        <div class="col-sm mb-2 mb-sm-0 d-flex align-items-center justify-content-between">
                            <div class="left">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb breadcrumb-no-gutter">
                                        <li class="breadcrumb-item"><a class="breadcrumb-link" href="javascript:void(0);">@lang('Dashboard')</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">@lang('Payment Gateways')</li>
                                    </ol>
                                </nav>
                                <h1 class="page-header-title">@lang('Payment Gateways')</h1>
                            </div>
                            <a class="btn btn-primary btn-sm" href="{{ route('user.payment.gateway.manage') }}"><i class="bi-pencil pe-2"></i>@lang('Manage')</a>
                        </div>
                    </div>
                </div>

                <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-3 uGate">
                    @forelse($gateways ?? [] as $gateway)
                        <div class="col mb-3 mb-lg-5">
                            <div class="card h-100">
                                <div class="card-pinned">
                                    <div class="card-pinned-top-end">
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm rounded-circle" id="connectionsDropdown2" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi-three-dots-vertical"></i>
                                            </button>

                                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end" aria-labelledby="connectionsDropdown2">
                                                <a class="dropdown-item" href="{{ route('user.payment.gateway.edit', $gateway->id) }}"><i class="bi-pencil-square pe-1"></i>@lang('Edit')</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body text-center gateway-card-image">
                                    <!-- Avatar -->
                                    <div class="avatar avatar-xl avatar-circle avatar-centered mb-3">
                                        <img class="avatar-img" src="{{ getFile($gateway->driver, $gateway->image) }}" alt="{{ $gateway->name }}">
                                    </div>

                                    <h3 class="mb-1">
                                        <a class="text-dark">{{ $gateway->name }}</a>
                                    </h3>

                                    @if(isset($gateway->description))
                                        <div class="mb-3">
                                            <i class="bi-building me-1"></i>
                                            <span>{{ $gateway->description }}</span>
                                        </div>
                                    @endif

                                    <ul class="list-inline mb-0 mt-2">
                                        @foreach($gateway->supported_currency as $item)
                                            <li class="list-inline-item">
                                                <a class="badge bg-soft-secondary text-secondary p-2">
                                                    {{ $item }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-between align-items-center">
                                        <div class="col-auto py-1">
                                            <p class="fs-6 text-body mb-0">
                                                {{ count($gateway->supported_currency) }} out of {{ $gateway->countGatewayCurrency() }} @lang('gateway currencies are active')
                                            </p>

                                        </div>

                                        <div class="col-auto py-1">
                                            <span class="badge bg-success">
                                                <i class="bi-check-circle me-1"></i> @lang('Active')
                                            </span>
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
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <style>
        .avatar-img{
            max-width: 100% !important;
            height: 88% !important;
        }
        .avatar-xl.avatar-circle .avatar-sm-status {
            bottom: 10px !important;
        }
        .uGate{
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endpush




