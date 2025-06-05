@template_extends('views.layouts.account')

@title('حساب کاربری', false)

@section('page-content')
    <div class="card">
        <div class="card-header">
            <span class="card-title">سفارش‌ها</span>
        </div>
        <div class="card-content">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-5 gap-y-8 mt-8">
                <a href="{{ route('client.account.orders.index', ['status' => 'pending']) }}" class="flex flex-col items-center justify-center">
                    <img src="{{ template_asset('/assets/images/icons/invoice.svg') }}" width="50" height="50" alt="">
                    <div class="text-sm lg:text-base mt-3">در انتظار پرداخت</div>
                    <span class="badge badge-light mt-3">{{ $orderStats->pending }}</span>
                </a>

                <a href="{{ route('client.account.orders.index', ['status' => 'paid']) }}" class="flex flex-col items-center justify-center">
                    <img src="{{ template_asset('/assets/images/icons/cart-colorful.svg') }}" width="50" height="50" alt="">
                    <div class="text-sm lg:text-base mt-3">در حال پردازش</div>
                    <span class="badge badge-orange mt-3">{{ $orderStats->paid }}</span>
                </a>

                <a href="{{ route('client.account.orders.index', ['status' => 'fulfilled']) }}" class="flex flex-col items-center justify-center">
                    <img src="{{ template_asset('/assets/images/icons/order-fulfilled.svg') }}" width="50" height="50" alt="">
                    <div class="text-sm lg:text-base mt-3">ارسال شده</div>
                    <span class="badge badge-success mt-3">{{ $orderStats->fulfilled }}</span>
                </a>

                <a href="{{ route('client.account.orders.index', ['status' => 'returned']) }}" class="flex flex-col items-center justify-center">
                    <img src="{{ template_asset('/assets/images/icons/order-returned.svg') }}" width="50" height="50" alt="">
                    <div class="text-sm lg:text-base mt-3">مرجوع شده</div>
                    <span class="badge badge-light mt-3">{{ $orderStats->returned }}</span>
                </a>
            </div>
        </div>
    </div>
@endsection
