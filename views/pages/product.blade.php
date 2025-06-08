@template_extends('views.layouts.default')

@title($product->title, false)
@description($product->content->summary ?: $product->content->description)

@push('meta-tags')
    @if ($product->image)
        <meta property="og:image" content="{{ $product->image->url['medium'] }}">
        <meta property="twitter:image" content="{{ $product->image->url['medium'] }}">
    @endif
@endpush

@section('content')
    <div class="main">
        <div class="container">
            <div class="flex flex-col sm:flex-row">
                <!-- Images -->
                @template_include('views.pages._partials.product.images')

                <div class="sm:mr-5 mt-5 sm:mt-0 flex-1">
                    <div class="flex">
                        <div class="flex-1">
                            <!-- Title -->
                            <h1 class="md:text-lg lg:text-xl font-bold">{{ $product->title }}</h1>

                            <div class="space-y-2 mt-2">
                                @if (auth()->check() && auth()->user()->is_staff)
                                    <!-- Edit -->
                                    <div class="flex items-center">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="text-sky-500 font-bold">ویرایش</a>
                                    </div>
                                @endif

                                <!-- SKU -->
                                <div class="flex items-center">
                                    <span class="text-neutral-500">کد محصول:</span>
                                    <span class="mr-2">{{ $product->sku }}</span>
                                </div>

                                @if ($product->brands->isNotEmpty())
                                    <!-- Brands -->
                                    <div class="flex items-center">
                                        <span class="text-neutral-500">برند:</span>
                                        <span class="mr-2">
                                        {!! $product->brands->map(function ($brand) {
                                            return <<<HTML
                                                <a href="{$brand->url}">{$brand->name}</a>
                                            HTML;
                                        })->implode('، ') !!}
                                    </span>
                                    </div>
                                @endif
                            </div>

                            <!-- Badges -->
                            @if ($product->is_discounted || $product->is_shipping_free)
                                <div class="mt-3">
                                    @if ($product->is_discounted) <span class="badge badge-lime">%{{ $product->discount_percentage }} تخفیف</span> @endif
                                    @if ($product->is_shipping_free) <span class="badge badge-danger">ارسال رایگان</span> @endif
                                </div>
                            @endif
                        </div>

                        @feature('favorites')
                            <div class="mr-3">
                                <x-tpl::like-product :$product :status="$product->is_liked" class="text-2xl" />
                            </div>
                        @endfeature
                    </div>

                    @if ($product->content->description)
                        <!-- Description -->
                        <div class="text-[15px] font-normal mt-5 [&>ul]:ps-5 [&>ul>li]:text-black [&>ul>li>strong]:font-normal [&>ul>li>strong]:text-neutral-600 [&>ul>li]:list-disc marker:text-neutral-400 leading-[30px]">
                            {!! $product->content->description !!}
                        </div>
                    @endif

                    @template_include('views.pages._partials.product.add-to-cart')
                </div>
            </div>

            <div class="mt-5">
                @template_include('views.pages._partials.product.tabs')
            </div>

            @if ($product->is_commentable)
                <div class="mt-10">
                    @template_include('views.pages._partials.product.comments')
                </div>
            @endif

            @template_include('views.pages._partials.product.related-products')
        </div>
    </div>
@endsection

@push('bottom-scripts')
    <link rel="stylesheet" href="{{ template_versioned_asset('/assets/js/libs/swiper/swiper-bundle.min.css') }}" />
    <script src="{{ template_versioned_asset('/assets/js/libs/swiper/swiper-bundle.min.js') }}"></script>
    @if ($product->image)
        <script>
            var swiper = new Swiper("#product-slider", {
                zoom: true,
                pagination: {
                    el: ".swiper-pagination",
                },
            });

            swiper.on('slideChange', function () {
                $('.slider-thumbnail').attr('data-is-active', false);
                $('.slider-thumbnail[data-index="'+ swiper.activeIndex +'"]').attr('data-is-active', true);
            });

            $('.slider-thumbnail').on('click', function () {
                swiper.slideTo($(this).data('index'));
            });
        </script>
    @endif
    <script>
        $('.product-tabs > ul > li').on('click', function () {
            const tab = $(this).data('tab');

            $(this).attr('data-active', true).siblings().attr('data-active', false);

            $('.tab-sections .tab-section[data-id="'+ tab +'"]').attr('data-active', true).siblings().attr('data-active', false);
        });
    </script>
@endpush
