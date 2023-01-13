@extends('admin.layouts.reviewer')
@section('panel')
    <form action="{{ route('reviewer.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-xl-3 col-lg-4 mb-30">
                <div class="card b-radius--5 overflow-hidden">
                    <div class="card-body p-0">
                        <div class="bg--white p-3">
                            <div class="form-group">
                                <div class="image-upload">
                                    <div class="thumb">
                                        <div class="avatar-preview">
                                            <div class="profilePicPreview" style="background-image: url({{ getImage(getFilePath('reviewer') . '/' . $reviewer->image, getFileSize('reviewer')) }})">
                                                <button class="remove-image" type="button"><i class="fa fa-times"></i></button>
                                            </div>
                                        </div>
                                        <div class="avatar-edit">
                                            <input class="profilePicUpload" id="profilePicUpload1" name="image" type="file" accept=".png, .jpg, .jpeg">
                                            <label class="bg--success" for="profilePicUpload1">@lang('Upload Profile Image')</label>
                                            <small class="text-facebook mt-2">@lang('Supported files'): <b>@lang('jpeg, jpg, png')</b>. @lang('Image will be resized into') {{ getFileSize('reviewer') }} @lang('px')</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-9 col-lg-8 mb-30">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title border-bottom pb-2">@lang('Profile Information')</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('First Name')</label>
                                    <input class="form-control" name="firstname" type="text" value="{{ $reviewer->firstname }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('Last Name') </label>
                                    <input class="form-control" name="lastname" type="text" value="{{ $reviewer->lastname }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('Email') </label>
                                    <input class="form-control" name="email" type="email" value="{{ $reviewer->email }}" readonly required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('Mobile Number') </label>
                                    <input class="form-control" name="mobile" type="text" value="{{ $reviewer->mobile }}" readonly required>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('Address') </label>
                                    <input class="form-control" name="address" type="text" value="{{ @$reviewer->address->address }}">
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('City') </label>
                                    <input class="form-control" name="city" type="text" value="{{ @$reviewer->address->city }}">
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('State') </label>
                                    <input class="form-control" name="state" type="text" value="{{ @$reviewer->address->state }}">
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('Zip/Postal') </label>
                                    <input class="form-control" name="zip" type="text" value="{{ @$reviewer->address->zip }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <button class="btn btn--primary w-100 h-45" type="submit">@lang('Save Changes')
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </form>
@endsection

@push('breadcrumb-plugins')
    <a class="btn btn-sm btn-outline--primary" href="{{ route('reviewer.password') }}"><i class="fa fa-key"></i>@lang('Password Setting')</a>
@endpush
