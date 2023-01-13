@php
    $content = getContent('breadcrumb.content', true);
@endphp

<section class="inner-page-hero bg_img" style="background-image: url({{ getImage('assets/images/frontend/breadcrumb/' . @$content->data_values->image, '1920x200') }});">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <h2 class="page-title">{{ __($pageTitle) }}</h2>
            </div>
        </div>
    </div>
</section>
