<div id="stepGeneralInfo" class="step-content-section">
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div class="mb-4">
                <label for="nameLabel" class="form-label">@lang('Name')<span class="text-danger ps-1">*</span> <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Type your destination name here..."></i></label>
                <input type="text" class="form-control" name="name" id="nameLabel" placeholder="e.g dhaka" aria-label="name" value="{{ old('name', optional($package)->title) }}">
                @error('name')
                <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                @enderror
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="mb-4">
                <label for="slugLabel" class="form-label">@lang('Slug') <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Slug will be auto-generated based on the name."></i></label>
                <input type="text" class="form-control" name="slug" id="slugLabel" placeholder="e.g. centipade-tour-guided-arizona-desert-tour-by-atv" aria-label="slug" value="{{ old('slug', $package->slug)  }}">

                @error('slug')
                <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                @enderror
            </div>
        </div>
        <div class="col-sm-6">
            <div class="mb-4">
                <label class="form-label" for="adultPriceLabel">@lang('Price For Adult')<span class="text-danger ps-1">*</span>
                    <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Price for every 18+ tourist."></i>
                </label>
                <div class="input-group">
                    <input type="text" class="form-control flex-grow-1" name="adult_price" id="adultPriceLabel" value="{{ old('adult_price', $package->adult_price) }}" placeholder="e.g 500" aria-label="price">
                    <span class="input-group-text">{{ basicControl()->base_currency }}</span>
                </div>
                @error('adult_price')
                <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                @enderror
            </div>
        </div>

        <div class="col-sm-6">
            <div class="mb-4">
                <label class="form-label" for="childrenPriceLabel">@lang('Price For Children')<span class="text-danger ps-1">*</span>
                    <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Price for every 12-18 year tourist."></i>
                </label>
                <div class="input-group">
                    <input type="text" class="form-control flex-grow-1" name="children_price" id="childrenPriceLabel" value="{{ old('children_price', $package->children_Price)  }}" placeholder="e.g 500" aria-label="price">
                    <span class="input-group-text">{{ basicControl()->base_currency }}</span>
                </div>
                @error('children_price')
                <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                @enderror
            </div>
        </div>

        <div class="col-sm-6">
            <div class="mb-4">
                <label class="form-label" for="infantPriceLabel">@lang('Price For Infant')<span class="text-danger ps-1">*</span>
                    <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Price for every below 12 year tourist."></i>
                </label>
                <div class="input-group">
                    <input type="text" class="form-control flex-grow-1" name="infant_price" id="infantPriceLabel" value="{{ old('infant_price', $package->infant_price) }}" placeholder="e.g 500" aria-label="price">
                    <span class="input-group-text">{{ basicControl()->base_currency }}</span>
                </div>
                @error('infant_price')
                <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                @enderror
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="mb-4">
                <label class="form-label" for="category">@lang('Category')<span class="text-danger ps-1">*</span></label>
                <select class="form-control js-select" id="category" name="category_id">
                    <option value="" disabled>@lang('Select Category')</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ (old('category_id', $package->package_category_id) == $category->id) ? 'selected' : '' }}>
                            @lang($category->name)
                        </option>
                    @endforeach
                </select>

                @error('category_id')
                <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                @enderror
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="mb-4">
                <label class="form-label" for="category">@lang('Destination')<span class="text-danger ps-1">*</span></label>
                <select class="form-control js-select" id="destination" name="destination_id">
                    <option value="" disabled selected>@lang('Select Destination')</option>
                    @foreach($destinations as $item)
                        <option value="{{$item->id}}" data-places="{{ json_encode($item->place) }}" {{ ( old('destination_id', $package->destination_id) == $item->id) ? 'selected' : '' }}>@lang($item->title)</option>
                    @endforeach
                </select>

                @error('destination_id')
                <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                @enderror
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="mb-4">
                <label class="form-label" for="places">@lang('Select Places')<span class="text-danger ps-1">*</span></label>
                <select class="js-select form-select" id="places" autocomplete="off"
                        name="places[]" multiple
                        data-hs-tom-select-options='{
                                                "placeholder": "Select Places"
                                            }'>
                    <option value="">@lang('Select Places')</option>
                </select>

                @error('places')
                <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                @enderror
            </div>
        </div>

        <div class="col-lg-6 col-md-6">
            <div class="col-lg-6 col-sm-4">
                @php
                    $amenitiesData = null;

                    if ($package->amenities != null && is_string($package->amenities)) {
                        $amenitiesData = json_decode($package->amenities, true);
                    } elseif ($package->amenities !== null && is_object($package->amenities)) {
                        $amenitiesData = (array) $package->amenities;
                    }

                    $selectedAmenities = collect(array_merge(
                        $amenitiesData['amenity'] ?? [],
                        $amenitiesData['favourites'] ?? [],
                        $amenitiesData['safety_item'] ?? []
                    ));
                @endphp
                <div class="mb-4">
                    <label class="form-label" for="amenities">@lang('Amenities')<span class="text-danger ps-1">*</span></label>
                    <select class="form-select form-control" id="amenities" multiple="multiple" name="amenities_id[]">
                        @foreach ($amenities ?? [] as $item)
                            <option value="{{ $item->id }}" {{ $selectedAmenities->contains((string) $item->id) ? 'selected' : '' }}>
                                @lang($item->title)
                            </option>
                        @endforeach
                    </select>

                    @error('amenities_id')
                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-sm-4">
            <div class="mb-4">
                <label class="form-label" for="guides">@lang('Guides')<span class="text-danger ps-1">*</span></label>
                <select class="form-select form-control" id="guides" multiple="multiple" name="guides[]">
                    @foreach (auth()->user()->guides ?? [] as $item)
                        <option value="{{ $item->code }}" {{ in_array($item->code, $package->guides ?? []) ? 'selected' : '' }}>
                            @lang($item->name)
                        </option>
                    @endforeach
                </select>

                @error('guides')
                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                @enderror
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="mb-4">
                <label class="form-label" for="address">@lang('Address')</label>
                <input type="text" name="address" id="address" class="form-control" placeholder="@lang('e.g. Les Corts, 08028 Barcelona, Spain')" value="{{ old('address',$package->address) }}">
                @error('address')
                <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                @enderror
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="mb-4">
                <label class="form-label" for="minimumTravelers">@lang('Minimum Travelers')<span class="text-danger ps-1">*</span></label>
                <input type="text" name="minimumTravelers" class="form-control" placeholder="e.g. 5" id="minimumTravelers" value="{{ old('minimumTravelers',$package->minimumTravelers) }}">
                @error('minimumTravelers')
                <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                @enderror
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="mb-4">
                <label class="form-label" for="maximumTravelers">@lang('Maximum Travelers')<span class="text-danger ps-1">*</span></label>
                <input type="text" name="maximumTravelers" class="form-control" placeholder="e.g. 15" id="maximumTravelers" value="{{ old('maximumTravelers',$package->maximumTravelers) }}">
                @error('maximumTravelers')
                <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                @enderror
            </div>
        </div>
        <div class="col-lg-6 col-sm-4">
            <div class="mb-4">
                <label class="form-label" for="tourDuration">@lang('Tour Duration')<span class="text-danger ps-1">*</span></label>
                <input type="text" name="tourDuration" class="form-control" placeholder="e.g. 5 days 4 night" value="{{ old('tourDuration', $package->duration) }}" id="tourDuration">
                @error('tourDuration')
                <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                @enderror
            </div>
        </div>
        <div class="col-lg-6 col-sm-4">
            <div class="mb-4">
                <label for="video" class="form-label">@lang('Video Link')</label>
                <input type="text" name="video" class="form-control" placeholder="enter a video link"  value="{{ old('video', $package->video)  }}" id="video">
                @error('video')
                <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                @enderror
            </div>
        </div>
        <div class="col-12">
            <div class="row mb-3">
                <div class="col-lg-6 col-md-6">
                    <label class="form-label" for="timeFrom">@lang('Tour Time Slot')</label>
                    <div class="row g-2">
                        <div class="col-md-5">
                            <input type="text" id="timeFrom" class="form-control" placeholder="@lang('Start Time')">
                        </div>
                        <div class="col-md-5">
                            <input type="text" id="timeTo" class="form-control" placeholder="@lang('End Time')">
                        </div>
                        <div class="col-md-2">
                            <button type="button" id="addTimeRange" class="btn btn-primary btn-sm w-100">
                                <i class="fas fa-plus"></i> @lang('Add')
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <label for="timeSlot">@lang('Time Slot(s)')</label>
                    <div id="timeSlotContainer" class="d-flex flex-wrap gap-2 py-2">
                        @if(isset($package->timeSlot) && !empty($package->timeSlot))
                            @php
                                $timeSlots = is_array($package->timeSlot) ? $package->timeSlot : json_decode($package->timeSlot, true);
                            @endphp

                            @foreach($timeSlots as $slot)
                                <div class="time-slot">
                                    <input type="hidden" name="timeSlot[]" value="{{ $slot }}">
                                    <span class="badge bg-success">
                                                                    {{ str_replace(' to ', ' - ', $slot) }}
                                                                    <span class="btn-remove-slot">&times;</span>
                                                                </span>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-message text-muted">@lang('No time slots added yet')</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mt-3">
            <div class="row">
                <div class="col-lg-4 col-sm-4">
                    <div class="mb-4">
                        <label class="CountryLevel form-label" for="country">@lang('Country')</label>
                        <input type="text" class="form-control" name="country" value="{{ old('country', optional($package->countryTake)->name) }}" readonly/>
                        @error('country')
                        <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-4 col-sm-4">
                    <div class="mb-4">
                        <label for="state" class="form-label">@lang('State')</label>
                        <input type="text" class="form-control" name="state" value="{{ old('state', optional($package->stateTake)->name) }}" readonly/>
                        @error('state')
                        <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-4 col-sm-4">
                    <div class="mb-4">
                        <label for="city" class="form-label">@lang('City')</label>
                        <input type="text" class="form-control" name="city" value="{{ old('city', optional($package->cityTake)->name) }}" readonly/>
                        @error('city')
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
                        <label class="form-label" for="lat">@lang('Latitude')<span class="text-danger ps-1">*</span></label>
                        <input class="form-control" name="lat" type="text" id="lat" value="{{ old('lat', $package->lat) }}" placeholder="Select Location For Get Latitude" readonly />
                        @error('lat')
                        <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-4 col-sm-12">
                    <div class="mb-4">
                        <label class="form-label" for="long">@lang('Longitude')<span class="text-danger ps-1">*</span></label>
                        <input class="form-control" name="long" type="text" id="long" value="{{ old('long', $package->long) }}" placeholder="Select Location For Get Longitude" readonly />

                        @error('long')
                        <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="btn-bottom">
        <button type="button" class="btn btn-primary btn-sm"
                data-hs-step-form-next-options='{ "targetSelector": "#stepFacilityDetails" }'>
            @lang('Next')<i class="bi-arrow-bar-right ps-1"></i>
        </button>
    </div>
