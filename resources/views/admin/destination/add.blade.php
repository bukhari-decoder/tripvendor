@extends('admin.layouts.app')
@section('page_title', __('Destination Add'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="javascript:void(0)">
                                    @lang('Dashboard')
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Manage Destination')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Destination Add')</h1>
                </div>
            </div>
        </div>
        <div class="alert alert-soft-dark mb-5" role="alert">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <img class="avatar avatar-xl alert_image"
                         src="{{ asset('assets/admin/img/oc-megaphone.svg') }}"
                         alt="Image Description" data-hs-theme-appearance="default">
                    <img class="avatar avatar-xl alert_image"
                         src="{{ asset('assets/admin/img/oc-megaphone-light.svg') }}"
                         alt="Image Description" data-hs-theme-appearance="dark">
                </div>

                <div class="flex-grow-1 ms-3">
                    <div class="d-flex align-items-center">
                        <p class="mb-0">
                            @lang("To generate the map, a valid Google Maps API key is required. Please ensure you have a valid key configured to proceed.")
                            <a type="button"
                               class="btn btn-white btn-sm getApi"
                               data-bs-toggle="modal"
                               data-bs-target="#getApiKey"
                            >@lang('How to get Map Api Key?')</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-12 mb-3 mb-lg-0">
                <form action="{{ route('admin.destination.store') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="card mb-3 mb-lg-5">
                        <div class="card-header">
                            <a type="button" href="{{ route('admin.all.destination') }}" class="btn btn-white btn-sm float-end"><i class="bi bi-arrow-left"></i>@lang('Back')</a>
                            <h4 class="card-header-title">@lang('Destination information')</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <label for="productNameLabel" class="form-label">@lang('Name')
                                    <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Type your destination name here..."></i>
                                </label>
                                <input type="text" class="form-control" name="name" id="nameLabel" placeholder="e.g dhaka" aria-label="name" value="{{ old('name') }}" onkeyup="generateSlug()">
                                @error('name')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="slug" class="form-label">@lang('Slug')
                                    <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="destination slug"></i>
                                </label>
                                <input type="text" class="form-control" name="slug" id="slug" aria-label="slug" value="{{ old('slug') }}">
                                @error('slug')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-sm-4">
                                    <div class="mb-4">
                                        <label class="CountryLevel" for="country">@lang('Country')</label>
                                        <select id="country" class="form-control js-select" name="country">
                                            <option value="" disabled selected>@lang('Select Country')</option>
                                            @foreach($location as $item)
                                                <option value="{{$item->id}}">@lang($item->name)</option>
                                            @endforeach
                                        </select>
                                        @error('country')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-4">
                                    <div class="mb-4">
                                        <label for="state">@lang('State')</label>
                                        <select name="state" id="state" class="form-control js-select">
                                        </select>
                                        @error('state')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-4">
                                    <div class="mb-4">
                                        <label for="city">@lang('City')</label>
                                        <select name="city" id="city" class="form-control js-select">
                                        </select>
                                        @error('city')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-4">
                                    <div class="mb-4">
                                        <label class="form-label" for="city">@lang('Main Language')</label>
                                        <input class="form-control" type="text" name="main_language" id="main_language" aria-controls="@lang('Main Language')" value="{{ old('main_language') }}" placeholder="@lang('e.g English')" />
                                        @error('main_language')
                                        <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-4">
                                    <div class="mb-4">
                                        <label class="form-label" for="currency">@lang('Most Used Currency')</label>
                                        <input class="form-control" type="text" name="currency" id="currency" aria-controls="@lang('Currency')" value="{{ old('currency') }}" placeholder="@lang('e.g EURO')" />
                                        @error('currency')
                                        <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-12">
                                    <div class="mb-4">
                                        <label class="form-label" for="area">@lang('Area')</label>
                                        <div class="input-group">
                                            <input class="form-control" type="text" name="area" id="area" value="{{ old('area') }}" aria-controls="@lang('Area')" placeholder="@lang('e.g 88.00')" />
                                            <span class="input-group-text">@lang('km²')</span>
                                        </div>
                                        @error('area')
                                        <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-12">
                                    <div class="mb-4">
                                        <label class="form-label" for="map">@lang('Map')</label>
                                        <input class="form-control" name="full_address" type="text" id="mapInput" placeholder="Click to View Map" readonly />
                                        <div id="mapModal" class="d-none position-fixed top-50 start-50 translate-middle p-3 bg-white border shadow-lg" style="width: 60%; height: 60%; z-index: 1000;">
                                            <input class="form-control mb-2" id="search" type="text" placeholder="Search location" />
                                            <div id="map" style="width: 100%; height: 90%;"></div>
                                            <a class="btn btn-danger mt-2 w-100" onclick="closeMap()">Close</a>
                                        </div>
                                        @error('map')
                                        <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-12">
                                    <div class="mb-4">
                                        <label class="form-label" for="lat">@lang('Latitude')</label>
                                        <input class="form-control" name="lat" type="text" id="lat" value="{{ old('lat') }}" placeholder="Select Location For Get Latitude" readonly />

                                        @error('lat')
                                        <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-12">
                                    <div class="mb-4">
                                        <label class="form-label" for="long">@lang('Longitude')</label>
                                        <input class="form-control" name="long" type="text" id="long" value="{{ old('long') }}" placeholder="Select Location For Get Longitude" readonly />

                                        @error('long')
                                        <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-4">
                                        <div class="justify-content-between">
                                            <div class="form-group">
                                                <a href="javascript:void(0)" class="btn btn-soft-info btn-sm float-left mt-3 generate">
                                                    <i class="fa fa-plus-circle"></i> @lang('Add Place')
                                                </a>
                                            </div>
                                            <div class="row addedField mt-3 col-12"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3 mb-lg-5">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <label class="form-label" for="details">@lang('Destination Details')</label>
                                    <textarea
                                        name="details"
                                        class="form-control summernote"
                                        cols="30"
                                        rows="5"
                                        id="details"
                                        placeholder="destination details"
                                    ></textarea>
                                    @error('details')
                                    <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3 mb-lg-5">
                        <div class="card-body">
                            <label class="form-label" for="destinationThumbnail">@lang('Destination Thumbnail')</label>
                            <label class="form-check form-check-dashed" for="logoUploader" id="content_img">
                                <img id="previewImage"
                                     class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                     src="{{ asset("assets/admin/img/oc-browse-file.svg") }}"
                                     alt="Image Preview" data-hs-theme-appearance="default">
                                <span class="d-block">@lang("Browse your file here")</span>
                                <input type="file" class="js-file-attach form-check-input" name="thumb"
                                       id="logoUploader" data-hs-file-attach-options='{
                                                                  "textTarget": "#previewImage",
                                                                  "mode": "image",
                                                                  "targetAttr": "src",
                                                                  "allowTypes": [".png", ".jpeg", ".jpg"]
                                                               }'>
                            </label>
                            @error('thumb')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <p>@lang('For better resolution, please use an image with a size of') {{ config('filelocation.destination.size') }} @lang(' pixels.')</p>
                        </div>
                    </div>
                    <div class="card mb-3 mb-lg-5">
                        <div class="card-body">
                            <label class="form-label" for="offerImage">@lang('Offer Image')<sub>@lang('(Optional)')</sub></label>
                            <label class="form-check form-check-dashed" for="offerImageUploader" id="content_img">
                                <img id="previewOfferImage"
                                     class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                     src="{{ asset("assets/admin/img/oc-browse-file.svg") }}"
                                     alt="Image Preview" data-hs-theme-appearance="default">
                                <span class="d-block">@lang("Browse your file here")</span>
                                <input type="file" class="js-file-attach form-check-input" name="offer_image"
                                       id="offerImageUploader" data-hs-file-attach-options='{
                                                                  "textTarget": "#previewOfferImage",
                                                                  "mode": "image",
                                                                  "targetAttr": "src",
                                                                  "allowTypes": [".png", ".jpeg", ".jpg"]
                                                               }'>
                            </label>
                            @error('offer_image')
                            <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <p>@lang('For better resolution, please use an image with a size of') {{ config('filelocation.destination.offer_size') }} @lang(' pixels.')</p>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100">@lang("Save")</button>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="getApiKey" tabindex="-1" aria-labelledby="getApiKeyLabel" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>


                <div class="modal-body">
                    <h3 class="pb-2">@lang('How to Get a Google Maps API Key')</h3>
                    <ol class="list-group list-group-numbered">
                        <li class="list-group-item">@lang('Go to the') <a href="https://console.cloud.google.com/" target="_blank">@lang('Google Maps Platform')</a></li>
                        <li class="list-group-item">@lang('Log in with your Google account')</li>
                        <li class="list-group-item">@lang('Go to the') <a href="https://console.cloud.google.com/apis/credentials" target="_blank">@lang('Credentials page')</a></li>
                        <li class="list-group-item">@lang('Click') <strong>@lang('Create credentials')</strong></li>
                        <li class="list-group-item">@lang('Select') <strong>@lang('API key')</strong></li>
                        <li class="list-group-item">@lang('Click') <strong>@lang('Close')</strong></li>
                        <li class="list-group-item">@lang('Find your new API key under ')<strong>@lang('API keys')</strong> @lang('on the Credentials page')</li>
                    </ol>
                    <p class="mt-3">@lang('You can restrict your API key to specific domains or websites. This is recommended before using the API key in production.')</p>
                    <h5>@lang('Additional Details')</h5>
                    <ul class="d-flex flex-column align-items-start gap-2">
                        <li>@lang('You need a Google account with billing enabled to create a Google Maps API key.')</li>
                        <li>@lang('You can use the API key to authenticate requests associated with your project for usage and billing purposes.')</li>
                        <li>@lang('You can find your API key again by going to ')<strong>@lang('Keys and credentials')</strong> @lang('and selecting your API key.')</li>
                        <li><a href="https://www.youtube.com/results?search_query=Google+Maps+API+key" target="_blank">@lang('Watch a tutorial on YouTube')</a></li>
                        <li>@lang('Read more in the') <a href="https://developers.google.com/maps/gmp-get-started" target="_blank">@lang('Google Maps Platform documentation')</a></li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <div class="d-flex gap-3">
                        <button type="button" class="btn btn-white btn-sm" data-bs-dismiss="modal" aria-label="Close">@lang('Close')</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/summernote-bs5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/flatpickr.min.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <style>
        .ts-wrapper.form-control{
            height: 45px;
        }
        a.btn.btn-primary.ms-1 {
            padding: 1px 10px;
        }
    </style>
