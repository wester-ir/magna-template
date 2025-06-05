@template_extends('views.layouts.account')

@title('جزئیات سفارش #'. $order->id, false)

@section('page-content')
    <div class="card">
        <div class="card-header">
            <span class="card-title">جزئیات سفارش</span>
        </div>

        <div class="card-content">
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
                <img src="{{ template_asset('/assets/images/icons/'. $orderIcon) }}" class="w-6 h-6" alt="">
                <div class="font-medium mr-3">{{ $order->status->label() }}</div>
            </div>

            <div class="flex flex-col md:flex-row md:items-center md:justify-between text-sm mt-3">
                <div class="flex flex-col md:flex-row md:items-center space-y-3 md:space-y-0 md:space-x-6 md:space-x-reverse">
                    <div class="flex items-center justify-between md:justify-start">
                        <span class="font-light text-neutral-400">کد سفارش</span>
                        <span class="font-medium mr-2">{{ $order->id }}</span>
                    </div>

                    @if ($order->is_paid_overall)
                        <div class="flex items-center justify-between md:justify-start">
                            <span class="font-light text-neutral-400">پرداخت شده در</span>
                            <span
                                class="font-medium mr-2">{{ $order->paid_at->toJalali()->format('%d %B %Y') }}</span>
                        </div>
                    @endif

                    @if ($order->is_fulfilled)
                        <div class="flex items-center justify-between md:justify-start">
                            <span class="font-light text-neutral-400">تکمیل شده در</span>
                            <span
                                class="font-medium mr-2">{{ $order->fulfilled_at->toJalali()->format('%d %B %Y') }}</span>
                        </div>
                    @endif

                    @if ($order->is_cancelled)
                        <div class="flex items-center justify-between md:justify-start">
                            <span class="font-light text-neutral-400">لغو شده در</span>
                            <span
                                class="font-medium mr-2">{{ $order->cancelled_at->toJalali()->format('%d %B %Y') }}</span>
                        </div>
                    @endif

                    @if ($order->is_returned)
                        <div class="flex items-center justify-between md:justify-start">
                            <span class="font-light text-neutral-400">مرجوع شده در</span>
                            <span
                                class="font-medium mr-2">{{ $order->returned_at->toJalali()->format('%d %B %Y') }}</span>
                        </div>
                    @endif

                    @if ($order->points > 0)
                        <div class="flex items-center justify-between md:justify-start">
                            <span class="font-light text-neutral-400">امتیاز</span>
                            <span
                                class="font-medium mr-2">{{ number_format($order->meta->points) }}</span>
                        </div>
                    @endif
                </div>

                @if ($order->is_payable)
                    <div class="flex items-center justify-between mt-3 md:mt-0">
                        <div class="flex items-center justify-center font-medium text-sm text-red-500 w-12" style="direction: ltr;"
                                data-role="pending-order-countdown"
                                data-seconds="{{ (int) now()->diffInSeconds($order->expires_at, false) }}"></div>

                        <div class="flex items-center space-x-4 space-x-reverse mr-3">
                            <form action="{{ route('client.cart.finalizing.order.cancel', $order) }}" method="POST" data-role="cancel-purchase-form">
                                @csrf
                                @method('PATCH')

                                <button data-role="cancel-purchase" class="btn btn-sm text-red-500 font-medium p-0 h-auto">لغو</button>
                            </form>

                            <form action="{{ route('client.cart.finalizing.order.purchase', $order) }}" method="POST">
                                @csrf

                                <button data-role="purchase" class="btn btn-sm text-green-500 font-bold p-0">پرداخت سفارش</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>

            <hr class="my-4">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between text-sm mt-3">
                <div class="flex flex-col md:flex-row md:items-center space-y-3 md:space-y-0 md:space-x-6 md:space-x-reverse">
                    <div class="flex items-center justify-between md:justify-start">
                        <span class="font-light text-neutral-400">تحویل گیرنده</span>
                        <span
                            class="font-medium text-neutral-600 mr-2">{{ $order->address->full_name }}</span>
                    </div>

                    <div class="flex items-center justify-between md:justify-start">
                        <span class="font-light text-neutral-400">شماره موبایل</span>
                        <span class="font-medium text-neutral-600 mr-2">{{ $order->address->number }}</span>
                    </div>
                </div>
            </div>

            <div class="flex md:items-center flex-col md:flex-row text-sm mt-3">
                <span class="font-light text-neutral-400">آدرس</span>
                <span class="font-medium text-neutral-600 mt-2 md:mt-0 md:mr-2">{{ $order->address->full_address }}</span>
            </div>

            @if ($order->tracking_number || $order->payment_ref_number)
                <hr class="my-5">

                <div class="flex flex-col md:flex-row md:items-center space-y-3 md:space-y-0 md:space-x-6 md:space-x-reverse">
                    @if ($order->payment_ref_number)
                        <div class="flex md:items-center justify-between md:justify-start text-sm">
                            <span class="font-light text-neutral-400">کد مرجع تراکنش</span>
                            <span class="font-medium text-neutral-600 mr-2">{{ $order->payment_ref_number }}</span>
                        </div>
                    @endif

                    @if ($order->address->tracking_number)
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between md:justify-start text-sm">
                            <span class="font-light text-neutral-400">کد پیگیری مرسوله</span>
                            <span class="font-medium text-neutral-600 mt-2 md:mt-0 md:mr-2">{{ $order->address->tracking_number }}</span>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <hr>

        <div
            class="card-content flex flex-col md:flex-row md:items-center space-y-3 md:space-y-0 md:space-x-6 md:space-x-reverse text-sm">
            <div class="flex items-center justify-between md:justify-start">
                <span class="font-light text-neutral-400">مبلغ</span>
                <span
                    class="font-medium mr-2">{{ number_format($order->invoice->total_amount) }} {{ productCurrency()->label() }}</span>
            </div>

            <div class="flex items-center justify-between md:justify-start">
                <span class="font-light text-neutral-400">ارسال توسط</span>
                <span class="font-medium text-neutral-600 mr-2">
                    {{ $order->meta['courier']['name'] }} ({{ $order->meta['courier']['type'] }})
                </span>
            </div>

            <div class="flex items-center justify-between md:justify-start">
                <span class="font-light text-neutral-400">هزینه ارسال</span>
                <span class="font-medium text-neutral-600 mr-2">
                    @if ($order->invoice->shipping_cost > 0)
                        {{ number_format($order->invoice->shipping_cost) }} {{ productCurrency()->label() }}
                    @else
                        رایگان
                    @endif
                </span>
            </div>
        </div>
    </div>

    <div class="space-y-3 mt-3">
        @foreach ($order->items as $item)
            <div class="border border-neutral-200 rounded-lg p-5">
                @if ($item->has_returned_quantities)
                    <div class="mb-5">
                        @if ($item->is_returned)
                            <span class="badge badge-danger">این محصول مرجوع شده است</span>
                        @else
                            <span class="badge badge-danger">{{ $item->returned_quantity }} عدد از این محصول مرجوع شده است.</span>
                        @endif
                    </div>
                @endif

                <div class="flex">
                    <div>
                        @if ($item->combination?->relationLoaded('image') && $item->combination->image)
                            <a href="{{ $item->product?->url ?: '#' }}">
                                <img src="{{ $item->combination->image['url']['thumbnail'] }}"
                                    class="w-24 h-24 object-cover rounded-md"
                                    title="{{ $item->meta['title'] }}" alt="{{ $item->meta['title'] }}">
                            </a>
                        @else
                            <img src="{{ template_asset('assets/images/no-image.jpg') }}" class="w-24 h-24 object-cover rounded-md" alt="">
                        @endif
                    </div>

                    <div class="flex-1 mr-5">
                        <h4><a href="{{ $item->product?->url ?: '#' }}">{{ $item->meta['title'] }}</a></h4>
                        <div class="my-4 space-y-3">
                            @if ($item->meta['is_variable'])
                                @foreach ($item->meta['variants'] as $variant)
                                    <div class="flex items-center text-sm">
                                        <div
                                            class="text-neutral-600 font-light w-32">{{ $variant['variant'] }}
                                            :
                                        </div>
                                        <div class="flex items-center font-medium">
                                            @if ($variant['type'] === 'color')
                                                <div class="rounded-full w-4 h-4 shadow ml-2"
                                                        style="background-color: {{ $variant['value'] }};"></div>
                                            @endif
                                            <div>{{ $variant['item'] }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            <div class="flex items-center text-sm">
                                <div class="text-neutral-600 w-32">تعداد :</div>
                                <div class="flex items-center font-medium">{{ $item->quantity }}</div>
                            </div>

                            @if ($item->returned_quantity > 0)
                                <div class="flex items-center text-sm">
                                    <div class="text-red-500 w-32">تعداد مرجوع شده :</div>
                                    <div
                                        class="flex items-center font-medium text-red-500">{{ $item->returned_quantity }}</div>
                                </div>
                            @endif
                        </div>

                        <div>
                            <div
                                class="text-neutral-500 text-sm">{{ number_format($item->unit_price) }} {{ productCurrency()->label() }}</div>
                            <div
                                class="font-medium mt-1">{{ number_format($item->total_price) }} {{ productCurrency()->label() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        @if ($order->items->isEmpty())
            <div class="card">
                <div class="card-content text-xs text-center text-neutral-500">محصولی وجود ندارد.</div>
            </div>
        @endif
    </div>
@endsection
