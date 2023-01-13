<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@lang('Invoice')</title>
    <!-- favicon -->
    <link type="image/png" href="{{ getImage(getFilePath('logoIcon') . '/favicon.png') }}" rel="icon" sizes="16x16" />
</head>
<style>
    @page {
        size: 8.27in 11.7in;
        margin: .5in;
    }

    body {
        font-family: "Arial", sans-serif;
        font-size: 14px;
        line-height: 1.5;
        color: #023047;
    }

    /* Typography */
    .strong {
        font-weight: 700;
    }

    .fw-md {
        font-weight: 500;
    }

    .primary-text {
        color: #219ebc;
    }

    h1,
    .h1 {
        font-family: "Arial", sans-serif;
        margin-top: 8px;
        margin-bottom: 8px;
        font-size: 67px;
        line-height: 1.2;
        font-weight: 500;
    }

    h2,
    .h2 {
        font-family: "Arial", sans-serif;
        margin-top: 8px;
        margin-bottom: 8px;
        font-size: 50px;
        line-height: 1.2;
        font-weight: 500;
    }

    h3,
    .h3 {
        font-family: "Arial", sans-serif;
        margin-top: 8px;
        margin-bottom: 8px;
        font-size: 38px;
        line-height: 1.2;
        font-weight: 500;
    }

    h4,
    .h4 {
        font-family: "Arial", sans-serif;
        margin-top: 8px;
        margin-bottom: 8px;
        font-size: 28px;
        line-height: 1.2;
        font-weight: 500;
    }

    h5,
    .h5 {
        font-family: "Arial", sans-serif;
        margin-top: 8px;
        margin-bottom: 8px;
        font-size: 20px;
        line-height: 1.2;
        font-weight: 500;
    }

    h6,
    .h6 {
        font-family: "Arial", sans-serif;
        margin-top: 8px;
        margin-bottom: 8px;
        font-size: 16px;
        line-height: 1.2;
        font-weight: 500;
    }

    .text-uppercase {
        text-transform: uppercase;
    }

    .text-end {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    /* List Style */
    ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    /* Utilities */
    .d-block {
        display: block;
    }

    .mt-0 {
        margin-top: 0;
    }

    .m-0 {
        margin: 0;
    }

    .mt-3 {
        margin-top: 16px;
    }

    .mt-4 {
        margin-top: 24px;
    }

    .mb-3 {
        margin-bottom: 16px;
    }

    /* Title */
    .title {
        display: inline-block;
        letter-spacing: 0.05em;
    }

    /* Table Style */
    table {
        width: 7.27in;
        caption-side: bottom;
        border-collapse: collapse;
        border: 1px solid #ffffff;
        color: #000000;
        vertical-align: top;
    }

    table td {
        padding: 5px 15px;
    }

    table th {
        padding: 5px 15px;
    }

    table,
    td,
    th {
        border: 1px solid #ddd;
    }

    table th:last-child {
        text-align: right !important;
    }

    .table> :not(caption)>*>* {
        padding: 12px 24px;
        background-color: #ffffff;
        border-bottom-width: 1px;
        box-shadow: inset 0 0 0 9999px #ffffff;
    }

    .table>tbody {
        vertical-align: inherit;
        border: 1px solid #eafbff;
    }

    .table>thead {
        vertical-align: bottom;
        background: #219ebc;
        color: #000;
    }

    .table>thead th {
        font-family: "Arial", sans-serif;
        text-align: left;
        font-size: 16px;
        letter-spacing: 0.03em;
        font-weight: 500;
    }

    .table td:last-child {
        text-align: right;
    }

    .table th:last-child {
        text-align: right;
    }

    .table> :not(:first-child) {
        border-top: 0;
    }

    .table-sm> :not(caption)>*>* {
        padding: 5px;
    }

    .table-bordered> :not(caption)>* {
        border-width: 1px 0;
    }

    .table-bordered> :not(caption)>*>* {
        border-width: 0 1px;
    }

    .table-borderless> :not(caption)>*>* {
        border-bottom-width: 0;
    }

    .table-borderless> :not(:first-child) {
        border-top-width: 0;
    }

    .table-striped>tbody>tr:nth-of-type(even)>* {
        background: #eafbff;
    }

    /* Logo */
    .logo {
        display: flex;
        align-items: center;
        width: 100%;
        max-width: 200px;
        height: 50px;
        font-size: 24px;
        text-transform: capitalize;
    }

    .logo-img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .info {
        justify-content: space-between;
        padding-top: 15px;
        padding-bottom: 15px;
        border-top: 1px solid #02304726;
        border-bottom: 1px solid #02304726;
    }

    .address {
        padding-top: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #02304726;
    }

    header {
        padding-top: 15px;
        padding-bottom: 15px;
    }

    .body {
        padding-top: 30px;
        padding-bottom: 30px;
    }

    footer {
        padding-bottom: 15px;
    }

    .badge {
        display: inline-block;
        padding: 2px 15px;
        font-size: 10px;
        line-height: 1;
        border-radius: 15px;
    }

    .badge--success {
        color: white;
        background: #02c39a;
    }

    .badge--warning {
        color: white;
        background: #ffb703;
    }

    .align-items-center {
        align-items: center;
    }

    .footer-link {
        text-decoration: none;
        color: #219ebc;
    }

    .footer-link:hover {
        text-decoration: none;
        color: #219ebc;
    }

    .list--row {
        overflow: auto
    }

    .list--row::after {
        content: '';
        display: block;
        clear: both;
    }

    .float-left {
        float: left;
    }

    .float-right {
        float: right;
    }

    .d-block {
        display: block;
    }

    .d-inline-block {
        display: inline-block;
    }
</style>

<body onload="window.print()">
    <header>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="list--row">
                        <div class="logo float-left">
                            <img class="logo-img" src="{{ getImage(getFilePath('logoIcon') . '/logo.png') }}" alt="image" />
                        </div>
                        <h4 class="float-right m-0">@lang('Invoice')</h4>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="info list--row">
                        <div class="info-left float-left">
                            <div class="list list--row">
                                <span class="strong">@lang('Invoice Date'):</span>
                                <span> {{ showDateTime($sell->created_at, 'Y-m-d') }} </span>
                            </div>
                        </div>
                        <div class="info-right float-right">
                            <div class="list list--row text-right">
                                <span class="strong">@lang('Purchase Code'):</span>
                                <span> {{ __($sell->code) }} </span>
                            </div>
                        </div>
                    </div>
                    <div class="address list--row">
                        <div class="address-to float-left">
                            <h5 class="text-uppercase">@lang('Invoice To')</h5>
                            <ul class="list" style="--gap: 0.3rem">
                                <li>
                                    <div class="list list--row" style="--gap: 0.5rem">
                                        <span class="strong">@lang('Username'):</span>
                                        <span>{{ __($sell->user->fullname) }}</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="list list--row" style="--gap: 0.5rem">
                                        <span class="strong">@lang('Email') :</span>
                                        <span>{{ __($sell->user->email) }}</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="list list--row" style="--gap: 0.5rem">
                                        <span class="strong">@lang('Category') :</span>
                                        <span>{{ __(@$sell->product->category->name) }}</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="list list--row" style="--gap: 0.5rem">
                                        <span class="strong">@lang('Licence') :</span>
                                        <span>
                                            @if ($sell->license == Status::REGULAR_LICENSE)
                                                @lang('Regular')
                                            @elseif($sell->license == Status::EXTENDED_LICENSE)
                                                @lang('Extended')
                                            @endif
                                        </span>
                                    </div>
                                </li>
                                <li>
                                    <div class="list list--row" style="--gap: 0.5rem">
                                        <span class="strong">@lang('Support Time') :</span>
                                        <span>
                                            @if ($sell->support_time)
                                                {{ $sell->support_time }}
                                            @else
                                                @lang('No support')
                                            @endif
                                        </span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="address-form float-right">
                            <ul class="text-end">
                                <li>
                                    <h5 class="text-uppercase">@lang('Author')</h5>
                                </li>
                                <li>
                                    <span class="d-inline-block strong">@lang('Name')</span>
                                    <span class="d-inline-block">{{ __(@$sell->author->username) }}</span>
                                </li>
                                <li>
                                    <span class="d-inline-block strong">@lang('Email')</span>
                                    <span class="d-inline-block">{{ __(@$sell->author->email) }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="body">
                        <div class="mt-4 mb-3 text-center">
                            <div class="title-inset">
                                <h6 class="title text-uppercase m-0">@lang('Purchased Details')</h6>
                            </div>
                        </div>
                        <table class="table-striped table">
                            <thead>
                                <tr>
                                    <th>@lang('Product')</th>
                                    <th>@lang('Price')</th>
                                    <th>@lang('Support Charge')</th>
                                    <th>@lang('Subtotal')</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <span>{{ __(@$sell->product->name) }}</span>
                                    </td>
                                    <td>
                                        <span>{{ $general->cur_sym }}{{ showAmount($sell->product_price) }}</span>
                                    </td>

                                    <td>
                                        <span>{{ $general->cur_sym }}{{ showAmount($sell->support_fee) }}</span>
                                    </td>

                                    <td>
                                        <span>{{ $general->cur_sym }}{{ showAmount($sell->total_price) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-end" colspan="3">@lang('Total Amount')</td>
                                    <td><span>{{ $general->cur_sym }}{{ showAmount($sell->total_price) }}</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <span class="d-block text-center">
                        @lang('Copyright') &copy; @php date('Y') @endphp @lang('All Right Reserved By '){{ $general->site_name }}
                    </span>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>
