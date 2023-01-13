@extends('admin.layouts.master')
@section('content')
    <div class="page-wrapper default-version">
        @include('reviewer.partials.sidenav')
        @include('reviewer.partials.topnav')

        <div class="body-wrapper">
            <div class="bodywrapper__inner">
                @include('admin.partials.breadcrumb')
                @yield('panel')
            </div>
        </div>
    </div>
@endsection
