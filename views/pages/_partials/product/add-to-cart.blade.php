@if ($product->is_variable)
    <!-- Variants & Combinations -->
    <div class="space-y-5 mt-8">
        @foreach ($product->variants as $variant)
            <div data-role="variant-list" data-id="{{ $variant->id }}" data-type="{{ $variant->type->snake() }}" data-style="{{ $variant->style->snake() }}">
                <div class="flex items-center mb-3">
                    <div class="font-semibold text-xl">{{ $variant->name }}</div>
                    <span class="mx-2">:</span>
                    <div data-role="selected-variant-item"></div>
                </div>

                @if ($variant->style->name === 'Default')
                    <div class="flex space-x-3 rtl:space-x-reverse">
                        @foreach ($product->getVariantItems($variant->id) as $item)
                            @if ($variant->type->name === 'Color')
                                <div data-role="variant-item" data-id="{{ $item->id }}" data-name="{{ $item->name }}" data-itooltip="{{ $item->name }}" data-type="color" data-selected="false" style="background-color: {{ $item->value }};"></div>
                            @else
                                <div data-role="variant-item" data-id="{{ $item->id }}" data-name="{{ $item->name }}" data-type="default" data-selected="false">
                                    {{ $item->name }}
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <select class="default">
                        @foreach ($product->getVariantItems($variant->id) as $item)
                            <option data-role="variant-item" data-id="{{ $item->id }}" data-name="{{ $item->name }}" data-selected="false" data-available="false">{{ $item->name }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
        @endforeach
    </div>
@endif

<div data-role="cart-panel" data-product-id="{{ $product->id }}" data-amount-per-sale="{{ $product->amount_per_sale }}" class="mt-8" style="display: none;">
    <div data-role="cart-controls" data-for="product">
        <div class="flex">
            <div class="flex items-center bg-neutral-200 p-5 rounded-xl">
                <div class="flex">
                    <button class="trigger" data-role="cart-refresh">
                        <i class="fi-rr-refresh flex"></i>
                    </button>
                    <button class="trigger" data-role="cart-increase">
                        <i class="fi-rr-plus flex"></i>
                    </button>
                    <div data-role="cart-quantity"></div>
                    <button class="trigger" data-role="cart-decrease">
                        <i class="fi-rr-minus flex"></i>
                    </button>
                    <button class="trigger" data-role="cart-remove">
                        <i class="fi-rr-trash flex"></i>
                    </button>
                </div>

                <div class="text-sm mr-5">در سبد خرید شما</div>
            </div>
        </div>
    </div>

    <button data-role="cart-add" class="bg-black hover:bg-black/90 text-white font-semibold px-8 py-3 rounded">
        <div class="flex items-center">
            <i class="fi fi-rr-basket-shopping-simple"></i>
            <span class="mr-4">افزودن به سبد خرید</span>
        </div>
    </button>
</div>

<div class="space-y-5 mt-5">
    @if ($product->points > 0)
        <div data-role="points" class="text-sm text-green-700">
            با خرید این محصول <span class="font-bold">{{ number_format($product->points) }} امتیاز</span> دریافت کنید.
        </div>
    @endif

    @if ($product->quantity > 0)
        <div data-role="price-container">
            <div class="text-xl font-semibold">
                <span data-role="final-price">{{ number_format($product->final_price) }}</span>
                <span class="mr-2">تومان</span>
            </div>

            @if ($product->is_discounted)
                <div class="line-through">
                    <span data-role="old-price">{{ number_format($product->price) }}</span>
                    <span class="mr-2">تومان</span>
                </div>
            @endif
        </div>
    @endif

    <template id="template-unavailable-message">
        <div data-role="unavailable-message" class="font-medium text-danger">
            متاسفانه محصول مورد نظر موجود نمی باشد.
        </div>
    </template>

    <template id="template-max-available-quantity-message">
        <div data-role="max-available-quantity-message" class="text-danger">
            تنها <span data-role="quantity"></span> عدد باقی مانده است.
        </div>
    </template>
</div>

@push('bottom-scripts')
    <script>
        const pid = {{ $product->id }};
        combinationManager.setCombinations({{ Js::from($product->jsonable_combinations) }});
        combinationManager.selectByUid('{{ $product->combinations[0]->uid }}');

        function unknownError() {
            toast.error('خطایی رخ داد.');
        }

        $('[data-role="variant-item"]').on('click', function () {
            const id = $(this).data('id');
            const variantId = $(this).closest('[data-role="variant-list"]').data('id');

            combinationManager.selectVariantItem(id, variantId);
        });

        $('[data-role="variant-list"] select').on('change', function () {
            const id = $(this).find('option:selected').data('id');
            const variantId = $(this).closest('[data-role="variant-list"]').data('id');

            combinationManager.selectVariantItem(id, variantId);
        });

        $('[data-role="cart-add"]').on('click', function () {
            const id = combinationManager.getCombination().id;

            cartManager.add(pid, id, (data) => {
                // success
                combinationManager.updateCart(id, data.result.data);

                if (data.result.data.quantity === {{ $product->amount_per_sale }}) {
                    toast.dismissAll();
                    toast.success('محصول مورد نظر به سبد خرید اضافه شد.', {
                        buttons: [
                            {
                                innerHTML: 'مشاهده سبد',
                                className: 'btn btn-success text-sm text-center flex-1',
                                href: '{{ route('client.cart.index') }}',
                            }, {
                                innerHTML: 'بستن',
                                className: 'btn btn-light text-sm text-center',
                                onclick: function () {
                                    toast.dismiss();
                                },
                            },
                        ],
                    });
                }
            }, (data) => {
                // cart-error
                combinationManager.updateCart(id, data.result.data);

                handleCartErrors(data.result);
            }, (e) => {
                unknownError();
            },
            () => {

            });
        });

        $('[data-role="cart-refresh"]').on('click', function () {
            const id = combinationManager.getCombination().id;

            cartManager.refresh(pid, id, {{ $product->amount_per_sale }}, (data) => {
                combinationManager.updateCart(id, data.result.data);
                toast.success('موجودی محصول در سبد خرید تصحیح شد.');
            }, (data) => {
                combinationManager.updateCart(id, data.result.data);

                handleCartErrors(data.result);
            }, () => {
                unknownError();
            }, () => {

            });
        });

        $('[data-role="cart-increase"]').on('click', function () {
            const id = combinationManager.getCombination().id;

            cartManager.add(pid, id, (data) => {
                combinationManager.updateCart(id, data.result.data);
            }, (data) => {
                combinationManager.updateCart(id, data.result.data);

                handleCartErrors(data.result);
            }, () => {
                unknownError();
            }, () => {

            });
        });

        $('[data-role="cart-decrease"]').on('click', function () {
            const combination = combinationManager.getCombination();
            const newQuantity = combination.cart.quantity - {{ $product->amount_per_sale }};

            cartManager.decrease(pid, combination.id, newQuantity, (data) => {
                combinationManager.updateCart(combination.id, data.result.data);
            }, (data) => {
                combinationManager.updateCart(combination.id, data.result.data);

                handleCartErrors(data.result);
            }, () => {
                unknownError();
            }, () => {

            });
        });

        $('[data-role="cart-remove"]').on('click', function () {
            const id = combinationManager.getCombination().id;

            cartManager.remove(pid, id, (data) => {
                combinationManager.updateCart(id, data.result.data);
            }, (data) => {
                combinationManager.updateCart(id, data.result.data);

                handleCartErrors(data.result, ['product_deleted']);
            }, () => {
                unknownError();
            }, () => {

            });
        });
    </script>
@endpush
