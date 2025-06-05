@template_extends('views.layouts.account')

@title('اعلان‌ها', false)

@section('page-content')
    <div class="card">
        <div class="card-header">
            <span class="card-title">اعلان‌ها</span>
        </div>
        <div class="divide-y">
            @foreach ($notifications as $notification)
                @php
                    switch ($notification->type_name) {
                        case 'order_paid':
                            $icon = 'checklist.svg';
                            $title = <<<HTML
                                <div>
                                    سفارش <span class="inline-flex ltr-direction">#{$notification->order->id}</span> با موفقیت پرداخت شد.
                                </div>
                            HTML;
                            $message = 'کد رهگیری مرسوله پس از ارسال سفارش برایتان فرستاده خواهد شد.';
                        break;

                        case 'order_fulfilled':
                            $icon = 'order-fulfilled.svg';
                            $title = <<<HTML
                                <div>
                                    سفارش <span class="inline-flex ltr-direction">#{$notification->order->id}</span> تکمیل و ارسال شد.
                                </div>
                            HTML;
                            $trackingNumber = $notification->order->address->tracking_number;
                            $message = $trackingNumber ? 'کد رهگیری مرسوله: '. $trackingNumber : null;
                        break;
                    }
                @endphp

                <div class="card-content flex text-[15px]">
                    <img src="{{ template_asset('/assets/images/icons/'. $icon) }}" class="w-6 h-6 min-w-6 min-h-6">

                    <div class="flex-1 w-0 mr-3 pt-0.5">
                        <div class="font-semibold">{!! $title !!}</div>

                        @if ($message)
                            <div class="text-neutral-600 text-sm mt-1">{{ $message }}</div>
                        @endif

                        <div class="flex items-center mt-3 space-x-3 space-x-reverse overflow-x-auto hide-scrollbar">
                            @foreach ($notification->order->items->take(3) as $item)
                                @if ($item->combination?->relationLoaded('image'))
                                    <a href="{{ $item->product?->url ?: '#' }}">
                                        <img src="{{ $item->combination->image['url']['thumbnail'] }}"
                                            class="w-16 h-16 object-cover rounded-md"
                                            title="{{ $item->meta['title'] }}">
                                    </a>
                                @else
                                    <img src="{{ template_asset('/assets/images/no-image.jpg') }}" class="w-16 h-16 object-cover rounded-md">
                                @endif
                            @endforeach

                            @if ($notification->order->items->count() > 3)
                                <div class="flex items-center justify-center text-center text-xs text-neutral-500 w-16 h-16 min-w-16 min-h-16 border rounded-md">
                                    {{ $notification->order->items->count() - 3 }} مورد دیگر
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center justify-between text-xs mt-3">
                            <a href="{{ route('client.account.orders.order.index', $notification->order->id) }}" class="flex items-center text-sky-500">
                                <span>مشاهده سفارش</span>
                                <span class="fi-rr-angle-small-left flex mr-1"></span>
                            </a>
                            <span class="font-light text-neutral-500">{{ $notification->created_at->toJalali()->format('%d %B %Y') }}</span>
                        </div>
                    </div>
                </div>
            @endforeach

            @if ($notifications->isEmpty())
                <div class="text-center font-light">هیچ اعلانی ندارید.</div>
            @endif
        </div>
    </div>

    {{ $notifications->links() }}
@endsection
