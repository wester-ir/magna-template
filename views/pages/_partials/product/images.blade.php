<div class="flex -mt-6 sm:mt-0 -mx-4 sm:mx-0">
    <div class="sm:flex w-full sm:w-auto">
        @if (! $product->images->isEmpty())
            <!-- Thumbnails -->
            <div class="w-[70px] ml-2 space-y-2 overflow-auto hidden md:block">
                @foreach ($product->images as $index => $image)
                    <div class="slider-thumbnail border-neutral-600 data-[is-active=true]:border-2 rounded overflow-hidden" data-index="{{ $index }}" data-is-active="{{ as_string($index === 0) }}">
                        <img src="{{ $image->url['thumbnail'] }}" loading="lazy" class="w-full object-cover h-20" alt="{{ $product->title }}">
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Slider -->
        <div class="w-full sm:w-[250px] md:w-[300px] lg:w-[450px] sm:rounded overflow-hidden">
            @if (! $product->images->isEmpty())
                <div class="swiper" id="product-slider">
                    <div class="swiper-wrapper">
                        @foreach ($product->images as $image)
                            <div class="swiper-slide h-auto" data-image-id="{{ $image->id }}">
                                <div class="swiper-zoom-container">
                                    <img src="{{ $image->url['medium'] }}" class="w-full h-auto aspect-auto object-cover">
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="swiper-pagination"></div>
                </div>
            @else
                <img src="{{ template_asset('assets/images/no-image.jpg') }}" class="w-full" alt="{{ $product->title }}">
            @endif
        </div>
    </div>
</div>
