<div class="container">
    <div class="wrapper">
        <div class="navbar-items">
            <!-- Categories -->
            @template_include('views.layouts.default.categories')

            <!-- Separator -->
            <div class="h-6 w-px bg-stone-300 mx-4"></div>

            <div class="flex items-center space-x-4 space-x-reverse overflow-x-auto hide-scrollbar">
                @if ($menu = get_menu('header_navbar'))
                    @foreach ($menu->links as $link)
                        <a
                        @if ($link->url)
                            href="{{ $link->display_url }}"
                        @endif
                            target="{{ $link->target }}"
                        @if ($link->rel)
                            rel="{{ $link->rel }}"
                        @endif
                        class="navbar-item navbar-indicator-trigger" {!! $link->custom_attributes !!}>
                            {!! $link->icon !!}

                            @if ($link->label)
                                <span class="label">{{ $link->label }}</span>
                            @endif
                        </a>
                    @endforeach
                @endif
            </div>

            <!-- Indictor -->
            <span id="navbar-indicator"></span>
        </div>

        <div class="hidden md:flex items-center mr-auto">
            <i class="fi fi-rr-shipping-fast"></i>
            <span class="text-sm mr-2">ارسال به تمام نقاط کشور</span>
        </div>
    </div>
</div>
