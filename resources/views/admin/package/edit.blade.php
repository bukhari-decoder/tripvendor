@extends('admin.layouts.app')
@section('page_title', __('Package Edit'))
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
                            <li class="breadcrumb-item active" aria-current="page">@lang('Manage Package')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Package Edit')</h1>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8 mb-3 mb-lg-0">
                <form action="{{ route('admin.package.update', $package->id) }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="card mb-3 mb-lg-5">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center justify-content-start">
                                <h4 class="card-header-title pe-2">@lang('Package information')</h4>
                                @if($package->status == 1)
                                    <span class="badge bg-soft-success text-success">
                                        <span class="legend-indicator bg-success"></span>@lang('Accepted')
                                    </span>
                                @elseif($package->status == 0)
                                    <span class="badge bg-soft-warning text-warning">
                                        <span class="legend-indicator bg-warning"></span>@lang('Pending')
                                    </span>
                                @elseif($package->status == 2)
                                    <span class="badge bg-soft-info text-info">
                                        <span class="legend-indicator bg-info"></span>@lang('Resubmitted')
                                    </span>
                                @elseif($package->status == 3)
                                    <span class="badge bg-soft-secondary text-secondary">
                                        <span class="legend-indicator bg-secondary"></span>@lang('Holded')
                                    </span>
                                @elseif($package->status == 4)
                                    <span class="badge bg-soft-dark text-dark">
                                        <span class="legend-indicator bg-dark"></span>@lang('Soft Rejected')
                                    </span>
                                @elseif($package->status == 5)
                                    <span class="badge bg-soft-danger text-danger">
                                        <span class="legend-indicator bg-danger"></span>@lang('Rejected')
                                    </span>
                                @endif
                            </div>
                            <div class="d-flex align-items-center justify-content-end gap-2">
                                @if($package->is_featured == 2)
                                    <a href="#"
                                       class="btn btn-primary btn-sm d-flex align-items-center accFeatured"
                                       data-bs-toggle="modal"
                                       data-bs-target="#acceptFeatured">
                                        <i class="bi bi-check-circle me-1"></i> @lang('Featured Status')
                                    </a>
                                @endif

                                <a class="btn btn-icon btn-sm btn-white" data-bs-toggle="modal" data-bs-target="#action"
                                   href="#">
                                    <i class="bi-list-ul me-1"></i>
                                </a>

                                <a type="button" href="{{ route('admin.all.package') }}" class="btn btn-info btn-sm float-end"><i class="bi bi-arrow-left"></i>@lang('Back')</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="mb-4">
                                        <label for="nameLabel" class="form-label">@lang('Name') <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Type your destination name here..."></i></label>
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
                                        <label class="form-label" for="adultPriceLabel">@lang('Price For Adult')
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
                                        <label class="form-label" for="childrenPriceLabel">@lang('Price For Children')
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
                                        <label class="form-label" for="infantPriceLabel">@lang('Price For Infant')
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
                                        <label class="form-label" for="category">@lang('Category')</label>
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
                                        <label class="form-label" for="category">@lang('Destination')</label>
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
                                        <label class="form-label" for="places">@lang('Select Places')</label>
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
                                            <label class="form-label" for="amenities">@lang('Amenities')</label>
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
                                        <label class="form-label" for="guides">@lang('Guides')</label>
                                        <select class="form-select form-control" id="guides" multiple="multiple" name="guides[]">
                                            @foreach ($package->owner->guides ?? [] as $item)
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
                                        <label class="form-label" for="minimumTravelers">@lang('Minimum Travelers')</label>
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
                                        <label class="form-label" for="maximumTravelers">@lang('Maximum Travelers')</label>
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
                                        <label class="form-label" for="tourDuration">@lang('Tour Duration')</label>
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
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-6 col-md-6">
                                    <label class="form-label" for="timeFrom">@lang('Tour Time Slot')</label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="text" id="timeFrom" class="form-control" placeholder="@lang('Start Time')">
                                            <div class="invalid-feedback">Please select start time</div>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="text" id="timeTo" class="form-control" placeholder="@lang('End Time')">
                                            <div class="invalid-feedback">Please select end time</div>
                                        </div>
                                        <div class="col-md-4">
                                            <button type="button" id="addTimeRange" class="btn btn-primary mt-md-0 mt-2 w-100">@lang('Add Time Slot')</button>
                                        </div>
                                    </div>
                                    <small class="text-muted">Select time range and click add</small>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div>
                                        <label for="timeSlot">@lang('Time Slot(s)')</label>
                                        <div id="timeSlotContainer" class="d-flex flex-wrap gap-2">
                                            @php
                                                $timeSlots = is_array($package->timeSlot) ? $package->timeSlot : json_decode($package->timeSlot, true);
                                            @endphp

                                            @if (!empty($timeSlots))
                                                @foreach ($timeSlots as $slot)
                                                    @php
                                                        $formattedSlot = str_replace(' to ', ' - ', $slot);
                                                    @endphp
                                                    <div class="position-relative time-slot-badge">
                                                        <input type="hidden" name="timeSlot[]" value="{{ $slot }}">
                                                        <span class="badge bg-success pe-4">{{ $formattedSlot }}</span>
                                                        <span class="position-absolute start-100 translate-middle badge rounded-pill bg-danger cursor-pointer remove-time-slot" style="cursor:pointer;">&times;</span>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="text-muted">No time slots added yet</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-sm-4">
                                    <div class="mb-4">
                                        <label class="CountryLevel" for="country">@lang('Country')</label>
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
                                        <label class="form-label" for="lat">@lang('Latitude')</label>
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
                                        <label class="form-label" for="long">@lang('Longitude')</label>
                                        <input class="form-control" name="long" type="text" id="long" value="{{ old('long', $package->long) }}" placeholder="Select Location For Get Longitude" readonly />

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
                                                <a href="javascript:void(0)" class="btn btn-success float-left mt-3 generate">
                                                    <i class="fa fa-plus-circle"></i> @lang('Included Facility')</a>
                                            </div>
                                            <div class="row addedField mt-3 col-md-10">
                                                @if (isset($package->facility))
                                                    @foreach($package->facility as $key => $value)
                                                        <div class="col-md-6 pb-2">
                                                            <div class="form-group">
                                                                <div class="input-group">
                                                                    <input name="facility[]" class="form-control" type="text"
                                                                           value="{{ old('facility.'.$key, $value ?? '') }}"
                                                                           required placeholder="{{ trans('Enter a included facility') }}">
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
                                        @error('facility')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-4">
                                    <div class=" justify-content-between">
                                        <div class="form-group">
                                            <a href="javascript:void(0)" class="btn btn-success float-left mt-3 generateExcluded">
                                                <i class="fa fa-plus-circle"></i> @lang('Excluded Facility')</a>
                                        </div>
                                        <div class="row addedExcludedField mt-3 col-md-10">
                                            @if (isset($package->excluded))
                                                @foreach($package->excluded as $key => $value)
                                                    <div class="col-md-6 pb-2">
                                                        <div class="form-group">
                                                            <div class="input-group">
                                                                <input name="excluded[]" class="form-control" type="text"
                                                                       value="{{ old('excluded.'.$key, $value ?? '') }}"
                                                                       required placeholder="{{ trans('Enter a excluded facility') }}">
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
                                    </div>@error('excluded')
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
                                            <a href="javascript:void(0)" class="btn btn-success float-left mt-3 generateExpect">
                                                <i class="fa fa-plus-circle"></i> @lang('What We Expect')</a>
                                        </div>
                                        <div class="row addedExpectField mt-3">
                                            @if (isset($package->expected))
                                                @foreach($package->expected as $key => $value)
                                                    <div class="col-md-6 pb-2 expectationArea">
                                                        <div class="form-group">
                                                            <div class="inputArea">
                                                                <input name="expect[]" class="form-control expect" type="text"
                                                                       value="{{ old('expect.'.$key, $value->expect ?? '') }}"
                                                                       required placeholder="{{ trans('Enter a expect title') }}">

                                                                <textarea name="expect_details[]" class="form-control mt-2" rows="4"
                                                                          placeholder="Expectation details">{{ $value->expect_detail }}</textarea>
                                                            </div>
                                                            <div class="deleteExpectArea ms-1">
                                                                <div class="input-group-btn">
                                                                    <button class="btn btn-white delete-btn" type="button" title="Delete">
                                                                        <i class="bi bi-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    @error('expect')
                                    <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    @error('expect_details')
                                    <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3 mb-lg-5">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <label class="pt-2" for="details">@lang('Package Details')</label>
                                    <textarea
                                        name="details"
                                        class="form-control summernote"
                                        cols="30"
                                        rows="5"
                                        id="details"
                                        placeholder="Package details"
                                    >{{ $package->description }}</textarea>
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
                                     src="{{ getFile($package->thumb_driver, $package->thumb) }}"
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
                            <p class="pt-2">@lang('For better resolution, please use an image with a size of') {{ config('filelocation.package_thumb.size') }} @lang(' pixels.')</p>
                            @error('thumb')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="card mb-3 mb-lg-5">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <label for="image" class="form-label">@lang('Package Images')</label>
                                    <div class="input-images" id="image"></div>

                                    @if($errors->has('images'))
                                        <span class="invalid-feedback d-block">
                                            <strong>{{ $errors->first('images') }}</strong>
                                        </span>
                                    @endif
                                    <p class="pt-2">@lang('For better resolution, please use an image with a size of') {{ config('filelocation.package.size') }} @lang(' pixels.')</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">@lang("Save")</button>
                </form>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fa fa-history"></i> @lang('Activity Log')</h5>
                        <ul class="step mt-4">
                            @forelse($activity as $k => $row)
                                <li class="step-item">
                                    <div class="step-content-wrapper">
                                        <div class="step-avatar">
                                            <img class="step-avatar-img"
                                                 src="{{getFile(optional($row->activityable)->image_driver, optional($row->activityable)->image)}}"
                                                 alt="{{optional($row->activityable)->username}}">
                                        </div>

                                        <div class="step-content">
                                            <h5 class="mb-1">@lang($row->title) ({{diffForHumans($row->created_at)}})</h5>

                                            <p class="fs-5 mb-1">@lang($row->description)
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <div class="text-center ms-6 p-4">
                                    <img class="dataTables-image mb-3"
                                         src="{{ asset('assets/admin/img/oc-error.svg') }}" alt="Image Description"
                                         data-hs-theme-appearance="default">
                                    <img class="dataTables-image mb-3"
                                         src="{{ asset('assets/admin/img/oc-error-light.svg') }}"
                                         alt="Image Description" data-hs-theme-appearance="dark">
                                    <p class="mb-0">@lang('No data to show')</p>
                                </div>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="action" data-bs-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> @lang('Action')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.package.action') }}" method="post">
                    @csrf

                    <input type="hidden" name="property_id" value="{{$package->id}}">
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold mb-2">@lang('Status') </label>
                            <select id="status" class="form-control js-select" name="status" aria-label=".form-select-lg example" required>
                                <option value="" selected disabled>@lang('Select Status')</option>
                                <option value="1" {{ ( $package->status == 1) ? 'selected' : '' }}>@lang('Approve')</option>
                                <option value="3" {{ ( $package->status == 3) ? 'selected' : '' }}>@lang('Hold')</option>
                                <option value="4" {{ ( $package->status == 4) ? 'selected' : '' }}>@lang('Soft Rejected')</option>
                                <option value="5" {{ ( $package->status == 5) ? 'selected' : '' }}>@lang('Hard Rejected')</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="comments" class="font-weight-bold mb-2"> @lang('Comment') </label>
                            <textarea name="comments" rows="4" class="form-control" value="" required></textarea>

                            @error('comments')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-soft-primary"><span>@lang('Submit')</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="acceptFeatured" data-bs-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="acceptFeaturedLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> @lang('Confirmation')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.package.featured.action') }}" method="post">
                    @csrf

                    <input type="hidden" name="property_id" value="{{$package->id}}">
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped align-middle mb-0">
                                <tbody>
                                <tr>
                                    <th>@lang('Title')</th>
                                    <td>@lang($package->title)</td>
                                </tr>
                                <tr>
                                    <th>@lang('Price')</th>
                                    <td>{{ currencyPosition($package->adult_price) }}</td>
                                </tr>
                                <tr>
                                    <th>@lang('Total sell')</th>
                                    <td>@lang($package->total_sell)</td>
                                </tr>
                                <tr>
                                    <th>@lang('Created at')</th>
                                    <td>{{ dateTime($package->created_at) }}</td>
                                </tr>
                                <tr>
                                    <th>@lang('Featured item limit Left')</th>
                                    <td>{{ $package->owner?->vendorInfo?->plan?->featured_listing - $package->owner?->featuredPackages?->count() }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-soft-danger" name="confirm" value="3"><span>@lang('Cancel')</span></button>
                        <button type="submit" class="btn btn-soft-primary" name="confirm" value="1"><span>@lang('Accept')</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/global/css/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/image-uploader.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/summernote-bs5.min.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <style>
        .remove-time-slot {
            font-size: 9px !important;
            line-height: 1;
            cursor: pointer;
            top: 13px !important;
            right: 0;
            display: flex !important;
            align-items: center;
            justify-content: center;
            padding: 1px 5px;
        }
    </style>
@endpush
@push('js-lib')
    <script src="{{ asset('assets/admin/js/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/summernote-bs5.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/image-uploader.min.js') }}"></script>
    <script src="{{ asset("assets/admin/js/hs-file-attach.min.js") }}"></script>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>

    <script>
        "use strict";
        function formatTimeSlot(slot) {
            const [from, to] = slot.split(' to ');
            return `${from} - ${to}`;
        }

        flatpickr("#timeFrom", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "h:i K",
            defaultHour: 8,
            defaultMinute: 0,
            minuteIncrement: 15,
            time_24hr: false,
            onReady: function(selectedDates, dateStr, instance) {
                instance.input.classList.add('is-valid');
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
            onReady: function(selectedDates, dateStr, instance) {
                instance.input.classList.add('is-valid');
            }
        });

        document.getElementById('addTimeRange').addEventListener('click', function () {
            const fromInput = document.getElementById('timeFrom');
            const toInput = document.getElementById('timeTo');
            const from = fromInput.value.trim();
            const to = toInput.value.trim();

            fromInput.classList.remove('is-invalid');
            toInput.classList.remove('is-invalid');

            if (!from) {
                fromInput.classList.add('is-invalid');
                return;
            }
            if (!to) {
                toInput.classList.add('is-invalid');
                return;
            }

            const range = `${from} to ${to}`;
            const container = document.getElementById('timeSlotContainer');

            const existingSlots = Array.from(container.querySelectorAll('input[name="timeSlot[]"]')).map(el => el.value);
            if (existingSlots.includes(range)) {
                toastr.error('This time slot already exists');
                return;
            }

            const wrapper = document.createElement('div');
            wrapper.className = 'position-relative time-slot-badge';

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'timeSlot[]';
            input.value = range;

            const badge = document.createElement('span');
            badge.className = 'badge bg-success pe-4';
            badge.textContent = formatTimeSlot(range);

            const removeBtn = document.createElement('span');
            removeBtn.className = 'position-absolute start-100 translate-middle badge rounded-pill bg-danger cursor-pointer remove-time-slot';
            removeBtn.innerHTML = '&times;';
            removeBtn.style.cursor = 'pointer';

            removeBtn.addEventListener('click', function () {
                wrapper.remove();
                if (container.children.length === 0) {
                    const emptyMsg = document.createElement('div');
                    emptyMsg.className = 'text-muted';
                    emptyMsg.textContent = 'No time slots added yet';
                    container.appendChild(emptyMsg);
                }
            });

            wrapper.appendChild(input);
            wrapper.appendChild(badge);
            wrapper.appendChild(removeBtn);

            const emptyMsg = container.querySelector('.text-muted');
            if (emptyMsg) {
                emptyMsg.remove();
            }

            container.appendChild(wrapper);

            fromInput._flatpickr.clear();
            toInput._flatpickr.clear();
            fromInput.focus();
        });

        document.querySelectorAll('.remove-time-slot').forEach(el => {
            el.addEventListener('click', function () {
                const container = document.getElementById('timeSlotContainer');
                this.closest('.time-slot-badge').remove();

                if (container.children.length === 0) {
                    const emptyMsg = document.createElement('div');
                    emptyMsg.className = 'text-muted';
                    emptyMsg.textContent = 'No time slots added yet';
                    container.appendChild(emptyMsg);
                }
            });
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
        $(document).ready(() => new HSFileAttach('.js-file-attach'));
        $(document).ready(function(){

            ['#amenities', '#country', '#state', '#city', '#places','#destination','#category','status','#guides'].forEach(id => {
                HSCore.components.HSTomSelect.init(id, {
                    maxOptions: 250,
                    placeholder: `Select ${id.replace('#', '').replace('_', ' ')}`
                });
            });
            $(".generate").on('click', function () {
                let lang = $(this).data();
                let form = `<div class="col-md-6 pb-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input name="facility[]" class="form-control " type="text" value="" required placeholder="{{trans('Enter a facility')}}">

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
            $(".generateExcluded").on('click', function () {
                let lang = $(this).data();
                let form = `<div class="col-md-6 pb-2">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input name="excluded[]" class="form-control " type="text" value="" required placeholder="{{trans('Enter a excluded facility')}}">

                                        <span class="input-group-btn">
                                            <button class="btn btn-white delete_desc" type="button">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div> `;
                $(this).parents('.form-group').siblings('.addedExcludedField').append(form)
            });
            $(".generateExpect").on('click', function () {
                let form = `<div class="col-md-6 pb-2 expectationArea">
                                <div class="form-group">
                                    <div class="inputArea">
                                        <input name="expect[]" class="form-control expect" type="text" value="" required placeholder="{{trans('Enter a expect title')}}">
                                        <textarea
                                            name="expect_details[]"
                                            class="form-control summernote"
                                            cols="30"
                                            rows="5"
                                            id="details"
                                            placeholder="Expectation details"
                                        ></textarea>
                                    </div>
                                    <div class="deleteExpectArea ms-1">
                                        <span class="input-group-btn">
                                            <button class="btn btn-white delete_desc" type="button">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div> `;
                $(this).parents('.form-group').siblings('.addedExpectField').append(form)
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

            let images = @json($images);
            let oldImage = @json($oldimg);
            let preloaded = [];

            images.forEach(function(value, index) {
                preloaded.push({
                    id: oldImage[index],
                    src: value,
                });
            });

            $('.input-images').imageUploader({
                preloaded: preloaded
            });
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

