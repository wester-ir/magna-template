@props(['product', 'imageClass' => '', 'imageStyle' => '', 'discountCountdown' => false])
@php
    $showQuantitySettings = settings('product')['show_quantity'];
@endphp

<a href="{{ $product->url }}">
    <div class="relative">
        <!-- Image -->
        <div class="overflow-hidden rounded">
            <img
                src="{{ $product->image->url['medium'] ?? template_asset('assets/images/no-image.jpg') }}"
                alt="{{ $product->title }}"
                class="w-full h-full object-cover transition-transform duration-300 hover:scale-110 {{ $imageClass }}"
                style="{{ $imageStyle }}">
        </div>

        <!-- Discount Percentage -->
        @if ($product->is_discounted)
            <div class="absolute left-0 top-3 flex items-center bg-black text-white font-semibold px-2 h-8">{{ $product->discount_percentage }}%</div>
        @endif

        <!-- Free Shipping -->
        @if ($product->is_shipping_free)
            <div class="absolute right-0 top-3 flex items-center bg-black text-white text-sm font-semibold px-2 h-8">ارسال رایگان</div>
        @endif
    </div>

    <div class="mt-2">
        <!-- Title -->
        <h3 class="text-sm">{{ $product->title }}</h3>

        <div class="mt-1">
            <!-- Price -->
            <div class="flex items-center">
                @if ($product->is_quantity_unlimited || $product->quantity > 0)
                    @if ($product->is_discounted)
                        <!-- Old Price -->
                        <div class="text-neutral-500 text-sm line-through ml-2" >
                            <span>{{ number_format($product->price) }}</span>
                        </div>
                    @endif

                    <!-- Final Price -->
                    <div class="font-semibold">
                        <span>{{ number_format($product->final_price) }}</span>
                        <span class="text-sm">تومان</span>
                    </div>
                @else
                    <!-- Out of Stock -->
                    <span class="text-neutral-500 mr-auto font-semibold">ناموجود</span>
                @endif
            </div>

            @if (! $product->is_quantity_unlimited && $showQuantitySettings['status'] && $showQuantitySettings['limit'] >= $product->quantity && $product->quantity > 0)
                <!-- Show Quantity -->
                <div class="text-sm text-red-500 mt-3">
                    تنها {{ $product->quantity}} عدد باقی مانده است.
                </div>
            @endif
        </div>

        @if ($discountCountdown && $product->is_discounted && $product->discount_expires_at && now()->diffInHours($product->discount_expires_at, false) < 100)
            <div class="flex flex-row-reverse mt-2">
                <span class="text-danger text-sm font-medium" data-role="basic-countdown" data-include-hours="true" data-seconds="{{ (int) now()->diffInSeconds($product->discount_expires_at, false) }}"></span>
            </div>
        @endif
    </div>
</a>
