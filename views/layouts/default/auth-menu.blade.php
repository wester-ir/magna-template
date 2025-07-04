@if (auth()->check())
    <div data-role="dropdown" class="auth-menu">
        <button type="button" data-role="dropdown-trigger" data-is-active="false">
            <div class="hidden md:block font-semibold text-sm ml-1" data-role="users-full-name">{{ auth()->user()->full_name ?? 'کاربر' }}</div>
            <i class="fi fi-rr-user flex text-xl md:hidden"></i>
            <i class="fi fi-rr-angle-small-down"></i>

            @if ($unreadNotifications)
                <div class="bulb"></div>
            @endif
        </button>

        <div data-role="dropdown-content" class="hidden">
            <!-- Account -->
            <a href="{{ route('client.account.index') }}" class="item">
                <img src="{{ template_asset('assets/images/user.png') }}" class="w-6 h-6 rounded-full shadow">
                <span class="label">حساب کاربری</span>
            </a>

            @if (is_wallet_feature_active())
                <a href="{{ route('client.account.wallet.index') }}" class="item">
                    <div class="icon">
                        <i class="fi fi-rr-wallet"></i>
                    </div>
                    <div class="flex items-center justify-between flex-1">
                        <span class="label">کیف پول</span>
                        <span class="label ltr-direction">{{ number_format(auth()->user()->wallet->balance) }}</span>
                    </div>
                </a>
            @endif

            <hr class="my-1 border-neutral-100">

            <!-- Orders -->
            <a href="{{ route('client.account.orders.index') }}" class="item">
                <div class="icon">
                    <i class="fi fi-rr-basket-shopping-simple"></i>
                </div>
                <span class="label">سفارش‌ها</span>
            </a>

            <!-- Addresses -->
            <a href="{{ route('client.account.addresses.index') }}" class="item">
                <div class="icon">
                    <i class="fi fi-rr-address-book"></i>
                </div>
                <span class="label">آدرس‌ها</span>
            </a>

            <!-- Notifications -->
            <a href="{{ route('client.account.notifications.index') }}" class="item">
                <div class="icon">
                    <i class="fi fi-rr-bell"></i>
                </div>
                <span class="label">اعلان‌ها</span>

                @if ($unreadNotifications)
                    <span class="badge badge-danger mr-auto">{{ $unreadNotifications > 100 ? '+100' : $unreadNotifications }}</span>
                @endif
            </a>

            <hr class="my-1 border-neutral-100">

            @if (auth()->user()->is_staff)
                <!-- Management -->
                <a href="{{ route('admin.index') }}" class="item">
                    <div class="icon">
                        <div class="bg-amber-500 rounded-full w-2 h-2 ring-4 ring-amber-100"></div>
                    </div>
                    <span class="label text-amber-500">مدیریت</span>
                </a>
            @endif

            <!-- Logout -->
            <button type="button" class="item" onclick="document.getElementById('logout-form').submit()">
                <div class="icon">
                    <div class="bg-red-500 rounded-full w-2 h-2 ring-4 ring-red-100"></div>
                </div>
                <span class="label text-red-600">خروج</span>
            </button>

            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
            </form>
        </div>
    </div>
@else
    <div class="ml-1">
        <div class="hidden md:flex items-center whitespace-nowrap">
            <a href="{{ route('auth.login') }}" class="ml-3 text-green-600">وارد شوید</a>
            <a href="{{ route('auth.login') }}" class="btn btn-success px-4 rounded-full">ثبت نام</a>
        </div>

        <div class="block md:hidden">
            <a href="{{ route('auth.login') }}">
                <i class="fi fi-rr-user flex text-2xl"></i>
            </a>
        </div>
    </div>
@endif
