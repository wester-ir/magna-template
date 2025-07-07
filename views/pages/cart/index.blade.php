@template_extends('views.layouts.default')

@title('سبد خرید', false)

@section('content')
    <div class="main">
        <div class="container">
            <div data-role="loading">
                <x-tpl::loading />
            </div>
            <div data-role="container" class="hidden">
                <div data-role="error-purchase-not-allowed" class="border border-red-300 text-danger rounded mb-3 p-5">
                    <div>امکان خرید محصول برای شما وجود ندارد.</div>
                </div>

                <div data-role="error-products-unavailable" class="border border-red-300 text-danger rounded mb-3 p-5">
                    <div>تعدادی از محصولات در سبد خرید، ناموجود شده اند.</div>
                </div>

                <div data-role="error-stock-limit-exceeded" class="border border-red-300 text-danger rounded mb-3 p-5">
                    <div>موجودی تعدادی از محصولات در سبد خرید بیش از موجودی انبار است.</div>
                </div>

                <div data-role="error-products-deleted" class="border border-red-300 text-danger rounded mb-3 p-5">
                    <div>تعدادی از محصولات از سایت حذف شده اند.</div>
                </div>

                <div data-role="error-cart-capacity-exceeded" class="border border-red-300 text-danger rounded mb-3 p-5">
                    <div>تعداد محصولات در سبد خرید بیش از حد مجاز است.</div>
                </div>

                <div class="flex flex-col space-y-3 md:space-y-0 md:flex-row md:space-x-3 md:space-x-reverse">
                    <div class="flex-1">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title">سبد خرید شما</h2>
                                <div class="flex items-center justify-between mt-1">
                                    <div class="text-sm">
                                        <span data-role="cart-item-count"></span>
                                        <span class="mr-0.5">کالا</span>
                                    </div>

                                    <button class="text-danger text-xs" data-role="cart-clear">حذف همه</button>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="space-y-5" data-role="cart-items"></div>
                            </div>
                        </div>
                    </div>

                    <div class="md:w-80">
                        <div class="card">
                            <div class="card-content text-sm">
                                <div class="space-y-3">
                                    <div class="flex items-start justify-between">
                                        <div class="text-neutral-600">
                                            <span>قیمت کالاها</span>
                                            <span class="mr-0.5">(<span data-role="cart-total-quantity"></span>)</span>
                                        </div>
                                        <div class="text-neutral-600">
                                            <span data-role="cart-total-base-price"></span>
                                            <span class="mr-0.5">تومان</span>
                                        </div>
                                    </div>

                                    <div class="flex items-start justify-between">
                                        <div class="text-neutral-600">سود از خرید</div>
                                        <div class="text-neutral-600">
                                            <span data-role="cart-total-discount"></span>
                                            <span class="mr-0.5">تومان</span>
                                        </div>
                                    </div>

                                    <div class="flex items-start justify-between font-medium">
                                        <div>جمع سبد</div>
                                        <div>
                                            <span data-role="cart-total-final-price"></span>
                                            <span class="mr-0.5">تومان</span>
                                        </div>
                                    </div>
                                </div>

                                <a href="{{ route('client.cart.finalizing.index') }}" class="btn btn-success block mt-5" data-role="cart-finalize">ثبت سفارش</a>
                            </div>
                        </div>

                        <div class="text-neutral-600 mt-3 text-xs leading-5">جهت جلوگیری از اتمام موجودی هر چه سریعتر نسبت به پرداخت هزینه سفارش خود اقدام کنید.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('bottom-scripts')
    <script>
        cartManager.init(renderItems, function (data) {
            $('[data-role="loading"]').remove();
            $('[data-role="container"]').removeClass('hidden');
        });

        function renderItems(items) {
            let html = '';

            function itemImage(image) {
                const src = image ? image.thumbnail : '{{ template_asset('assets/images/no-image.jpg') }}';

                return '<img src="'+ src +'" class="bg-neutral-100 object-cover w-full h-full rounded">';
            }

            function itemTitle(item) {
                let classes = ['text-sm', 'md:text-base'];

                if (item.is_deleted || ! item.is_lowest_qty_available) {
                    classes.push('text-danger');
                }

                classes = classes.join(' ');

                if (item.title) {
                    return '<a href="'+ (item.url ? item.url : '#') +'"><h2 class="'+ classes +'">'+ item.title +'</h2></a>';
                }

                return '<h2 class="'+ classes +'">حذف شده</h2>';
            }

            function itemDetails(item) {
                if (! item.variants) {
                    return '';
                }

                let html = '';

                item.variants.forEach(function (variant) {
                    html += renderItemVariant(variant);
                });

                return '<div class="mt-5 space-y-2 font-light text-sm">'+ html + '</div>';
            }

            function renderItemVariant(variant) {
                let html = '<div class="flex items-center">'+
                    '<div class="text-neutral-600 w-full max-w-[80px] min-w-[50px]">'+ variant.variant +' :</div>'+
                        '<div class="font-medium">';

                        if (variant.type === 'color' && variant.variant) {
                            html += '<div class="flex items-center">'+
                                '<div class="rounded-full w-4 h-4 shadow" style="background-color: '+ variant.value +';"></div>'+
                                '<div class="mr-2">'+ variant.item +'</div>'+
                            '</div>';
                        } else {
                            html += variant.item;
                        }

                    html += '</div>'+
                '</div>';

                return html;
            }

            function itemCartPanel(item) {
                if (item.is_deleted || ! item.is_lowest_qty_available) {
                    return '<button class="btn btn-danger text-sm w-full">حذف</button>';
                }

                return '<div data-role="cart-panel" data-product-id="'+ item.product_id +'" data-combination-id="'+ item.product_combination_id +'" data-amount-per-sale="'+ item.amount_per_sale +'">'+
                    '<div class="flex" data-role="cart-controls" data-for="cart">'+
                        (item.is_max_qty_exceeded ?
                            '<button class="trigger" data-role="cart-refresh"><i class="fi-rr-refresh flex"></i></button>' :
                            '<button class="trigger" data-role="cart-increase" '+ (item.is_max_qty_reached ? 'disabled' : '') +'><i class="fi-rr-plus flex"></i></button>'
                        )+
                        '<div class="w-auto flex-1" data-role="cart-quantity">'+ item.quantity +'</div>'+
                        (item.quantity > item.amount_per_sale && ! item.is_max_qty_exceeded ?
                            '<button class="trigger" data-role="cart-decrease"><i class="fi-rr-minus flex"></i></button>' :
                            '<button class="trigger" data-role="cart-remove"><i class="fi-rr-trash flex"></i></button>'
                        )+
                    '</div>'+
                '</div>';
            }

            function itemPrice(item) {
                if (item.is_deleted) {
                    return '<span class="text-danger text-sm">حذف شده</span>';
                }

                if (! item.is_lowest_qty_available) {
                    return '<span class="text-danger text-sm">ناموجود</span>';
                }

                if (item.is_max_qty_exceeded) {
                    return '<span class="text-danger text-sm">موجودی کافی نیست</span>';
                }

                return formatNumber(item.final_price) +' تومان';
            }

            items.forEach(item => {
                html += '<div data-role="cart-item" data-product-id="'+ item.product_id +'" data-combination-id="'+ item.product_combination_id +'">'+
                    '<div class="flex">'+
                        '<div class="w-32 h-32">'+
                            itemImage(item.image)+
                        '</div>'+

                        '<div class="flex-1 mr-5">'+
                            itemTitle(item)+
                            itemDetails(item)+
                        '</div>'+

                        '<div class="mr-1">'+
                            '<button class="text-danger text-xs" data-role="cart-remove">حذف</button>'+
                        '</div>'+
                    '</div>'+

                    '<div class="flex items-center mt-2">'+
                        '<div class="w-32">'+
                            itemCartPanel(item)+
                        '</div>'+

                        '<div class="flex-1 mr-5">'+
                            itemPrice(item)+
                        '</div>'+
                    '</div>'+
                '</div>';
            });

            if (items.length === 0) {
                $('[data-role="cart-clear"]').remove();

                html = '<div class="text-sm text-center">سبد خرید شما خالی است.</div>';
            }

            $('[data-role="cart-items"]').html(html);
        }

        $(document).on('click', '[data-role="cart-refresh"]', function () {
            const panel = $(this).closest('[data-role="cart-panel"]');
            const pid = panel.data('product-id');
            const cid = panel.data('combination-id');
            const amountPerSale = Number(panel.data('amount-per-sale'));

            cartManager.refresh(pid, cid, amountPerSale, (data) => {
                cartManager.renderItems();
                toast.success('موجودی محصول در سبد خرید تصحیح شد.');
            }, (data) => {
                cartManager.renderItems();

                handleCartErrors(data.result);
            }, () => {
                unknownError();
            }, () => {

            });
        });

        $(document).on('click', '[data-role="cart-increase"]', function () {
            const panel = $(this).closest('[data-role="cart-panel"]');
            const pid = panel.data('product-id');
            const cid = panel.data('combination-id');

            cartManager.add(pid, cid, (data) => {
                cartManager.renderItems();
            }, (data) => {
                cartManager.renderItems();

                handleCartErrors(data.result);
            }, () => {
                unknownError();
            }, () => {

            });
        });

        $(document).on('click', '[data-role="cart-decrease"]', function () {
            const panel = $(this).closest('[data-role="cart-panel"]');
            const pid = panel.data('product-id');
            const cid = panel.data('combination-id');
            const amountPerSale = Number(panel.data('amount-per-sale'));

            const currentQuantity = cartManager.getQuantity(cid);
            const newQuantity = currentQuantity - amountPerSale;

            cartManager.decrease(pid, cid, newQuantity, (data) => {
                cartManager.renderItems();
            }, (data) => {
                cartManager.renderItems();

                handleCartErrors(data.result);
            }, () => {
                unknownError();
            }, () => {

            });
        });

        $(document).on('click', '[data-role="cart-remove"]', function () {
            modal.defaults.confirmDanger(() => {
                const panel = $(this).closest('[data-role="cart-item"]');
                const pid = panel.data('product-id');
                const cid = panel.data('combination-id');

                cartManager.remove(pid, cid, (data) => {
                    cartManager.renderItems();
                }, (data) => {
                    cartManager.renderItems();

                    handleCartErrors(data.result, ['product_deleted']);
                }, () => {
                    unknownError();
                }, () => {

                });
            });
        });

        $(document).on('click', '[data-role="cart-clear"]', function () {
            modal.defaults.confirmDanger(() => {
                cartManager.clear((data) => {
                    cartManager.renderItems();
                }, (data) => {
                    cartManager.renderItems();
                }, () => {
                    unknownError();
                }, () => {

                });
            });
        });

        function unknownError() {
            toast.error('خطایی رخ داد.');
        }
    </script>
@endpush
