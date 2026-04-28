@extends(template().'layouts.user')
@section('page_title',trans('Notification Settings'))
@section('content')
    <div class="content container-fluid">
        <div class="row justify-content-lg-center">
            <div class="col-lg-10">
                <div class="page-header custom-header">
                    <div class="row align-items-end">
                        <div class="col-sm">
                            <h1 class="page-header-title">@lang("Notifications Permissions")</h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-no-gutter">
                                    <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                                   href="{{ route('user.dashboard') }}">@lang('Dashboard')</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">@lang("Notifications Permissions")</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title py-2">@lang('Notifications Permissions')</h4>
                    </div>
                    <div class="alert alert-soft-dark card-alert" role="alert">
                        @lang('We need permission from your browser to show notifications.') <a class="alert-link" href="#">@lang('Request permission')</a>
                    </div>

                    <form action="{{ route('user.notification.permission') }}" method="post">
                        @csrf

                        <div class="table-responsive datatable-custom">
                            <table class="table table-thead-bordered table-nowrap table-align-middle table-first-col-px-0">
                                <thead class="thead-light">
                                <tr>
                                    <th class="text-center" scope="col">@lang('type')</th>
                                    <th class="text-center" scope="col">✉️ @lang('email')</th>
                                    <th class="text-center" scope="col">🖥 @lang('browser')</th>
                                    <th class="text-center" scope="col">📱 @lang('sms')</th>
                                    <th class="text-center" scope="col">👩🏻‍💻 @lang('app')</th>
                                </tr>
                                </thead>

                                <tbody>
                                @forelse($notificationTemplates as $key => $item)
                                    <tr>
                                        <td data-label="Type" class="text-center">
                                            <div class="d-flex align-items-center">
                                                <span>{{ $item->name }}</span>
                                            </div>
                                        </td>
                                        <td data-label="✉️ Email">
                                            <div class="form-check form-switch d-flex align-items-center justify-content-center">
                                                <input class="form-check-input" type="checkbox"
                                                       role="switch" name="email_key[]"
                                                       value="{{$item->template_key ?? ""}}"
                                                       {{ !$item->email ? 'disabled':'' }}
                                                       id="emailSwitch"
                                                    {{ in_array($item->template_key, optional($user->notifypermission)->template_email_key ?? []) ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td data-label="🖥 Browser">
                                            <div class="form-check form-switch d-flex align-items-center justify-content-center">
                                                <input class="form-check-input" type="checkbox"
                                                       role="switch" name="sms_key[]"
                                                       value="{{ $item->template_key ?? "" }}"
                                                       {{ !$item->sms ? 'disabled' : '' }}
                                                       id="pushSwitch"
                                                    {{ in_array($item->template_key, optional($user->notifypermission)->template_sms_key ?? []) ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td data-label="🖥 Browser">
                                            <div class="form-check form-switch d-flex align-items-center justify-content-center">
                                                <input class="form-check-input" type="checkbox"
                                                       role="switch" name="push_key[]"
                                                       value="{{ $item->template_key ?? "" }}"
                                                       {{ !$item->push ? 'disabled' : '' }}
                                                       id="pushSwitch"
                                                    {{ in_array($item->template_key, optional($user->notifypermission)->template_push_key ?? []) ? 'checked' : '' }}>
                                            </div>
                                        </td>

                                        <td data-label="👩🏻‍💻 App">
                                            <div class="form-check form-switch d-flex align-items-center justify-content-center">
                                                <input class="form-check-input" type="checkbox"
                                                       role="switch" name="in_app_key[]"
                                                       value="{{$item->template_key ?? ""}}"
                                                       id="appSwitch"
                                                    {{!$item->in_app ? 'disabled':''}}
                                                    {{ in_array($item->template_key, optional($user->notifypermission)->template_in_app_key ?? []) ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <th colspan="100%" class="text-center text-dark">
                                            <div class="no_data_iamge text-center">
                                                <img class="no_image_size" src="{{ asset('assets/global/img/oc-error.svg') }}">
                                            </div>
                                            <p class="text-center">@lang('Notification Template List is empty here!.')</p>
                                        </th>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-start mx-3 my-1 mb-3">
                            <button type="submit" class="btn btn-primary btn-sm">@lang('Save changes')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('style')
    <style>
        .card .card-header{
            padding: 10px 25px 10px 25px;
        }
    </style>
@endpush
