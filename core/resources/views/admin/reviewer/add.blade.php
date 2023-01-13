@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="{{ route('admin.reviewers.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="image-upload">
                                        <div class="thumb">
                                            <div class="avatar-preview">
                                                <div class="profilePicPreview" style="background-image: url({{ getImage(getFilePath('reviewer'), getFileSize('reviewer')) }})">
                                                    <button class="remove-image" type="button"><i class="fa fa-times"></i></button>
                                                </div>
                                            </div>
                                            <div class="avatar-edit">
                                                <input class="profilePicUpload" id="profilePicUpload1" name="image" type="file" accept=".png, .jpg, .jpeg">
                                                <label class="bg--success" for="profilePicUpload1">@lang('Upload Image')</label>
                                                <small class="mt-2">@lang('Supported files'): <b>@lang('jpeg'), @lang('jpg'), @lang('png').</b> @lang('Image will be resized into ') {{ getFileSize('reviewer') }} @lang('px') </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('First Name')</label>
                                            <input name="firstname" type="text"class="form-control" value="{{ old('firstname') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Last Name')</label>
                                            <input name="lastname" type="text"class="form-control" value="{{ old('lastname') }}" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Username')</label>
                                            <input name="username" type="text"class="form-control checkUser" value="{{ old('username') }}" required>
                                            <small class="text--danger usernameExist"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Email')</label>
                                            <input name="email" type="email"class="form-control checkUser" value="{{ old('email') }}" required>
                                            <small class="text--danger emailExist"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Password')</label>
                                            <div class="input-group">
                                                <input name="password" type="text"class="form-control" required>
                                                <button class="input-group-text pass-generate" type="button">@lang('Generate')</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Confirm Password')</label>
                                            <input class="form-control" name="password_confirmation" type="password" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Country') </label>
                                            <select class="form-control" name="country" required>
                                                @foreach ($countries as $key => $country)
                                                    <option data-mobile_code="{{ $country->dial_code }}" data-code="{{ $key }}" value="{{ $key }}">{{ __($country->country) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Mobile')</label>
                                            <div class="input-group">
                                                <span class="input-group-text mobile-code"></span>
                                                <input class="form-control form--control checkUser" name="mobile" type="number" value="{{ old('mobile') }}" required>
                                            </div>
                                            <small class="text--danger mobileExist"></small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Address')</label>
                                            <input name="address" type="text"class="form-control" value="{{ old('address') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('City') </label>
                                            <input class="form-control" name="city" type="text" value="{{ old('city') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('State') </label>
                                            <input class="form-control" name="state" type="text" value="{{ old('state') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('Zip/Postal') </label>
                                            <input class="form-control" name="zip" type="text" value="{{ old('zip') }}">
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn--primary w-100 h-45" type="submit">@lang('Submit')</button>
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
        (function() {
            "use strict";
            @if ($mobileCode)
                $(`option[data-code={{ $mobileCode }}]`).attr('selected', '');
            @endif

            countryCode();

            $('select[name=country]').change(function() {
                countryCode();
            });

            function countryCode() {
                $('.mobile-code').text('+' + $('select[name=country] :selected').data('mobile_code'));
            }

            $('.pass-generate').on('click', function() {
                var charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-+=,<.>;:";
                var password = '';
                for (var i = 0; i <= 8; i++) {
                    password += charset.charAt(Math.floor(Math.random() * charset.length));
                }
                $('[name=password]').val(password);
            });

            $('.checkUser').on('focusout', function(e) {
                var url = '{{ route('admin.reviewers.check') }}';
                var value = $(this).val();
                var token = '{{ csrf_token() }}';
                if ($(this).attr('name') == 'mobile') {
                    var mobile = `${$('.mobile-code').text().substr(1)}${value}`;
                    var data = {
                        mobile: mobile,
                        _token: token
                    }
                }
                if ($(this).attr('name') == 'email') {
                    var data = {
                        email: value,
                        _token: token
                    }
                }
                if ($(this).attr('name') == 'username') {
                    var data = {
                        username: value,
                        _token: token
                    }
                }
                $.post(url, data, function(response) {
                    if (response.data != false) {
                        $(`.${response.type}Exist`).text(`${response.type} already exist`);
                    }
                });
            });
        })(jQuery)
    </script>
@endpush
