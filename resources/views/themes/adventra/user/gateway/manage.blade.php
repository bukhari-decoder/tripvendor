@extends(template().'layouts.user')
@section('page_title', __('Manage Gateways'))
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
                                        <li class="breadcrumb-item active" aria-current="page">@lang('Manage Gateways')</li>
                                    </ol>
                                </nav>
                                <h1 class="page-header-title">@lang('Manage Gateways')</h1>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">@lang('Available gateways')</h4>
                    </div>

                    <!-- Body -->
                    <div class="card-body pt-0">
                        <div class="list-group list-group-lg list-group-flush list-group-no-gutters">
                            @forelse($gateways ?? [] as $item)
                                <div class="list-group-item">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <img class="avatar avatar-xs" src="{{ getFile($item->driver, $item->image) }}" alt="{{ $item->name }}">
                                        </div>

                                        <div class="flex-grow-1 ms-3">
                                            <div class="row align-items-center">
                                                <div class="col">
                                                    <h4 class="mb-0">{{ $item->name }}</h4>
                                                    @foreach($item->supported_currency ?? [] as $currency)
                                                        <span class="badge bg-secondary bg-opacity-25 text-dark">
                                                            {{ $currency }}
                                                        </span>
                                                    @endforeach
                                                </div>

                                                <div class="col-auto">
                                                    <input type="hidden" name="status" value="0">

                                                    <div class="form-check form-switch">
                                                        <input
                                                            class="form-check-input"
                                                            type="checkbox"
                                                            id="statusChecked"
                                                            name="status"
                                                            value="1"
                                                            data-id="{{ $item->id }}"
                                                            {{ $item->status ? 'checked' : '' }}
                                                        >
                                                        <label class="form-check-label" for="statusChecked"></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                @include('empty')
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).ready(function() {
            $('.form-check-input').on('change', function() {
                let status = $(this).is(':checked') ? 1 : 0;
                let id = $(this).data('id');

                $.ajax({
                    url: '{{ route('user.payment.gateway.update.status') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        status: status
                    },
                    success: function(response) {
                        if(response.status == 1){
                            Notiflix.Notify.success(response.msg);
                        }else{
                            Notiflix.Notify.failure(response.msg);
                        }
                    },
                    error: function(xhr) {
                        let message = 'Status update failed';

                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            message = xhr.responseJSON.error;
                        }
                        Notiflix.Notify.failure(message);
                    }
                });
            });
        });
    </script>
@endpush
