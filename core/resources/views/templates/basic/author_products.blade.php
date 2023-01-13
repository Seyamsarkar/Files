@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="pt-120 pb-120">
        <div class="container">
            <div class="row gy-4 justify-content-center">
                @include($activeTemplate . 'product.card.list', ['products' => $products])
            </div>
        </div>
    </section>
@endsection
