@extends($activeTemplate . 'layouts.master')
@section('content')
    @php
        $kycContent = getContent('user_kyc.content', true);
    @endphp
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card custom--card">
                <div class="card-body">
                    @if (auth()->user()->kv == Status::KYC_PENDING)
                        <div class="alert alert-warning mb-3" role="alert">
                            <p class="mb-0"> {{ __($kycContent->data_values->pending_content) }}</p>
                        </div>
                    @endif
                    @if ($user->kyc_data)
                        <ul class="list-group list-group-flush">
                            @foreach ($user->kyc_data as $val)
                                @continue(!$val->value)
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    {{ __($val->name) }}
                                    <span>
                                        @if ($val->type == 'checkbox')
                                            {{ implode(',', $val->value) }}
                                        @elseif($val->type == 'file')
                                            <a class="text--base" href="{{ route('user.attachment.download', encrypt(getFilePath('verify') . '/' . $val->value)) }}"><i class="fa fa-file"></i> @lang('Attachment') </a>
                                        @else
                                            <p>{{ __($val->value) }}</p>
                                        @endif
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <h5 class="text-center">@lang('KYC data not found')</h5>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
