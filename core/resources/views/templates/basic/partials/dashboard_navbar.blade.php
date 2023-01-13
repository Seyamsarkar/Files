<div class="user-area">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-sm-7">
                <div class="user-wrapper">
                    <div class="thumb">
                        <img src="{{ getImage(getFilePath('userProfile') . '/' . auth()->user()->image, getFileSize('userProfile')) }}" alt="@lang('image')">
                    </div>
                    <div class="content">
                        <h4 class="name">{{ auth()->user()->fullname }}</h4>
                        <p class="fs-14px">@lang('Member since') {{ showDateTime(auth()->user()->created_at, 'F, Y') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-5 text-end">
                <div class="user-header-status">
                    <div class="left">
                        <span>@lang('Author Rating')</span>
                        <div class="ratings">
                            @php echo displayRating(auth()->user()->avg_rating) @endphp
                            ({{ auth()->user()->total_response }} @lang('Ratings'))
                        </div>
                    </div>
                    <div class="right">
                        <span>@lang('Purchased')</span>
                        <h4>{{ auth()->user()->buy()->where('status', 1)->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-12">
                <ul class="nav nav-tabs user-nav-tabs">

                    <li class="nav-item">
                        <a class="nav-link {{ menuActive('user.home') }}" href="{{ route('user.home') }}">@lang('Dashboard')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ menuActive('user.deposit*') }}" href="{{ route('user.deposit.history') }}">@lang('Deposit')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ menuActive('user.withdraw*') }}" href="{{ route('user.withdraw.history') }}">@lang('Withdraw')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ menuActive('user.product*') }}" href="{{ route('user.product.index') }}">@lang('My Products')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ menuActive('user.hidden.product') }}" href="{{ route('user.hidden.product') }}">@lang('Hidden Products')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ menuActive('user.sell.history') }}" href="{{ route('user.sell.history') }}">@lang('Sell History')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ menuActive('user.purchased.history') }}" href="{{ route('user.purchased.history') }}">@lang('Purchased History')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ menuActive('user.transactions') }}" href="{{ route('user.transactions') }}">@lang('Transactions')</a>
                    </li>

                    @if ($general->rb)
                        <li class="nav-item">
                            <a class="nav-link {{ menuActive('user.referral*') }}" href="{{ route('user.referral') }}">@lang('Referral')</a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link {{ menuActive('ticket*') }}" href="{{ route('ticket.index') }}">@lang('Support')</a>
                    </li>
                    @if (gs()->api)
                        <li class="nav-item">
                            <a class="nav-link {{ menuActive('user.api*') }}" href="{{ route('user.api.index') }}">@lang('Api')</a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
