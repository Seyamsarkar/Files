@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card custom--card">
                <div class="card-body">
                    <div class="user-profile-area">
                        <form action="" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label>@lang('Profile Cover Image')</label>
                                <div class="user-profile-header p-0">
                                    <div class="profile-thumb product-profile-thumb">

                                        <div class="avatar-preview">
                                            <div class="profilePicPreview productPicPreview" style="background-image: url({{ getImage(getFilePath('userCoverImage') . '/' . $user->cover_image, getFileSize('userCoverImage')) }})"></div>
                                        </div>
                                        <div class="avatar-edit">
                                            <input class="profilePicUpload" id="profilePicUpload2" name="cover_image" type='file' accept=".png, .jpg, .jpeg">
                                            <label for="profilePicUpload2"><i class="la la-pencil text-white"></i></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>@lang('Profile Info')</label>
                                <div class="user-profile-header">
                                    <div class="profile-thumb">
                                        <div class="avatar-preview">
                                            <div class="profilePicPreview" style="background-image: url({{ getImage(getFilePath('userProfile') . '/' . $user->image, getFileSize('userProfile')) }})"></div>
                                        </div>
                                        <div class="avatar-edit">
                                            <input class="profilePicUpload" id="profilePicUpload1" name="image" type='file' accept=".png, .jpg, .jpeg" />
                                            <label for="profilePicUpload1"><i class="la la-pencil text-white"></i></label>
                                        </div>
                                    </div>
                                    <div class="content mt-2">
                                        <ul class="caption-list px-sm-3">
                                            <li class="px-sm-0 px-2">
                                                <span class="caption">@lang('Full Name')</span>
                                                <span class="value">{{ $user->fullname }}</span>
                                            </li>
                                            <li class="px-sm-0 px-2">
                                                <span class="caption">@lang('Username')</span>
                                                <span class="value">{{ $user->username }}</span>
                                            </li>
                                            <li class="px-sm-0 px-2">
                                                <span class="caption">@lang('E-mail')</span>
                                                <span class="value">{{ $user->email }}</span>
                                            </li>
                                            <li class="px-sm-0 px-2">
                                                <span class="caption">@lang('Phone')</span>
                                                <span class="value">{{ $user->mobile }}</span>
                                            </li>
                                            <li class="px-sm-0 px-2">
                                                <span class="caption">@lang('Country')</span>
                                                <span class="value">{{ @$user->address->country }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 form-group">
                                    <label>@lang('First Name')</label>
                                    <input class="form--control" name="firstname" type="text" value="{{ $user->firstname }}" required>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label>@lang('Last Name')</label>
                                    <input class="form--control" name="lastname" type="text" value="{{ $user->lastname }}" required>
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label>@lang('Address')</label>
                                    <input class="form--control" name="address" type="text" value="{{ @$user->address->address }}">
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label>@lang('State')</label>
                                    <input class="form--control" name="state" type="text" value="{{ @$user->address->state }}">
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label>@lang('Zip Code')</label>
                                    <input class="form--control" name="zip" type="text" value="{{ @$user->address->zip }}">
                                </div>
                                <div class="col-lg-6 form-group">
                                    <label>@lang('City')</label>
                                    <input class="form--control" name="city" type="text" value="{{ @$user->address->city }}">
                                </div>
                                <div class="col-lg-12 form-group">
                                    <label>@lang('Description') <code>(@lang('HTML or plain text allowed'))</code></label>
                                    <textarea class="form-control nicEdit" name="description" rows="15" placeholder="@lang('Enter your message')">@php echo $user->description @endphp</textarea>
                                </div>
                                <div class="col-lg-12">
                                    <button class="btn btn--base w-100" type="submit">@lang('Submit')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-lib')
    <script src="{{ asset('assets/global/js/nicEdit.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        bkLib.onDomLoaded(function() {
            $(".nicEdit").each(function(index) {
                $(this).attr("id", "nicEditor" + index);
                new nicEditor({
                    fullPanel: true
                }).panelInstance('nicEditor' + index, {
                    hasPanel: true
                });
            });
        });

        (function($) {
            function proPicURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var preview = $(input).parents('.profile-thumb').find('.profilePicPreview');
                        $(preview).css('background-image', 'url(' + e.target.result + ')');
                        $(preview).addClass('has-image');
                        $(preview).hide();
                        $(preview).fadeIn(650);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            $(".profilePicUpload").on('change', function() {
                proPicURL(this);
            });
        })(jQuery)
    </script>
@endpush
