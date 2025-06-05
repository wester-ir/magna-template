<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @stack('meta-tags')
    @ifdesc<meta property="description" content="@description">
    <meta property="og:description" content="@description">@endifdesc
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ settings('general')['name'] }}">
    @iftitle<meta property="og:title" content="@title">
    <meta name="twitter:title" content="@title">@endiftitle
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:locale" content="fa_IR">
    <meta name="twitter:widgets:csp" content="on">
    <meta name="twitter:card" content="summary">
    <meta name="apple-mobile-web-app-title" content="{{ settings('general')['name'] }}">
    <meta name="color-scheme" content="light">

    @if (has_site_logo())
        <!-- Icons -->
        <link rel="icon" type="image/png" sizes="32x32" href="{{ site_favicon(32) }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ site_favicon(16) }}">
    @endif

    <title>@title</title>

    <!-- Canonical -->
    <link rel="canonical" href="{{ url()->current() }}" />

    <!-- Styles -->
    <link href="{{ template_versioned_asset('assets/css/main.css') }}" rel="stylesheet">

    <!-- Icons -->
    <link href="{{ versioned_asset('/assets/icons/fi/rr/css/uicons-regular-rounded.css') }}" rel="stylesheet">
    <link href="{{ versioned_asset('/assets/icons/fi/sr/css/uicons-solid-rounded.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script type="text/javascript" src="{{ versioned_asset('/assets/js/utils.js') }}"></script>
    <script type="text/javascript" src="{{ versioned_asset('/assets/js/libs/axios/axios.min.js') }}"></script>
    <script type="text/javascript" src="{{ versioned_asset('/assets/js/libs/jquery/jquery.min.js') }}"></script>
    <script>
        const API = {
            cart: {
                details: '{{ route('client.cart.ajax.details') }}',
                add: '{{ route('client.cart.ajax.add') }}',
                update: '{{ route('client.cart.ajax.update') }}',
                remove: '{{ route('client.cart.ajax.remove') }}',
                clear: '{{ route('client.cart.ajax.clear') }}',
            },
            product: {
                like: '{{ route('client.product.ajax.like', '@id') }}',
                unlike: '{{ route('client.product.ajax.unlike', '@id') }}',
            },
        };
    </script>

    <!-- Toast -->
    <script type="text/javascript" src="{{ versioned_asset('/assets/js/libs/toast/toast.min.js') }}"></script>
    <link href="{{ versioned_asset('/assets/js/libs/toast/toast.css') }}" rel="stylesheet">

    <!-- Selectbox -->
    <script type="text/javascript" src="{!! versioned_asset('/assets/js/libs/selectbox/selectbox.min.js') !!}"></script>
    <link href="{!! versioned_asset('/assets/js/libs/selectbox/selectbox.css') !!}" rel="stylesheet">

    <!-- Modal -->
    <script type="text/javascript" src="{!! versioned_asset('/assets/js/libs/modal/modal.min.js') !!}"></script>
    <link href="{{ versioned_asset('/assets/js/libs/modal/modal.css') }}" rel="stylesheet">

    @stack('head-scripts')

    {!! template_head() !!}
</head>
<body>
    <header>
        <div class="main-section">
            <div class="container">
                <!-- Logo -->
                @template_include('views.layouts.default.logo')

                <!-- Search -->
                @template_include('views.layouts.default.search')

                <div class="flex items-center space-x-4 space-x-reverse">
                    <!-- Auth -->
                    @template_include('views.layouts.default.auth-menu')

                    <!-- Cart -->
                    @template_include('views.layouts.default.cart')
                </div>
            </div>
        </div>
        <nav id="navbar" data-hidden="false">
            @template_include('views.layouts.default.navbar')
        </nav>
    </header>

    <div class="flex flex-col flex-1 mt-[60px]">
        <x-tpl::session-messages class="mb-5" />

        @template_include('views.layouts.default.pending-order')

        @yield('content')

        @template_include('views.layouts.default.footer')
    </div>

    <!-- Bottom Scripts -->
    <script type="text/javascript" src="{{ template_versioned_asset('assets/js/utils.js') }}"></script>
    <script type="text/javascript" src="{{ template_versioned_asset('assets/js/main.js') }}"></script>
    <script type="text/javascript" src="{{ versioned_asset('/assets/js/global.js') }}"></script>
    <script type="text/javascript" src="{{ versioned_asset('/assets/js/libs/template/manager.js') }}"></script>
    <script type="text/javascript" src="{{ versioned_asset('/assets/js/libs/template/location-provider.js') }}"></script>
    <link rel="stylesheet" href="{{ template_versioned_asset('/assets/js/libs/swiper/swiper-bundle.min.css') }}" />
    <script src="{{ template_versioned_asset('/assets/js/libs/swiper/swiper-bundle.min.js') }}"></script>
    <script>
        FormManager.setErrorCallback(422, () => {
            toast.error('لطفاً خطاهای فرم را رفع کنید.');
        });
        FormManager.setErrorCallback(429, () => {
            toast.error('لطفاً بعدا امتحان کنید.');
        });
        FormManager.setErrorCallback(403, () => {
            toast.error('عملیات مورد نظر غیرمجاز می باشد.');
        });
    </script>
    @if (auth()->check())
        <script>
            window.user = {
                first_name: '{{ auth()->user()->first_name }}',
                last_name: '{{ auth()->user()->last_name }}',
                full_name: '{{ auth()->user()->full_name }}',
                number: '{{ auth()->user()->number }}',
            };
        </script>
    @endif
    @stack('bottom-scripts')
<script>
    function disablePurchaseButtons() {
        setTimeout(() => $('[data-role="purchase"], [data-role="cancel-purchase"]').prop('disabled', true), 1);
    }

    $('[data-role="cancel-purchase-form"]').on('submit', function () {
        return modal.defaults.confirmDanger(() => {
            this.submit();
            disablePurchaseButtons();
        });
    })

    $('[data-role="purchase"]').on('click', disablePurchaseButtons);

    // Pending order countdown
    $('[data-role="pending-order-countdown"]').each(function (i, elem) {
        countdown(elem, elem.getAttribute('data-seconds'), function () {
            $('#pending-order').remove();
            location.reload();
        });
    });

    // Basic countdown
    $('[data-role="basic-countdown"]').each(function (i, elem) {
        countdown(elem, elem.getAttribute('data-seconds'));
    });
</script>
</body>
</html>
