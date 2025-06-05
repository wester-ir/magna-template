@php
    $withName ??= true;
@endphp

<a href="{{ route('client.index') }}" class="flex items-center leading-none">
    @if ($logo = site_logo())
        <img src="{{ $logo }}" class="w-10 h-10 min-w-[40px] min-h-[40px]">
    @else
        <div class="flex items-center justify-center font-medium text-white w-9 h-9 min-w-[30px] min-h-[30px] rounded-lg bg-green-400">L</div>
    @endif

    @if ($withName)
        <span class="font-medium ms-3 hidden md:block">{{ settings('general')['name'] }}</span>
    @endif
</a>
