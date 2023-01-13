@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="pb-100">
        <div class="user-area py-4">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8">
                        <div class="user-wrapper">
                            <div class="thumb">
                                <img src="{{ getImage(getFilePath('userProfile') . '/' . $user->image, getFileSize('userProfile')) }}" alt="@lang('image')">
                            </div>
                            <div class="content">
                                <h4 class="name">{{ $user->username }}</h4>
                                <p class="fs-14px">@lang('Member since') {{ showDateTime($user->created_at, 'F, Y') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4 text-end">
                        <div class="user-header-status">
                            <div class="left">
                                <span>@lang('Author Rating')</span>
                                <div class="ratings">
                                    @php echo displayRating($user->avg_rating) @endphp
                                    ({{ $user->total_response }} @lang('Ratings'))
                                </div>
                            </div>
                            <div class="right">
                                <span>@lang('Sales')</span>
                                <h4>{{ $totalSell }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="pt-50 container">
            <div class="row gy-5">
                <div class="col-lg-8">
                    <div class="portfolio-single">
                        <div class="portforlio-single-thumb">
                            <img src="{{ getImage(getFilePath('userCoverImage') . '/' . $user->cover_image, getFileSize('userCoverImage')) }}" alt="@lang('image')">
                        </div>
                        @if ($user->description)
                            <div class="portforlio-single-content mt-4">
                                <h6 class="mb-3">@lang('About Seller')</h6>
                                @php echo $user->description; @endphp
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="product-widget">
                        @if ($user->level)
                            <div class="author-widget">
                                <div class="thumb">
                                    <img src="{{ getImage(getFilePath('level') . '/' . $user->level->image, getFileSize('level')) }}" alt="@lang('image')">
                                </div>
                                <div class="content">
                                    <h5 class="author-name">{{ $user->level->name }}</h5>
                                    <span class="txt">
                                        <a class="text--base" href="{{ route('author.products', $user->username) }}">@lang('Total Products') : {{ $totalProduct }}</a>
                                    </span>
                                </div>
                            </div>
                        @endif
                        <ul class="author-badge-list w-100 border-top mt-3 pt-3">
                            @foreach ($levels as $key => $level)
                                <li>
                                    <img data-bs-toggle="tooltip" data-placement="top" src="{{ getImage(getFilePath('level') . '/' . $level->image, getFileSize('level')) }}" title="{{ $level->name }}" alt="@lang('image')">
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="product-widget mt-4">
                        <h5 class="title border-bottom mb-3 pb-3">@lang('Email to') {{ $user->username }}</h5>
                        @auth
                            @if ($user->id != auth()->user()->id)
                                <form action="{{ route('user.email.author') }}" method="POST">
                                    @csrf
                                    <input name="author" type="hidden" value="{{ $user->username }}">
                                    <div class="form-group mb-3">
                                        <input class="form-control form--control" type="text" value="{{ auth()->user()->email }}" disabled>
                                    </div>
                                    <div class="form-group mb-3">
                                        <textarea class="form-control form--control border" name="message" placeholder="@lang('Your Message')" required>{{ old('message') }}</textarea>
                                    </div>
                                    <button class="btn btn--base w-100" type="submit">@lang('Send Email')</button>
                                </form>
                            @else
                                @lang('This is your own profile')
                            @endif
                        @else
                            @lang('Please') <a class="text--base" href="{{ route('user.login') }}">@lang('sign in')</a> @lang('to contact this author').
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