@endpush
@push('script')
    <script src="{{ asset('assets/admin/js/summernote-bs5.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/timepicker-bs4.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.dateandtime.js') }}"></script>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script>
        "use strict";

        document.getElementById('offerImageUploader').addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('previewOfferImage').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        function generateSlug() {
            let name = document.getElementById('nameLabel').value;
            let slug = name.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
            document.getElementById('slug').value = slug;
        }

        document.getElementById('logoUploader').addEventListener('change', function() {
            let file = this.files[0];
            let reader = new FileReader();

            reader.onload = function(e) {
                document.getElementById('previewImage').src = e.target.result;
            }

            reader.readAsDataURL(file);
        });
        flatpickr('#Date', {
            enableTime: false,
            dateFormat: "Y-m-d",
            minDate: 'today'
        });

        $(document).ready(function(){

            $(".generate").on('click', function () {
                let lang = $(this).data();
                let form = `<div class="col-md-6 pb-2">
                    <div class="form-group">
                        <div class="input-group">
                            <input name="place[]" class="form-control" type="text" value="" required placeholder="{{trans('Enter a place')}}">
                            <span class="input-group-btn">
                                <button class="btn btn-white delete_desc" type="button">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>`;
                $(this).parents('.form-group').siblings('.addedField').append(form);
            });

            $(document).on('click', '.delete_desc', function () {
                $(this).closest('.col-md-6').remove();
            });

            $('.summernote').summernote({
                height: 200,
                disableDragAndDrop: true,
                callbacks: {
                    onBlurCodeview: function () {
                        let codeviewHtml = $(this).siblings('div.note-editor').find('.note-codable').val();
                        $(this).val(codeviewHtml);
                    }
                }
            });
            HSCore.components.HSTomSelect.init('#category_id', {
                maxOptions: 250,
                placeholder: 'Select category'
            });
            HSCore.components.HSTomSelect.init('#country', {
                maxOptions: 250,
                placeholder: 'Select Country'
            });

            //state dropdown
            $('#country').on('change', function () {
                let idCountry = this.value;

                if ($('#state')[0].tomselect) {
                    $('#state')[0].tomselect.destroy();
                }
                $("#state").html('');

                $.ajax({
                    url: "{{route('admin.fetch.state')}}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "country_id": idCountry,
                    },
                    dataType: 'json',
                    success: function (result) {
                        let stateOptions = '<option value="">-- Select State --</option>';
                        $.each(result.states, function (key, value) {
                            stateOptions += '<option value="' + value.id + '">' + value.name + '</option>';
                        });
                        $("#state").html(stateOptions);

                        HSCore.components.HSTomSelect.init('#state', {
                            maxOptions: 250,
                            placeholder: 'Select State'
                        });
                    }
                });
            });

            //City Dropdown
            $('#state').on('change', function () {
                let idState = this.value;

                if ($('#city')[0].tomselect) {
                    $('#city')[0].tomselect.destroy();
                }

                $("#city").html('');

                $.ajax({
                    url: "{{route('admin.fetch.city')}}",
                    type: "POST",
                    data: {
                        state_id: idState,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (res) {
                        let cityOptions = '<option value="">-- Select City --</option>';
                        $.each(res.cities, function (key, value) {
                            cityOptions += '<option value="' + value.id + '">' + value.name + '</option>';
                        });
                        $("#city").html(cityOptions);

                        HSCore.components.HSTomSelect.init('#city', {
                            maxOptions: 250,
                            placeholder: 'Select City'
                        });
                    }
                });
            });
        });
        document.addEventListener("DOMContentLoaded", function () {
            let map, marker;

            document.getElementById("mapInput").addEventListener("click", function () {
                document.getElementById("mapModal").classList.remove("d-none");

                if (!map) {
                    map = L.map("map").setView([20, 0], 2);
                    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                        attribution: "© OpenStreetMap contributors"
                    }).addTo(map);

                    map.on("click", function (e) {
                        if (marker) marker.remove();
                        marker = L.marker(e.latlng).addTo(map);

                        document.getElementById("lat").value = e.latlng.lat;
                        document.getElementById("long").value = e.latlng.lng;
                        getAddress(e.latlng.lat, e.latlng.lng);
                    });
                }
            });

            function getAddress(lat, lon) {
                fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.display_name) {
                            document.getElementById("mapInput").value = data.display_name;
                        }
                        closeMap();
                    })
                    .catch(error => console.error("Error fetching address:", error));
            }

            document.getElementById("search").addEventListener("keypress", function (event) {
                if (event.key === "Enter") {
                    event.preventDefault();
                    let query = document.getElementById("search").value;
                    searchLocation(query);
                }
            });

            function searchLocation(query) {
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            let lat = data[0].lat;
                            let lon = data[0].lon;
                            map.setView([lat, lon], 12);

                            if (marker) marker.remove();
                            marker = L.marker([lat, lon]).addTo(map);

                            document.getElementById("lat").value = lat;
                            document.getElementById("long").value = lon;
                            document.getElementById("mapInput").value = data[0].display_name;
                        } else {
                            alert("Location not found. Try again.");
                        }
                    })
                    .catch(error => console.error("Error fetching location:", error));
            }
        });
        function closeMap() {
            document.getElementById("mapModal").classList.add("d-none");
        }
    </script>
@endpush
