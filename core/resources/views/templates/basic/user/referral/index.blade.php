@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex justify-content-start table--form flex-wrap">
                    @if ($user->referral)
                        <h6 class="mb-2">@lang('You are referred by') {{ @$user->referral->fullname }}</h6>
                    @endif
                    <div class="input-group">
                        <input class="form-control form--control referralURL" type="text" value="{{ route('home') }}?reference={{ $user->username }}" readonly>
                        <button class="input-group-text bg--base" id="copyBoard"><i class="fas fa-copy"></i></button>
                    </div>
                </div>
            </div>
            @if ($user->allReferrals->count() > 0 && $maxLevel > 0)
                <div class="row justify-content-center mt-4">
                    <div class="col-md-12">
                        <div class="card custom--card">
                            <div class="card-body">
                                <div class="treeview-container">
                                    <ul class="treeview">
                                        <li class="items-expanded"> {{ $user->fullname }} ( {{ $user->username }} )
                                            @include($activeTemplate . 'partials.under_tree', ['user' => $user, 'layer' => 0, 'isFirst' => true])
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a class="btn btn-sm btn-outline--base" href="{{ route('user.referral.commissions.logs') }}"><i class="las la-list"> </i>@lang('Commission Logs')</a>
@endpush

@push('style-lib')
    <link type="text/css" href="{{ asset($activeTemplateTrue . 'css/jquery.treeView.css') }}" rel="stylesheet">
@endpush

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'js/jquery.treeView.js') }}"></script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.treeview').treeView();

            $('#copyBoard').click(function() {
                var copyText = document.getElementsByClassName("referralURL");
                copyText = copyText[0];
                copyText.select();
                copyText.setSelectionRange(0, 99999);
                /*For mobile devices*/
                document.execCommand("copy");
                copyText.blur();
                this.classList.add('copied');
                setTimeout(() => this.classList.remove('copied'), 1500);
            });
        })(jQuery);
    </script>
@endpush
