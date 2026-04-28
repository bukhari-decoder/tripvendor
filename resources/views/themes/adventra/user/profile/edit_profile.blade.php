@extends(template().'layouts.user')
@section('page_title',trans('Profile'))
@section('content')
    <div class="content container-fluid">
        <div class="row justify-content-lg-center">
            <div class="col-lg-10">
                <div class="page-header custom-header">
                    <div class="row align-items-end">
                        <div class="col-sm">
                            <h1 class="page-header-title">@lang("Profile")</h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb breadcrumb-no-gutter">
                                    <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                                   href="{{ route('user.dashboard') }}">@lang('Dashboard')</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">@lang("User Profile")</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3">
                        <!-- Navbar -->
                        <div class="navbar-expand-lg navbar-vertical mb-3 mb-lg-5">
                            <div class="d-grid">
                                <button type="button" class="navbar-toggler btn btn-white mb-3" data-bs-toggle="collapse" data-bs-target="#navbarVerticalNavMenu" aria-label="Toggle navigation" aria-expanded="false" aria-controls="navbarVerticalNavMenu">
                                    <span class="d-flex justify-content-between align-items-center">
                                      <span class="text-dark">@lang('Menu')</span>

                                      <span class="navbar-toggler-default">
                                        <i class="bi-list"></i>
                                      </span>

                                      <span class="navbar-toggler-toggled">
                                        <i class="bi-x"></i>
                                      </span>
                                    </span>
                                </button>
                            </div>
                            <!-- End Navbar Toggle -->
                            <!-- End Navbar Toggle -->

                            <!-- Navbar Collapse -->
                            <div id="navbarVerticalNavMenu" class="collapse navbar-collapse">
                                <ul id="navbarSettings" class="js-sticky-block js-scrollspy card card-navbar-nav nav nav-tabs nav-lg nav-vertical" data-hs-sticky-block-options='{
                             "parentSelector": "#navbarVerticalNavMenu",
                             "targetSelector": "#header",
                             "breakpoint": "lg",
                             "startPoint": "#navbarVerticalNavMenu",
                             "endPoint": "#stickyBlockEndPoint",
                             "stickyOffsetTop": 20
                           }'>
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#content">
                                            <i class="bi-person nav-icon"></i> @lang('Basic information')
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#passwordSection">
                                            <i class="bi-key nav-icon"></i> @lang('Password')
                                        </a>
                                    </li>
                                    @if(auth()->user()->role == 1)
                                        <li class="nav-item">
                                            <a class="nav-link" href="#deleteAccountSection">
                                                <i class="bi-trash nav-icon"></i> @lang('Delete account')
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-9">
                        <div class="d-grid gap-3 gap-lg-5">
                            <div class="card">
                                <div class="profile-cover">
                                    <div class="profile-cover-img-wrapper">
                                        <img id="profileCoverImg" class="profile-cover-img" src="{{ asset(template(true).'img/img2.jpg') }}" alt="Image Description">

                                    </div>
                                </div>
                                <form id="avatarUploadForm" action="{{ route('user.profile.update.image') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <label class="avatar avatar-xxl avatar-circle avatar-uploader profile-cover-avatar" for="editAvatarUploaderModal">
                                        <img id="editAvatarImgModal" class="avatar-img" src="{{ getFile(auth()->user()->image_driver, auth()->user()->image) }}" alt="Image Description">

                                        <input type="file" name="image" class="js-file-attach avatar-uploader-input" id="editAvatarUploaderModal"
                                               data-hs-file-attach-options='{
                                                   "textTarget": "#editAvatarImgModal",
                                                   "mode": "image",
                                                   "targetAttr": "src",
                                                   "allowTypes": [".png", ".jpeg", ".jpg"]
                                               }' onchange="document.getElementById('avatarUploadForm').submit();">

                                        <span class="avatar-uploader-trigger">
                                            <i class="bi-pencil-fill avatar-uploader-icon shadow-sm"></i>
                                        </span>
                                    </label>
                                </form>
                                <!-- End Avatar -->

                                <!-- Body -->
                                <div class="card-body">
                                    <div class="row align-items-center justify-content-center w-100">
                                        <div class="col-sm-5 w-100 text-center">
                                            <h1 class="page-header-title text-center">
                                                @lang(auth()->user()->firstname. ' ' . auth()->user()->lastname)
                                            </h1>
                                            <ul class="list-inline list-px-2">
                                                @if(isset(auth()->user()->address_one) || isset(auth()->user()->city) || isset(auth()->user()->country))
                                                    <li class="list-inline-item">
                                                        <i class="bi-geo-alt me-1"></i>
                                                        @if(isset(auth()->user()->address_one))
                                                            <span>@lang(auth()->user()->address_one),</span>
                                                        @endif
                                                        @if(isset(auth()->user()->city))
                                                            <span>@lang(auth()->user()->city),</span>
                                                        @endif
                                                        @if(isset(auth()->user()->country))
                                                            <span>@lang(auth()->user()->country)</span>
                                                        @endif
                                                    </li>
                                                @endif

                                                @if(isset(auth()->user()->created_at))
                                                    <li class="list-inline-item">
                                                        <i class="bi-calendar-week me-1"></i>
                                                        <span>{{ 'Joined ' . dateTime(auth()->user()->created_at, 'M Y') }}</span>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h2 class="card-title h4">@lang('Basic information')</h2>
                                </div>

                                <div class="card-body">
                                    <form action="{{ route('user.profile.update') }}" method="post"
                                          enctype="multipart/form-data">
                                        @csrf

                                        <div class="row mb-4">
                                            <label for="firstNameLabel" class="col-sm-3 col-form-label form-label">@lang('Full name') <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Displayed on public forums, such as Front."></i></label>

                                            <div class="col-sm-9">
                                                <div class="input-group input-group-sm-vertical">
                                                    <input type="text" class="form-control" name="firstname" id="firstNameLabel" placeholder="Your first name" aria-label="Your first name" value="{{ old('firstname', auth()->user()->firstname) }}">
                                                    <input type="text" class="form-control" name="lastname" id="lastNameLabel" placeholder="Your last name" aria-label="Your last name" value="{{ old('lastname', auth()->user()->lastname) }}">
                                                </div>
                                                @error('firstname')
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                                @error('lastname')
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <label for="usernameLabel" class="col-sm-3 col-form-label form-label">@lang('Username')</label>

                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="username" id="usernameLabel" placeholder="@lang('e.g. david')" aria-label="@lang('username')" value="{{ old('username', auth()->user()->username) }}">
                                                @error('username')
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <label for="emailLabel" class="col-sm-3 col-form-label form-label">@lang('Email')</label>

                                            <div class="col-sm-9">
                                                <input type="email" class="form-control" name="email" id="emailLabel" placeholder="Email" aria-label="Email" value="{{ old('email', auth()->user()->email) }}">
                                                @error('email')
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <label for="phoneLabel" class="col-sm-3 col-form-label form-label">@lang('Phone')</label>

                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <select class="form-select js-select w-auto d-inline-block" name="phone_code" id="phoneCode" aria-label="Phone code">
                                                        @foreach(config('country') ?? [] as $code)
                                                            <option value="{{ $code['phone_code'] }}" {{ (auth()->user()->phone_code == $code['phone_code']) ? 'selected': '' }}>{{ $code['phone_code'] }}</option>
                                                        @endforeach
                                                    </select>
                                                    <input type="text" class="js-input-mask form-control" name="phone" id="phoneLabel" placeholder="(xxx)xxx-xx-xx" aria-label="(xxx)xxx-xx-xx" value="{{ auth()->user()->phone }}" data-hs-mask-options='{
                                                       "mask": "0000000000"
                                                     }'>
                                                </div>
                                                @error('phone_code')
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                                @error('phone')
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <label for="addressOneLabel" class="col-sm-3 col-form-label form-label">@lang('Address One')</label>

                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="address_one" id="addressOneLabel" placeholder="e.g H/41, NY, USA" aria-label="@lang('Address One')" value="{{ auth()->user()->address_one }}">
                                                @error('address_one')
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <label for="addressTwoLabel" class="col-sm-3 col-form-label form-label">@lang('Address Two')</label>

                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="address_two" id="addressTwoLabel" placeholder="e.g H/41, NY, USA" aria-label="@lang('Address Two')" value="{{ auth()->user()->address_two }}">
                                                @error('address_two')
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <label for="zipCodeLabel" class="col-sm-3 col-form-label form-label">@lang('Zip Code')</label>

                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="zipcode" id="zipCodeLabel" placeholder="e.g. 1000" aria-label="@lang('Zip Code')" value="{{ auth()->user()->zip_code }}">
                                                @error('zipcode')
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div id="accountType" class="row mb-4">
                                            <label class="col-sm-3 col-form-label form-label">@lang('About Me')</label>

                                            <div class="col-sm-9">
                                                <textarea class="form-control" name="about_me" placeholder="@lang('Text about me')">{{ auth()->user()->about_me }}</textarea>
                                                @error('about_me')
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <label for="locationLabel" class="col-sm-3 col-form-label form-label">@lang('Location')</label>

                                            <div class="col-sm-9">
                                                <div class="tom-select-custom mb-4">
                                                    <select id="country" class="js-select form-select" name="country" id="locationLabel">
                                                        @foreach($counties ?? [] as $country)
                                                            <option value="{{ $country->id }}" data-option-template='<span class="d-flex align-items-center"><img class="avatar avatar-xss avatar-circle me-2" src="{{ getFile($country->image_driver, $country->image) }}" alt="{{ $country->name .' Flag' }}" /><span class="text-truncate">{{ $country->name ?? '' }}</span></span>'>{{ $country->name ?? '' }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('state')
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label" for="state">@lang('State')</label>
                                                    <select name="state" id="state" class="form-select js-select">
                                                        <option value="{{ optional(auth()->user()->stateTake)->id }}" {{ ( optional(auth()->user()->stateTake)->name == auth()->user()->state) ? 'selected' : '' }}>@lang(optional(auth()->user()->stateTake)->name)</option>
                                                    </select>
                                                    @error('state')
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="mb-4">
                                                    <label class="form-label" for="city">@lang('City')</label>
                                                    <select name="city" id="city" class="form-select js-select">
                                                        <option value="{{ optional(auth()->user()->cityTake)->id }}" {{ ( optional(auth()->user()->cityTake)->name == auth()->user()->city) ? 'selected' : '' }}>@lang(optional(auth()->user()->cityTake)->name)</option>
                                                    </select>
                                                    @error('city')
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="mb-4">
                                                    <label class="form-label" for="language">@lang('Prefered Language')</label>
                                                    <select name="language" id="language" class="form-select js-select">
                                                        @foreach($languages ?? [] as $lang)
                                                            <option value="{{ $lang->id }}" {{ (auth()->user()->language_id == $lang->id) ? 'selected' : '' }}>{{ $lang->name ?? '' }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('language')
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        @if(auth()->user()->role == 1)
                                            <div class="row mb-4">
                                                <label for="locationLabel" class="col-sm-3 col-form-label form-label">@lang('Links')</label>
                                                <div class="col-sm-9">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="facebook_link">@lang('Facebook Link')</label>
                                                        <input type="text" class="form-control" name="facebook_link" id="facebook_link" placeholder="e.g https://www.facebook.com/" aria-label="@lang('Facebook Link')" value="{{ $vendorInfo->facebook_link }}">
                                                        @error('facebook_link')
                                                            <span class="invalid-feedback d-block" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label" for="twitter_link">@lang('Twitter Link')</label>

                                                        <input type="text" class="form-control" name="twitter_link" id="twitter_link" placeholder="e.g https://www.x.com/" aria-label="@lang('Twitter Link')" value="{{ $vendorInfo->twitter_link }}">
                                                        @error('twitter_link')
                                                            <span class="invalid-feedback d-block" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label" for="instagram_link">@lang('Instagram Link')</label>
                                                        <input type="text" class="form-control" name="instagram_link" id="instagram_link" placeholder="e.g https://www.instagram.com/" aria-label="@lang('Instagram Link')" value="{{ $vendorInfo->instagram_link }}">
                                                        @error('instagram_link')
                                                            <span class="invalid-feedback d-block" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label" for="linkedin_link">@lang('Linkedin Link')</label>
                                                        <input type="text" class="form-control" name="linkedin_link" id="linkedin_link" placeholder="e.g https://www.linkedin.com/" aria-label="@lang('Linkedin Link')" value="{{ $vendorInfo->linkedin_link }}">
                                                        @error('linkedin_link')
                                                            <span class="invalid-feedback d-block" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary">@lang('Save changes')</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div id="passwordSection" class="card">
                                <div class="card-header">
                                    <h4 class="card-title">@lang('Change your password')</h4>
                                </div>

                                <div class="card-body">
                                    <form action="{{ route('user.updatePassword') }}" method="post">
                                        @csrf

                                        <div class="row mb-4">
                                            <label for="currentPasswordLabel" class="col-sm-3 col-form-label form-label">@lang('Current password')</label>
                                            <div class="col-sm-9 position-relative">
                                                <input type="password" class="form-control" name="current_password" id="currentPasswordLabel" placeholder="Enter current password" aria-label="@lang('Enter current password')">
                                                <i class="fa fa-eye-slash toggle-password" id="toggleCurrentPassword"></i>
                                                @if($errors->has('current_password'))
                                                    <div class="error text-danger">@lang($errors->first('current_password')) </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <label for="newPassword" class="col-sm-3 col-form-label form-label">@lang('New password')</label>
                                            <div class="col-sm-9 position-relative">
                                                <input type="password" class="form-control" name="password" id="newPassword" placeholder="@lang('Enter new password')" aria-label="@lang('Enter new password')">
                                                <i class="fa fa-eye-slash toggle-password" id="toggleNewPassword"></i>
                                                @if($errors->has('password'))
                                                    <div class="error text-danger">@lang($errors->first('password')) </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <label for="confirmNewPasswordLabel" class="col-sm-3 col-form-label form-label">@lang('Confirm new password')</label>
                                            <div class="col-sm-9 ">
                                                <div class="mb-3 position-relative">
                                                    <input type="password" class="form-control" name="password_confirmation" id="confirmNewPasswordLabel" placeholder="@lang('Confirm your new password')" aria-label="@lang('Confirm your new password')">
                                                    <i class="fa fa-eye-slash toggle-password toggle-password-confirm" id="toggleConfirmPassword"></i>
                                                </div>
                                                <div id="password-match-message" class="text-sm"></div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary">@lang('Save Changes')</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            @if(auth()->user()->role == 1)
                                <div id="deleteAccountSection" class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">@lang('Delete your account')</h4>
                                    </div>

                                    <div class="card-body">
                                        <form action="{{ route('user.delete.account') }}" method="GET">
                                            <p class="card-text">@lang('When you delete your account, you lose access to Front account services, and we permanently delete your personal data. You can cancel the deletion for 14 days.')</p>

                                            <div class="mb-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="deleteConfirm" value="1" id="deleteAccountCheckbox">
                                                    <label class="form-check-label" for="deleteAccountCheckbox">
                                                        @lang('Confirm that I want to delete my account.')
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="d-flex justify-content-end gap-3">
                                                <button type="submit" class="btn btn-danger">@lang('Delete')</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div id="stickyBlockEndPoint"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <style>
        .position-relative {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            top: 45%;
            right: 25px;
            transform: translateY(-50%);
            cursor: pointer;
        }
        .toggle-password-confirm{
            right: 15px !important;
        }
    </style>
@endpush
@push('script')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
            Notiflix.Notify.failure("{{ $error }}");
            @endforeach
        </script>
    @endif
    <script>
        document.getElementById('toggleCurrentPassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('currentPasswordLabel');
            const icon = this;
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });

        document.getElementById('toggleNewPassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('newPassword');
            const icon = this;
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });

        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('confirmNewPasswordLabel');
            const icon = this;
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });

        $(document).ready(function () {
            HSCore.components.HSTomSelect.init('.js-select', {
                placeholder: 'Select One'
            });
            $('#country').on('change', function () {
                let idCountry = this.value;

                if ($('#state')[0].tomselect) {
                    $('#state')[0].tomselect.destroy();
                }
                $("#state").html('');

                $.ajax({
                    url: "{{route('user.fetch.state')}}",
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
                    url: "{{route('user.fetch.city')}}",
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

        document.getElementById('editAvatarUploaderModal').addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('editAvatarImgModal').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });


        const newPassword = document.getElementById('newPassword');
        const confirmPassword = document.getElementById('confirmNewPasswordLabel');
        const matchMessage = document.getElementById('password-match-message');

        function checkPasswordMatch() {
            const newVal = newPassword.value;
            const confirmVal = confirmPassword.value;

            if (!newVal || !confirmVal) {
                matchMessage.textContent = '';
                matchMessage.classList.remove('text-success', 'text-danger');
                return;
            }

            if (newVal === confirmVal) {
                matchMessage.textContent = 'Passwords match';
                matchMessage.classList.add('text-success');
                matchMessage.classList.remove('text-danger');
            } else {
                matchMessage.textContent = 'Passwords do not match';
                matchMessage.classList.add('text-danger');
                matchMessage.classList.remove('text-success');
            }
        }

        newPassword.addEventListener('keyup', checkPasswordMatch);
        confirmPassword.addEventListener('keyup', checkPasswordMatch);
    </script>

@endpush
