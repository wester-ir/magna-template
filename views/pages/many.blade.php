@template_extends('views.layouts.default')

@title([$pageTitle ?? $title, 'صفحه '. $products->currentPage()], false)
@description($metaDescription ?? $description ?? null)

@section('content')
    <div class="main mt-3">
        <div class="container">
            <h1 class="text-2xl font-bold">{{ $title }}</h1>

            @if ($products->isNotEmpty())
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 mt-5">
                    @foreach ($products as $product)
                        <x-tpl::product-grid-items.basic :$product imageClass="aspect-[372/446]" :discountCountdown="$countdown ?? false" />
                    @endforeach
                </div>
            @else
                <div class="text-center py-10">محصولی برای نمایش وجود ندارد!</div>
            @endif

            {{ $products->links() }}
        </div>

        @if (isset($description))
            <div class="container mt-5">
                <div class="bg-neutral-100 text-sm leading-7 px-5 py-4 rounded-lg">
                    {!! $description !!}
                </div>
            </div>
        @endif
    </div>
@endsection
