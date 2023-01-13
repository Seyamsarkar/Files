@extends('admin.layouts.app')

@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12 col-md-12 mb-30">
            <div class="card">
                <div class="card-body">
                    <div class="alert-info mb-3 mb-3 rounded p-3 p-3" role="alert">
                        <h5 class="alert-heading">@lang('Important Note')!</h5>
                        <p>@lang("Please Remember, Be very carefull about changing storage or changing FTP host, Because if you change setting, make sure you copy all image and file directory of uploaded photos to your new FTP or LOCAL storage. Otherwise files can't be download from the site.")</p>
                    </div>
                    @php
                        $ftp = @$setting->ftp;
                    @endphp
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('FTP Hosting root path') <small class="text--primary">( @lang('Please Enter With http/https protocol') )</small></label>
                                    <input class="form-control" name="ftp[domain]" type="text" value="{{ @$ftp->domain }}" required>
                                    <code>@lang('https://yourdomain.com')</code>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Host')</label>
                                    <input class="form-control" name="ftp[host]" type="text" value="{{ @$ftp->host }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Username')</label>
                                    <input class="form-control" name="ftp[username]" type="text" value="{{ @$ftp->username }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Password')</label>
                                    <input class="form-control" name="ftp[password]" type="text" value="{{ @$ftp->password }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Port')</label>
                                    <input class="form-control" name="ftp[port]" type="text" value="{{ @$ftp->port }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Root Folder')</label>
                                    <input class="form-control" name="ftp[root]" type="text" value="{{ @$ftp->root }}" required>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn--primary h-45 w-100" type="submit">@lang('Update')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
