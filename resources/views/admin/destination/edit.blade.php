@extends('admin.layouts.app')
@section('page_title', __('Destination Edit'))
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
                    <h1 class="page-header-title">@lang('Destination Edit')</h1>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-12 mb-3 mb-lg-0">
                <form action="{{ route('admin.destination.update', $destination->id) }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="card mb-3 mb-lg-5">
                        <div class="card-header">
                            <a type="button" href="{{ route('admin.all.destination') }}" class="btn btn-white btn-sm float-end"><i class="bi bi-arrow-left me-1"></i>@lang('Back')</a>
                            <h4 class="card-header-title">@lang('Destination information')</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <label for="productNameLabel" class="form-label">@lang('Name')
                                    <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Type your destination name here..."></i>
                                </label>
                                <input type="text" class="form-control" name="name" id="nameLabel" placeholder="e.g dhaka" aria-label="name" value="{{ optional($destination)->title }}" onkeyup="generateSlug()">
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
                                <input type="text" class="form-control" name="slug" id="slug" aria-label="slug" value="{{ $destination->slug }}">
                                @error('slug')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-lg-4 col-sm-4">
                                    <div class="mb-4">
                                        <label class="form-label" for="country">@lang('Country')</label>
                                        <select id="country" class="form-control js-select" name="country">
                                            <option value="" disabled selected>@lang('Select Country')</option>
                                            @foreach($location as $item)
                                                <option value="{{$item->id}}" {{ ( optional($destination->countryTake)->id == $item->id) ? 'selected' : '' }}>@lang($item->name)</option>
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
                                        <label class="form-label" for="state">@lang('State')</label>
                                        <select name="state" id="state" class="form-control js-select">
                                            <option value="{{ optional($destination->stateTake)->id }}" {{ ( optional($destination->stateTake)->id == $destination->state) ? 'selected' : '' }}>@lang(optional($destination->stateTake)->name)</option>
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
                                        <label class="form-label" for="city">@lang('City')</label>
                                        <select name="city" id="city" class="form-control js-select">
                                            <option value="{{ optional($destination->cityTake)->id }}" {{ ( optional($destination->cityTake)->id == $destination->city) ? 'selected' : '' }}>@lang(optional($destination->cityTake)->name)</option>
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
                                        <input class="form-control" type="text" name="main_language" id="main_language" aria-controls="@lang('Main Language')" value="{{ old('main_language', $destination->main_language) }}" placeholder="@lang('e.g English')" />
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
                                        <input class="form-control" type="text" name="currency" id="currency" aria-controls="@lang('Currency')" value="{{ old('currency', $destination->currency) }}" placeholder="@lang('e.g EURO')" />
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
                                            <input class="form-control" type="text" name="area" id="area" value="{{ old('area', $destination->area) }}" aria-controls="@lang('Area')" placeholder="@lang('e.g 88.00')" />
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
                                        <input class="form-control" name="lat" type="text" id="lat" value="{{ old('lat', $destination->lat) }}" placeholder="Select Location For Get Latitude" readonly />
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
                                        <input class="form-control" name="long" type="text" id="long" value="{{ old('long', $destination->long) }}" placeholder="Select Location For Get Longitude" readonly />

                                        @error('long')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-4">
                                        <div class=" justify-content-between">
                                            <div class="form-group">
                                                <a href="javascript:void(0)" class="btn btn-soft-info btn-sm float-left mt-3 generate">
                                                    <i class="fa fa-plus-circle"></i> @lang('Add Place')</a>
                                            </div>
                                            <div class="row addedField mt-3 col-md-10">
                                                @if (isset($destination->place))
                                                    @foreach($destination->place as $key => $value)
                                                        <div class="col-md-6 pb-2">
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <input name="place[]" class="form-control" type="text"
                                                                           value="{{ old('place', isset($key) ? $value : '') }}"
                                                                           required placeholder="{{ trans('Enter a Place') }}">
                                                                    <span class="input-group-btn">
                                                                        <button class="btn btn-white delete_desc" type="button">
                                                                            <i class="bi bi-trash"></i>
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
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
                                    <label class="pt-2" for="details">@lang('Destination Details')</label>
                                    <textarea
                                        name="details"
                                        class="form-control summernote"
                                        cols="30"
                                        rows="5"
                                        id="details"
                                        placeholder="Destination details"
                                    >{{ $destination->details }}</textarea>
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
                                     src="{{ getFile($destination->thumb_driver, $destination->thumb) }}"
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
                                     src="{{ getFile($destination->offer_image_driver, $destination->offer_image) }}"
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
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/summernote-bs5.min.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
@endpush
@push('js-lib')
    <script src="{{ asset('assets/admin/js/summernote-bs5.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script>
        "use strict";

        function generateSlug() {
            let name = document.getElementById('nameLabel').value;
            let slug = name.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
            document.getElementById('slug').value = slug;
        }
        document.getElementById('offerImageUploader').addEventListener('change', function() {
            let file = this.files[0];
            let reader = new FileReader();

            reader.onload = function(e) {
                document.getElementById('previewOfferImage').src = e.target.result;
            }

            reader.readAsDataURL(file);
        });
        document.getElementById('logoUploader').addEventListener('change', function() {
            let file = this.files[0];
            let reader = new FileReader();

            reader.onload = function(e) {
                document.getElementById('previewImage').src = e.target.result;
            }

            reader.readAsDataURL(file);
        });
        $(document).on('ready', function () {
            HSCore.components.HSTomSelect.init('.js-select', {
                maxOptions: 250,
            })
        });

        $(document).ready(function(){
            $(".generate").on('click', function () {
                let lang = $(this).data();
                let form = `<div class="col-md-6 pb-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input name="place[]" class="form-control " type="text" value="" required placeholder="{{trans('Enter a place')}}">

                                        <span class="input-group-btn">
                                            <button class="btn btn-white delete_desc" type="button">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div> `;
                $(this).parents('.form-group').siblings('.addedField').append(form)
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
