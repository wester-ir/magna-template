@template_extends('views.layouts.account')

@title('لایک‌ها', false)

@section('page-content')
    <div class="card">
        <div class="card-header">
            <span class="card-title">لایک‌ها</span>
        </div>
        <div class="card-content">
            @if ($products->isNotEmpty())
                <div class="grid grid-cols-2 md:grid-cols-3 gap-5">
                    @foreach ($products as $product)
                        <div>
                            <a href="{{ $product->url }}">
                                <img src="{{ $product->image->url['thumbnail'] ?? template_asset('/assets/images/no-image.jpg') }}" class="aspect-square object-cover rounded w-full">
                            </a>

                            <div class="mt-2">
                                <div class="flex items-center justify-between">
                                    <h2 class="ml-2 text-sm truncate">{{ $product->title }}</h2>

                                    <x-tpl::like-product :$product />
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center font-light">چیزی برای نمایش وجود ندارد.</div>
            @endif
        </div>
    </div>

    {{ $products->links() }}
@endsection
