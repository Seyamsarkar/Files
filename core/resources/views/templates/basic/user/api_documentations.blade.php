@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="table-responsive--md">
        <table class="custom--table mb-0 table">
            <thead>
                <tr>
                    <th>@lang('Item')</th>
                    <th>@lang('Data')</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="fw-bold text--dark">@lang('API URL')</td>
                    <td class="text-start">{{ route('api.verify.purchase.code') }}</td>
                </tr>
                <tr>
                    <td class="fw-bold text--dark">@lang('Accept')</td>
                    <td class="text-start">@lang('application/json')</td>
                </tr>
                <tr>
                    <td class="fw-bold text--dark">@lang('HTTP Method')</td>
                    <td class="text-start">@lang('POST')</td>
                </tr>
                <tr>
                    <td class="fw-bold text--dark">@lang('APP SECRET')</td>
                    <td class="text-start">@lang('Your app secret can be found under api option from user dashboard')</td>
                </tr>
                <tr>
                    <td class="fw-bold text--dark">@lang('APP KEY')</td>
                    <td class="text-start">@lang('Your app key can be found under api option from user dashboard')</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="mt-4 border p-1">
        <h6>@lang('Request Body')</h6>
        <span><strong>api_key</strong>: @lang('Your Api Key') </span> <br>
        <span><strong>app_secret</strong>: @lang('Your App Secret') </span> <br>
        <span><strong>purchase_code</strong>: @lang('The purchase code you want to verify') </span> <br>
    </div>
    <div class="accordion custom--accordion apiDocumentation mt-4" id="faqAccordion">
        <div class="row gy-3">
            <div class="col-lg-12">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="success">
                        <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#c-success" type="button" aria-expanded="false" aria-controls="c-success">
                            @lang('Success Response')
                        </button>
                    </h2>
                    <div class="accordion-collapse collapse" id="c-success" data-bs-parent="#faqAccordion" aria-labelledby="success">
                        <div class="accordion-body">
                            <pre>
{
    "status": "success",
    "message": "Purchase information found",
    "info": {
        "name": "Coupon Lab - Coupon Listing Platform",
        "code": "YKXDJGUZ98RA",
        "license": "Extended",
        "has_support": "Yes",
        "sold_at": "2022-12-11T10:51:14.000000Z",
        "support_time": "2023-05-15",
        "buyer": {
            "name": "John Doe",
            "email ": "user@site.com",
            "mobile ": "+0123456789"
        }
    }
}
                            </pre>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="error-one">
                        <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#c-error-one" type="button" aria-expanded="false" aria-controls="c-error-one">
                            @lang('Error Response - 1')
                        </button>
                    </h2>
                    <div class="accordion-collapse collapse" id="c-error-one" data-bs-parent="#faqAccordion" aria-labelledby="error-one">
                        <div class="accordion-body">
                            <pre>
{
    "status": "error",
    "message": "Invalid purchased code.",
    "info": []
}
                            </pre>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="error-two">
                        <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#c-error-two" type="button" aria-expanded="false" aria-controls="c-error-two">
                            @lang('Error Response - 2')
                        </button>
                    </h2>
                    <div class="accordion-collapse collapse" id="c-error-two" data-bs-parent="#faqAccordion" aria-labelledby="error-two">
                        <div class="accordion-body">
                            <pre>
{
    "status": "error",
    "message": "Invalid api key or app secret",
    "info": []
}
                            </pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
