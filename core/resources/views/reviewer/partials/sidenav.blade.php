<div class="sidebar bg--dark">
    <button class="res-sidebar-close-btn"><i class="las la-times"></i></button>
    <div class="sidebar__inner">
        <div class="sidebar__logo">
            <a class="sidebar__main-logo" href="{{ route('reviewer.home') }}"><img src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="@lang('image')"></a>
        </div>

        <div class="sidebar__menu-wrapper" id="sidebar__menuWrapper">
            <ul class="sidebar__menu">
                <li class="sidebar-menu-item {{ menuActive('reviewer.home') }}">
                    <a class="nav-link" href="{{ route('reviewer.home') }}">
                        <i class="menu-icon las la-home"></i>
                        <span class="menu-title">@lang('Dashboard')</span>
                    </a>
                </li>
                <li class="sidebar-menu-item {{ menuActive('reviewer.product.pending') }}">
                    <a class="nav-link" href="{{ route('reviewer.product.pending') }}">
                        <i class="menu-icon las la-spinner"></i>
                        <span class="menu-title">@lang('Pending Products')</span>
                        @if ($pendingProductCount)
                            <span class="menu-badge pill bg--danger ms-auto">{{ $pendingProductCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="sidebar-menu-item {{ menuActive('reviewer.product.approved') }}">
                    <a class="nav-link" href="{{ route('reviewer.product.approved') }}">
                        <i class="menu-icon las la-check-circle"></i>
                        <span class="menu-title">@lang('Approved Products')</span>
                    </a>
                </li>
                <li class="sidebar-menu-item {{ menuActive('reviewer.product.soft.rejected') }}">
                    <a class="nav-link" href="{{ route('reviewer.product.soft.rejected') }}">
                        <i class="menu-icon las la-times-circle"></i>
                        <span class="menu-title">@lang('Soft Rejected')</span>
                        @if ($softProductCount)
                            <span class="menu-badge pill bg--danger ms-auto">{{ $softProductCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="sidebar-menu-item {{ menuActive('reviewer.product.hard.rejected') }}">
                    <a class="nav-link" href="{{ route('reviewer.product.hard.rejected') }}">
                        <i class="menu-icon las la-ban"></i>
                        <span class="menu-title">@lang('Hard Rejected')</span>
                        @if ($hardProductCount)
                            <span class="menu-badge pill bg--danger ms-auto">{{ $hardProductCount }}</span>
                        @endif
                    </a>
                </li>

                <li class="sidebar-menu-item {{ menuActive('reviewer.product.resubmitted') }}">
                    <a class="nav-link" href="{{ route('reviewer.product.resubmitted') }}">
                        <i class="menu-icon las la-undo-alt"></i>
                        <span class="menu-title">@lang('Resubmitted Product')</span>
                        @if ($resubmitProductCount)
                            <span class="menu-badge pill bg--danger ms-auto">{{ $resubmitProductCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="sidebar-menu-item {{ menuActive('reviewer.product.reviewed.by.me') }}">
                    <a class="nav-link" href="{{ route('reviewer.product.reviewed.by.me') }}">
                        <i class="menu-icon las la-star"></i>
                        <span class="menu-title">@lang('Reviewed By Me')</span>
                    </a>
                </li>
                <li class="sidebar-menu-item {{ menuActive('reviewer.update.product.pending') }}">
                    <a class="nav-link" href="{{ route('reviewer.update.product.pending') }}">
                        <i class="menu-icon las la-pen-square"></i>
                        <span class="menu-title">@lang('Update Product')</span>
                        @if ($updatePendingProductCount)
                            <span class="menu-badge pill bg--danger ms-auto">{{ $updatePendingProductCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="sidebar-menu-item {{ menuActive('reviewer.twofactor*') }}">
                    <a class="nav-link" href="{{ route('reviewer.twofactor') }}">
                        <i class="menu-icon las la-shield-alt"></i>
                        <span class="menu-title">@lang('2FA Security')</span>
                    </a>
                </li>
            </ul>
            <div class="text-uppercase mb-3 text-center">
                <span class="text--primary">{{ __(systemDetails()['name']) }}</span>
                <span class="text--success">@lang('V'){{ systemDetails()['version'] }} </span>
            </div>
        </div>
    </div>
</div>
<!-- sidebar end -->

@push('script')
    <script>
        if ($('li').hasClass('active')) {
            $('#sidebar__menuWrapper').animate({
                scrollTop: eval($(".active").offset().top - 320)
            }, 500);
        }
    </script>
@endpush
