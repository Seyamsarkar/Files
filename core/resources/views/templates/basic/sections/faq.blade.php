@php
    $content = getContent('faq.content', true);
    $faqs = getContent('faq.element', false, null, true);
@endphp
<section class="pt-100 pb-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section-header text-center">
                    <h2 class="section-title">{{ __(@$content->data_values->heading) }}</h2>
                    <p class="mt-3">{{ __(@$content->data_values->subheading) }}</p>
                </div>
            </div>
        </div>
        <div class="accordion custom--accordion" id="faqAccordion">
            <div class="row">
                <div class="col-lg-6">
                    @foreach ($faqs as $faq)
                        @if ($loop->odd)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="h-{{ $loop->index }}">
                                    <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#c-{{ $loop->index }}" type="button" aria-expanded="false" aria-controls="c-{{ $loop->index }}">
                                        {{ __(@$faq->data_values->question) }}
                                    </button>
                                </h2>
                                <div class="accordion-collapse collapse" id="c-{{ $loop->index }}" data-bs-parent="#faqAccordion" aria-labelledby="h-{{ $loop->index }}">
                                    <div class="accordion-body">
                                        <p>@php echo @$faq->data_values->answer @endphp</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="col-lg-6 mt-lg-0 mt-4">
                    @foreach ($faqs as $faq)
                        @if ($loop->even)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="h-{{ $loop->index }}">
                                    <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#c-{{ $loop->index }}" type="button" aria-expanded="false" aria-controls="c-{{ $loop->index }}">
                                        {{ __(@$faq->data_values->question) }}
                                    </button>
                                </h2>
                                <div class="accordion-collapse collapse" id="c-{{ $loop->index }}" data-bs-parent="#faqAccordion" aria-labelledby="h-{{ $loop->index }}">
                                    <div class="accordion-body">
                                        <p>@php echo @$faq->data_values->answer @endphp</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
