@php $order = get_pending_order(); @endphp

@if ($order)
    <div id="pending-order" class="container mb-5">
        <div class="card border-0 bg-red-50">
            <div class="card-content">
                <div class="flex flex-col lg:flex-row items-center">
                    <div class="text-center lg:text-right">
                        <div class="text-danger font-bold">یک سفارش در انتظار پرداخت دارید!</div>
                        <div class="text-sm mt-1">لطفاً نسبت به پرداخت سفارش خود اقدام و یا لغو کنید.</div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center lg:ms-auto mt-3 lg:mt-0">
                        <div class="bg-white sm:bg-transparent rounded-md flex items-center px-3 py-2 sm:px-0 sm:py-0">
                            <div class="text-center font-medium">{{ number_format($order->invoice->total_amount) }} {{ currency()->label() }}</div>
                            <div class="w-px h-4 bg-neutral-400 mx-3"></div>
                            <div class="flex items-center text-sm text-danger font-medium">
                                <div class="hidden 2xs:block ml-1.5">زمان باقی مانده:</div>
                                <div data-role="pending-order-countdown" data-seconds="{{ (int) now()->diffInSeconds($order->expires_at, false) }}" class="w-12"></div>
                            </div>
                        </div>

                        <div class="flex items-center space-x-4 space-x-reverse sm:mr-5 mt-3 sm:mt-0">
                            @auth
                                <a href="{{ route('client.account.orders.order.index', $order) }}" class="text-sm font-bold">مشاهده</a>
                            @endauth

                            <form action="{{ route('client.cart.finalizing.order.cancel', $order) }}" method="POST" data-role="cancel-purchase-form">
                                @csrf
                                @method('PATCH')

                                <button data-role="cancel-purchase" class="text-sm font-bold text-danger">لغو</button>
                            </form>

                            <form action="{{ route('client.cart.finalizing.order.purchase', $order) }}" method="POST">
                                @csrf

                                <button data-role="purchase" class="btn btn-success btn-sm font-bold">پرداخت</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
