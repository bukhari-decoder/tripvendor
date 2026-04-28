<div id="stepGeneralInfo" class="step-content-section">
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div class="mb-4">
                <label for="productNameLabel" class="form-label">
                    @lang('Name')<span class="text-danger  ps-1">*</span>
                    <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Type your package name here..."></i>
                </label>
                <input type="text" class="form-control required-field" name="name" id="nameLabel" placeholder="e.g. Centipade Tour - Guided Arizona Desert Tour By ATV" aria-label="name" value="{{ old('name') }}">

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
                <input type="text" class="form-control" name="slug" id="slugLabel" placeholder="e.g. centipade-tour-guided-arizona-desert-tour-by-atv" aria-label="slug" value="{{ old('slug') }}">

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
                    <input type="text" class="form-control flex-grow-1 required-field" name="adult_price" id="adultPriceLabel" value="{{ old('adult_price') }}" placeholder="e.g 500" aria-label="price">
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
                    <input type="text" class="form-control flex-grow-1 required-field" name="children_price" id="childrenPriceLabel" value="{{ old('children_price') }}" placeholder="e.g 500" aria-label="price">
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
                    <input type="text" class="form-control flex-grow-1 required-field" name="infant_price" id="infantPriceLabel" value="{{ old('infant_price') }}" placeholder="e.g 500" aria-label="price">
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
                <label class="form-label" for="category_id">@lang('Category')<span class="text-danger ps-1">*</span></label>
                <select class="form-control js-select required-field" id="category" name="category_id">
                    <option value="" disabled>@lang('Select Category')</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                <label class="form-label" for="destination_id">@lang('Destination')<span class="text-danger ps-1">*</span></label>
                <select class="form-control js-select required-field" id="destination" name="destination_id">
                    <option value="" disabled>@lang('Select Destination')</option>
                    @foreach($destinations as $item)
                        <option value="{{ $item->id }}" data-places="{{ json_encode($item->place) }}"
                            {{ old('destination_id') == $item->id ? 'selected' : '' }}>
                            @lang($item->title)
                        </option>
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
                <select class="js-select form-select required-field" autocomplete="off" id="places"
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
        <div class="col-lg-6 col-sm-4">
            <div class="mb-4">
                <label class="form-label" for="amenities">@lang('Amenities')<span class="text-danger ps-1">*</span></label>
                <select class="form-select form-control required-field" id="amenities" multiple="multiple" name="amenities_id[]">
                    @foreach ($amenities ?? [] as $item)
                        <option value="{{ $item->id }}" >
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
        <div class="col-lg-6 col-sm-4">
            <div class="mb-4">
                <label class="form-label" for="guides">@lang('Guides')<span class="text-danger ps-1">*</span></label>
                <select class="form-select form-control required-field" id="guides" multiple="multiple" name="guides[]">
                    @foreach (auth()->user()->guides ?? [] as $item)
                        <option value="{{ $item->code }}" >
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
        <div class="col-lg-6 col-sm-4">
            <div class="mb-4">
                <label for="minimumTravelers" class="form-label">@lang('Minimum Travelers')<span class="text-danger ps-1">*</span></label>
                <input type="number" name="minimumTravelers" class="form-control required-field" placeholder="e.g. 5" value="{{ old('minimumTravelers') }}" id="minimumTravelers">
                @error('minimumTravelers')
                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                @enderror
            </div>
        </div>
        <div class="col-lg-6 col-sm-4">
            <div class="mb-4">
                <label for="maximumTravelers" class="form-label">@lang('Maximum Travelers')<span class="text-danger ps-1">*</span></label>
                <input type="number" name="maximumTravelers" class="form-control required-field" placeholder="e.g. 15" value="{{ old('maximumTravelers') }}" id="maximumTravelers">
                @error('maximumTravelers')
                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                @enderror
            </div>
        </div>
        <div class="col-lg-6 col-sm-4">
            <div class="mb-4">
                <label for="tourDuration" class="form-label">@lang('Tour Duration')<span class="text-danger ps-1">*</span></label>
                <input type="text" name="tourDuration" class="form-control required-field" placeholder="e.g. 5 days 4 night" value="{{ old('tourDuration') }}" id="tourDuration">
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
                <input type="text" name="video" class="form-control" placeholder="enter a video link" value="{{ old('video') }}" id="video">
                @error('video')
                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                @enderror
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-lg-6 col-md-6">
                <label class="form-label" for="timeFrom">@lang('Tour Time Slot')</label>
                <div class="row g-2">
                    <div class="col-md-5">
                        <input type="text" id="timeFrom" class="form-control time-picker" placeholder="@lang('Start Time')">
                    </div>
                    <div class="col-md-5">
                        <input type="text" id="timeTo" class="form-control time-picker" placeholder="@lang('End Time')">
                    </div>
                    <div class="col-md-2 position-relative">
                        <div class="timeSlotAddButton">
                            <button type="button" id="addTimeRange" class="btn btn-primary btn-sm w-100 h-100">
                                <i class="fas fa-plus"></i> @lang('Add')
                            </button>
                        </div>
                        <span class="d-none time-slot-chat-popup" id="time-slot-error"></span>
                    </div>
                </div>
                <small class="text-muted">@lang('Select start and end times')</small>
            </div>

            <div class="col-lg-6 col-md-6">
                <div>
                    <label for="timeSlot">@lang('Time Slot(s)')</label>
                    <div id="timeSlotContainer" class="d-flex flex-wrap gap-2 align-items-center py-2">
                        <div class="text-muted no-slots-message">@lang('No time slots added yet')</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
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
                    <input class="form-control required-field" name="lat" type="text" id="lat" value="{{ old('lat') }}" placeholder="Select Location For Get Latitude" readonly />

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
                    <input class="form-control required-field" name="long" type="text" id="long" value="{{ old('long') }}" placeholder="Select Location For Get Longitude" readonly />

                    @error('long')
                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                    @enderror
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

        .time-slot-chat-popup {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 0.75rem;
            padding: 8px 12px;
            max-width: 200px;
            white-space: normal;
            z-index: 1000;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            font-size: 0.85rem;
            animation: fadeIn 0.3s ease-in-out;
        }

        /* Chat bubble tail */
        .time-slot-chat-popup::after {
            content: "";
            position: absolute;
            top: -8px;
            left: 12px;
            border-width: 8px;
            border-style: solid;
            border-color: transparent transparent #f8d7da transparent;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

    </style>
@endpush
@push('script')
    <script src="{{ asset('assets/admin/js/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script>
        "use strict";
        flatpickr("#timeFrom", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "h:i K",
            defaultHour: 8,
            defaultMinute: 0,
            minuteIncrement: 15,
            time_24hr: false,
            onReady: function () {
                this.input.classList.add('is-valid');
            }
        });

        flatpickr("#timeTo", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "h:i K",
            defaultHour: 17,
            defaultMinute: 0,
            minuteIncrement: 15,
            time_24hr: false,
            onReady: function () {
                this.input.classList.add('is-valid');
            }
        });

        function showTimeSlotError(message) {
            const errorSpan = document.getElementById('time-slot-error');
            errorSpan.textContent = message;
            errorSpan.classList.remove('d-none');

            setTimeout(() => {
                errorSpan.textContent = '';
                errorSpan.classList.add('d-none');
            }, 3000);
        }

        document.getElementById('addTimeRange').addEventListener('click', function () {
            const fromInput = document.getElementById('timeFrom');
            const toInput = document.getElementById('timeTo');
            const from = fromInput.value.trim();
            const to = toInput.value.trim();
            const container = document.getElementById('timeSlotContainer');
            const errorSpan = document.getElementById('time-slot-error');

            fromInput.classList.remove('is-invalid');
            toInput.classList.remove('is-invalid');
            errorSpan.textContent = '';
            errorSpan.classList.add('d-none');

            if (!from && !to) {
                fromInput.classList.add('is-invalid');
                toInput.classList.add('is-invalid');
                showTimeSlotError('Please select start and end times');
                return;
            }

            if (!from) {
                fromInput.classList.add('is-invalid');
                showTimeSlotError('Please select start time');
                return;
            }

            if (!to) {
                toInput.classList.add('is-invalid');
                showTimeSlotError('Please select end time');
                return;
            }

            const range = `${from} - ${to}`;
            const existingSlots = Array.from(container.querySelectorAll('input[name="timeSlot[]"]'))
                .map(input => input.value);

            if (existingSlots.includes(range)) {
                showTimeSlotError('This time slot already exists');
                return;
            }

            const wrapper = document.createElement('div');
            wrapper.className = 'position-relative time-slot-badge';

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'timeSlot[]';
            input.value = range;

            const badge = document.createElement('span');
            badge.className = 'badge bg-success pe-4 py-2';
            badge.innerHTML = `<i class="fas fa-clock me-1"></i>${range}`;

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn btn-sm btn-danger position-absolute translate-middle p-0 remove-time-slot';
            removeBtn.innerHTML = '&times;';
            removeBtn.style.width = '19px';
            removeBtn.style.height = '20px';
            removeBtn.style.fontSize = '12px';
            removeBtn.style.lineHeight = '1';
            removeBtn.style.top = '14px';
            removeBtn.style.right = '-8px';

            removeBtn.addEventListener('click', function () {
                wrapper.remove();
                if (container.querySelectorAll('.time-slot-badge').length === 0) {
                    container.innerHTML = '<div class="text-muted no-slots-message">No time slots added yet</div>';
                }
            });

            wrapper.appendChild(input);
            wrapper.appendChild(badge);
            wrapper.appendChild(removeBtn);

            const noSlotsMsg = container.querySelector('.no-slots-message');
            if (noSlotsMsg) noSlotsMsg.remove();

            container.appendChild(wrapper);

            fromInput._flatpickr.clear();
            toInput._flatpickr.clear();
            fromInput.focus();
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
                    });

                    tom.refreshOptions();
                }
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
