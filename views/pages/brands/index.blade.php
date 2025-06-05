@template_extends('views.layouts.default')

@title(['برندها', 'صفحه '. $brands->currentPage()], false)

@section('content')
    <div class="main mt-3">
        <div class="container">
            <h1 class="text-2xl font-bold">برندها</h1>

            @if ($brands->isNotEmpty())
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 mt-5">
                    @foreach ($brands as $brand)
                        <a href="{{ $brand->url }}">
                            <div class="bg-neutral-50 hover:bg-neutral-100 p-3 rounded transition-colors">
                                @if ($brand->logo)
                                    <img src="{{ $brand->logo_url }}" class="w-full aspect-square" alt="{{ $brand->name }}">
                                @else
                                    <div class="flex items-center justify-center text-lg bg-neutral-200 w-full aspect-square">
                                        {{ $brand->latin_name ?: $brand->name }}
                                    </div>
                                @endif
                            </div>

                            <div class="text-center mt-3">{{ $brand->name }}</div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-10">محصولی برای نمایش وجود ندارد!</div>
            @endif

            {{ $brands->links() }}
        </div>
    </div>
@endsection
