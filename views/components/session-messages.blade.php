@if (session()->hasAny(['success', 'error', 'info']))
    <div class="container">
        @if (session()->has('success'))
            <x-tpl::alert type="success" :message="session('success')" :closable="true" {{ $attributes }} />
        @endif

        @if (session()->has('error'))
            <x-tpl::alert type="error" :message="session('error')" :closable="true" {{ $attributes }} />
        @endif

        @if (session()->has('info'))
            <x-tpl::alert type="info" :message="session('info')" :closable="true" {{ $attributes }} />
        @endif
    </div>
@endif
