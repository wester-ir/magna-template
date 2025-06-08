@template_extends('views.layouts.default')
@use('App\Core\Client\ProductCore')

@php
    $discountedProducts = ProductCore::paginateDiscountedProducts();
    $newestProducts = ProductCore::paginateNewestProducts();
    $bestSellingProducts = ProductCore::paginateBestSellingProducts();
@endphp

@section('content')
    <div class="main">
        <!-- Banner -->
         @template_include('views.pages._partials.index.banner')

        <div class="container">
            <div class="space-y-10 mt-8">
                @if ($discountedProducts->isNotEmpty())
                    <!-- Discounted Products -->
                    <section class="swiper" id="discounted-products">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold">محصولات تخفیف‌دار</h2>
                            <a href="{{ route('client.discounted', ['page' => get_next_page_number($discountedProducts)]) }}" class="default flex items-center text-[13px] font-semibold">
                                <span>مشاهده همه</span>
                                <i class="fi fi-rr-angle-small-left mr-1"></i>
                            </a>
                        </div>

                        <div class="swiper-wrapper">
                            @foreach ($discountedProducts as $product)
                                <div class="swiper-slide w-[222px] h-auto">
                                    <x-tpl::product-grid-items.basic :$product imageClass="aspect-[372/446]" :discountCountdown="true" />
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                <!-- Newest Products -->
                <section>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold">جدیدترین محصولات</h2>
                        <a href="{{ route('client.newest', ['page' => get_next_page_number($newestProducts)]) }}" class="default flex items-center text-[13px] font-semibold">
                            <span>مشاهده همه</span>
                            <i class="fi fi-rr-angle-small-left mr-1"></i>
                        </a>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
                        @foreach ($newestProducts as $product)
                            <x-tpl::product-grid-items.basic :$product imageClass="aspect-[372/446]" />
                        @endforeach
                    </div>
                </section>

                <!-- Best-selling Products -->
                <section class="swiper" id="best-selling-products">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold">پرفروش‌ترین محصولات</h2>
                        <a href="{{ route('client.best-selling', ['page' => get_next_page_number($bestSellingProducts)]) }}" class="default flex items-center text-[13px] font-semibold">
                            <span>مشاهده همه</span>
                            <i class="fi fi-rr-angle-small-left mr-1"></i>
                        </a>
                    </div>

                    <div class="swiper-wrapper">
                        @foreach ($bestSellingProducts as $product)
                            <div class="swiper-slide w-[222px] h-auto">
                                <x-tpl::product-grid-items.basic :$product imageClass="aspect-[372/446]" />
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection

@push('bottom-scripts')
    <script>
        var swiper = new Swiper("#best-selling-products, #discounted-products", {
            slidesPerView: "auto",
            spaceBetween: 13,
            freeMode: true,
        });
    </script>
@endpush
