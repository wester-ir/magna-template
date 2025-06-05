@template_extends('views.layouts.account')

@title('آدرس‌ها', false)

@section('page-content')
    <div class="card">
        <div class="card-header flex items-center">
            <span class="card-title">آدرس‌ها</span>

            @can('create', App\Models\Address::class)
                <div class="mr-auto">
                    <a class="btn btn-light" href="{{ route('client.account.addresses.create') }}">افزودن</a>
                </div>
            @endcan
        </div>
        <div class="card-content">
            @if ($addresses->isNotEmpty())
                <div class="space-y-6">
                    @foreach ($addresses as $key => $address)
                        <div>
                            <div class="flex">
                                <div class="font-medium ml-3">{{ $address->address }}</div>
                                <div class="flex items-center space-x-4 space-x-reverse mr-auto">
                                    <a class="text-sky-500" href="{{ route('client.account.addresses.edit', $address) }}"><i class="fi fi-rr-edit"></i></a>
                                    <form action="{{ route('client.account.addresses.destroy', $address) }}" method="POST" onsubmit="return modal.defaults.confirmDanger(() => this.submit());">
                                        @csrf
                                        @method('DELETE')

                                        <button class="flex text-danger">
                                            <i class="fi fi-rr-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="flex items-center text-sm font-light mt-3">
                                <i class="fi fi-rr-marker flex"></i>
                                <span class="mr-3">{{ $address->province->name }}، {{ $address->city->name }}</span>
                            </div>
                            <div class="flex items-center text-sm font-light mt-2">
                                <i class="fi fi-rr-user flex"></i>
                                <span class="mr-3">{{ $address->is_self ? auth()->user()->full_name : $address->full_name }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="font-light text-center">آدرسی برای نمایش ندارید!</div>
            @endif
        </div>
    </div>
@endsection
