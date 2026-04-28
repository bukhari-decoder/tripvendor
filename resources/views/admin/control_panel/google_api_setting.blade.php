@extends('admin.layouts.app')
@section('page_title', __('Google Map Api'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title">@lang('Google Map API Configuration')</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('admin.dashboard') }}">
                                    @lang('Dashboard')
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Settings')</li>
                            <li class="breadcrumb-item active"
                                aria-current="page">@lang('Google Map API Configuration')</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                @include('admin.control_panel.components.sidebar', ['settings' => config('generalsettings.settings'), 'suffix' => 'settings'])
            </div>
            <div class="col-lg-9">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <a type="button"
                               class="btn btn-primary float-end addApi"
                               data-bs-toggle="modal"
                               data-bs-target="#createAKIKeyModal"
                               data-route="{{ route('admin.add.google.map.api') }}"

                            >@lang('Add New')</a>
                            <h2 class="card-title h4">@lang('Google Map API Configuration')</h2>
                        </div>
                        <div class="table-responsive position-relative">
                            <table
                                class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                                id="supported_currency_table">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">@lang('Sl')</th>
                                        <th scope="col">@lang('Google Map Api')</th>
                                        <th scope="col">@lang('Status')</th>
                                        <th scope="col">@lang('Action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($apis as $api)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>@lang(snake2Title($api->api_key))</td>
                                            <td>
                                                @if($api->status == 1)
                                                    <span class="badge bg-soft-success text-success">
                                                            <span class="legend-indicator bg-success"></span>@lang('Active')
                                                        </span>
                                                @else
                                                    <span class="badge bg-soft-danger text-danger">
                                                            <span class="legend-indicator bg-danger"></span>@lang('Inactive')
                                                        </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a class="btn btn-white btn-sm editApi"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#editAKIKeyModal"
                                                       data-route="{{ route('admin.edit.google.map.api',$api->id) }}"
                                                       data-key="{{ $api->api_key }}"
                                                       >
                                                        <i class="bi-pencil-fill me-1"></i> @lang('Edit')
                                                    </a>
                                                    <div class="btn-group">
                                                        <button type="button"
                                                                class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty"
                                                                id="productsEditDropdown1" data-bs-toggle="dropdown"
                                                                aria-expanded="false"></button>
                                                        <div class="dropdown-menu dropdown-menu-end mt-1 "
                                                             aria-labelledby="productsEditDropdown1">
                                                            <a class="dropdown-item set editStatus" href="#"
                                                               data-route="{{ route('admin.editStatus.google.map.api', $api->id) }}"
                                                               data-bs-toggle="modal" data-bs-target="#changeStatusModal">
                                                                <i class="fa-light fa-check dropdown-item-icon text-success"></i> @lang('Change Status')
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <div class="text-center p-4">
                                                <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error.svg') }}" alt="Image Description" data-hs-theme-appearance="default">
                                                <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error-light.svg') }}" alt="Image Description" data-hs-theme-appearance="dark">
                                                <p class="mb-0">@lang("No data to show")</p>
                                            </div>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="createAKIKeyModal" tabindex="-1" aria-labelledby="createAKIKeyModalLabel" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="createAKIKeyModalLabel">@lang('Add Map API Key')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addApiForm" action="" class="addApiForm" method="post">
                    @csrf

                    <div class="modal-body">
                        <input type="text" class="form-control" name="key" placeholder="Map api key">
                    </div>
                    <div class="modal-footer">
                        <div class="d-flex gap-3">
                            <button type="button" class="btn btn-white" data-bs-dismiss="modal" aria-label="Close">@lang('Cancel')</button>
                            <button type="submit" class="btn btn-primary">@lang('Add')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editAKIKeyModal" tabindex="-1" aria-labelledby="editAKIKeyModalLabel" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editAKIKeyModalLabel">@lang('Add Map API Key')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editApiForm" action="" class="editApiForm" method="post">
                    @csrf

                    <div class="modal-body">
                        <input type="text" class="form-control" name="key" id="key" placeholder="Map api key">
                        <div class="modal-footer">
                            <div class="d-flex gap-3">
                                <button type="button" class="btn btn-white" data-bs-dismiss="modal" aria-label="Close">@lang('Cancel')</button>
                                <button type="submit" class="btn btn-primary">@lang('Update')</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="changeStatusModal" tabindex="-1" aria-labelledby="changeStatusModalLabel" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="changeStatusModalLabel">@lang('Confirmation')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editApiStatusForm" action="" class="editApiStatusForm" method="post">
                    @csrf

                    <div class="modal-body">
                        <p>@lang(' Are you sure to change this api status?.')</p>
                        <div class="modal-footer">
                            <div class="d-flex gap-3">
                                <button type="button" class="btn btn-white" data-bs-dismiss="modal" aria-label="Close">@lang('Cancel')</button>
                                <button type="submit" class="btn btn-primary">@lang('Change')</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        document.querySelectorAll('.addApi').forEach(function (btn) {
            btn.addEventListener('click', function () {

                let route = this.getAttribute('data-route');

                document.querySelector('#addApiForm').setAttribute('action', route);

                $('#createAKIKeyModal').modal('show');
            });
        });
        document.querySelectorAll('.editApi').forEach(function (btn) {
            btn.addEventListener('click', function () {

                let route = this.getAttribute('data-route');
                let api_key = this.getAttribute('data-key');

                document.querySelector('#key').value = api_key;
                document.querySelector('#editApiForm').setAttribute('action', route);

                $('#editAKIKeyModal').modal('show');
            });
        });
        document.querySelectorAll('.editStatus').forEach(function (btn) {
            btn.addEventListener('click', function () {

                let route = this.getAttribute('data-route');
                document.querySelector('#editApiStatusForm').setAttribute('action', route);

                $('#changeStatusModal').modal('show');
            });
        });
    </script>
@endpush
