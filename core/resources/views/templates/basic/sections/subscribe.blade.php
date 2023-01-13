@php
    $content = getContent('subscribe.content', true);
@endphp
<section class="subscribe-section">
    <div class="el"><img src="{{ getImage($activeTemplateTrue . 'images/bg-el.png') }}" alt="image"></div>
    <div class="container">
        <div class="row align-items-center justify-content-between gy-4">
            <div class="col-lg-5 text-lg-start text-center">
                <h3 class="text-white">{{ __(@$content->data_values->heading) }}</h3>
            </div>
            <div class="col-lg-6">
                <form class="subscribe-form">
                    <div class="custom-icon-field">
                        <i class="las la-envelope"></i>
                        <input class="form--control" name="email" type="email" placeholder="@lang('Email Address')" required>
                    </div>
                    <button class="subs" type="button">@lang('Subscribe') <i class="lab la-telegram-plane"></i></button>
                </form>
            </div>
        </div>
    </div>
</section>

@push('script')
    <script>
        'use strict';
        (function($) {
            $('.subs').on('click', function(e) {
                e.preventDefault()
                var email = $('[name=email]').val();
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                    url: "{{ route('subscribe.post') }}",
                    method: "POST",
                    data: {
                        email: email
                    },
                    success: function(response) {
                        if (response.success) {
                            $('[name=email]').val('')
                            notify('success', response.success);
                        } else {
                            notify('error', response.error);
                        }
                    }
                });
            });
        })(jQuery);
    </script>
@endpush
