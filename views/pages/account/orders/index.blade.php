@template_extends('views.layouts.account')

@title('سفارش‌ها', false)

@section('page-content')
    <div class="card">
        <div class="card-header">
            <span class="card-title">سفارش‌ها</span>
        </div>
        <div class="card-tabs">
            <a id="tab-pending" href="{{ route('client.account.orders.index', ['status' => 'pending']) }}" class="card-tab" data-active="{{ as_string($status === 'pending') }}">
                <span>در انتظار پرداخت</span>
                @if ($orderStats->pending !== 0)
                    <span class="badge mr-2">{{ $orderStats->pending }}</span>
                @endif
            </a>
            <a id="tab-paid" href="{{ route('client.account.orders.index', ['status' => 'paid']) }}" class="card-tab" data-active="{{ as_string($status === 'paid') }}">
                <span>در حال پردازش</span>
                @if ($orderStats->paid !== 0)
                    <span class="badge mr-2">{{ $orderStats->paid }}</span>
                @endif
            </a>
            <a id="tab-fulfilled" href="{{ route('client.account.orders.index', ['status' => 'fulfilled']) }}" class="card-tab" data-active="{{ as_string($status === 'fulfilled') }}">
                <span>تکمیل شده</span>
                @if ($orderStats->fulfilled !== 0)
                    <span class="badge mr-2">{{ $orderStats->fulfilled }}</span>
                @endif
            </a>
            <a id="tab-cancelled" href="{{ route('client.account.orders.index', ['status' => 'cancelled']) }}" class="card-tab" data-active="{{ as_string($status === 'cancelled') }}">
                <span>لغو شده</span>
                @if ($orderStats->cancelled !== 0)
                    <span class="badge mr-2">{{ $orderStats->cancelled }}</span>
                @endif
            </a>
            <a id="tab-returned" href="{{ route('client.account.orders.index', ['status' => 'returned']) }}" class="card-tab" data-active="{{ as_string($status === 'returned') }}">
                <span>مرجوع شده</span>
                @if ($orderStats->returned !== 0)
                    <span class="badge mr-2">{{ $orderStats->returned }}</span>
                @endif
            </a>
        </div>
        <div class="divide-y">
            @foreach ($orders as $order)
                <a href="{{ route('client.account.orders.order.index', $order) }}" class="card-content block">
                    <!-- Head -->
                    <div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                @php
                                    $orderIcon = match ($order->status->name) {
                                        'Pending' => 'clock.svg',
                                        'Paid' => 'checklist.svg',
                                        'Fulfilled' => 'order-fulfilled.svg',
                                        'Cancelled', 'Expired' => 'cancel.svg',
                                        'Returned' => 'order-returned.svg',
                                    };
                                @endphp
                                <img src="{{ template_asset('/assets/images/icons/'. $orderIcon) }}" class="w-6 h-6">
                                <div class="font-medium mr-3">{{ $order->status->label() }}</div>
                            </div>

                            <span class="icon icon-arrow-light-left w-3 h-3"></span>
                        </div>

                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between text-sm mt-3">
                            <div class="flex flex-col md:flex-row md:items-center space-y-3 md:space-y-0 md:space-x-6 md:space-x-reverse">
                                <div class="flex items-center justify-between md:justify-start">
                                    <span class="font-light text-neutral-400">کد سفارش</span>
                                    <span class="font-medium mr-2">{{ $order->id }}</span>
                                </div>
                                @if ($order->is_paid_overall)
                                    <div class="flex items-center justify-between md:justify-start">
                                        <span class="font-light text-neutral-400">پرداخت شده در</span>
                                        <span class="font-medium mr-2">{{ $order->paid_at->toJalali()->format('%d %B %Y') }}</span>
                                    </div>
                                @endif
                                @if ($order->is_fulfilled)
                                    <div class="flex items-center justify-between md:justify-start">
                                        <span class="font-light text-neutral-400">تکمیل شده در</span>
                                        <span class="font-medium mr-2">{{ $order->fulfilled_at->toJalali()->format('%d %B %Y') }}</span>
                                    </div>
                                @endif
                                @if ($order->is_cancelled)
                                    <div class="flex items-center justify-between md:justify-start">
                                        <span class="font-light text-neutral-400">لغو شده در</span>
                                        <span class="font-medium mr-2">{{ $order->cancelled_at->toJalali()->format('%d %B %Y') }}</span>
                                    </div>
                                @endif
                                @if ($order->is_returned)
                                    <div class="flex items-center justify-between md:justify-start">
                                        <span class="font-light text-neutral-400">مرجوع شده در</span>
                                        <span class="font-medium mr-2">{{ $order->returned_at->toJalali()->format('%d %B %Y') }}</span>
                                    </div>
                                @endif
                                <div class="flex items-center justify-between md:justify-start">
                                    <span class="font-light text-neutral-400">مبلغ</span>
                                    <span class="font-medium mr-2">{{ number_format($order->invoice->paid_amount) }} {{ currency()->label() }}</span>
                                </div>
                            </div>

                            @if ($order->is_payable)
                                <div class="flex items-center mt-3 lg:mt-0">
                                    <div class="flex items-center font-light text-sm text-red-500">
                                        زمان باقی مانده: <div class="flex items-center font-medium w-12 mx-1" style="direction: ltr;" data-role="pending-order-countdown" data-seconds="{{ (int) now()->diffInSeconds($order->expires_at, false) }}"></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <hr class="my-3">

                    <!-- Items -->
                    <div class="flex space-x-3 space-x-reverse">
                        @foreach ($order->items->take(5) as $item)
                            <div>
                                @if ($item->combination?->is_image_available)
                                    <img src="{{ $item->combination->image['url']['thumbnail'] }}" class="w-16 h-16 object-cover rounded-md" title="{{ $item->meta['title'] }}" alt="{{ $item->meta['title'] }}">
                                @else
                                    <img src="{{ template_asset('assets/images/no-image.jpg') }}" class="w-16 h-16 object-cover rounded-md">
                                @endif
                            </div>
                        @endforeach

                        @if ($order->items->count() > 3)
                            <div class="flex items-center justify-center text-center text-xs text-neutral-500 w-16 h-16 min-w-16 min-h-16 border rounded-md">
                                {{ $order->items->count() - 3 }} مورد دیگر
                            </div>
                        @endif

                        @if ($order->items->isEmpty())
                            <div class="flex-1 text-xs text-center text-neutral-500">محصولی وجود ندارد.</div>
                        @endif
                    </div>
                </a>
            @endforeach

            @if ($orders->count() === 0)
                <div class="p-5 text-center font-light text-neutral-500">سفارشی موجود نیست.</div>
            @endif
        </div>
    </div>

    {{ $orders->links() }}
@endsection

@push('bottom-scripts')
    <script>
        scrollToTab('tab-{{ $status }}');
    </script>
@endpush