</div>
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/global/css/flatpickr.min.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <style>
        .time-slot {
            position: relative;
        }

        .time-slot .badge {
            padding: 8px 25px 8px 12px;
            font-size: 0.9rem;
            font-weight: normal;
        }

        .btn-remove-slot {
            position: absolute;
            right: 3px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: white;
            opacity: 0.7;
            padding: 0px 3px 2px;
            line-height: 1;
            background: red;
            border-radius: 4px;
        }

        .btn-remove-slot:hover {
            opacity: 1;
            color: #ffcc00;
        }

        #timeSlotContainer {
            min-height: 50px;
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 10px;
        }
        #timeSlotError {
            position: absolute;
            background: #f8d7da;
            color: #721c24;
            padding: 5px 10px;
            border-radius: 4px;
            z-index: 10;
            width: max-content;
            max-width: 200px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-top: 5px;
        }

        #timeSlotError:before {
            content: "";
            position: absolute;
            bottom: 100%;
            left: 10px;
            border-width: 5px;
            border-style: solid;
            border-color: transparent transparent #f8d7da transparent;
        }

    </style>
@endpush
@push('script')
    <script src="{{ asset('assets/admin/js/flatpickr.min.js') }}"></script>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

    <script>
        "use strict";

        flatpickr("#timeFrom", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "h:i K",
            defaultHour: 8,
            defaultMinute: 0,
            minuteIncrement: 15
        });

        flatpickr("#timeTo", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "h:i K",
            defaultHour: 20,
            defaultMinute: 0,
            minuteIncrement: 15
        });

        document.getElementById('addTimeRange').addEventListener('click', function() {
            const from = document.getElementById('timeFrom').value;
            const to = document.getElementById('timeTo').value;
            const errorElement = document.getElementById('timeSlotError');

            if (errorElement) errorElement.remove();

            if (!from || !to) {
                showTimeSlotError('Please select both start and end times');
                return;
            }

            const range = `${from} to ${to}`;
            const container = document.getElementById('timeSlotContainer');

            const existingSlots = Array.from(container.querySelectorAll('input[name="timeSlot[]"]'))
                .map(input => input.value);

            if (existingSlots.includes(range)) {
                showTimeSlotError('This time slot already exists');
                return;
            }

            const emptyMsg = container.querySelector('.empty-message');
            if (emptyMsg) emptyMsg.remove();

            const slotHTML = `
                <div class="time-slot">
                    <input type="hidden" name="timeSlot[]" value="${range}">
                    <span class="badge bg-success">
                        ${range.replace(' to ', ' - ')}
                        <span class="btn-remove-slot">&times;</span>
                    </span>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', slotHTML);

            document.getElementById('timeFrom').value = '';
            document.getElementById('timeTo').value = '';
            document.getElementById('timeFrom').focus();
        });

        function showTimeSlotError(message) {
            const existingError = document.getElementById('timeSlotError');
            if (existingError) existingError.remove();

            const errorElement = document.createElement('div');
            errorElement.id = 'timeSlotError';
            errorElement.className = 'text-danger small mt-2';
            errorElement.textContent = message;

            document.getElementById('addTimeRange').closest('.col-md-2')
                .appendChild(errorElement);

            setTimeout(() => {
                errorElement.remove();
            }, 3000);
        }

        document.getElementById('timeSlotContainer').addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-remove-slot')) {
                const slotElement = e.target.closest('.time-slot');
                slotElement.remove();

                if (this.querySelectorAll('.time-slot').length === 0) {
                    this.innerHTML = '<div class="empty-message text-muted">No time slots added yet</div>';
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const nameInput = document.getElementById('nameLabel');
            const slugInput = document.getElementById('slugLabel');

            nameInput.addEventListener('input', function () {
                slugInput.value = generateSlug(nameInput.value);
            });

            function generateSlug(text) {
                return text
                    .toString()
                    .toLowerCase()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .replace(/[^a-z0-9 -]/g, '')
                    .trim()
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const destinationSelect = document.getElementById('destination');
            const placesSelect = document.querySelector('select[name="places[]"]');
            const preselectedPlaces = @json($package->places) || [];
            let initialLoad = true;

            function populatePlaces() {
                const selectedOption = destinationSelect.options[destinationSelect.selectedIndex];
                const placesData = selectedOption.getAttribute('data-places');

                let places = [];

                try {
                    places = JSON.parse(placesData);
                    if (!Array.isArray(places)) {
                        places = [];
                    }
                } catch (e) {
                    console.warn("Invalid JSON in data-places:", placesData);
                    places = [];
                }

                if (placesSelect.tomselect) {
                    const tom = placesSelect.tomselect;
                    tom.clear();
                    tom.clearOptions();

                    places.forEach(place => {
                        tom.addOption({ value: place, text: place });
                        if (initialLoad && preselectedPlaces.includes(place)) {
                            tom.addItem(place);
                        }
                    });

                    tom.refreshOptions();
                }

                initialLoad = false;
            }

            setTimeout(() => {
                if (destinationSelect.value) {
                    populatePlaces();
                }
            }, 0);

            destinationSelect.addEventListener('change', populatePlaces);
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
