@extends($activeTemplate . 'layouts.app')
@section('app')
    @include($activeTemplate . 'partials.header')

    @include($activeTemplate . 'partials.dashboard_navbar')
    <div class="dashboard-area pt-100 pb-100">
        <div class="container">
            @include($activeTemplate . 'partials.dashboard_breadcrumb')
            <div class="dashboard-content-wrapper">
                @yield('content')
            </div>
        </div>
    </div>

    @include($activeTemplate . 'partials.footer')
@endsection
