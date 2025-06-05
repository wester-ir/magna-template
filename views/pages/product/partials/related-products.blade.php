@php
    $relatedProducts = \App\Core\Client\ProductCore::paginateRelatedProducts($product);
@endphp

@if ($relatedProducts->isNotEmpty())
    <div class="swiper border px-5 py-4 rounded-lg mt-5" id="related-products">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-lg">محصولات مرتبط</h3>
        </div>

        <div class="swiper-wrapper">
            @foreach ($relatedProducts as $product)
                <div class="swiper-slide w-[200px] h-auto">
                    <x-tpl::product-grid-items.basic :$product imageClass="aspect-square" />
                </div>
            @endforeach
        </div>
    </div>

    @push('bottom-scripts')
        <script>
            var swiper = new Swiper("#related-products", {
                slidesPerView: "auto",
                spaceBetween: 13,
                freeMode: true,
            });
        </script>
    @endpush
@endif
